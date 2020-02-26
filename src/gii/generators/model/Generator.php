<?php
namespace bvb\admin\gii\generators\model;

use yii\db\Schema;
use yii\gii\CodeFile;
use Yii;

/**
 * Accelm cusomized file for generating one or multiple ActiveRecord classes for the specified database table.
 *
 * @author Brian Van Buren <brian@accelm.com>
 */
class Generator extends \yii\gii\generators\model\Generator
{
    /**
     * Default namespace set to common\models
     * @var string
     */
    public $ns = 'common\models';

    /**
     * Namespace to use for backend model creation
     * @var string
     */
    public $backendNs = 'backend\models';

    /**
     * List of fields to ignore generating validation rules for
     * @var array
     */
    public $ignoredRulesColumns = [
        'create_time',
        'update_time'
    ];

    /**
     * Whether to generator a backend namespaced model
     * @var boolean
     */
    public $generateBackendModel = true;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['generateBackendModel'], 'boolean'],
            [['backendNs'], 'string']
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        $files = [];
        $relations = $this->generateRelations();
        $db = $this->getDbConnection();
        foreach ($this->getTableNames() as $tableName) {
            // model :
            $modelClassName = $this->generateClassName($tableName);
            $queryClassName = ($this->generateQuery) ? $this->generateQueryClassName($modelClassName) : false;
            $tableSchema = $db->getTableSchema($tableName);
            $params = [
                'tableName' => $tableName,
                'className' => $modelClassName,
                'queryClassName' => $queryClassName,
                'tableSchema' => $tableSchema,
                'properties' => $this->generateProperties($tableSchema),
                'labels' => $this->generateLabels($tableSchema),
                'rules' => ($this->generateBackendModel) ? null : $this->generateRules($tableSchema), // --- Customization
                'generateBackendModel' => false,  // --- Customization
                'relations' => isset($relations[$tableName]) ? $relations[$tableName] : [],
            ];

            $files[] = new CodeFile(
                Yii::getAlias('@' . str_replace('\\', '/', $this->ns)) . '/' . $modelClassName . '.php',
                $this->render('model.php', $params)
            );

            // query :
            if ($queryClassName) {
                $params['className'] = $queryClassName;
                $params['modelClassName'] = $modelClassName;
                $files[] = new CodeFile(
                    Yii::getAlias('@' . str_replace('\\', '/', $this->queryNs)) . '/' . $queryClassName . '.php',
                    $this->render('query.php', $params)
                );
            }

            // --- Customization for generating backend rules
            if ($this->generateBackendModel) {
                $params = [
                    'className' => $modelClassName,
                    'rules' => $this->generateRules($tableSchema),
                    'generateBackendModel' => true
                ];

                $files[] = new CodeFile(
                    Yii::getAlias('@'.str_replace('\\', '/', $this->backendNs)) .'/'. $modelClassName . '.php',
                    $this->render('model.php', $params)
                );
            }
        }

        return $files;
    }

    /**
     * Override default to not generate any labels
     * {@inheritdoc}
     */
    public function generateLabels($table)
    {
        return [];
    }

    /**
     * Override default to add tinyint(1) fields as boolean validation
     * {@inheritdoc}
     */
    public function generateRules($table)
    {
        $types = [];
        $lengths = [];
        foreach ($table->columns as $column) {
            if ($column->autoIncrement || in_array($column->name, $this->ignoredRulesColumns) ) { // --- Customized to ignore the columns we don't have manual input on
                continue;
            }
            if (!$column->allowNull && $column->defaultValue === null) { 
                $types['required'][] = $column->name;
            }
            
            switch ($column->type) {
                case Schema::TYPE_SMALLINT:
                case Schema::TYPE_INTEGER:
                case Schema::TYPE_BIGINT:
                case Schema::TYPE_TINYINT:
                    if($column->dbType == 'tinyint(1)'){ // --- Customeized since we use tinyint(1) for boolean vlues
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
                case Schema::TYPE_JSON:
                    $types['safe'][] = $column->name;
                    break;
                default: // strings
                    if ($column->size > 0) {
                        $lengths[$column->size][] = $column->name;
                    } else {
                        $types['string'][] = $column->name;
                    }
            }
        }
        $rules = [];
        $driverName = $this->getDbDriverName();
        foreach ($types as $type => $columns) {
            if ($driverName === 'pgsql' && $type === 'integer') {
                $rules[] = "[['" . implode("', '", $columns) . "'], 'default', 'value' => null]";
            }
            $rules[] = "[['" . implode("', '", $columns) . "'], '$type']";
        }
        foreach ($lengths as $length => $columns) {
            $rules[] = "[['" . implode("', '", $columns) . "'], 'string', 'max' => $length]";
        }

        $db = $this->getDbConnection();

        // Unique indexes rules
        try {
            $uniqueIndexes = array_merge($db->getSchema()->findUniqueIndexes($table), [$table->primaryKey]);
            $uniqueIndexes = array_unique($uniqueIndexes, SORT_REGULAR);
            foreach ($uniqueIndexes as $uniqueColumns) {
                // Avoid validating auto incremental columns
                if (!$this->isColumnAutoIncremental($table, $uniqueColumns)) {
                    $attributesCount = count($uniqueColumns);

                    if ($attributesCount === 1) {
                        $rules[] = "[['" . $uniqueColumns[0] . "'], 'unique']";
                    } elseif ($attributesCount > 1) {
                        $columnsList = implode("', '", $uniqueColumns);
                        $rules[] = "[['$columnsList'], 'unique', 'targetAttribute' => ['$columnsList']]";
                    }
                }
            }
        } catch (NotSupportedException $e) {
            // doesn't support unique indexes information...do nothing
        }

        // Exist rules for foreign keys
        foreach ($table->foreignKeys as $refs) {
            $refTable = $refs[0];
            $refTableSchema = $db->getTableSchema($refTable);
            if ($refTableSchema === null) {
                // Foreign key could point to non-existing table: https://github.com/yiisoft/yii2-gii/issues/34
                continue;
            }
            $refClassName = $this->generateClassName($refTable);
            unset($refs[0]);
            $attributes = implode("', '", array_keys($refs));
            $targetAttributes = [];
            foreach ($refs as $key => $value) {
                $targetAttributes[] = "'$key' => '$value'";
            }
            $targetAttributes = implode(', ', $targetAttributes);
            $rules[] = "[['$attributes'], 'exist', 'skipOnError' => true, 'targetClass' => $refClassName::class, 'targetAttribute' => [$targetAttributes]]";
        }

        return $rules;
    }
}
