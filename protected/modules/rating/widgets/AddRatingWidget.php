<?php
namespace app\modules\rating\widgets;

use app\modules\rating\assets\RatingAsset;
use yii\base\Widget;
use yii\helpers\Url;
use app\modules\rating\models\Rating;

class AddRatingWidget extends Widget
{

    public $options;

    public $pluginOptions;

    public $model;

    public function init()
    {
        parent::init();

        $this->getView()->registerAssetBundle(RatingAsset::class);
    }

    public function run()
    {
        $this->renderHtml();
    }

    public function renderHtml()
    {
        $ratingModel = Rating::find()->where([
            'model_id' => $this->model->id,
            'model_type' => get_class($this->model),
            'created_by_id' => \Yii::$app->user->id
        ])->one();

        if (empty($ratingModel)) {
            $ratingModel = new Rating();
        }

        echo $this->render('_rating-form', [
            'options' => $this->options,
            'pluginOptions' => $this->pluginOptions,
            'model' => $this->model,
            'ratingModel' => $ratingModel
        ]);
    }
}
