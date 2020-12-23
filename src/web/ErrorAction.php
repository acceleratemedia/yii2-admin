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
        Yii::setAlias("@bvb-admin", __DIR__.'/../');
        Yii::$app->layout = '@bvb-admin/views/layouts/base';
        Yii::$app->view->params['bodyClass'] = 'text-center p-5 m-5';
    }
}