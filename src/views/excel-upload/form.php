<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JqueryAsset;

$this->registerJsFile('@web/js/canvasjs/canvasjs.min.js',
    ['depends' => JqueryAsset::class]);

/* @var $this yii\web\View */
/* @var $model app\models\forms\ExcelUploadForm */
/* @var $form ActiveForm */
?>


<div class="row">
    <div class="excel-upload-form col-12">

        <?php if(!is_null($errorMessage)): ?>
        <div class="alert alert-danger">
            <?php echo $errorMessage; ?>
        </div>
        <?php endif; ?>

        <h1>Excel Upload</h1>
        <p>Upload your Excel File Here </p>

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
        <div class="form-group">
            <?= $form->field($model, 'excelFile')->fileInput() ?>
        </div>


        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>

    </div>
</div>

<?php if(!is_null($chartData)): ?>
<div class="row">
    <div class="col-12">
        <div id="chartContainer" style="height: 600px; width: 100%;"></div>
    </div>
</div>

    <script type="text/javascript">
        window.onload = function () {
            var chart = new CanvasJS.Chart("chartContainer",
                {
                    title:{
                        text: "<?php echo $chartData['title'] ?>"
                    },
                    legend: {
                        maxWidth: 350,
                        itemWidth: 120
                    },
                    data: [
                        {
                            type: "pie",
                            toolTipContent: "{y} - #percent %",
                            showInLegend: true,
                            legendText: "{indexLabel}",
                            dataPoints: [
                                <?php foreach($chartData['data'] as $data): ?>
                                { y: <?php echo $data['count']?>, indexLabel: "<?php echo $data['name']?>" },
                                <?php endforeach; ?>
                            ]
                        }
                    ]
                });
            chart.render();
        }
    </script>
<?php endif; ?>

