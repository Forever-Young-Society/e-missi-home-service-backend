<?php
/**
 *
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author     : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */
namespace app\parsers;

use app\models\Feed;
use app\models\ImportFile;
use app\modules\workzone\models\Postcode;
use app\modules\workzone\models\Zone;

class LocationMasterUpload implements ImportfileInterface
{

    const COLUMN_SERIAL = 'S.No.';

    const COLUMN_TITLE = 'Title';

    const COLUMN_ZIPCODE = 'Zipcode';

    private $final_data = null;

    private $data_type = null;

    public $file_error = null;

    function __construct($metaData, $data_type)
    {
        if (empty($metaData) || ! is_array($metaData)) {
            $this->file_error = [
                "error" => "No Data Passed"
            ];

            return $this->file_error;
        }

        $class = substr(strrchr(get_class($data_type), "\\"), 1);

        $finalResult = [];

        $this->data_type = $data_type;

        foreach ($metaData as $key => $row) {

            if ($key === 0) {
                $serialNo = array_search(self::COLUMN_SERIAL, $row);
                $titleNo = array_search(self::COLUMN_TITLE, $row);
                $zipNo = array_search(self::COLUMN_ZIPCODE, $row);

                if ($titleNo === '' || $zipNo === '') {

                    $this->file_error = [
                        "error" => "One of the required column is not found"
                    ];
                    // throw new \Exception("One of the required column is not found");

                    return $this->file_error;
                    break;
                }
                continue;
            }

            $finalResultData = [
                'title' => $row[$titleNo],
                'primary_zipcode' => $row[$zipNo]
            ];

            $finalResult[] = [
                $class => $finalResultData
            ];
        }

        $this->final_data = $finalResult;
    }

    public function getResults()
    {
        return $this->final_data;
    }

    public function getMapped($class)
    {
        foreach ($this->final_data as $key => $value) {
            // $model = $class;
            $class_name = substr(strrchr(get_class($class), "\\"), 1);

            $model = $class;
            $type = [
                'class' => get_class($class)
            ];

            $title = $value['Zone']['title'];
            $title = trim($title);
            $model = Zone::find()->where([
                'LOWER(title)' => strtolower($title)
            ])->one();
            if (empty($model)) {
                $model = \Yii::createObject($type);
            }

            if ($model->load($value)) {
                if (! empty($model->primary_zipcode)) {
                    $zipcodeArray = explode(',', $model->primary_zipcode);
                    if (is_array($zipcodeArray)) {
                        Postcode::deleteAll([
                            'location_id' => $model->id
                        ]);
                        foreach ($zipcodeArray as $zipcode) {
                            $postCode = new Postcode();
                            $postCode->location_id = $model->id;
                            $postCode->post_code = $zipcode;
                            $postCode->title = $model->title;
                            $postCode->type_id = Postcode::TYPE_PRIMARY_LOCATION;
                            if (! $postCode->save()) {
                                Feed::add($model, "IMPORT SAVE ELSE - " . $postCode->getErrorsString());
                            }
                        }
                    }
                }
                if (! empty($model))

                    if ($model->save()) {

                        Feed::add($model, 'Created new ' . $class_name . ' Record for ' . $model->title);
                    } else {
                        Feed::add($model, "IMPORT SAVE ELSE - " . $model->getErrorsString());
                    }
            } else {

                Feed::add($model, "ERR IMPORT LOAD - " . $model->getErrorsString());
            }
        }
    }

    public function getUploadData($class, $import_data)
    {
        $i = 0;
        foreach ($this->final_data as $key => $value) {

            // $model = $class;
            $class_name = substr(strrchr(get_class($class), "\\"), 1);

            $model = $class;
            $type = [
                'class' => get_class($class)
            ];
            $model = \Yii::createObject($type);

            if ($model->load($value)) {

                if ($model->save()) {

                    $i = $i + 1;
                    $import_data->download_count = $i;

                    $done = $i;
                    $percentage = 0;
                    $total = sizeof($this->final_data);
                    $percentage = round(100 * $done / $total);
                    if ($percentage > 100) {
                        $percentage = 100;
                    }

                    $import_data->percentage = $percentage;
                    $import_data->updateAttributes([
                        'percentage',
                        'download_count'
                    ]);
                    Feed::add($model, 'Created new ' . $class_name . ' Record for ' . $model->title);
                } else {
                    $import_data->state_id = ImportFile::STATE_DELETED;
                    $import_data->failure_reason = "IMPORT SAVE ELSE - " . $model->getErrorsString();
                    $import_data->updateAttributes([
                        'state_id',
                        'failure_reason'
                    ]);
                    Feed::add($model, "IMPORT SAVE ELSE - " . $model->getErrorsString());
                }
            } else {
                $import_data->state_id = ImportFile::STATE_DELETED;
                $import_data->failure_reason = "ERR IMPORT LOAD - " . $model->getErrorsString();
                $import_data->updateAttributes([
                    'state_id',
                    'failure_reason'
                ]);
                Feed::add($model, "ERR IMPORT LOAD - " . $model->getErrorsString());
            }
        }
    }
}

?>
