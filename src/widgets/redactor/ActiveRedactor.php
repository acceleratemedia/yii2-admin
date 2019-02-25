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
        return $this->render('active_redactor', [
            'form' => $this->form,
            'model' => $this->model,
            'attribute' => $this->attribute
        ]);
    }
}
?>