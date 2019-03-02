<?php
namespace bvb\admin\widgets;

use yii\base\Widget;
use yii\helpers\ArrayHelper;
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
     * Configuration options for the tag wrapper for the title. If set to false or null will use title property raw
     * @var array
     */
    public $titleTag = [
        'tag' => 'h1',
        'options' => [
            'class' => 'h2'
        ]
    ];

    /**
     * Buttons to be rendered in the right side of the toolbar
     * @var string
     */
    public $buttons;

    /**
     * Configuration options for the title. If set to false or null will use title property raw
     * @var array
     */
    public $buttonContainer = [
        'tag' => 'div',
        'options' => [
            'class' => 'btn-toolbar mb-2 mb-md-0'
        ]
    ];

    /**
     * Template used to adjust layout of elements
     * @var string
     */
    public $template = '{title}{buttons}';

    /**
     * 
     * {@inheritdoc}
     */
    public function run()
    {
        // --- Hold the parts in an array
        $parts = [];

        // --- If there is no title set default to the title of the view
        $parts['{title}'] = ($this->title === null) ? $this->getView()->title : $this->title;
        if(!empty($this->titleTag) && !empty($parts['{title}'])){
            $tag = ArrayHelper::remove($this->titleTag, 'tag');
            $parts['{title}'] = Html::tag($tag, $parts['{title}'], $this->titleTag['options']);
        }

        $parts['{buttons}'] = $this->buttons;
        if(!empty($this->buttonContainer) && !empty($parts['{buttons}'])){
            $tag = ArrayHelper::remove($this->buttonContainer, 'tag');
            $parts['{buttons}'] = Html::tag($tag, $parts['{buttons}'], $this->buttonContainer['options']);
        }

        $content = strtr($this->template, $parts);
        $html = <<<HTML
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    $content
</div>
HTML;
        echo $html;
   }   
}