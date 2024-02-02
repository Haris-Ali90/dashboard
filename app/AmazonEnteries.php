<?php

namespace App;

use App\Http\Traits\BasicModelFunctions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AmazonEnteries extends Model
{

    use BasicModelFunctions;

    protected $table = 'amazon_enteries';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'id', 'sprint_id', 'task_id', 'creator_id', 'route_id', 'ordinal', 'tracking_id', 'joey_id', 'joey_name', 'picked_up_at', 'sorted_at', 'delivered_at', 'task_status_id', 'order_image', 'address_line_1', 'address_line_2', 'address_line_3','hub_return_scan','returned_at'
    ];

    public function getAmazonCountsForLoop($taskIds, $type)
    {
        $totalRecord = DB::table('amazon_enteries')->whereIn('task_id', $taskIds)
            ->get(['sorted_at','picked_up_at','hub_return_scan','delivered_at','returned_at','task_status_id']);
        $total = 0;
        $sorted = 0;
        $pickup = 0;
        $delivered_order = 0;
        $return_orders = 0;
        $hub_return_scan = 0;
        $notscan = 0;
        $reattempted =0;
        $completion_ratio = 0;
        foreach ($totalRecord as $record)
        {
            if ($record->sorted_at != null){
                $sorted = $sorted + 1 ;
            }
            if ($record->picked_up_at != null){
                $pickup = $pickup + 1 ;
            }
            if ($record->delivered_at != null){
                $delivered_order = $delivered_order + 1 ;
            }
            if ($record->returned_at != null){
                $return_orders = $return_orders + 1 ;
            }
            if ($record->returned_at != null and $record->hub_return_scan != null){
                $hub_return_scan = $hub_return_scan + 1 ;
            }
            $total = $total + 1 ;
        }
        $notscan = count(DB::table('amazon_enteries')->whereIn('task_id', $taskIds)->where('task_status_id',61)->pluck('task_id'));
        $reattempted = count(DB::table('amazon_enteries')->whereIn('task_id', $taskIds)->where('task_status_id', 13)->pluck('task_id'));
        $counts['total'] = $total;
        $counts['sorted'] = $sorted;
        $counts['pickup'] = $pickup;
        $counts['delivered_order'] = $delivered_order;
        $counts['return_orders'] = $return_orders;
        $counts['hub_return_scan'] = $hub_return_scan;
        $counts['notscan'] = $notscan;
        $counts['reattempted'] = $reattempted;
        if($pickup > 0){
            $completion_ratio = round(($delivered_order/$pickup)*100,2);
        }
        $counts['completion_ratio'] = $completion_ratio;
        return $counts;
    }

    public function getInprogressOrders($taskIds, $type)
    {
        $totalRecord = DB::table('amazon_enteries')->whereIn('task_id', $taskIds)->whereIn('task_status_id',[133,121])
            ->get(['route_id','task_status_id']);
        $total = 0;
        $remaining_sorted = 0;
        $remaining_pickup = 0;
        $remaining_route = [];
        $routes = [];
        foreach ($totalRecord as $record)
        {
            if ($record->task_status_id == 133){
                $remaining_sorted = $remaining_sorted + 1 ;
            }
            if ($record->task_status_id == 121){
                $remaining_pickup = $remaining_pickup + 1 ;
            }
            if ($record->task_status_id == 121 ){
                $routes[] = $record->route_id;
            }
        }

        $counts['remaining_sorted'] = $remaining_sorted;
        $counts['remaining_pickup'] = $remaining_pickup;
        $counts['remaining_route'] = count(array_unique($routes));
        return $counts;
    }

    public function getAmazonCounts($taskIds, $type)
    {
        if (in_array($type, ['all','total'])) {
           // $counts['total'] = $this->totalOrders($taskIds);
           $counts['total'] = count($taskIds);
        }
        if (in_array($type, ['all', 'sorted'])) {
            $counts['sorted'] = $this->sorted($taskIds);
        }
        if (in_array($type, ['all', 'picked'])) {
            $counts['pickup'] = $this->pickup($taskIds);
        }
        if (in_array($type, ['all', 'delivered'])) {
            $counts['delivered_order'] = $this->delivery_order($taskIds);
        }
        if (in_array($type, ['all', 'return'])) {
            $counts['return_orders'] = $this->return_orders($taskIds);
            $counts['hub_return_scan'] = $this->hub_return_scan($taskIds);
        }
        if (in_array($type, ['all', 'scan'])) {
            $counts['notscan'] = $this->notscan($taskIds);
        }
        return $counts;
    }

    public function totalOrders($taskIds)
    {
        $total = DB::table('amazon_enteries')->whereIn('task_id', $taskIds)->pluck('task_id');
        return count($total);
    }

    public function sorted($taskIds)
    {
        $sorted = DB::table('amazon_enteries')->whereIn('task_id', $taskIds)->whereNotNull('sorted_at')->pluck('task_id');
        return count($sorted);
    }

    public function pickup($taskIds)
    {
        $pickup = DB::table('amazon_enteries')->whereIn('task_id', $taskIds)->whereNotNull('picked_up_at')->pluck('task_id');
        return count($pickup);
    }

    public function delivery_order($taskIds)
    {
        return $delivery_order = count(DB::table('amazon_enteries')->whereIn('task_id', $taskIds)->whereNotNull('delivered_at')->pluck('task_id'));
    }

    public function return_orders($taskIds)
    {
        return $return_orders = count(DB::table('amazon_enteries')->whereIn('task_id', $taskIds)->whereNotNull('returned_at')->pluck('task_id'));
    }

    public function hub_return_scan($taskIds)
    {
        return $hub_return_scan = count(DB::table('amazon_enteries')->whereIn('task_id', $taskIds)->whereNotNull('returned_at')->whereNotNull('hub_return_scan')->pluck('task_id'));
    }

    public function notscan($taskIds)
    {
        return $notscan = count(DB::table('amazon_enteries')->whereIn('task_id', $taskIds)->whereIn('task_status_id', [61, 13])->pluck('task_id'));
    }

    public function getAmazonCountsWithCustom($taskIds, $type)
    {
        if (in_array($type, ['all','total'])) {
            $counts['total'] = $this->totalOrdersWithCustom($taskIds);
        }
        if (in_array($type, ['all', 'sorted'])) {
            $counts['sorted'] = $this->sortedWithCustom($taskIds);
        }
        if (in_array($type, ['all', 'picked'])) {
            $counts['pickup'] = $this->pickupWithCustom($taskIds);
        }
        if (in_array($type, ['all', 'delivered'])) {
            $counts['delivered_order'] = $this->delivery_orderWithCustom($taskIds);
        }
        if (in_array($type, ['all', 'return'])) {
            $counts['return_orders'] = $this->return_ordersWithCustom($taskIds);
            $counts['hub_return_scan'] = $this->hub_return_scanWithCustom($taskIds);
        }
        if (in_array($type, ['all', 'scan'])) {
            $counts['notscan'] = $this->notscanWithCustom($taskIds);
        }
        return $counts;
    }

    public function totalOrdersWithCustom($taskIds)
    {
        $total = DB::table('amazon_enteries')->whereIn('task_id', $taskIds)->pluck('task_id');
        return count($total);
    }

    public function sortedWithCustom($taskIds)
    {
        $sorted = DB::table('amazon_enteries')->whereIn('task_id', $taskIds)->whereNotNull('sorted_at')->pluck('task_id');
        return count($sorted);
    }

    public function pickupWithCustom($taskIds)
    {
        $pickup = DB::table('amazon_enteries')->whereIn('task_id', $taskIds)->whereNotNull('picked_up_at')->pluck('task_id');
        return count($pickup);
    }

    public function delivery_orderWithCustom($taskIds)
    {
        return $delivery_order = count(DB::table('amazon_enteries')->whereIn('task_id', $taskIds)->whereIn('task_status_id', $this->getStatusCodes('competed'))->pluck('task_id'));
    }

    public function return_ordersWithCustom($taskIds)
    {
        return $return_orders = count(DB::table('amazon_enteries')->whereIn('task_id', $taskIds)->whereIn('task_status_id', $this->getStatusCodes('return'))->pluck('task_id'));
    }

    public function hub_return_scanWithCustom($taskIds)
    {
        return $hub_return_scan = count(DB::table('amazon_enteries')->whereIn('task_id', $taskIds)->whereIn('task_status_id', $this->getStatusCodes('return'))->whereNotNull('hub_return_scan')->pluck('task_id'));
    }

    public function notscanWithCustom($taskIds)
    {
        return $notscan = count(DB::table('amazon_enteries')->whereIn('task_id', $taskIds)->whereIn('task_status_id', [61, 13])->pluck('task_id'));
    }


}
