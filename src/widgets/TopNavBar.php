<?php

namespace bvb\admin\widgets;

use yii\helpers\Html;
use yii\widgets\Menu;
use Yii;

/**
 * TopNavBar implements a NavBar at the top of the page
 */
class TopNavBar extends Menu
{
    /**
     * @var string The text to be in the upper left brand part of the navbar
     */
    public $brand;

    /**
     * {@inheritdoc}
     */
    public $linkTemplate = '<a class="nav-link" href="{url}">{label}</a>';

    /**
     * {@inheritdoc}
     */
    public $options = [
        'class' => 'navbar-nav ml-auto nav'
    ];

    /**
     * {@inheritdoc}
     */
    public $itemOptions = [
        'class' => 'nav-item'
    ];

    /**
     * @var boolean whether or not to render the logout button
     */
    public $logoutButton = true;

    /**
     * @var boolean whether or not to render a link to the front end
     */
    public $frontendLink = true;

    /**
     * Implements an opening 'nav' tag with changea
     * {@inheritdoc}
     */
    public function run()
    {
        if($this->brand === null){
            $this->brand = Yii::$app->name;
        }
        if($this->logoutButton){
            $this->renderLogoutButton();
        }
        if($this->frontendLink){
            $this->renderFrontendLink();
        }
        echo Html::beginTag('nav', ['id' => 'top-nav', 'class' => 'navbar navbar-dark bg-dark']);
            echo Html::a($this->brand, ['/'], ['class' => 'navbar-brand']);
            parent::run();
        echo Html::endTag('nav');
    }

    /**
     * Add a menu item link to the front end
     * @return void
     */
    public function renderLogoutButton()
    {
        $this->items[] = ['label' => 'Frontend', 'url' => '/'];
    }

    /**
     * Add a menu item which will be a form to logout
     * @return void
     */
    public function renderFrontendLink()
    {
        $this->items[] = [
            'template' => Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout',
                    ['class' => 'btn btn-link nav-link logout']
                )
                . Html::endForm()
        ];
    }
}


