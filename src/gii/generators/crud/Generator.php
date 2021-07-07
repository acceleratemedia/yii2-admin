<?php
namespace bvb\admin\gii\generators\crud;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Schema;
use yii\gii\CodeFile;
use yii\helpers\Inflector;
use yii\web\Controller;

/**
 * Custom implementation of templates and code generation for Accelm using Gii
 * @author Brian Van Buren <brian@accelm.com>
 */
class Generator extends \yii\gii\generators\crud\Generator
{
    public $modelClass='backend\models\\';
    public $searchModelClass='backend\models\search\\';
    public $controllerClass='backend\controllers\\';
    public $viewPath = '@backend/views/';
    public $baseControllerClass = 'bvb\crud\controllers\ActiveController';

    /**
     * Extend to make tinyint(1) db types use checkbox fields
     * Also use form propety of View component
     * {@inheritdoc}
     */
    public function generateActiveField($attribute)
    {
        $return = parent::generateActiveField($attribute);

        $tableSchema = $this->getTableSchema();
        $column = $tableSchema->columns[$attribute];
        if ($column->dbType == 'tinyint(1)') {
            $return = "\$this->form->field(\$model, '$attribute')->checkbox()";
        }

        // --- Use the form property of the View component
        $return = str_replace('$form', '$this->form', $return);

        return $return;
    }

    /**
     * Override to handle tinyint(1) db cols as booleab
     * {@inheritdoc}
     */
    public function generateColumnFormat($column)
    {
        $return = parent::generateColumnFormat($column);
        if ($column->dbType == 'tinyint(1)'){
            $return = 'boolean';
        } 
        return $return;
    }

    /**
     * Override to include tinyint(1) fields as boolean types
     * {@inheritdoc}
     */
    public function generateSearchRules()
    {
        if (($table = $this->getTableSchema()) === false) {
            return ["[['" . implode("', '", $this->getColumnNames()) . "'], 'safe']"];
        }
        $types = [];
        foreach ($table->columns as $column) {
            switch ($column->type) {
                case Schema::TYPE_SMALLINT:
                case Schema::TYPE_INTEGER:
                case Schema::TYPE_BIGINT:
                    if($column->dbType == 'tinyint(1)'){
                        $types['boolean'][] = $column->name;
                    } else {
                        $types['integer'][] = $column->name;
                    }
                    break;
                case Schema::TYPE_BOOLEAN:
                    $types['boolean'][] = $column->name;
                    break;
                case Schema::TYPE_FLOAT:
                case Schema::TYPE_DOUBLE:
                case Schema::TYPE_DECIMAL:
                case Schema::TYPE_MONEY:
                    $types['number'][] = $column->name;
                    break;
                case Schema::TYPE_DATE:
                case Schema::TYPE_TIME:
                case Schema::TYPE_DATETIME:
                case Schema::TYPE_TIMESTAMP:
                default:
                    $types['safe'][] = $column->name;
                    break;
            }
        }

        $rules = [];
        foreach ($types as $type => $columns) {
            $rules[] = "[['" . implode("', '", $columns) . "'], '$type']";
        }

        return $rules;
    }
}
