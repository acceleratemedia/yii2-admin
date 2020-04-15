<?php

namespace bvb\admin\grid;

use yii\bootstrap4\Dropdown;
use Yii;
use yii\helpers\Html;

/**
 * Override this class so that we can use font awesome buttons
 */
class ActionColumn extends \yii\grid\ActionColumn
{
    /**
     * Set to true to display links in a popover. If this is set to true it will
     * use the order left to right in [[template]] of the buttons to render them
     * top to bottom and anything else in [[template]] is ignored
     * @var boolean
     */
    public $linksInDropdown = false;

    /**
     * Customized to use font awesome classes
     * {@inheritdoc}
     */
    protected function initDefaultButtons()
    {
        $this->initDefaultButton('view', 'eye'); // --- customized
        $this->initDefaultButton('update', 'edit'); // --- customized
        $this->initDefaultButton('delete', 'trash', [
            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
            'data-method' => 'post',
        ]);
    }

    /**
     * Customized to use font awesome classes
     * {@inheritdoc}
     */
    protected function initDefaultButton($name, $iconName, $additionalOptions = [])
    {
        if (!isset($this->buttons[$name]) && strpos($this->template, '{' . $name . '}') !== false) {
            $this->buttons[$name] = function ($url, $model, $key) use ($name, $iconName, $additionalOptions) {
                switch ($name) {
                    case 'view':
                        $title = Yii::t('yii', 'View');
                        break;
                    case 'update':
                        $title = Yii::t('yii', 'Update');
                        break;
                    case 'delete':
                        $title = Yii::t('yii', 'Delete');
                        break;
                    default:
                        $title = ucfirst($name);
                }
                $options = array_merge([
                    'title' => $title,
                    'aria-label' => $title,
                    'data-pjax' => '0',
                ], $additionalOptions, $this->buttonOptions);
                $icon = Html::tag('i', '', ['class' => "fas fa-$iconName"]); // --- Customized
                return Html::a($icon, $url, $options);
            };
        }
    }

    /**
     * If [[linksInDropdown]] is set to true, render the links in a dropdown
     * displayed to the left
     * {@inheritdoc}
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        if($this->linksInDropdown){
            $content = parent::renderDataCellContent($model, $key, $index);
            preg_match_all('#(.+?</a>)#', $content, $links);
            $dropdownItems = [];
            foreach($links[0] as $link){
                $dropdownItems[] = '<div class="dropdown-item">'.$link.'</div>';
            }
            $dropdownMenu = Dropdown::widget([
                'items' => $dropdownItems
            ]);
return <<<HTML
<div class="btn-group dropleft">
    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Actions
    </button>
    $dropdownMenu
</div>
HTML;

        }
        return parent::renderDataCellContent($model, $key, $index);
    }
}
