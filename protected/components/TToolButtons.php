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
namespace app\components;

use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use Yii;
use yii\base\InvalidRouteException;

/**
 * TToolButtons
 *
 * @property array $htmlOptions
 * @property string $title
 */
class TToolButtons extends TBaseWidget
{

    public $title;

    public $htmlOptions;

    /**
     *
     * @var string Current action id.
     */
    public $actionId;

    /**
     *
     * @var array List of buttons actions. ('index', 'add', 'update', 'delete')
     *     
     */
    public $actions = [
        'add' => '<span class="glyphicon glyphicon-plus"></span>',
        'update' => '<span class="glyphicon glyphicon-pencil"></span>',
        'view' => '<span class="glyphicon glyphicon-eye-open"></span>',
        'delete' => '<span class="glyphicon glyphicon-trash"></span>',
        'clear' => '<span class="glyphicon glyphicon-remove-sign"></span>',
        'clone' => '<span class="glyphicon glyphicon-copy"></span>'
    ];

    /**
     *
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (! $this->actionId) {
            $this->actionId = Yii::$app->controller->action->id;
        }
    }

    /**
     *
     * @inheritdoc
     */
    public function run()
    {
        echo '<div class="text-right">';

        if (is_array(\Yii::$app->controller->menu))
            foreach (\Yii::$app->controller->menu as $key => $menu) {

                if ($key != 0 && ! $this->hasAction($key)) {
                    Yii::error($key . " action  doesn't exist");
                    if (YII_ENV == 'dev') {
                        throw new InvalidRouteException($key . " action  doesn't exist");
                    }
                    continue;
                }

                if (isset($this->actions[$key])) {

                    if ($key == 'add') {

                        $menu['class'] = 'btn btn-success';
                    } elseif ($key == 'clean') {

                        $menu['class'] = 'btn btn-warning';
                    } elseif ($key == 'delete' || $key == 'clear') {

                        $menu['class'] = 'btn btn-danger';

                        $menu['label'] = $this->actions[$key];
                        $menu['htmlOptions']['data'] = [
                            'method' => 'POST',
                            'confirm' => Yii::t('app', 'Are you sure you want to delete this ?')
                        ];
                    }
                    if (! isset($menu['label'])) {
                        $menu['label'] = $this->actions[$key];
                    }
                }
                $visible = true;
                if (isset($menu['visible']))
                    if ($menu['visible'] == true)
                        $visible = true;
                    else
                        $visible = false;
                $this->htmlOptions = [
                    'class' => isset($menu['class']) ? $menu['class'] : 'btn btn-warning',
                    'title' => isset($menu['title']) ? $menu['title'] : $menu['label'],
                    'id' => isset($menu['id']) ? $menu['id'] : "tool-btn-" . $key,
                    'data-pjax' =>  isset($menu['htmlOptions']['data-pjax']) ? $menu['htmlOptions']['data-pjax'] : 0
                ];
                if (isset($menu['htmlOptions']))
                    $this->htmlOptions = array_merge($menu['htmlOptions'], $this->htmlOptions);
                if ($visible)
                    echo ' ' . Html::a($menu['label'], $menu['url'], $this->htmlOptions);
            }
        echo '</div>';
    }

    /**
     * Returns a value indicating whether a controller action is defined.
     *
     * @param string $id
     *            Action id
     * @return bool
     */
    protected function hasAction($action)
    {
        return true; // Yii::$app->controller->hasAction($action);
    }
}