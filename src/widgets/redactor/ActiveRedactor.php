<?php
namespace bvb\admin\widgets\redactor;

use yii\base\Widget;
use Yii;

/**
 * Custom widget with styles and desired configuration for redactor
 */
class ActiveRedactor extends Widget
{
    /**
     * @var \yii\widgets\ActiveForm
     */
    public $form;

    /**
     * @var \yii\db\ActiveRecord
     */
    public $model;

    /**
     * @var string
     */
    public $attribute;

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerInlineCss();
        return $this->render('active_redactor', [
            'form' => $this->form,
            'model' => $this->model,
            'attribute' => $this->attribute
        ]);
    }

    /**
     * Registers the inline CSS to the page for the redactor
     * @return void
     */
    public function registerInlineCss()
    {
        $css = <<<CSS
.redactor-container {
    max-height: 500px;
    overflow: auto;
    margin-bottom: 30px;
}
.redactor-box ~ .invalid-feedback{
    display:block;
}
.is-invalid ~ .redactor-editor{
    display:none;
}
CSS;

        $this->getView()->registerCss( preg_replace('/\s+/', '', $css) );
    }
}
?>