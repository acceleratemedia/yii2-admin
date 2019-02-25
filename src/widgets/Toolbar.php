<?php
namespace bvb\admin\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use Yii;

/**
 * Toolbar implements a horizontal toolbar across the top of admin pages
 */
class Toolbar extends Widget
{
    /**
     * The title to be displayed in the top left of the toolbar. Defaults to the title
     * of the view if left empty.
     * @var string
     */
    public $title;

    /**
     * Buttons to be rendered in the right side of the toolbar
     * @var string
     */
    public $buttons;

    /**
     * 
     * {@inheritdoc}
     */
    public function run()
    {
        // --- If there is no title set default to the title of the view
        if(empty($this->title)){
            $this->title = $this->getView()->title;
        }
        $title = Html::encode($this->title);
        $html = <<<HTML
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">{$title}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        {$this->buttons}
    </div>
</div>
HTML;
        echo $html;
   }   
}