<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\components;

use Yii;
use yii\helpers\Html;

class TActionColumn extends \yii\grid\ActionColumn
{

    function init()
    {
        parent::init();
        $this->initDefaultButtons();
        $this->urlCreator = function ($action, $model, $key, $index) {
            return $model->getUrl($action);
        };
    }

    protected function initDefaultButtons()
    {
        if ($this->hasAction('view') && ! isset($this->buttons['view'])) {
            $this->buttons['view'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'View'),
                    'aria-label' => Yii::t('yii', 'View'),
                    'data-pjax' => '0',
                    'class' => 'btn btn-success'
                ], $this->buttonOptions);
                return Html::a('<span class="fa fa-eye"></span>', $url, $options);
            };
        }
        if ($this->hasAction('update') && ! isset($this->buttons['update'])) {
            $this->buttons['update'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'Update'),
                    'aria-label' => Yii::t('yii', 'Update'),
                    'data-pjax' => '0',
                    'class' => 'btn btn-info'
                ], $this->buttonOptions);
                return Html::a('<span class="fa fa-pencil"></span>', $url, $options);
            };
        }
        if ($this->hasAction('delete') && ! isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'Delete'),
                    'aria-label' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                    'class' => 'btn btn-danger'
                ], $this->buttonOptions);
                return Html::a('<span class="fa fa-trash-o"></span>', $url, $options);
            };
        }
    }

    protected function hasAction($action)
    {
        return true;
        // return Yii::$app->controller->hasAction($action);
    }
}