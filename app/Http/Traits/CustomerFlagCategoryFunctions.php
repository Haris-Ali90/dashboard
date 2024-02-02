<?php
namespace App\Http\Traits;


/**
 * creator : Adnan nadeem
 * Email : adnannadeem1994@gmail.com
 */

trait CustomerFlagCategoryFunctions {

    private $current_cat_metadata = null;
    private $is_data_load = false;
    private $filters_data = [];


    private function loadData()
    {

        if(!$this->is_data_load)
        {
            $this->current_cat_metadata = $this->flagMetaData;
            $this->is_data_load = true;
        }

    }

    private function loadFilter($filter_name)
    {
        // checking the filter loaded beofer
        if(!isset($this->filters_data[$filter_name]))
        {
            $this->filters_data[$filter_name] = $this->current_cat_metadata->where('type',$filter_name)->pluck('value')->toArray();
        }
    }

    public function isFliterExist($filter_name,$filter_values = '')
    {
        // loading meta data
        $this->loadData();
        // now loading filter data
        $this->loadFilter($filter_name);
        // getting values type
        $filter_values_type = gettype($filter_values);

        if($filter_values === '' )
        {
            return (count($this->filters_data[$filter_name]) > 0)? true: false;
        }
        elseif($filter_values_type == 'array')
        {
            $matching_values = array_intersect($filter_values,$this->filters_data[$filter_name]);
            return (count($matching_values) > 0)? true: false;

        }
        elseif($filter_values_type =='string' || $filter_values_type == 'integer' || $filter_values_type == 'double' || $filter_values_type ==  'boolean')
        {
            return in_array($filter_values,$this->filters_data[$filter_name],true);
        }

    }


}
