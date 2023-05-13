<?php
namespace app\modules\rating\widgets;

use app\modules\rating\assets\RatingAsset;
use yii\base\Widget;
use yii\data\ActiveDataProvider;
use app\modules\rating\models\Rating;

class RatingWidget extends Widget
{

    public $options;

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
        $query = Rating::findActive()->andWhere([
            'model_id' => $this->model->id,
            'model_type' => get_class($this->model)
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);

        echo $this->render('_rating-view', [
            'model' => $this->model,
            'options' => $this->options,
            'dataProvider' => $dataProvider,
            'query' => $query
        ]);
    }
}
