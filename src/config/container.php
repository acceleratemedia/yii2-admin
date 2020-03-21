<?php

// --- Holds site-wide common configuration for certain classes
return [
    'container' => [
        'definitions' => [
            // --- Special format for forms
            \kartik\form\ActiveField::class => [
                'hintType' => \kartik\form\ActiveField::HINT_SPECIAL,
                'hintSettings' => [
                    'onLabelHover' => false,
                    'onLabelClick' => true,
                ]
            ],
            // --- Default desired configuration for Select2 widget
            \kartik\select2\Select2::class => [
                'options' => [
                    'placeholder' => 'Select one or more..',
                    'encode' => false,
                    'multiple' => true
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                    'tags' => true,
                    'closeOnSelect' => false
                ]
            ],
            \kartik\datetime\DateTimePicker::class => [
                'pluginOptions' => [
                    'autoclose' => true,
                    'todayBtn' => true,
                    'format' => 'yyyy-mm-dd hh:ii:ss'
                ]
            ],
        ]
    ]
];