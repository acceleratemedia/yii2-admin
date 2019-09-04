<?php

use bvb\admin\widgets\Slugify;

$model_form_name = strtolower($model->formName());
?>

<div class="name-slug-container">
    <div class="name-container">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Enter Name Here']) ?>
    </div>

    <div class="slug-container">
        <?= $form->field($model, 'slug', [
            'options' => [
                'class' => 'slug-field-container'
            ],
            'addon' => [
                'append' => [
                    'content' => '<a class="btn btn-sm">Edit</a>',
                    'asButton' => true
                ],
                'prepend' => [
                    'content' => 'Link: '.$linkTextPrefix.'/<span id="dynamic-slug-text">'.$model->slug.'</span>'
                ]
            ],
            'hintType' => \kartik\form\ActiveField::HINT_SPECIAL,
            'hintSettings' => [
                'iconBesideInput' => true,
                'inputTemplate' => '{input}{help}',
            ]
        ])->textInput(['maxlength' => true]) ?>
    </div>
</div>

<?php
// --- Slugify the name
Slugify::widget([
    'generating_input_id' => $model_form_name.'-name',
    'slug_receiving_input_id' => $model_form_name.'-slug',
]);

$ready_js = <<<JS
$("body").on("click", ".slug-field-container .btn", function(e){
    $("#dynamic-slug-text, .slug-field-container .btn").fadeOut(300, function(e){
        $(".slug-field-container input").fadeIn();
    });
})

$("body").on("change", "#{$model_form_name}-slug", function(e){
    $("#dynamic-slug-text").text($(this).val());
})
JS;

$this->registerJs($ready_js);