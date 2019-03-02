<?php

namespace bvb\admin\widgets;

use Yii;
use yii\web\View;

/**
 * Slugify widget generates a slug for one input after blurring another input.
 * Optional prefix can be put onto slug
 */
class Slugify extends \yii\base\Widget
{
    /**
     * The prefix applied to the slug if desred
     * @var string
     */
    public $prefix;

    /**
     * The ID attribute of the input that will be used to generate the slug
     * @var string
     */
    public $generating_input_id;

    /**
     * The ID attribute of the input the slug will be put into
     * @var string
     */
    public $slug_receiving_input_id = '';

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $js = <<<JS
function slugify(text)
{
  return text.toString().toLowerCase()
    .replace(/\s+/g, '-')           // Replace spaces with -
    .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
    .replace(/\-\-+/g, '-')         // Replace multiple - with single -
    .replace(/^-+/, '')             // Trim - from start of text
    .replace(/-+$/, '');            // Trim - from end of text
}
JS;
        $this->getView()->registerJs($js, View::POS_END, 'slugify-function');

        $ready_js = <<<JS
$("body").on("blur", "#{$this->generating_input_id}", function(e){
    $("#{$this->slug_receiving_input_id}").val( "{$this->prefix}"+slugify( $(this).val() ) );
    $("#{$this->slug_receiving_input_id}").trigger("change");
});
JS;
        $this->getView()->registerJs($ready_js);
    }
}
