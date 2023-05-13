<?php

/**
 * Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * Author : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\models;

use Yii;
use yii\helpers\StringHelper;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use app\components\helpers\TFileHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "tbl_file".
 *
 * @property integer $id
 * @property string $name
 * @property integer $size
 * @property string $key
 * @property string $model_type
 * @property integer $model_id
 * @property integer $type_id
 * @property string $created_on
 * @property integer $created_by_id
 *
 */
class File extends \app\components\TActiveRecord
{

    const TYPE_FILE = 0;

    const TYPE_LINK = 1;

    const TYPE_URL = 2;

    const FILE_TYPE_MEDICAL_REPORT = 1;

    const FILE_TYPE_QUALIFICATION_DOCUMENT = 2;

    const DOCUMENT_PENDING = 0;

    const DOCUMENT_APPROVED = 1;

    const DOCUMENT_REJECTED = 2;

    public function __toString()
    {
        return (string) $this->name;
    }

    public static function getTypeOptions()
    {
        return [
            self::TYPE_FILE => "File",
            self::TYPE_LINK => "SybolicLink",
            self::TYPE_URL => "URL"
        ];
    }

    public function getType()
    {
        $list = self::getTypeOptions();
        return isset($list[$this->type_id]) ? $list[$this->type_id] : 'Not Defined';
    }

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            if (! isset($this->created_on)) {
                $this->created_on = date('Y-m-d H:i:s');
            }

            if (! isset($this->created_by_id)) {
                $this->created_by_id = self::getCurrentUser();
            }
        } else {}
        return parent::beforeValidate();
    }

    public function getApproveBadge()
    {
        $list = [
            self::DOCUMENT_REJECTED => "danger",
            self::DOCUMENT_APPROVED => "success",
            self::DOCUMENT_PENDING => "warning"
        ];
        return isset($list[$this->is_approve]) ? \yii\helpers\Html::tag('span', $this->getApprove(), [
            'class' => 'label label-' . $list[$this->is_approve]
        ]) : Yii::t('app', 'Not Defined');
    }

    /**
     *
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%file}}';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        $scenarios['reject-document'] = [
            'reason'
        ];
        return $scenarios;
    }

    /**
     *
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'name',
                    'size',
                    'key',
                    'model_type',
                    // 'model_id',
                    'type_id',
                    'created_by_id'
                ],
                'required'
            ],
            [
                [
                    'size',
                    'type_id',
                    'model_id',
                    'created_by_id',
                    'file_type',
                    'is_approve'
                ],
                'integer'
            ],
            [
                [
                    'created_on'
                ],
                'safe'
            ],
            [
                [
                    'name'
                ],
                'string',
                'max' => 1024
            ],
            [
                [
                    'reason'
                ],
                'string',
                'max' => 512
            ],
            [
                [
                    'key'
                ],
                'string',
                'max' => 255
            ],
            [
                [
                    'model_type'
                ],
                'string',
                'max' => 128
            ],
            [
                [
                    'name',
                    'key',
                    'model_type'
                ],
                'trim'
            ],

            [
                [
                    'type_id'
                ],
                'in',
                'range' => array_keys(self::getTypeOptions())
            ],
            [
                [
                    'reason'
                ],
                'required',
                'on' => 'reject-document'
            ]
        ];
    }

    /**
     *
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'size' => Yii::t('app', 'Size'),
            'key' => Yii::t('app', 'Key'),
            'model_type' => Yii::t('app', 'Model Type'),
            'model_id' => Yii::t('app', 'Model'),
            'type_id' => Yii::t('app', 'Type'),
            'created_on' => Yii::t('app', 'Create On'),
            'created_by_id' => Yii::t('app', 'Created By')
        ];
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::class, [
            'id' => 'created_by_id'
        ]);
    }

    public static function getHasManyRelations()
    {
        $relations = [];
        return $relations;
    }

    public static function getHasOneRelations()
    {
        $relations = [];
        $relations['created_by_id'] = [
            'createdBy',
            'User',
            'id'
        ];
        return $relations;
    }

    public function beforeDelete()
    {
        if (! parent::beforeDelete()) {
            return false;
        }
        $path = $this->getFullPath();
        self::log("File full Path:" . $path);
        if (is_file($path)) {

            unlink($path);
        }
        return true;
    }

    public function asJson($with_relations = false)
    {
        $json = [];
        $json['id'] = $this->id;
        // $json['model_type'] = $this->model_type;
        // $json['model_id'] = $this->model_id;
        $json['created_by_id'] = $this->created_by_id;
        $json['is_approve'] = $this->is_approve;
        $json['file_name'] = $this->name;
        $json['reason'] = $this->reason;
        if (isset($this->key)) {
            $json['file_link'] = $this->getImageUrl();
        } else {
            $json['file_link'] = '';
        }
        if ($with_relations) {}
        return $json;
    }

    public function getImageUrl($thumbnail = false)
    {
        $params = [
            '/' . $this->getControllerID() . '/image'
        ];
        $params['id'] = $this->id;

        $params['file'] = $this->key;
        if ($thumbnail)
            $params['thumbnail'] = is_numeric($thumbnail) ? $thumbnail : 150;
        return Url::toRoute($params);
    }

    public static function add($model, $data = null, $filename = null, $file_type = null)
    {
        if (empty($data)) {
            return null;
        }
        if (isset($filename)) {
            $old = File::find()->where([
                'model_type' => get_class($model),
                'model_id' => $model->id,
                'name' => basename($filename)
            ])->one();

            if ($old) {
                return $old;
            }
        }
        $attachment = new File();
        $attachment->loadDefaultValues();
        $attachment->model_id = $model->id;
        $attachment->model_type = get_class($model);
        $attachment->type_id = File::TYPE_FILE;
        $attachment->file_type = ! empty($file_type) ? $file_type : File::TYPE_FILE;

        $attachment->created_by_id = self::getCurrentUser();

        $models = StringHelper::dirname($attachment->model_type);
        $module = StringHelper::dirname($models);
        $dir = StringHelper::basename($module);
        if ($dir == 'app') {
            $dir = '.';
        }
        $dir = $dir . '/' . StringHelper::basename($attachment->model_type);

        if (! is_dir(UPLOAD_PATH . $dir)) {
            TFileHelper::createDirectory(UPLOAD_PATH . $dir);
        }

        if ($data instanceof UploadedFile) {
            $attachment->name = $data->basename . '.' . $data->extension;
            $filename = $attachment->model_id . '_' . $attachment->name;

            $filename = preg_replace("/[^A-Za-z0-9\_\-\.]/", '-', $filename);
            $filename = $dir . '/' . $filename;
            if (is_file(UPLOAD_PATH . $filename)) {
                TFileHelper::unlink(UPLOAD_PATH . $filename);
            }
            $data->saveAs(UPLOAD_PATH . $filename);
        } else {

            $attachment->name = basename($filename);
            $filename = $attachment->model_id . '_' . preg_replace("/[^A-Za-z0-9\_\-\.]/", '-', $filename);

            $filename = $dir . '/' . $filename;
            if (is_file(UPLOAD_PATH . $filename)) {
                TFileHelper::unlink(UPLOAD_PATH . $filename);
            }
            @file_put_contents(UPLOAD_PATH . $filename, $data);
        }

        $attachment->size = 0;

        if (is_file(UPLOAD_PATH . $filename)) {

            $attachment->size = @filesize(UPLOAD_PATH . $filename);
        }
        $attachment->key = $filename;

        if (! $attachment->save()) {
            return null;
        }
        return $attachment;
    }

    public function getModel()
    {
        $modelType = $this->model_type;
        if (class_exists($modelType)) {
            return $modelType::findOne($this->model_id);
        }
        return null;
    }

    public function getFullPath()
    {
        return UPLOAD_PATH . $this->key;
    }

    public function rename()
    {
        $path = $this->getFullPath();

        if (is_file($path)) {
            File::log("Update file :" . $this->id . ' - ' . $this . '==>' . $path);
            $dir = dirname($this->key);
            if (empty($dir) || $dir == '.') {
                // We Must Move
                $dir = StringHelper::basename($this->model_type);
                if (! is_dir(UPLOAD_PATH . $dir)) {
                    @mkdir(UPLOAD_PATH . $dir, true);
                }
                $path_dst = str_replace('/' . $dir . '_', '/' . $dir . '/', $path);
                if (rename($path, $path_dst)) {
                    File::log("New file :" . $this->id . ' - ' . $this . '==>' . $path_dst);
                    $this->key = str_replace(UPLOAD_PATH, '', $path_dst);
                    $this->updateAttributes([
                        'key'
                    ]);
                }
                if (! isset($this->project_id) && $dir == 'Project') {
                    $this->project_id = $this->model_id;
                    $this->updateAttributes([
                        'project_id'
                    ]);
                }
            }
        }
    }

    public function isAllowed($manager = false)
    {
        if ($this->getModel()->isAllowed()) {
            return true;
        }
        return false;
    }

    public static function findByKey($id)
    {
        if (is_numeric($id)) {
            $model = self::findOne($id);
        } else {
            $model = self::find()->where([
                'key' => $id
            ]);
        }
        if ($model == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $model;
    }
}
