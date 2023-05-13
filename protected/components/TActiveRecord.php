<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\components;

use app\base\TBaseActiveRecord;
use app\components\helpers\TLogHelper;
use app\models\Feed;
use app\models\File;
use app\models\User;
use app\modules\comment\models\Comment;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\UploadedFile;
use yii\caching\TagDependency;
use app\components\helpers\TStringHelper;

/**
 *
 * {@inheritdoc}
 */
class TActiveRecord extends TBaseActiveRecord
{

    use TLogHelper;

    protected $_controllerId = null;

    public static function find()
    {
        return Yii::createObject(TActiveQuery::className(), [
            get_called_class()
        ]);
    }

    public static function findActive($state_id = 1)
    {
        return Yii::createObject(TActiveQuery::class, [
            get_called_class()
        ])->andWhere([
            'state_id' => $state_id
        ]);
    }

    public static function label($n = 1)
    {
        $className = Inflector::camel2words(StringHelper::basename(get_called_class()));
        if ($n == 2)
            return Inflector::pluralize($className);
        return $className;
    }

    public function __toString()
    {
        return $this->label(1);
    }

    public function getStateBadge()
    {
        return '';
    }

    public static function getStateOptions()
    {
        return [];
    }

    public function isAllowed()
    {
        if (method_exists(get_parent_class(), 'isAllowed')) {
            return parent::isAllowed();
        }
        if (User::isAdmin())
            return true;

        if ($this instanceof User && $this->id == Yii::$app->user->id) {
            return true;
        }
        if ($this->hasAttribute('created_by_id') && $this->created_by_id == Yii::$app->user->id) {
            return true;
        }

        if ($this->hasAttribute('user_id') && $this->user_id == Yii::$app->user->id) {
            return true;
        }

        return false;
    }

    public function saveUploadedFile($model, $attribute = 'image_file', $old = null)
    {
        $uploaded_file = UploadedFile::getInstance($model, $attribute);
        if ($uploaded_file != null) {
            $path = UPLOAD_PATH;
            $filename = $path . str_replace('/', '-', Yii::$app->controller->id) . '-' . time() . '-' . $attribute . '-user_id_' . Yii::$app->user->id . '.' . $uploaded_file->extension;
            if (is_file($filename))
                unlink($filename);
            if (! empty($old) && is_file(UPLOAD_PATH . $old))
                unlink(UPLOAD_PATH . $old);
            $uploaded_file->saveAs($filename);
            $model->$attribute = basename($filename);
            return true;
        }
        return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (! defined('MIGRATION_IN_PROGRESS')) {
            $this->processFeed($insert, $changedAttributes);
        }

        return parent::afterSave($insert, $changedAttributes);
    }

    /**
     *
     * @param
     *            insert
     *            changedAttributes
     */
    protected function processFeed($insert, $changedAttributes)
    {
        if ($insert)
            $msg = 'Created new ' . $this->label();
        else {
            $msg = 'Modified ' . $this->label() . ' ';
            $msg .= $this->getChangedContent($changedAttributes);
        }

        if ($this->hasAttribute('id')) {
            $this->updateFeeds($msg);
        }
    }

    public function getChangedContent($changedAttributes)
    {
        $msg = '';
        foreach ($changedAttributes as $key => $change) {
            if (is_null($change)) {
                // $msg .= $key . '=> null' . PHP_EOL;
                continue;
            }
            // Checks if value is changed or not
            if ($change != $this->$key) {
                $keyLabel = $this->getAttributeLabel($key);

                $column = $this->getTableSchema()->columns[$key];

                if ($key == 'state_id') {
                    // Checks if table has 'state' column
                    if (isset($this->getTableSchema()->columns['state'])) {
                        $change = $keyLabel . ' : ' . $this->getStateOptions()[$change] . '==>' . $this->getState();
                    } else {
                        $change = $keyLabel . ' : ' . $this->getStateOptions()[$change] . '==>' . $this->state;
                    }
                } else if ($key == 'type_id') {
                    $change = $keyLabel . ' : ' . $this->getTypeOptions()[$change] . '==>' . $this->type;
                } elseif ($this->hasAttribute($key) && ! strstr($column->dbType, 'text')) {
                    $change = $keyLabel . ' : ' . $change . '==>' . $this->getRelatedDataLink($key, true);
                }
                // Concatenate field name with message if datatype is text else adds changed message
                if (strstr($column->dbType, 'text') || in_array($key, [
                    'password'
                ])) {
                    $msg .= $keyLabel . PHP_EOL;
                } else {
                    $msg .= $change . PHP_EOL;
                }
            }
        }
        return $msg;
    }

    public function beforeDelete()
    {
        if (! parent::beforeDelete()) {
            return false;
        }

        if ($this->hasAttribute('id')) {

            Comment::deleteRelatedAll($this->getComments());

            Feed::deleteRelatedAll($this->getFeeds());

            File::deleteRelatedAll($this->getFiles());
        }
        return true;
    }

    public function updateFeeds($content)
    {
        if ($this instanceof Feed)
            return;

        Feed::add($this, $content);
        TagDependency::invalidate(Yii::$app->cache, $this->getCacheTag('feed'));
        return true;
    }

    public function updateHistory($comment)
    {
        $model = new Comment();
        $model->model_type = get_class($this);
        $model->model_id = $this->id;
        $model->comment = $comment;
        $model->state_id = Comment::STATE_ACTIVE;

        $model->save();
        TagDependency::invalidate(Yii::$app->cache, $this->getCacheTag());
        return true;
    }

    protected function getControllerID()
    {
        if (empty($this->_controllerId)) {
            $admin = '';
            if (! (\Yii::$app instanceof yii\console\Application) && Yii::$app->user->isAdminMode) {
                $adminPath = Yii::$app->controller->module->basePath . DIRECTORY_SEPARATOR . 'controllers/admin';
                if (is_dir($adminPath)) {
                    $admin = 'admin/';
                }
            }
            $modelClass = get_class($this);
            $pos = strrpos($modelClass, '\\');
            $class = substr($modelClass, $pos + 1);
            $this->_controllerId = $admin . Inflector::camel2id($class);
        }
        return $this->_controllerId;
    }

    public function getAbsoluteUrl($action = 'view', $id = null)
    {
        return $this->getUrl($action, $id, true);
    }

    public function getUrl($action = 'view', $id = null, $absolute = false)
    {
        $params = [
            $this->getControllerID() . '/' . $action
        ];
        if ($id != null) {
            if (is_array($id))
                $params = array_merge($params, $id);
            else
                $params['id'] = $id;
        } elseif ($this->hasAttribute('id')) {
            $params['id'] = $this->id;
        }
        if ($this->hasAttribute('title')) {
            $params['title'] = $this->title;
        } else {
            $params['title'] = (string) $this;
        }

        $params = array_filter($params);

        if ($absolute || \Yii::$app instanceof yii\console\Application) {
            return Yii::$app->getUrlManager()->createAbsoluteUrl($params);
        }
        return Yii::$app->getUrlManager()->createUrl($params);
    }

    public function linkify($title = null, $absoluteUrl = false, $action = 'view')
    {
        if ($title == null) {
            $title = (string) $this;
        }

        $url = $this->getUrl($action, null, $absoluteUrl);

        return Html::a($title, $url);
    }

    public function getErrorsString()
    {
        $out = '';
        if ($this->hasErrors()) {
            foreach ($this->errors as $error) {
                $out = implode('.', $error);
            }
        }

        return $out;
    }

    public static function getHasOneRelations()
    {
        $relations = [];
        return $relations;
    }

    public function getRelatedDataLink($key, $link = null)
    {
        if ($link == null) {
            $link = \Yii::$app instanceof yii\console\Application || User::isAdmin();
        }
        if (! $link && isset(Yii::$app->params['enableRelatedDataLink'])) {
            $link = Yii::$app->params['enableRelatedDataLink'];
        }
        $hasOneRelations = get_called_class()::getHasOneRelations();
        if (isset($hasOneRelations[$key])) {
            $relation = $hasOneRelations[$key][0];
            if (isset($this->$relation)) {
                if ($link && $this->$relation instanceof TActiveRecord) {
                    return $this->$relation->linkify();
                }
                return $this->$relation;
            }
        }
        return $this->$key;
    }

    public static function deleteRelatedAll($query = [])
    {
        if (! ($query instanceof ActiveQuery)) {

            $query = self::find()->where($query);
        }
        $count = $query->count();
        foreach ($query->each() as $model) {
            $model->delete();
        }
        return $count;
    }

    public static function deleteRelatedFiles($query = [], $delete = false)
    {
        if (! ($query instanceof ActiveQuery)) {

            $query = self::find()->where($query);
        }
        $totalSize = 0;
        foreach ($query->each() as $model) {
            $filesQuery = $model->getFiles();
            foreach ($filesQuery->each() as $file) {

                if ($delete) {
                    self::log($file . ' : size ' . $file->sizeFormatted);
                    @unlink($file->fullPath);
                }
                $totalSize += $file->size;
            }
        }
        return $totalSize;
    }

    public function setEncryptedPassword($password)
    {
        $this->password = utf8_encode(\Yii::$app->security->encryptByPassword($password, \Yii::$app->id));
    }

    public function getDecryptedPassword()
    {
        $new = \Yii::$app->getSecurity()->decryptByPassword(utf8_decode($this->password), \Yii::$app->id);
        return $new;
    }

    public function isActive()
    {
        return ($this->state_id == $this::STATE_ACTIVE);
    }

    public static function truncate()
    {
        $table = get_called_class()::tableName();

        \Yii::$app->db->createCommand()
            ->checkIntegrity(false)
            ->execute();

        self::log("Cleaning " . $table);
        \Yii::$app->db->createCommand()
            ->truncateTable($table)
            ->execute();

        \Yii::$app->db->createCommand()
            ->checkIntegrity(true)
            ->execute();
    }

    public function checkRelatedData($models = null)
    {
        if ($models == null)
            $models = get_class()::getHasOneRelations();
        foreach ($models as $key => $class) {
            $class = is_array($class) ? $class[1] : $class;
            if ($class::find()->count() == 0) {
                $this->addError($key, $class::label() . ' atleast 1 record required');
            }
        }
    }

    /**
     * Get number of records created in each month
     *
     * @param integer $state
     * @param integer $created_by_id
     * @param string $dateAttribute
     * @return number[]
     */
    public static function monthly($state = null, $created_by_id = null, $dateAttribute = 'created_on')
    {
        $date = new \DateTime(date('Y-m'));

        $date->modify('-1 year');

        $count = [];
        $query = self::find()->cache();
        for ($i = 1; $i <= 12; $i ++) {
            $date->modify('+1 months');
            $year = $date->format('Y');
            $month = $date->format('m');

            $query->where([
                "MONTH($dateAttribute)" => $month,
                "YEAR($dateAttribute)" => $year
            ]);

            if ($created_by_id !== null) {
                $query->andWhere([
                    'created_by_id' => $created_by_id
                ]);
            }

            if ($state !== null) {
                $state = is_array($state) ? $state : [
                    $state
                ];
                $query->andWhere([
                    'in',
                    'state_id',
                    $state
                ]);
            }

            $count["$year-$month"] = (int) $query->count();
        }
        return $count;
    }

    public static function daily($state = null, $created_by_id = null, $dateAttribute = 'created_on')
    {
        $date = new \DateTime();
        $date->modify('-30 days');

        $count = [];
        $query = self::find()->cache(60);
        for ($i = 1; $i <= 30; $i ++) {
            $date->modify('+1 days');
            $day = $date->format('Y-m-d');

            $query->where([
                "DATE($dateAttribute)" => $day
            ]);
            if ($created_by_id !== null) {
                $query->andWhere([
                    'created_by_id' => $created_by_id
                ]);
            }

            if ($state !== null) {
                $state = is_array($state) ? $state : [
                    $state
                ];
                $query->andWhere([
                    'in',
                    'state_id',
                    $state
                ]);
            }
            $count[$day] = (int) $query->count();
        }
        return $count;
    }

    public function getFeeds()
    {
        return $this->hasMany(Feed::class, [
            'model_id' => 'id'
        ])
            ->andWhere([
            'model_type' => get_called_class()
        ])
            ->cache(60);
    }

    public static function logQuery($query)
    {
        $sql = '';
        if ($query instanceof ActiveDataProvider) {
            $query = $query->query;
        }

        if ($query instanceof TActiveQuery) {
            $sql = $query->createCommand()->rawSql;
        }
        echo ($sql) . PHP_EOL;
    }

    /**
     * Get current loggedin User
     *
     * @return number|string|number
     */
    public static function getCurrentUser()
    {
        if (\Yii::$app instanceof yii\console\Application || Yii::$app->user->isGuest) {
            $id = User::findActive()->cache()
                ->select([
                'id'
            ])
                ->scalar();
            if ($id == null) {
                $id = 1;
            }
            return $id;
        }
        return Yii::$app->user->id;
    }

    /**
     *
     * @inheritdoc
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        if (! parent::save($runValidation, $attributeNames)) {
            self::log(get_called_class() . ':' . $this->getErrorsString());
            return false;
        }

        return true;
    }

    public function getFiles()
    {
        return $this->hasMany(File::class, [
            'model_id' => 'id'
        ])
            ->andWhere([
            'model_type' => get_called_class()
        ])
            ->cache(60);
    }

    public function getCacheTag($type = 'comment')
    {
        return $type . get_called_class() . $this->id;
    }

    public function getComments()
    {
        return $this->hasMany(Comment::class, [
            'model_id' => 'id'
        ])
            ->andWhere([
            'model_type' => get_called_class()
        ])
            ->cache(60, new TagDependency([
            'tags' => $this->getCacheTag()
        ]));
    }

    public static function listData($data, $key = 'id', $func = null)
    {
        $result = [];
        foreach ($data as $element) {
            if ($func && $func instanceof \Closure) {
                $result[$element->$key] = $element->$func();
            } elseif ($func && $element->hasProperty($func)) {
                $result[$element->$key] = $element->$func;
            } else {
                $result[$element->$key] = (string) $element;
            }
        }
        return $result;
    }

    public static function massDelete($action = 'delete')
    {
        $Ids = \Yii::$app->request->post('ids', []);
        $response = [];
        $response['status'] = 'OK';
        if (! empty($Ids)) {
            try {
                foreach ($Ids as $Id) {
                    $model = self::findOne($Id);
                    if (! empty($model) && ($model instanceof ActiveRecord)) {
                        if ($action == 'delete') {
                            if (($model instanceof User) && ($model->id == \Yii::$app->user->id)) {
                                throw new HttpException('Could not delete');
                            } else {

                                $model->delete();
                            }
                        } else {
                            throw new HttpException('Delete Action not performed');
                        }
                    }
                }
            } catch (HttpException $e) {
                $response['status'] = 'NOK';
                $response['error'] = $e->getMessage();
            }
        }
        \Yii::$app->response->format = 'json';
        return $response;
    }

    /**
     * Cleanup model records by limit with time
     *
     * @param string $old
     * @param string $dateAttribute
     */
    public static function cleanup($old = "-1 year", $dateAttribute = 'created_on')
    {
        $query = self::find()->where([

            '<',
            $dateAttribute,
            date('Y-m-d H:i:s', strtotime($old))
        ])->orderBy('id asc');
        self::log("Cleaning up  : " . $query->count());
        foreach ($query->each() as $item) {
            self::log("Deleting   :" . $item->id . ' - ' . $item);
            try {
                $item->delete();
            } catch (Exception $e) {
                echo $e->getMessage();
                echo $e->getTraceAsString();
            }
        }
    }

    public static function sitemap()
    {
        return self::find()->active()->orderBy('id DESC');
    }

    public static function updateAll($attributes, $condition = '', $params = [])
    {
        $row = parent::updateAll($attributes, $condition, $params);
        // self::log('UpdateAll = ' . $row . ' rows');
        return $row;
    }

    public static function deleteAll($condition = null, $params = [])
    {
        $row = parent::deleteAll($condition, $params);
        // self::log('deleteAll = ' . $row . ' rows');
        return $row;
    }

    public function onComment()
    {}

    public function load($data, $formName = null)
    {
        if (parent::load($data, $formName)) {
            return true;
        }
        return false;
    }

    public function getImageAbsoluteUrl($thumbnail = false)
    {
        $url = $this->getImageUrl($thumbnail);

        return Yii::$app->getUrlManager()->makeAbsoluteUrl($url);
    }

    public function getImageUrl()
    {
        return Url::toRoute('/site/logo');
    }

    public function getSeoContent()
    {
        $content = '';
        if ($this->hasProperty('content')) {
            $content = $this->content;
        }
        if ($this->hasProperty('description')) {
            $content = $this->description;
        }
        if (! empty($content)) {
            return preg_replace("/[\\n\\r]+/", "", StringHelper::truncate(strip_tags($content), 150)); // str_replace(PHP_EOL, '', StringHelper::truncate(strip_tags($model->content), 150));
        }
        return $content;
    }

    public function handleSeen()
    {
        $user_ip = \Yii::$app->request->getUserIP();
        $user_agent = \Yii::$app->request->getUserAgent();

        if (! TStringHelper::startsWith($user_ip, '192.168.') && $user_agent && ! stristr($user_agent, 'proxy')) {
            $this->updateHistory('Seen by:' . $user_ip . '=>' . $user_agent);
            return true;
        }
        return false;
    }

    public function getPreviousItem()
    {
        return self::find()->andWhere([
            '<',
            'id',
            $this->id
        ])
            ->orderBy([
            'id' => SORT_DESC
        ])
            ->limit(1)
            ->one();
    }

    public function getNextItem()
    {
        return self::find()->andWhere([
            '>',
            'id',
            $this->id
        ])
            ->orderBy([
            'id' => SORT_ASC
        ])
            ->limit(1)
            ->one();
    }
}
