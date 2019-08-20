<?php

namespace bvb\admin\web;

use Yii;

/**
 * Override the default implementation to remove the layout and View component 
 * elements for the form and the toolbar. 
 */
class ErrorAction extends \yii\web\ErrorAction
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        Yii::$app->view->form = null;
        Yii::$app->view->toolbar = null;
    }
}