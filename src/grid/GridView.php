<?php

namespace bvb\admin\grid;

use Yii;
use yii\helpers\Html;

class GridView extends \yii\grid\GridView
{
    /**
     * {@inheritdoc}
     */
    public $filterSelector = '.filter-field';

    /**
     * Extend default funcitonality by adding an items per page dropdown
     * {@inheritdoc}
     */
    public function renderSummary()
    {
        $summary = parent::renderSummary();
        if(!empty($summary) && !empty($this->filterModel)){ // --- Also make sure the filter model is set because without it we can't render the dropdown
            $summary .=  '&nbsp; Items per page: '.$this->getItemsPerPageDropown();
            $summary = '<div class="summary form-inline">'.$summary.'</div>';
        }
        return $summary;
    }

    /**
     * @return string
     */
    private function getItemsPerPageDropown()
    {
        return  Html::dropdownList(
            $this->dataProvider->getPagination()->pageSizeParam,
            $this->dataProvider->getPagination()->pageSize,
            [
                20 => 20,
                50 => 50,
                100 => 100,
                1000 => 1000
            ],
            [
                'class' => 'filter-field form-control'
            ]
        );
    }
}