<?php

use bvb\admin\widgets\Slugify;

$modelFormName = strtolower($model->formName());
?>

<div class="name-slug-container">
    <div class="name-container">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Enter Name Here']) ?>
    </div>

    <div class="slug-container">
        <?php
        $slugHint = (isset($model->attributeHints()['slug']) ? $model->attributeHints()['slug'] : 'Unique string to identify this object usually for SEO friendly URLs');
        echo $form->field($model, 'slug', [
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
        ])->hint($slugHint)->textInput(['maxlength' => true]) ?>
    </div>
</div>

<?php
// --- Slugify the name
Slugify::widget([
    'generating_input_id' => $modelFormName.'-name',
    'slug_receiving_input_id' => $modelFormName.'-slug',
]);

$readyJs = <<<JAVASCRIPT
$("body").on("click", ".slug-field-container .btn", function(e){
    $("#dynamic-slug-text, .slug-field-container .btn").fadeOut(300, function(e){
        $(".slug-field-container input").fadeIn();
    });
})

$("body").on("change", "#{$modelFormName}-slug", function(e){
    $("#dynamic-slug-text").text($(this).val());
})
JAVASCRIPT;

$this->registerJs($readyJs);