<?php

/* @var $this \yii\web\View */
/* @var $content string */


use bvb\admin\assets\AppAsset;
use bvb\admin\widgets\CollapsibleSideNav;
use bvb\admin\widgets\TopNavBar;
use bvb\admin\widgets\Toolbar;
use common\widgets\Alert;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<?= TopNavBar::widget([
    'items' => isset($this->params['topNavBar']['items']) ? $this->params['topNavBar']['items'] : []
]); ?>
<div class="wrapper">
    <?= CollapsibleSideNav::widget([
        'items' => isset($this->params['sideNav']['items']) ? $this->params['sideNav']['items'] : [],
    ]);
    ?>
    <div id="page">
        <?php 
        if(isset($this->params['form'])){
            // --- This mimics what is in ActiveForm::run() to begin the form
            // --- The key part of this is that the form should already be registered during the action
            // --- based on whether or not we want the view will be a form
            echo Html::beginForm($this->params['form']->action, $this->params['form']->method, $this->params['form']->options);
        }
        if(isset($this->params['toolbar'])){
            $toolbarClass = ArrayHelper::remove($this->params['form'], 'class', Toolbar::class);
            echo $toolbarClass::widget($this->params['toolbar']);
        }
        echo Alert::widget();
        
        echo $content;
        
        if(isset($this->params['form'])){
            // --- This mimics what is in ActiveForm::run() to end the form. We register the client scripts
            // --- which should implement all of the validation and scripts compiled in the view
            if ($this->params['form']->enableClientScript) {
                $this->params['form']->registerClientScript();
            }
            echo Html::endForm();
        }
        ?>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
