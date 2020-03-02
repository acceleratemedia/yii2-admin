<?php

use yii\redactor\widgets\Redactor;
use yii\helpers\Html;

echo $form->field($model, $attribute, [
        'template' => "{label}<div id=\"redactor-$attribute-container\" class=\"redactor-container\">\n{input}{error}\n</div>\n{hint}\n",
        'options' => [
            'class' => 'form-group redactor-field-container'
        ]
    ])->widget(
        Redactor::class, 
        [
            'model' => $model,
            'attribute' => $attribute,
            'clientOptions' => [
                'toolbarFixedTarget' => '#redactor-'.$attribute.'-container'
            ]
        ]
);