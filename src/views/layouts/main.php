<?php

/* @var $this \yii\web\View */
/* @var $content string */

use bvb\admin\widgets\Alert;
use bvb\admin\widgets\CollapsibleSideNav;
use bvb\admin\widgets\TopNavBar;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->beginContent('@bvb-admin/views/layouts/base.php');

echo TopNavBar::widget([
    'items' => isset($this->params['topNavBar']['items']) ? $this->params['topNavBar']['items'] : []
]); ?>
<div class="wrapper">
    <?= CollapsibleSideNav::widget([
        'items' => $this->sideNavItems,
    ]);
    ?>
    <div id="page">
        <?php 
        if($this->form){
            // --- This mimics what is in ActiveForm::run() to begin the form
            // --- The key part of this is that the form should already be registered during the action
            // --- based on whether or not we want the view will be a form
            echo Html::beginForm($this->form->action, $this->form->method, $this->form->options);
        }
        if($this->toolbar){
            echo Yii::createObject($this->toolbar)->run();
        }
        echo Alert::widget();
        
        echo $content;
        
        if(isset($this->form)){
            // --- This mimics what is in ActiveForm::run() to end the form. We register the client scripts
            // --- which should implement all of the validation and scripts compiled in the view
            if ($this->form->enableClientScript) {
                $this->form->registerClientScript();
            }
            echo Html::endForm();
        }
        ?>
    </div>
</div>
<?php $this->endContent(); ?>