<?php

namespace bvb\admin\widgets;

use yii\bootstrap4\Widget;
use yii\helpers\Html;

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
     * @var \yii\bootstrap4\ActiveForm
     */
    public $form;

    /**
     * Overrides the default behavior to implement some fields that are common to all content
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->render('name_slug_display', [
            'form' => $this->form,
            'model' => $this->model
        ]);
    }
}