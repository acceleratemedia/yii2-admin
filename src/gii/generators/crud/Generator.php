<?php
namespace bvb\admin\gii\generators\crud;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Schema;
use yii\gii\CodeFile;
use yii\helpers\Inflector;
use yii\helpers\VarDumper;
use yii\web\Controller;

/**
 * Custom implementation of templates and code generation for Accelm using Gii
 * @author Brian Van Buren <brian@accelm.com>
 */
class Generator extends \yii\gii\generators\crud\Generator
{
    public $modelClass='common\models\\';
    public $searchModelClass='backend\models\\';
    public $controllerClass='backend\controllers\\';
    public $viewPath = '@backend/views/';
    public $baseControllerClass = 'bvb\crud\controllers\ActiveController';

    /**
     * Generates code for active field
     * This was updated to utilize the built in form into the view component and some other things I don't recall!
     * @param string $attribute
     * @return string
     */
    public function generateActiveField($attribute)
    {
        $tableSchema = $this->getTableSchema();
        if ($tableSchema === false || !isset($tableSchema->columns[$attribute])) {
            if (preg_match('/^(password|pass|passwd|passcode)$/i', $attribute)) {
                return "\$this->form->field(\$model, '$attribute')->passwordInput()";
            } else {
                return "\$this->form->field(\$model, '$attribute')";
            }
        }
        $column = $tableSchema->columns[$attribute];
        if (
            $column->phpType === 'boolean' ||
            $column->dbType == 'tinyint(1)'
        ) {
            return "\$this->form->field(\$model, '$attribute')->checkbox()";
        } elseif ($column->type === 'text') {
            return "\$this->form->field(\$model, '$attribute')->textarea(['rows' => 6])";
        } else {
            if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name)) {
                $input = 'passwordInput';
            } else {
                $input = 'textInput';
            }
            if (is_array($column->enumValues) && count($column->enumValues) > 0) {
                $dropDownOptions = [];
                foreach ($column->enumValues as $enumValue) {
                    $dropDownOptions[$enumValue] = Inflector::humanize($enumValue);
                }
                return "\$this->form->field(\$model, '$attribute')->dropDownList("
                    . preg_replace("/\n\s*/", ' ', VarDumper::export($dropDownOptions)).", ['prompt' => ''])";
            } elseif ($column->phpType !== 'string' || $column->size === null) {
                return "\$this->form->field(\$model, '$attribute')->$input()";
            } else {
                return "\$this->form->field(\$model, '$attribute')->$input(['maxlength' => true])";
            }
        }
    }

    /**
     * Generates column format
     * @param \yii\db\ColumnSchema $column
     * @return string
     */
    public function generateColumnFormat($column)
    {
        if ($column->phpType === 'boolean'  ||
            $column->dbType == 'tinyint(1)'
        ) {
            return 'boolean';
        } elseif ($column->type === 'text') {
            return 'ntext';
        } elseif (stripos($column->name, 'time') !== false && $column->phpType === 'integer') {
            return 'datetime';
        } elseif (stripos($column->name, 'email') !== false) {
            return 'email';
        } elseif (stripos($column->name, 'url') !== false) {
            return 'url';
        } else {
            return 'text';
        }
    }

    /**
     * Generates validation rules for the search model.
     * @return array the generated validation rules
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

    /**
     * Generates URL parameters
     * @return string
     */
    public function generateUrlParams()
    {
        /* @var $class ActiveRecord */
        $class = $this->modelClass;
        $pks = $class::primaryKey();
        $table_name = $this->getTableSchema()->name;
        if (count($pks) === 1) {
            if (is_subclass_of($class, 'yii\mongodb\ActiveRecord')) {
                return "'id' => (string)\${$table_name}->{$pks[0]}";
            } else {
                return "'id' => \${$table_name}->{$pks[0]}";
            }
        } else {
            $params = [];
            foreach ($pks as $pk) {
                if (is_subclass_of($class, 'yii\mongodb\ActiveRecord')) {
                    $params[] = "'$pk' => (string)\${$table_name}->$pk";
                } else {
                    $params[] = "'$pk' => \${$table_name}->$pk";
                }
            }

            return implode(', ', $params);
        }
    }
}
