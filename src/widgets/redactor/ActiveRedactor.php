<?php
namespace bvb\admin\widgets\redactor;

use yii\base\Widget;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Implement yii\redactor\widgets\Redactor inside of a container so that it may
 * grow and increase in size but not beyond a limit and stay in that container
 * while scrolling
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
     * Passed through to yii\redactor\widgets\Redactor::$clientOptions
     * @var array
     */
    public $clientOptions;

    /**
     * @inheritdoc
     */
    public function run()
    {
        self::registerInlineCss();
        $defaultOptions = [
            'toolbarFixedTarget' => '#redactor-'.$this->attribute.'-container',
        ];
        return $this->render('active_redactor', [
            'form' => $this->form,
            'model' => $this->model,
            'attribute' => $this->attribute,
            'clientOptions' => ArrayHelper::merge($this->clientOptions, $defaultOptions)
        ]);
    }

    /**
     * Registers the inline CSS to the page for the redactor
     * This function is static because other widgets might want to register this 
     * CSS to use the style
     * @return void
     */
    static function registerInlineCss()
    {
        $css = <<<CSS
.redactor-container {
    max-height: 800px;
    overflow: auto;
}
.redactor-field-container{
    margin-bottom: 30px;
}
.redactor-box ~ .invalid-feedback{
    display:block;
}
.is-invalid ~ .redactor-editor{
    display:none;
}
CSS;

        Yii::$app->getView()->registerCss( preg_replace('/\s+/', '', $css), [], 'active-redactor-css' );
    }
}
?>