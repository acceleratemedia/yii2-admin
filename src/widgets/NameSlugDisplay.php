<?php

namespace bvb\admin\widgets;

use Yii;
use yii\bootstrap4\Widget;
use yii\helpers\Html;
use yii\helpers\Inflector;

/**
 * Displays a name and slug field together where the slug display
 * will be shown under the name field with an edit button to reveal the
 * hidden slug field for editing
 */
class NameSlugDisplay extends Widget
{
    /**
     * @var \yii\db\ActiveRecord
     */
    public $model;

    /**
     * @var \yii\widgets\ActiveForm
     */
    public $form;

    /**
     * The text to be shown before the slug in the link display. Defaults to 
     * current host info
     * @var string
     */
    public $linkTextPrefix;

    /**
     * Overrides the default behavior to implement some fields that are common to all content
     * {@inheritdoc}
     */
    public function run()
    {
        if(empty($this->linkTextPrefix)){
            $this->linkTextPrefix = Yii::$app->request->getHostInfo().'/'.Inflector::camel2id($this->model->formName());
        }
        return $this->render('name_slug_display', [
            'form' => $this->form,
            'linkTextPrefix' => empty($this->linkTextPrefix) ?  : $this->linkTextPrefix,
            'model' => $this->model
        ]);
    }
}