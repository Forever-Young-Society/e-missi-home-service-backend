<?php
use app\components\grid\TGridView;
use yii\helpers\Html;

/**
 *
 * @copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * @author : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/* @var $this yii\web\View */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'APIs'),
    'url' => [
        'index'
    ]
];
?>


<div class="container-fluid wrapper">
	<div class="card">
		<div class="campaign-index">
        <?=  \app\components\PageHeader::widget(); ?>
        </div>

	</div>

	<div class="card">
		<div class="card-body">

               <?php
            echo TGridView::widget([
                'id' => 'helpers-grid',
                'dataProvider' => $dataProvider,
                'columns' => array(
                    'id',
                    'file',
                    [
                        'header' => 'Actions',
                        'format' => 'raw',
                        'value' => function ($data) {
                            return Html::a('Select', [
                                'move',
                                // 'id' => $data['id'],
                                'file' => $data['file']
                            ]);
                        }
                    ]
                )
            ]);
            ?>
		</div>
	</div>

</div>