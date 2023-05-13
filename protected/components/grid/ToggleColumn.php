<?php
/**
 *
 * @copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * @author : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\components\grid;

use yii\grid\DataColumn;
use yii\helpers\Html;
use yii\web\View;
use Yii;

/**
 * Toggle column
 *
 * @author shiv
 *        
 */
class ToggleColumn extends DataColumn
{

    /**
     * Toggle action that will be used as the toggle action in your controller
     *
     * @var string
     */
    public $action = 'toggle';

    public $onTitle = 'Click to Active';

    public $offTitle = 'Click to In-Active';

    /**
     *
     * @var string pk field name
     */
    public $primaryKey = 'id';

    /**
     * Whether to use ajax or not
     *
     * @var bool
     */
    public $enableAjax = true;

    public $gridViewId = null;

    public function init()
    {
        if ($this->enableAjax) {
            $this->registerJs();
        }
    }

    /**
     *
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $url = [
            $this->action,
            'id' => $model->{$this->primaryKey},
            'attribute' => $this->attribute,
            'page' => Yii::$app->request->get('page')
        ];

        $attribute = $this->attribute;
        $value = $model->$attribute;

        if ($value === null || $value == true) {
            $icon = 'ok';
            $class = 'btn btn-success';
            $title = Yii::t('yii', $this->offTitle);
        } else {
            $icon = 'remove';
            $class = 'btn btn-danger';
            $title = Yii::t('yii', $this->onTitle);
        }
        return Html::a('<span class="glyphicon glyphicon-' . $icon . '"></span>', $url, [
            'title' => $title,
            'class' => 'toggle-column ' . $class,
            'data-method' => 'post',
            'data-pjax' => '0'
        ]);
    }

    /**
     * Registers the ajax JS
     */
    public function registerJs()
    {
        $js = <<<'JS'
        $("a.toggle-column").on("click", function(e) {
            e.preventDefault();
            $.post($(this).attr("href"), function(data) {
                var pjaxId = $(e.target).closest(".grid-view").parent().attr("id");
                if ( pjaxId == null )
                {
                   pjaxId = $(e.target).closest(".grid-view").parent().parent().attr("id");
                } 
               $.pjax.reload({container:"#" + pjaxId});
            });
            return false;
        });
        JS;
        $this->grid->view->registerJs($js, View::POS_READY, 'pheme-toggle-column');
    }
}