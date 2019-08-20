<?php
namespace bvb\admin\controllers;

use bvb\user\backend\controllers\traits\AdminAccess;
use bvb\admin\web\ErrorAction;
use Yii;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * Implement AccessControl that requires admin role to access actions
     */
    use AdminAccess;

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays basic admin page
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Added to make sure views are being used from this module since in DCMS we consider
     * this the root module of the application but we also want to have it as a separate
     * installable module of its own
     * {@inheritdoc}
     */
    public function getViewPath()
    {
        return __DIR__.'/../views/site';
    }
}
