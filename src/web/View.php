<?php

namespace bvb\admin\web;

use bvb\admin\assets\AppAsset;
use Yii;

/**
 * View is an extension of Yii's web View component adding properties that
 * are conducive to the 'admin' theme
 */
class View extends \yii\web\View
{
    /**
     * In this theme the default layout consists of a the top nav bar, side 
     * navigation, and a 'page' area that in many cases will require a form.
     * By providing the form as a property on the view component, we can access
     * it in the layout as well as all rendered views. This provides the 
     * convenience of automatically wrapping the view in a form and being able 
     * to configure it in actions along with other layout components like the toolbar
     * @var \kartik\form\ActiveForm
     */
    public $form;

    /**
     * The 'page' area of the default layout has the option of a toolbar being
     * at the top. By providing this as a variable on the view component we can
     * configure it along with the form across views as well as in actions.
     * @var \kartik\form\ActiveForm
     */
    public $toolbar;

    /**
     * The name of the asset bundle which will be registered in the layout file.
     * By default it uses the asset bundle packaged with this admin theme. If 
     * you need additional CSS files to be rendered you may create your own 
     * AssetBundle class and the 'depends' property you can include the
     * bvb\admin\asset\AppAsset bundle to keep loading this
     * @var string
     */
    public $assetBundle = AppAsset::class;

    /**
     * Configuration for items to go in the side navigation. The format is the
     * same as when using \yii\widgets\Menu with an additional 'weight' property
     * for ordering the items and 'iconClass' for the font awesome icon to be 
     * rendered if desired:
     *
     * ```php
     *   [
     *       'iconClass' => 'fas fa-newspaper',
     *       'label' => 'Articles',
     *       'url' => ['/article/index']
     *       'weight' => 200,
     *       'title' => 'Articles'
     *   ],
     * ```
     */
    public $sideNavItems = [];

    /**
     * Implement common container definitions desired for the admin setup
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        Yii::configure(Yii::$app, include(__DIR__.'/../config/container.php'));
    }

    /**
     * Adds an item to the sidenav
     * @param array $itemConfig
     * @return void
     */
    public function registerSideNavItem($itemConfig)
    {
        $this->sideNavItems[] = $itemConfig;
    }
}