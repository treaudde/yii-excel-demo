<?php
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = 'Excel Upload';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Excel Upload</h1>

        <?php if(Yii::$app->user->isGuest ): ?>
        <p class="lead">Log In to access the uploader.</p>
        <?php else: ?>
            <p class="lead"><a href="<?=Url::toRoute('/excel-upload/index');?>">Click here</a> to upload a file.</p>
        <?php endif;?>

    </div>
</div>
