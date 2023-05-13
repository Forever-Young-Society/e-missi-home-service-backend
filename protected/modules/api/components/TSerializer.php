<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\api\components;

use app\components\TActiveRecord;
use yii\base\Arrayable;
use yii\rest\Serializer;

/**
 * Default controller for the `Api` module
 */
class TSerializer extends Serializer
{

    public $collectionEnvelope = 'list';
    /**
     * Serializes a model object.
     *
     * @param Arrayable $model
     * @return array the array representation of the model
     */
    protected function serializeModel($model)
    {
        if ($this->request->getIsHead()) {
            return null;
        }

        return $model->asJson();
    }
    protected function serializeModels(array $models)
    {
        foreach ($models as $i => $model) {

            if ($model instanceof TActiveRecord) {
                $models[$i] = $model->asJson();
            } elseif (is_array($model)) {
                $models[$i] = $model;
            }
        }
        return $models;
    }
}