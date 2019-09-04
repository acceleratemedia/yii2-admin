<?php
namespace bvb\admin\widgets;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\widgets\Menu;
use Yii;

/**
 * SideNav implements a collapsing side navigation. It runs exactly like [[yii\widgets\Menu]]
 * but it applies a <nav> wrapper and also to the [[items]] property it allows us to add an extra
 * key for the icon we want to render next to the text of the item.
 *
 * The way this is used in the admin theme by default is in the layout file main.php it will
 * look for the view params by a call to  "$this->params['sideNav']['items']" to populate this navigation
 * with links
 */
class CollapsibleSideNav extends Menu
{
    /**
     * Implements the attributes and markup for the collapse functionality on the labels .
     * {collapse-target-name} is replaced with a lowercased version of the label
     * {@inheritdoc}
     */
    public $labelTemplate = '<a data-target="#{collapse-target-name}-submenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">{label}</a>';

    /**
     * Adds a "nav-link" class to the link
     * {@inheritdoc}
     */
    public $linkTemplate = '<a class="nav-link" href="{url}">{label}</a>';

    /**
     * Implements the attributes and markup for submenus to collapse. {collapse-target-id} is
     * replaced with a lowercased version of the label
     * {@inheritdoc}
     */
    public $submenuTemplate = "\n<ul id=\"{collapse-target-id}-submenu\" class=\"collapse\">\n{items}\n</ul>\n";

    /**
     * Defaults the class to "navbar-nav" 
     * {@inheritdoc}
     */
    public $options = [
        'class' => 'navbar-nav'
    ];

    /**
     * Defaults te item clas to "nav-item"
     * {@inheritdoc}
     */
    public $itemOptions = [
        'class' => 'nav-item'
    ];

    /**
     * The tag and options used to render the wrapper
     * @var array
     */
    public $wrapperOptions = [
        'tag' => 'nav',
        'id' => 'sidenav',
        'class' => 'navbar-dark bg-dark'
    ];

    /**
     * The state of the side navigation. By default the menu is full on a large display
     * and minimized on a small display. This can change based on the display
     * @return string
     */
    public $state;

    /**
     * Name of the cookie used to determine the state of the sidenav. We use vanilla javascript
     * to set this to avoid using Yii's cookies since Yii's cookies will sometimes be hashed
     * and we want to be able to contain this functionality within this widget without
     * having to rely on any outside settings
     * @var string 
     */
    public $cookieName = 'sideNavState';

    /**
     * Identifier for when the menu's state is 'sm-maximized' which means showing the full menu
     * on a small screen
     * @var string
     */
    const STATE_SM_MAXIMIZED = 'sm-maximized';

    /**
     * Identifier for when the menu's state is 'lg-minimized' which means showing the minimized
     * on a large screeb
     * @var string
     */
    const STATE_LG_MINIMIZED = 'lg-minimized';


    /**
     * Sorts the items passed in by the value of the key 'weight'
     * Prepends items with a button to minimize and maximize
     * Sets the nav state of being visible or collapsed based on a cookie
     * Adds the state of the nav to the wrapper for the nav
     * {@inheritdoc}
     */
    public function run()
    {
        // --- Sort the items
        usort($this->items, function($a, $b){
            return ($a['weight'] == $b['weight']) ? 0 : ($a['weight'] < $b['weight'] ? -1 : 1);
        });

        // --- Prependd an item which is the collapse button to minimize the nav
        array_unshift($this->items, [
            'template' => '<a id="sidenav-lg-minimize-btn" class="nav-link text-center sidenav-size-toggler"><i class="fa fa-exchange-alt"></i></a>'.
                '<a id="sidenav-sm-maximize-btn" class="nav-link text-center sidenav-size-toggler"><i class="fa fa-exchange-alt"></i></a>',
            'options' => [
                'class' => 'nav-item text-center'
            ]
        ]);

        // --- If the state remains null try to get it from a cookie
        if($this->state === null && isset($_COOKIE[$this->cookieName])){
            $this->state = $_COOKIE[$this->cookieName];
        }

        $this->registerJavascript();
        $tag = ArrayHelper::remove($this->wrapperOptions, 'tag', 'nav');

        // --- Add the state as a class to the sidenav
        $this->wrapperOptions['class'] .= ' '.$this->state;
        echo Html::beginTag($tag, $this->wrapperOptions);
        
        parent::run();
        echo Html::endTag($tag);
    }   

    /**
     * Add the {collapse-target-id} replacement on submenu templates
     * {@inheritdoc}
     */
    protected function renderItems($items)
    {
        $n = count($items);
        $lines = [];
        foreach ($items as $i => $item) {
            $options = array_merge($this->itemOptions, ArrayHelper::getValue($item, 'options', []));
            $tag = ArrayHelper::remove($options, 'tag', 'li');
            $class = [];
            if ($item['active']) {
                $class[] = $this->activeCssClass;
            }
            if ($i === 0 && $this->firstItemCssClass !== null) {
                $class[] = $this->firstItemCssClass;
            }
            if ($i === $n - 1 && $this->lastItemCssClass !== null) {
                $class[] = $this->lastItemCssClass;
            }
            Html::addCssClass($options, $class);
            $menu = $this->renderItem($item);
            if (!empty($item['items'])) {
                $submenuTemplate = ArrayHelper::getValue($item, 'submenuTemplate', $this->submenuTemplate);
                $menu .= strtr($submenuTemplate, [
                    '{items}' => $this->renderItems($item['items']),
                    '{collapse-target-id}' => strtolower(Inflector::slug($item['label'])), // *** OUR UNIQUE ADDITION
                ]);
            }
            $lines[] = Html::tag($tag, $menu, $options);
        }
        return implode("\n", $lines);

    }

    /**
     * Add the {collapse-target-id} and {collapse-target-name} replacements on items
     * {@inheritdoc}
     */
    protected function renderItem($item)
    {
        $str = parent::renderItem($item);
        if(!empty($item['iconClass'])){
            $str = str_replace($item['label'], '<i class="'.$item['iconClass'].'" title="'.$item['label'].'"></i><span class="text">'.$item['label'].'</span>', $str);
        }
        if (strpos($str, '{collapse-target-name}') !== false) {
            $str = str_replace('{collapse-target-name}', strtolower(Inflector::slug($item['label'])), $str);
        }
        if (strpos($str, '{collapse-target-id}') !== false) {
            $str = str_replace('{collapse-target-id}', strtolower(Inflector::slug($item['label'])), $str);
        }
        return $str;
    }

    /**
     * Register the javascript that will collapse the sidebar navigation nad give the cookie
     * that will remember the state
     * @return void
     */
    private function registerJavascript()
    {
        $ready_js = <<<JAVASCRIPT
$('#sidenav-sm-maximize-btn').on('click', function () {
    $('#sidenav').toggleClass('sm-maximized').removeClass('lg-minimized');
    updateSidenavState()
});
$('#sidenav-lg-minimize-btn').on('click', function () {
    $('#sidenav').toggleClass('lg-minimized').removeClass('sm-maximized');
    updateSidenavState()
});
JAVASCRIPT;

        $this->getView()->registerJs($ready_js);

        $js = <<<JAVASCRIPT
function updateSidenavState()
{
    var state = ($("#sidenav").hasClass("lg-minimized") ? "lg-minimized" : ($("#sidenav").hasClass("sm-maximized") ? "sm-maximized": ""))
    document.cookie ="sideNavState="+state+"; path=/";
}
JAVASCRIPT;

        $this->getView()->registerJs($js, $this->getView()::POS_END);
    }
}


