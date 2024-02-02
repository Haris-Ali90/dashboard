<?php
namespace App\Http\Traits;


/**
 * creator : Adnan nadeem
 * Email : adnannadeem1994@gmail.com
 */

trait FlagMetaDataFunctions {

    private $current_cat_metadata = null;
    private $is_data_load = false;
    public $is_show_on_route = ["is_loaded" => false,'value' =>false];
    Public $vendor_ids = ["is_loaded" => false,'value' =>[]];
    public $portals = ["is_loaded" => false,'value' =>[]];

    private function loadData()
    {
        if(!$this->is_data_load)
        {
            $this->current_cat_metadata = self::where('category_ref_id', $this->category_ref_id)
                ->whereNull('deleted_at')
                ->get();

            $this->is_data_load = true;
        }
    }

    public function isShowOnRoute()
    {
        $this->loadData();

        if(!$this->is_show_on_route['is_loaded'])
        {
            $value = $this->current_cat_metadata->where('type','is_show_on_route')->first()->value;
            $this->is_show_on_route['is_loaded'] = true;
            $this->is_show_on_route['value'] = ($value == 1)?true:false;
        }

        return $this->is_show_on_route['value'];
    }

    public function isVendorExist($ids = null)
    {
        // loading main data
        $this->loadData();

        // checking the vendors data is loaded
        if(!$this->vendor_ids['is_loaded'])
        {
            // getting all existing values
            $value = $this->current_cat_metadata->where('type','vendor_relation')
                ->pluck('value')->toArray();
            //update values
            $this->vendor_ids['is_loaded'] = true;
            $this->vendor_ids['value'] = $value;
        }


        $arrg_type = gettype($ids);
        if(is_null($arrg_type))
        {
            return (count($this->vendor_ids['value']) > 0)? true: false;
        }
        elseif($arrg_type == 'array')
        {
           $matching_values = array_intersect($ids,$this->vendor_ids['value']);
           return (count($this->vendor_ids['value']) > 0)? true: false;

        }
        elseif($arrg_type =='string')
        {
            return in_array($arrg_type,$this->vendor_ids['value']);
        }

    }

}
