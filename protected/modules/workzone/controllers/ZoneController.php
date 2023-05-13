<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author    : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
namespace app\modules\workzone\controllers;

use app\components\TActiveForm;
use app\components\TController;
use app\models\User;
use app\modules\workzone\models\Zone;
use app\modules\workzone\models\search\Zone as ZoneSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use app\modules\workzone\models\Postcode;
use yii\web\UploadedFile;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use app\components\helpers\TFileHelper;
use app\models\SampleFile;
use app\models\ImportFile;
use app\parsers\LocationMasterUpload;

/**
 * ZoneController implements the CRUD actions for Zone model.
 */
class ZoneController extends TController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className()
                ],
                'rules' => [
                    [
                        'actions' => [
                            'clear',
                            'delete',
                            'index',
                            'add',
                            'view',
                            'update',
                            'ajax',
                            'import',
                            'generate-file'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isAdmin();
                        }
                    ]
                ]
            ]
        ];
    }

    /**
     * Lists all Zone models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ZoneSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionGenerateFile()
    {
        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        // Retrieve the current active worksheet
        $sheet = $spreadsheet->getActiveSheet();
        /**
         * Set cell B3 with the "Select from the drop down options:"
         * string value, will serve as the 'Select Option Title'.
         */
        $spreadsheet->getActiveSheet()
            ->getColumnDimension('B')
            ->setWidth("25");

        $spreadsheet->getActiveSheet()
            ->getColumnDimension('C')
            ->setWidth("200");

        // set column name
        // -------------------------------------------------
        $sheet = Zone::setXslColumnName($sheet);
        // --------------------------------------------------

        // set column name
        // -------------------------------------------------
        $sheet = Zone::setXslColumnValue($sheet);
        // --------------------------------------------------

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $file_name = 'workzone.xlsx';

        if (! is_dir(UPLOAD_PATH . 'sample')) {
            TFileHelper::createDirectory(UPLOAD_PATH . 'sample', FILE_MODE);
        }
        $FilePath = UPLOAD_PATH . 'sample/' . $file_name;
        if (is_file(UPLOAD_PATH . 'sample/' . $file_name)) {
            unlink(UPLOAD_PATH . 'sample/' . $file_name);
        }

        if (! is_file(UPLOAD_PATH . 'sample/' . $file_name)) {
            // Save the new .xlsx file
            $writer->save($FilePath);
            $model = SampleFile::findOne([
                'model_type' => Zone::class,
                'name' => 'sample/' . $file_name
            ]);
            if (empty($model)) {
                $model = new SampleFile();
            }

            $model->model_type = Zone::class;

            $model->name = 'sample/' . $file_name;
            if ($model->save()) {
                \Yii::$app->getSession()->setFlash('success', 'Retrieve Successfully');
                $file = $model->getFullPath();
                if (is_file($file))
                    return Yii::$app->response->sendFile($file);
                throw new NotFoundHttpException(Yii::t('app', "File not found"));
            } else {
                \Yii::$app->getSession()->setFlash('danger', $model->getErrorsString());
            }
        } else {
            $model = SampleFile::findOne([
                'model_type' => Zone::class,
                'name' => 'sample/' . $file_name
            ]);
            if (! empty($model)) {
                \Yii::$app->getSession()->setFlash('success', 'Retrieve Successfully');
                $file = $model->getFullPath();
                if (is_file($file))
                    return Yii::$app->response->sendFile($file);
                throw new NotFoundHttpException(Yii::t('app', "File not found"));
            } else {
                \Yii::$app->getSession()->setFlash('danger', 'Not Found');
            }
        }

        return $this->redirect([
            'index'
        ]);
    }

    public function actionImport()
    {
        $importFileModel = new ImportFile();
        $post = \yii::$app->request->post();
        if (\yii::$app->request->isAjax && $importFileModel->load($post)) {
            \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return TActiveForm::validate($importFileModel);
        }
        if ($importFileModel->load($post)) {
            if (! $_FILES) {
                \Yii::$app->session->setFlash('success', 'Please upload file');
            }
            $uploaded_file = UploadedFile::getInstance($importFileModel, 'name');
            $importFileModel->model_type = Zone::class;

            if ($uploaded_file != null) {
                if (! in_array($uploaded_file->extension, [
                    'csv',
                    'xls',
                    'xlsx'
                ])) {
                    \Yii::$app->session->setFlash('success', "Error !! Only .csv , .xls and .xlsx files are allowed");
                    return $this->render('import', [
                        'model' => $importFileModel
                    ]);
                }

                $fileName = time() . $uploaded_file->name;

                if (is_file(UPLOAD_PATH . 'import/' . $fileName)) {
                    unlink(UPLOAD_PATH . $fileName);
                }
                $uploaded_file->saveAs(UPLOAD_PATH . $fileName);

                // convert to CSV

                if ($uploaded_file->extension == 'xlsx') {
                    $reader = new Xlsx();
                } else if ($uploaded_file->extension == 'xls') {

                    $reader = new Xls();
                } else {

                    $csvFilePath = UPLOAD_PATH . $fileName;
                }

                if (! empty($reader)) {
                    $spreadsheet = $reader->load(UPLOAD_PATH . $fileName);

                    $loadedSheetNames = $spreadsheet->getSheetNames();

                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);

                    if (! empty($loadedSheetNames)) {
                        $loadedSheetNames_keys = array_keys($loadedSheetNames);
                        foreach ($loadedSheetNames_keys as $sheetIndex) {
                            $writer->setSheetIndex($sheetIndex);
                            $csvFilePath = UPLOAD_PATH . $uploaded_file->baseName . '.csv';
                            $importFileModel->name = $uploaded_file->baseName . '.csv';

                            $writer->save($csvFilePath);
                        }
                    }
                }

                // later use for background process
                $importFileModel->state_id = ImportFile::STATE_ACTIVE;

                if (! $importFileModel->save()) {
                    \Yii::$app->session->setFlash('success', $importFileModel->getErrorsString());
                    return $this->render('import', [
                        'model' => $importFileModel
                    ]);
                }
                // end

                if (($handle = fopen($csvFilePath, "r")) !== FALSE) {
                    $rows = Zone::csvToArray($handle);
                }
                $class = new Zone();
                // This format for TITLE and Description
                $dataObject = new LocationMasterUpload($rows, ($class));

                /**
                 * If file format not found
                 */

                if (isset($dataObject->file_error) && $dataObject->file_error['error']) {
                    \Yii::$app->session->setFlash('success', $dataObject->file_error['error']);
                    return $this->render('import', [
                        'model' => $importFileModel
                    ]);
                }

                $results = $dataObject->getResults();

                // $total = count($results);
                // $importFileModel->total_count = $total;
                $mappedResults = $dataObject->getMapped($class);
                // end
                \Yii::$app->session->setFlash('success', 'Locations imported successfully');
                return $this->redirect([
                    'index'
                ]);
            } else {
                \Yii::$app->session->setFlash('info', 'Please choose a file to import!');
            }
        }

        return $this->render('import', [
            'model' => $importFileModel
        ]);
    }

    public function actionImpsort()
    {
        $model = new Zone();
        $post = \yii::$app->request->post();
        if (\yii::$app->request->isAjax && $model->load($post)) {
            \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load($post)) {
            $db = \Yii::$app->db;
            $transaction = $db->beginTransaction();
            try {
                $uploaded_file = UploadedFile::getInstance($model, "file");
                if ($uploaded_file != null) {
                    $filename = UPLOAD_PATH . basename($uploaded_file->name);
                    $dir = dirname($filename);
                    if (! is_dir($dir)) {
                        @mkdir($dir, FILE_MODE, true);
                    }
                    $uploaded_file->saveAs($filename);
                    // read
                    if ($uploaded_file->extension == 'xlsx') {
                        $reader = new Xlsx();
                    } else if ($uploaded_file->extension == 'xls') {
                        $reader = new Xls();
                    } else {
                        $csvFilePath = $filename;
                    }
                    if (! empty($reader)) {
                        $spreadsheet = $reader->load($filename);
                        $loadedSheetNames = $spreadsheet->getSheetNames();
                        $writer = new Csv($spreadsheet);
                        if (! empty($loadedSheetNames))
                            foreach ($loadedSheetNames as $sheetIndex => $loadedSheetName) {
                                $writer->setSheetIndex($sheetIndex);
                                $csvFilePath = $filename . '.csv';
                                $writer->save($csvFilePath);
                            }
                    }
                    if (($handle = fopen($csvFilePath, "r")) !== FALSE) {
                        $rows = Zone::csvToArray($handle);
                    }
                    if (count($rows) == 1) {
                        $transaction->rollBack();
                        $model->addError('file', 'Invalid file format.');
                        return $this->render('import', [
                            'model' => $model,
                            'import' => $model
                        ]);
                    }
                    $title = 0;
                    $zip = 0;
                    foreach ($rows as $key => $value) {
                        if ($value[0] === '') {
                            continue;
                        }
                        if (empty($title)) {
                            $title = array_search('Location', $value);
                        }
                        if (empty($zip)) {
                            $zip = array_search('Zipcode', $value);
                        }
                        if ($key == 0) {
                            continue;
                        }
                        $location = trim($value[$title]);
                        $location_model = Zone::find()->where([
                            'LOWER(title)' => strtolower($location)
                        ])->one();
                        if (empty($location_model)) {
                            $location_model = new Zone();
                        }
                        $location_model->title = $value[$title];
                        $location_model->state_id = Zone::STATE_ACTIVE;
                        if ($location_model->save()) {
                            $zipcodes = $value[$zip];
                            $zipcodeArray = explode(',', $zipcodes);
                            if (is_array($zipcodeArray)) {
                                Postcode::updateAll([
                                    'state_id' => Postcode::STATE_DELETED
                                ], [
                                    'location_id' => $location_model->id
                                ]);
                                foreach ($zipcodeArray as $zipcode) {
                                    $zipcode = trim($zipcode);
                                    $already_exists = Postcode::find()->where([
                                        '!=',
                                        'location_id',
                                        $location_model->id
                                    ])
                                        ->andWhere([
                                        'post_code' => $zipcode
                                    ])
                                        ->one();
                                    if (! empty($already_exists)) {
                                        \Yii::$app->getSession()->setFlash('danger', 'Zipcode ' . $zipcode, ' is already added for ' . $already_exists->title);
                                        return $this->redirect([
                                            'view',
                                            'id' => $location_model->id
                                        ]);
                                    }
                                    $zipcode_exist = Postcode::findOne([
                                        'post_code' => $zipcode
                                    ]);
                                    if (! empty($zipcode_exist)) {
                                        $zipcode_exist->state_id = Postcode::STATE_ACTIVE;
                                        $zipcode_exist->updateAttributes([
                                            'state_id'
                                        ]);
                                    } else {
                                        $postCode = new Postcode();
                                        $postCode->location_id = $location_model->id;
                                        $postCode->post_code = $zipcode;
                                        $postCode->title = $location_model->title;
                                        $postCode->state_id = Postcode::STATE_ACTIVE;
                                        $postCode->type_id = Postcode::TYPE_PRIMARY_LOCATION;
                                        if (! $postCode->save()) {
                                            // $transaction->rollBack();
                                            \Yii::$app->getSession()->setFlash('danger', $postCode->getErrorsString());
                                            return $this->redirect([
                                                'view',
                                                'id' => $location_model->id
                                            ]);
                                        }
                                    }
                                }
                                Postcode::deleteAll([
                                    'state_id' => Postcode::STATE_DELETED,
                                    'location_id' => $location_model->id
                                ]);
                            }
                        } else {
                            $transaction->rollBack();
                            \Yii::$app->getSession()->setFlash('danger', $location_model->getErrorsString());
                            return $this->redirect([
                                'view',
                                'id' => $location_model->id
                            ]);
                        }
                    }
                    \Yii::$app->session->setFlash('success', 'Locations imported successfully');
                    return $this->redirect([
                        'index'
                    ]);
                } else {
                    \Yii::$app->session->setFlash('info', 'Please choose a file to import!');
                }
            } catch (\yii\base\Exception $e) {
                $transaction->rollBack();
                $data['error'] = \yii::t('app', $e->getMessage());
                return $data;
            }
        }
        return $this->render('import', [
            'model' => $model
        ]);
    }

    /**
     * Displays a single Zone model.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $this->updateMenuItems($model);
        return $this->render('view', [
            'model' => $model
        ]);
    }

    /**
     * Creates a new Zone model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionAdd(/* $id*/)
    {
        $model = new Zone();
        $model->loadDefaultValues();
        $model->state_id = Zone::STATE_ACTIVE;

        /*
         * if (is_numeric($id)) {
         * $post = Post::findOne($id);
         * if ($post == null)
         * {
         * throw new NotFoundHttpException('The requested post does not exist.');
         * }
         * $model->id = $id;
         *
         * }
         */

        $model->checkRelatedData([
            'created_by_id' => User::class
        ]);
        $post = \yii::$app->request->post();
        if (\yii::$app->request->isAjax && $model->load($post)) {
            \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        $transaction = \Yii::$app->db->beginTransaction();
        if ($model->load($post)) {
            try {
                $model->primary_zipcode = explode(',', $model->primary_zipcode);
                if ($model->save()) {
                    if (is_array($model->primary_zipcode)) {
                        foreach ($model->primary_zipcode as $zipcode) {
                            $postCode = new Postcode();
                            $postCode->location_id = $model->id;
                            $postCode->post_code = $zipcode;
                            $postCode->title = $model->title;
                            $postCode->type_id = Postcode::TYPE_PRIMARY_LOCATION;
                            if (! $postCode->save()) {
                                $transaction->rollBack();
                                Yii::$app->getSession()->setFlash('danger', $postCode->getErrorsString());
                                return $this->redirect([
                                    'view',
                                    'id' => $model->id
                                ]);
                            }
                        }
                    }
                    $transaction->commit();
                    return $this->redirect($model->getUrl());
                }
            } catch (\yii\base\Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('danger', $e->getMessage());
                return $this->redirect([
                    'view',
                    'id' => $model->id
                ]);
            }
            return $this->redirect($model->getUrl());
        }
        $this->updateMenuItems();
        return $this->render('add', [
            'model' => $model
        ]);
    }

    /**
     * Updates an existing Zone model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->primary_zipcode = $model->getLocationZipcodes(Postcode::TYPE_PRIMARY_LOCATION);
        $post = \yii::$app->request->post();
        if (\yii::$app->request->isAjax && $model->load($post)) {
            \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        $transaction = \Yii::$app->db->beginTransaction();
        if ($model->load($post)) {
            try {
                $model->primary_zipcode = explode(',', $model->primary_zipcode);
                if ($model->save()) {
                    if (is_array($model->primary_zipcode)) {
                        Postcode::deleteAll([
                            'location_id' => $model->id,
                            'type_id' => Postcode::TYPE_PRIMARY_LOCATION
                        ]);
                        foreach ($model->primary_zipcode as $zipcode) {
                            $postCode = new Postcode();
                            $postCode->location_id = $model->id;
                            $postCode->post_code = $zipcode;
                            $postCode->title = $model->title;
                            $postCode->type_id = Postcode::TYPE_PRIMARY_LOCATION;
                            if (! $postCode->save()) {
                                $transaction->rollBack();
                                Yii::$app->getSession()->setFlash('danger', $postCode->getErrorsString());
                                return $this->redirect([
                                    'view',
                                    'id' => $model->id
                                ]);
                            }
                        }
                    }
                    $transaction->commit();
                    return $this->redirect($model->getUrl());
                }

                if ($model->save()) {
                    return $this->redirect($model->getUrl());
                }
            } catch (\yii\base\Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('danger', $e->getMessage());
                return $this->redirect([
                    'view',
                    'id' => $model->id
                ]);
            }
        }
        $this->updateMenuItems($model);
        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing Zone model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if (\yii::$app->request->post()) {
            $model->delete();
            return $this->redirect([
                'index'
            ]);
        }
        return $this->render('delete', [
            'model' => $model
        ]);
    }

    /**
     * Truncate an existing Zone model.
     * If truncate is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionClear($truncate = true)
    {
        $query = Zone::find();
        foreach ($query->each() as $model) {
            $model->delete();
        }
        if ($truncate) {
            Zone::truncate();
        }
        \Yii::$app->session->setFlash('success', 'Zone Cleared !!!');
        return $this->redirect([
            'index'
        ]);
    }

    /**
     * Finds the Zone model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Zone the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $accessCheck = true)
    {
        if (($model = Zone::findOne($id)) !== null) {

            if ($accessCheck && ! ($model->isAllowed()))
                throw new HttpException(403, Yii::t('app', 'You are not allowed to access this page.'));

            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function updateMenuItems($model = null)
    {
        switch (\Yii::$app->controller->action->id) {

            case 'add':
                {
                    $this->menu['index'] = [
                        'label' => '<span class="glyphicon glyphicon-list"></span>',
                        'title' => Yii::t('app', 'Manage'),
                        'url' => [
                            'index'
                        ]
                        // 'visible' => User::isAdmin ()
                    ];
                }
                break;
            case 'index':
                {
                    $this->menu['add'] = [
                        'label' => '<span class="glyphicon glyphicon-plus"></span>',
                        'title' => Yii::t('app', 'Add'),
                        'url' => [
                            'add'
                        ]
                        // 'visible' => User::isAdmin ()
                    ];
                    $this->menu['Download Sample'] = [
                        'label' => '<span class="glyphicon glyphicon-import">Download Sample</span>',
                        'title' => Yii::t('app', 'Download Sample'),
                        'url' => [
                            'generate-file'
                        ],
                        'visible' => true
                    ];
                    $this->menu['import'] = [
                        'label' => '<span class="glyphicon glyphicon-import">Import</span>',
                        'title' => Yii::t('app', 'Import'),
                        'url' => [
                            'import'
                        ],
                        'visible' => true
                    ];
                }
                break;
            case 'update':
                {
                    $this->menu['add'] = [
                        'label' => '<span class="glyphicon glyphicon-plus"></span>',
                        'title' => Yii::t('app', 'add'),
                        'url' => [
                            'add'
                        ]
                        // 'visible' => User::isAdmin ()
                    ];
                    $this->menu['index'] = [
                        'label' => '<span class="glyphicon glyphicon-list"></span>',
                        'title' => Yii::t('app', 'Manage'),
                        'url' => [
                            'index'
                        ]
                        // 'visible' => User::isAdmin ()
                    ];
                }
                break;

            default:
            case 'view':
                {
                    $this->menu['index'] = [
                        'label' => '<span class="glyphicon glyphicon-list"></span>',
                        'title' => Yii::t('app', 'Manage'),
                        'url' => [
                            'index'
                        ]
                        // 'visible' => User::isAdmin ()
                    ];
                    if ($model != null) {
                        $this->menu['update'] = [
                            'label' => '<span class="glyphicon glyphicon-pencil"></span>',
                            'title' => Yii::t('app', 'Update'),
                            'url' => $model->getUrl('update')
                            // 'visible' => User::isAdmin ()
                        ];
                    }
                }
        }
    }
}
