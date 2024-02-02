<?php

namespace App\Http\Controllers\Backend;

use App\JoeyRouteLocations;
use App\Sprint;
use App\SprintReattempt;
use App\SprintTaskHistory;
use Illuminate\Http\Request;
use App\Amazon;
use App\Amazon_count;
use Illuminate\Support\Facades\DB;
use App\JoeyRoutes;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\Datatables\Datatables;

class MontrealController extends BackendController
{
    public static $status = array("136" => "Client requested to cancel the order",
        "137" => "Delay in delivery due to weather or natural disaster",
        "118" => "left at back door",
        "117" => "left with concierge",
        "135" => "Customer refused delivery",
        "108" => "Customer unavailable-Incorrect address",
        "106" => "Customer unavailable - delivery returned",
        "107" => "Customer unavailable - Left voice mail - order returned",
        "109" => "Customer unavailable - Incorrect phone number",
        "142" => "Damaged at hub (before going OFD)",
        "143" => "Damaged on road - undeliverable",
        "144" => "Delivery to mailroom",
        "103" => "Delay at pickup",
        "139" => "Delivery left on front porch",
        "138" => "Delivery left in the garage",
        "114" => "Successful delivery at door",
        "113" => "Successfully hand delivered",
        "120" => "Delivery at Hub",
        "110" => "Delivery to hub for re-delivery",
        "111" => "Delivery to hub for return to merchant",
        "121" => "Out for delivery",
        "102" => "Joey Incident",
        "104" => "Damaged on road - delivery will be attempted",
        "105" => "Item damaged - returned to merchant",
        "129" => "Joey at hub",
        "128" => "Package on the way to hub",
        "140" => "Delivery missorted, may cause delay",
        "116" => "Successful delivery to neighbour",
        "132" => "Office closed - safe dropped",
        "101" => "Joey on the way to pickup",
        "32" => "Order accepted by Joey",
        "14" => "Merchant accepted",
        "36" => "Cancelled by JoeyCo",
        "124" => "At hub - processing",
        "38" => "Draft",
        "18" => "Delivery failed",
        "56" => "Partially delivered",
        "17" => "Delivery success",
        "68" => "Joey is at dropoff location",
        "67" => "Joey is at pickup location",
        "13" => "At hub - processing",
        "16" => "Joey failed to pickup order",
        "57" => "Not all orders were picked up",
        "15" => "Order is with Joey",
        "112" => "To be re-attempted",
        "131" => "Office closed - returned to hub",
        "125" => "Pickup at store - confirmed",
        "61" => "Scheduled order",
        "37" => "Customer cancelled the order",
        "34" => "Customer is editting the order",
        "35" => "Merchant cancelled the order",
        "42" => "Merchant completed the order",
        "54" => "Merchant declined the order",
        "33" => "Merchant is editting the order",
        "29" => "Merchant is unavailable",
        "24" => "Looking for a Joey",
        "23" => "Waiting for merchant(s) to accept",
        "28" => "Order is with Joey",
        "133" => "Packages sorted",
        "55" => "ONLINE PAYMENT EXPIRED",
        "12" => "ONLINE PAYMENT FAILED",
        "53" => "Waiting for customer to pay",
        "141" => "Lost package",
        "60" => "Task failure",
        "255" => 'Order Delay',
        "145" => 'Returned To Merchant',
        "146" => "Delivery Missorted, Incorrect Address",
        '153' => 'Miss sorted to be reattempt',
        '154' => 'Joey unable to complete the route','155' => 'To be re-attempted tommorow',
        '155' => 'To be re-attempted tomorrow');

    public function statusmap($id)
    {
        $statusid = array("136" => "Client requested to cancel the order",
            "137" => "Delay in delivery due to weather or natural disaster",
            "118" => "left at back door",
            "117" => "left with concierge",
            "135" => "Customer refused delivery",
            "108" => "Customer unavailable-Incorrect address",
            "106" => "Customer unavailable - delivery returned",
            "107" => "Customer unavailable - Left voice mail - order returned",
            "109" => "Customer unavailable - Incorrect phone number",
            "142" => "Damaged at hub (before going OFD)",
            "143" => "Damaged on road - undeliverable",
            "144" => "Delivery to mailroom",
            "103" => "Delay at pickup",
            "139" => "Delivery left on front porch",
            "138" => "Delivery left in the garage",
            "114" => "Successful delivery at door",
            "113" => "Successfully hand delivered",
            "120" => "Delivery at Hub",
            "110" => "Delivery to hub for re-delivery",
            "111" => "Delivery to hub for return to merchant",
            "121" => "Out for delivery",
            "102" => "Joey Incident",
            "104" => "Damaged on road - delivery will be attempted",
            "105" => "Item damaged - returned to merchant",
            "129" => "Joey at hub",
            "128" => "Package on the way to hub",
            "140" => "Delivery missorted, may cause delay",
            "116" => "Successful delivery to neighbour",
            "132" => "Office closed - safe dropped",
            "101" => "Joey on the way to pickup",
            "32"  => "Order accepted by Joey",
            "14"  => "Merchant accepted",
            "36"  => "Cancelled by JoeyCo",
            "124" => "At hub - processing",
            "38"  => "Draft",
            "18"  => "Delivery failed",
            "56"  => "Partially delivered",
            "17"  => "Delivery success",
            "68"  => "Joey is at dropoff location",
            "67"  => "Joey is at pickup location",
            "13"  => "At hub - processing",
            "16"  => "Joey failed to pickup order",
            "57"  => "Not all orders were picked up",
            "15"  => "Order is with Joey",
            "112" => "To be re-attempted",
            "131" => "Office closed - returned to hub",
            "125" => "Pickup at store - confirmed",
            "61"  => "Scheduled order",
            "37"  => "Customer cancelled the order",
            "34"  => "Customer is editting the order",
            "35"  => "Merchant cancelled the order",
            "42"  => "Merchant completed the order",
            "54"  => "Merchant declined the order",
            "33"  => "Merchant is editting the order",
            "29"  => "Merchant is unavailable",
            "24"  => "Looking for a Joey",
            "23"  => "Waiting for merchant(s) to accept",
            "28"  => "Order is with Joey",
            "133" => "Packages sorted",
            "55"  => "ONLINE PAYMENT EXPIRED",
            "12"  => "ONLINE PAYMENT FAILED",
            "53"  => "Waiting for customer to pay",
            "141" => "Lost package",
            "60"  => "Task failure",
            "255" => 'Order Delay',
            "145" => 'Returned To Merchant',
            "146" => "Delivery Missorted, Incorrect Address",
            '153' => 'Miss sorted to be reattempt',
            '154' => 'Joey unable to complete the route','155' => 'To be re-attempted tommorow',
            '155' => 'To be re-attempted tomorrow');
        return $statusid[$id];
    }

    /**
     * Get Montreal dashboard
     */
    public function getNewMontreal(Request $request)
    {
$today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $today_date = date('Y-m-d', strtotime($today_date . ' -1 days'));
       // dd($today_date);
        $query = Sprint::where('creator_id',477260)->where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $today_date . "%")
            ->where('deleted_at', null)->whereNotIn('status_id', [36, 35, 38])->where('is_reattempt','=', 0)->get();
dd($query);
        return backend_view('montrealdashboard.montreal_new_dashboard');
    }

    /**
     * Yajra call after Montreal dashboard
     */
        public function montrealNewData(Datatables $datatables, Request $request)
    {
        
        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->addColumn('order_id', static function ($record) {
                if ($record->sprintCtcTasks) {
                    if ($record->sprintCtcTasks) {
                        return $record->id . '-' . $record->sprintCtcTasks->ordinal;
                    } else {
                        return " ";
                    }
                } else {
                    return "";
                }
            })
            ->addColumn('route_id', static function ($record) {
                if ($record->sprintCtcTasks) {
                    if ($record->sprintCtcTasks->taskRouteLocation) {
                        return 'R-' . $record->sprintCtcTasks->taskRouteLocation->route_id ;//. '-' . $record->sprintCtcTasks->taskRouteLocation->ordinal;
                    } else {
                        return " ";
                    }
                } else {
                    return "";
                }
            })
            ->addColumn('joey', static function ($record) {
                if ($record->sprintCtcTasks) {
                    if ($record->sprintCtcTasks->taskRouteLocation) {
                        if ($record->sprintCtcTasks->taskRouteLocation->joeyRoute->joey) {
                            return $record->sprintCtcTasks->taskRouteLocation->joeyRoute->joey->first_name . ' ' . $record->sprintCtcTasks->taskRouteLocation->joeyRoute->joey->last_name . ' (' . $record->sprintCtcTasks->taskRouteLocation->joeyRoute->joey_id . ')';
                        } else {
                            return "";
                        }
                    } else {
                        return "";
                    }
                } else {
                    return "";
                }
            })
         /*   ->addColumn('address', static function ($record) {
                if ($record->sprintCtcTasks) {
                    if ($record->sprintCtcTasks->task_Location) {
                        return $record->sprintCtcTasks->task_Location->address;
                    } else {
                        return "";
                    }
                } else {
                    return "";
                }
            })*/
            ->addColumn('picked_hub_time', static function ($record) {
                return $record->outForDelivery()->outdeliver;
            })
            ->addColumn('sorter_time', static function ($record) {
                return $record->sorterTime()->sortertime;
            })
            ->addColumn('eta_time', static function ($record) {
                if ($record->sprintCtcTasks) {
                    return $record->sprintCtcTasks->eta_time;
                } else {
                    return "";
                }
            })
            ->addColumn('delivery_time', static function ($record) {
                return $record->montrealDeliveryTime()->delivery_time;
            })
            ->addColumn('image', static function ($record) {
                if ($record->sprintCtcTasks) {
                    if ($record->sprintCtcTasks->sprintConfirmations) {
                        if ($record->sprintCtcTasks->sprintConfirmations->attachment_path) {
                            return '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "' . $record->sprintCtcTasks->sprintConfirmations->attachment_path . '" />';
                        } else {
                            return '';
                        }
                    } else {
                        return '';
                    }
                } else {
                    return '';
                }
            })
            ->addColumn('tracking_id', static function ($record) {
                if ($record->sprintCtcTasks) {
                    if ($record->sprintCtcTasks->taskMerchants) {
                        return substr($record->sprintCtcTasks->taskMerchants->tracking_id, strrpos($record->sprintCtcTasks->taskMerchants->tracking_id, '_') + 0);
                    } else {
                        return "";
                    }
                } else {
                    return "";
                }
            })
            ->editColumn('status_id', static function ($record) {
                return self::$status[$record->status_id];
            })

            ->addColumn('action', static function ($record) {
                return backend_view('montrealdashboard.new_action', compact('record'));
            })
            ->make(true);
    }

    /**
     * Get Montreal dashboard order Detail
     */
    public function montrealNewProfile(Request $request, $id)
    {
        $id = base64_decode($id);
        $amazon_montreal = Sprint::where('id', $id)->where('deleted_at',null)->first();

        return backend_view('montrealdashboard.montreal_new_profile', compact('amazon_montreal'));
    }

    /**
     * Get Montreal dashboard
     */
    public function getMontreal(Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $amazon_montreal_count = Amazon_count::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"),'like',$today_date."%")
            ->where(['vendor_id' => 477260])
            ->orderBy('id','DESC')
            ->first();
			 $date = date('Y-m-d', strtotime($today_date. ' -1 days'));
			$notscan_count = Sprint::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"),'like',$date."%")
            ->where(['creator_id' => 477260])
            ->whereIn('status_id', [61,13])
            ->count();
			//$amazon  = new Amazon();

        //$amazon_montreal_count = $amazon->montrealDashboardCounts($today_date);
		
        return backend_view('montrealdashboard.montreal_dashboard', compact('amazon_montreal_count','notscan_count'));
    }

    /**
     * Get Montreal dashboard orders excel report
     */
    public function montrealExcel($date = null)
    {
        if($date == null)
        {
            $date = date('Y-m-d');
        }

        $montreal_data = Amazon::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"),'like',$date."%")->where(['vendor_id' => 477260])->get();
        $montreal_array[] = array('JoeyCo Order 1 #', 'Route Number', 'Joey', 'Customer Address', 'Pickup From Hub', 'Sorter Time', 'Estimated Delivery ETA', 'Actual Arrival @ CX', 'Amazon tracking #', 'Status');

        foreach ($montreal_data as $montreal) {
            $montreal_array[] = array(
                'JoeyCo Order #' => $montreal->order_id,
                'Route Number' => $montreal->route,
                'Joey' => $montreal->joey,
                'Customer Address' => $montreal->address,
                'Pickup From Hub' => $montreal->picked_hub_time,
                'Sorter Time' => $montreal->sorter_time,
                'Estimated Delivery ETA' => $montreal->dropoff_eta,
                'Actual Arrival @ CX' => $montreal->delivery_time,
                'Amazon tracking #' => substr($montreal->tracking_id, ($pos = strrpos($montreal->tracking_id, '_')) == false ? 0 : $pos+1),
                'Status' => self::$status[$montreal->sprint_status]
            );
        }
        Excel::create('Montreal Data '.$date.'', function ($excel) use ($montreal_array) {
            $excel->setTitle('Montreal Data');
            $excel->sheet('Montreal Data', function ($sheet) use ($montreal_array) {
                $sheet->fromArray($montreal_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    /**
     * Yajra call after Montreal dashboard
     */
    public function montrealData(Datatables $datatables, Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $query = Amazon::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"),'like',$today_date."%")
            ->where(['vendor_id' => 477260])->orderBy('updated_at','DESC');
        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('sprint_status', static function ($record) {
                return self::$status[$record->sprint_status];
            })
            ->editColumn('image', static function ($record) {
                if (isset($record->image)) {
                    return '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "' . $record->image . '" />';
                } else {
                    return '';
                }
            })
            ->editColumn('tracking_id', static function ($record) {
                return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos+1);
            })

            ->addColumn('action', static function ($record) {
                return backend_view('montrealdashboard.action', compact('record'));
            })
            ->make(true);
    }

    /**
     * Get Montreal dashboard order Detail
     */
    public function montrealProfile(Request $request, $id)
    {
        $mont_id = base64_decode($id);
        $amazon_montreal = Amazon::where(['id' => $mont_id])->get();
        $amazon_montreal = $amazon_montreal[0];

        return backend_view('montrealdashboard.montreal_profile', compact('amazon_montreal'));
    }

    /**
     * Get Montreal Sorted
     */
    public function getSorter(Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $amazon_montreal_count = Amazon_count::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $today_date . "%")
            ->where(['vendor_id' => 477260])->orderBy('id','DESC')
            ->first();
			
			 $date = date('Y-m-d', strtotime($today_date. ' -1 days'));
$notscan_count = Sprint::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"),'like',$date."%")
            ->where(['creator_id' => 477260])
            ->whereIn('status_id', [61,13])
            ->count();
        $title_name = 'Montreal';
        return backend_view('montrealdashboard.sorted_order', compact('amazon_montreal_count', 'title_name','notscan_count'));
    }

    /**
     * Yajra call after Montreal Sorted
     */
    public function montrealSortedData(Datatables $datatables, Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $query = Amazon::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $today_date . "%")->where(['sprint_status' => 133, 'vendor_id' => 477260]);

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('sprint_status', static function ($record) {
                return self::$status[$record->sprint_status];
            })
            ->editColumn('image', static function ($record) {
                if (isset($record->image)) {
                    return '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "' . $record->image . '" />';
                } else {
                    return '';
                }
            })
            ->editColumn('tracking_id', static function ($record) {

                return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos+1);

            })
            ->addColumn('action', static function ($record) {
                return backend_view('montrealdashboard.action_sorted', compact('record'));
            })
            ->make(true);
    }

    /**
     * Get Montreal Sorted order detail
     */
    public function montrealsortedDetail(Request $request, $id)
    {
        $mont_id = base64_decode($id);
        $amazon_montreal = Amazon::where(['id' => $mont_id])->get();
        $amazon_montreal = $amazon_montreal[0];

        return backend_view('montrealdashboard.montreal_sorted_detail', compact('amazon_montreal'));
    }

    /**
     * Get Montreal Sorted orders excel report
     */
    public function montrealSortedExcel($date = null)
    {
        if($date == null)
        {
            $date = date('Y-m-d');
        }
        $montreal_data = Amazon::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"),'like',$date."%")->where(['sprint_status' => 133, 'vendor_id' => 477260])->get();
        $montreal_array[] = array('JoeyCo Order #', 'Route Number', 'Joey', 'Customer Address', 'Pickup From Hub', 'Sorter Time', 'Estimated Delivery ETA', 'Actual Arrival @ CX', 'Amazon tracking #', 'Status');
        foreach ($montreal_data as $montreal) {
            $montreal_array[] = array(
                'JoeyCo Order #' => $montreal->order_id,
                'Route Number' => $montreal->route,
                'Joey' => $montreal->joey,
                'Customer Address' => $montreal->address,
                'Pickup From Hub' => $montreal->picked_hub_time,
                'Sorter Time' => $montreal->sorter_time,
                'Estimated Delivery ETA' => $montreal->dropoff_eta,
                'Actual Arrival @ CX' => $montreal->delivery_time,
                'Amazon tracking #' => substr($montreal->tracking_id, ($pos = strrpos($montreal->tracking_id, '_')) == false ? 0 : $pos+1),
                'Status' =>   self::$status[$montreal->sprint_status]
            );
        }
        Excel::create('Montreal Sorted Data '.$date.'', function ($excel) use ($montreal_array) {
            $excel->setTitle('Montreal Sorted Data');
            $excel->sheet('Montreal Sorted Data', function ($sheet) use ($montreal_array) {
                $sheet->fromArray($montreal_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    /**
     * Get Montreal Picked up
     */
    public function getMontrealhub(Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $amazon_montreal_count = Amazon_count::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $today_date . "%")
            ->where(['vendor_id' => 477260])
            ->orderBy('id','DESC')
            ->first();
			 $date = date('Y-m-d', strtotime($today_date. ' -1 days'));
			$notscan_count = Sprint::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"),'like',$date."%")
            ->where(['creator_id' => 477260])
            ->whereIn('status_id', [61,13])
            ->count();
        $title_name = 'Montreal';
        return backend_view('montrealdashboard.pickup_hub', compact('amazon_montreal_count', 'title_name','notscan_count'));
    }

    /**
     * Yajra call after Montreal Picked up
     */
    public function montrealPickedUpData(Datatables $datatables, Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $query = Amazon::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $today_date . "%")->where(['sprint_status' => 121, 'vendor_id' => 477260]);
        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('sprint_status', static function ($record) {
                return self::$status[$record->sprint_status];
            })
            ->editColumn('image', static function ($record) {
                if (isset($record->image)) {
                    return '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "' . $record->image . '" />';
                } else {
                    return '';
                }
            })
            ->editColumn('tracking_id', static function ($record) {
                return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos+1);
            })
            ->addColumn('action', static function ($record) {
                return backend_view('montrealdashboard.action_pickup', compact('record'));
            })
            ->make(true);
    }

    /**
     * Get Montreal Picked up order detail
     */
    public function montrealpickupDetail(Request $request, $id)
    {
        $mont_id = base64_decode($id);
        $amazon_montreal = Amazon::where(['id' => $mont_id])->get();
        $amazon_montreal = $amazon_montreal[0];

        return backend_view('montrealdashboard.montreal_pickup_detail', compact('amazon_montreal'));
    }

    /**
     * Get Montreal Picked up orders excel report
     */
    public function montrealPickedupExcel($date = null)
    {
        if($date == null)
        {
            $date = date('Y-m-d');
        }
        $montreal_data = Amazon::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $date . "%")->where(['sprint_status' => 121, 'vendor_id' => 477260])->get();
        $montreal_array[] = array('JoeyCo Order #', 'Route Number', 'Joey', 'Customer Address', 'Pickup From Hub', 'Sorter Time', 'Estimated Delivery ETA', 'Actual Arrival @ CX', 'Amazon tracking #', 'Status');
        foreach ($montreal_data as $montreal) {
            $montreal_array[] = array(
                'JoeyCo Order #' => $montreal->order_id,
                'Route Number' => $montreal->route,
                'Joey' => $montreal->joey,
                'Customer Address' => $montreal->address,
                'Pickup From Hub' => $montreal->picked_hub_time,
                'Sorter Time' => $montreal->sorter_time,
                'Estimated Delivery ETA' => $montreal->dropoff_eta,
                'Actual Arrival @ CX' => $montreal->delivery_time,
                'Amazon tracking #' => substr($montreal->tracking_id, ($pos = strrpos($montreal->tracking_id, '_')) == false ? 0 : $pos+1),
                'Status' =>   self::$status[$montreal->sprint_status]
            );
        }
        Excel::create('Montreal Picked Up Data '.$date.'', function ($excel) use ($montreal_array) {
            $excel->setTitle('Montreal Picked Up Data');
            $excel->sheet('Montreal Picked Up Data', function ($sheet) use ($montreal_array) {
                $sheet->fromArray($montreal_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    /**
     * Get Montreal Not Scan
     */
    public function getMontnotscan(Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $amazon_montreal_count = Amazon_count::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $today_date . "%")
            ->where(['vendor_id' => 477260])
            ->orderBy('id','DESC')
            ->first();
			 $date = date('Y-m-d', strtotime($today_date. ' -1 days'));
			$notscan_count = Sprint::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"),'like',$date."%")
            ->where(['creator_id' => 477260])
            ->whereIn('status_id', [61,13])
            ->count();
        $title_name = 'Montreal';
        return backend_view('montrealdashboard.not_scanned_orders', compact('amazon_montreal_count', 'title_name','notscan_count'));
    }

    /**
     * Yajra call after Montreal Not Scan
     */
    public function montrealNotScanData(Datatables $datatables, Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $query = Amazon::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $today_date . "%")->whereIn('sprint_status' , [61,13])
            ->where(['vendor_id' => 477260]);
        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('sprint_status', static function ($record) {
                return self::$status[$record->sprint_status];
            })
            ->editColumn('image', static function ($record) {
                if (isset($record->image)) {
                    return '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "' . $record->image . '" />';
                } else {
                    return '';
                }
            })
            ->editColumn('tracking_id', static function ($record) {
                return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos+1);
            })
            ->addColumn('action', static function ($record) {
                return backend_view('montrealdashboard.action_notscan', compact('record'));
            })
            ->make(true);
    }

    /**
     * Get Montreal Not Scan order detail
     */
    public function montrealnotscanDetail(Request $request, $id)
    {
        $mont_id = base64_decode($id);
        $amazon_montreal = Amazon::where(['id' => $mont_id])->get();
        $amazon_montreal = $amazon_montreal[0];

        return backend_view('montrealdashboard.montreal_notscan_detail', compact('amazon_montreal'));
    }

    /**
     * Get Montreal Not Scan orders excel report
     */
    public function montrealNotscanExcel($date = null)
    {
        if($date == null)
        {
            $date = date('Y-m-d');
        }
        $montreal_data = Amazon::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $date . "%")->whereIn('sprint_status' , [61,13])
            ->where(['vendor_id' => 477260])->get();
        $montreal_array[] = array('JoeyCo Order #', 'Route Number', 'Joey', 'Customer Address', 'Pickup From Hub', 'Sorter Time', 'Estimated Delivery ETA', 'Actual Arrival @ CX', 'Amazon tracking #', 'Status');
        foreach ($montreal_data as $montreal) {
            $montreal_array[] = array(
                'JoeyCo Order #' => $montreal->order_id,
                'Route Number' => $montreal->route,
                'Joey' => $montreal->joey,
                'Customer Address' => $montreal->address,
                'Pickup From Hub' => $montreal->picked_hub_time,
                'Sorter Time' => $montreal->sorter_time,
                'Estimated Delivery ETA' => $montreal->dropoff_eta,
                'Actual Arrival @ CX' => $montreal->delivery_time,
                'Amazon tracking #' => substr($montreal->tracking_id, ($pos = strrpos($montreal->tracking_id, '_')) == false ? 0 : $pos+1),
                'Status' =>   self::$status[$montreal->sprint_status]
            );
        }
        Excel::create('Montreal Not Scan Data '.$date.'', function ($excel) use ($montreal_array) {
            $excel->setTitle('Montreal Not Scan Data');
            $excel->sheet('Montreal Not Scan Data', function ($sheet) use ($montreal_array) {
                $sheet->fromArray($montreal_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    /**
     * Get Montreal Delivered
     */
    public function getMontdelivered(Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $amazon_montreal_count = Amazon_count::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $today_date . "%")
            ->where(['vendor_id' => 477260])
            ->orderBy('id','DESC')
            ->first();
			 $date = date('Y-m-d', strtotime($today_date. ' -1 days'));
			$notscan_count = Sprint::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"),'like',$date."%")
            ->where(['creator_id' => 477260])
            ->whereIn('status_id', [61,13])
            ->count();
        $title_name = 'Montreal';
        return backend_view('montrealdashboard.delivered_orders', compact('amazon_montreal_count', 'title_name','notscan_count'));
    }

    /**
     * Yajra call after Montreal Delivered
     */
    public function montrealDeliveredData(Datatables $datatables, Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $query = Amazon::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $today_date . "%")->whereIn('sprint_status',  [17,113,114,116,117,118,138,139])->where('vendor_id', [477260]);
        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('sprint_status', static function ($record) {
                return self::$status[$record->sprint_status];
            })
            ->editColumn('image', static function ($record) {
                if (isset($record->image)) {
                    return '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "' . $record->image . '" />';
                } else {
                    return '';
                }
            })
            ->editColumn('tracking_id', static function ($record) {
                return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos+1);
            })
            ->addColumn('action', static function ($record) {
                return backend_view('montrealdashboard.action_delivered', compact('record'));
            })
            ->make(true);
    }

    /**
     * Get Montreal Delivered order detail
     */
    public function montrealdeliveredDetail(Request $request, $id)
    {
        $mont_id = base64_decode($id);
        $amazon_montreal = Amazon::where(['id' => $mont_id])->get();
        $amazon_montreal = $amazon_montreal[0];

        return backend_view('montrealdashboard.montreal_delivered_detail', compact('amazon_montreal'));
    }

    /**
     * Get Montreal Delivered orders excel report
     */
    public function montrealDeliveredExcel($date = null)
    {
        if($date == null)
        {
            $date = date('Y-m-d');
        }
        $montreal_data = Amazon::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $date . "%")->whereIn('sprint_status',  [17,113,114,116,117,118,138,139])->where('vendor_id', [477260])->get();
        $montreal_array[] = array('JoeyCo Order #', 'Route Number', 'Joey', 'Customer Address', 'Pickup From Hub', 'Sorter Time', 'Estimated Delivery ETA', 'Actual Arrival @ CX', 'Amazon tracking #', 'Status');
        foreach ($montreal_data as $montreal) {
            $montreal_array[] = array(
                'JoeyCo Order #' => $montreal->order_id,
                'Route Number' => $montreal->route,
                'Joey' => $montreal->joey,
                'Customer Address' => $montreal->address,
                'Pickup From Hub' => $montreal->picked_hub_time,
                'Sorter Time' => $montreal->sorter_time,
                'Estimated Delivery ETA' => $montreal->dropoff_eta,
                'Actual Arrival @ CX' => $montreal->delivery_time,
                'Amazon tracking #' => substr($montreal->tracking_id, ($pos = strrpos($montreal->tracking_id, '_')) == false ? 0 : $pos+1),
                'Status' =>   self::$status[$montreal->sprint_status]
            );
        }
        Excel::create('Montreal Delivered Data '.$date.'', function ($excel) use ($montreal_array) {
            $excel->setTitle('Montreal Delivered Data');
            $excel->sheet('Montreal Delivered Data', function ($sheet) use ($montreal_array) {
                $sheet->fromArray($montreal_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    /**
     * Get Montreal Returned
     */
    public function getMontreturned(Request $request)
    {

        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $amazon_montreal_count = Amazon_count::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $today_date . "%")
            ->where(['vendor_id' => 477260])
            ->orderBy('id','DESC')
            ->first();

        $date = date('Y-m-d', strtotime($today_date. ' -1 days'));
        $notscan_count = Sprint::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"),'like',$date."%")
            ->where(['creator_id' => 477260])
            ->whereIn('status_id', [61,13])
            ->count();

        $title_name = 'Montreal';
        return backend_view('montrealdashboard.returned_orders', compact('amazon_montreal_count', 'title_name','notscan_count'));
    }

    /**
     * Yajra call after Montreal Returned
     */
    public function montrealReturnedData(Datatables $datatables, Request $request)
    {

        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");

        $query = Amazon::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $today_date . "%")
            ->whereIn('sprint_status',  [101,102,103,104,105,106,107,108,109,110,111,112,131,135,136])
            ->where('vendor_id', [477260]);

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('sprint_status', static function ($record) {
                return self::$status[$record->sprint_status];
            })
            ->editColumn('image', static function ($record) {
                if (isset($record->image)) {
                    return '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "' . $record->image . '" />';
                } else {
                    return '';
                }
            })
            ->editColumn('tracking_id', static function ($record) {
                return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos+1);
            })
            ->addColumn('action', static function ($record) {
                return backend_view('montrealdashboard.action_returned', compact('record'));
            })
            ->make(true);
    }

    /**
     * Get Montreal Returned order detail
     */
    public function montrealreturnedDetail(Request $request, $id)
    {
        $mont_id = base64_decode($id);
        $amazon_montreal = Amazon::where(['id' => $mont_id])->get();
        $amazon_montreal = $amazon_montreal[0];

        return backend_view('montrealdashboard.montreal_returned_detail', compact('amazon_montreal'));
    }

    /**
     * Get Montreal Returned orders excel report
     */
    public function montrealReturnedExcel($date = null)
    {
        if($date == null)
        {
            $date = date('Y-m-d');
        }
        $montreal_data = Amazon::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $date . "%")->whereIn('sprint_status', [101,102,103,104,105,106,107,108,109,110,111,112,131,135,136])->where('vendor_id', [477260])->get();
        $montreal_array[] = array('JoeyCo Order #', 'Route Number', 'Joey', 'Customer Address', 'Pickup From Hub', 'Sorter Time', 'Estimated Delivery ETA', 'Actual Arrival @ CX', 'Amazon tracking #', 'Status');
        foreach ($montreal_data as $montreal) {
            $montreal_array[] = array(
                'JoeyCo Order #' => $montreal->order_id,
                'Route Number' => $montreal->route,
                'Joey' => $montreal->joey,
                'Customer Address' => $montreal->address,
                'Pickup From Hub' => $montreal->picked_hub_time,
                'Sorter Time' => $montreal->sorter_time,
                'Estimated Delivery ETA' => $montreal->dropoff_eta,
                'Actual Arrival @ CX' => $montreal->delivery_time,
                'Amazon tracking #' => substr($montreal->tracking_id, ($pos = strrpos($montreal->tracking_id, '_')) == false ? 0 : $pos+1),
                'Status' =>   self::$status[$montreal->sprint_status]
            );
        }
        Excel::create('Montreal Returned Data '.$date.'', function ($excel) use ($montreal_array) {
            $excel->setTitle('Montreal Returned Data');
            $excel->sheet('Montreal Returned Data', function ($sheet) use ($montreal_array) {
                $sheet->fromArray($montreal_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    /**
     * Get Montreal Reoute Info
     */
    public function getRouteinfo(Request $request)
    {

        $date = $request->input('datepicker');
        // dd($date);
        if($date==null){
            $date = date("Y-m-d");
        }
		
        /*JoeyRoutes:://where('created_at','like',$date."%")
			 where('date','like',$date."%")
            ->where('hub',16)
			->where('deleted_at',null)
            ->orderBy('date', 'DESC')
            ->groupBy('id')
            ->get();*/
			$montreal_info =  JoeyRoutes::join('joey_route_locations','joey_routes.id','=','joey_route_locations.route_id')
            ->where('joey_routes.date','like',$date."%")
            ->where('joey_routes.hub',16)
            ->where('joey_routes.deleted_at',null)
            ->where('joey_route_locations.deleted_at',null)
            ->orderBy('joey_routes.id', 'ASC')
            ->groupBy('joey_routes.id')
			->select('joey_routes.*')
            ->get();


        return backend_view('montrealdashboard.montreal_route_info', compact('montreal_info'));
    }

    /**
     * Get Montreal Route Info excel report
     */
    public function montrealRouteinfoExcel($date = null)
    {
        //setting up current date if null
        if($date == null)
        {
            $date = date('Y-m-d');
        }

        /*getting csv file data*/
        $montreal_route_data = JoeyRoutes::join('joey_route_locations','joey_routes.id','=','joey_route_locations.route_id')
            ->where('joey_routes.date','like',$date."%")
            ->where('joey_routes.hub',16)
            ->where('joey_routes.deleted_at',null)
            ->where('joey_route_locations.deleted_at',null)
            ->orderBy('joey_routes.id', 'ASC')
            ->groupBy('joey_routes.id')
			->select('joey_routes.*')
            ->get();
		//JoeyRoutes::where(\DB::raw("CONVERT_TZ(date,'UTC','America/Toronto')"),'like',$date."%")->where('hub',16)->get();

        //checking if data is null then return null
        if(count($montreal_route_data) <= 0)
        {
            // if the data null ten return empty array
            return [];
        }

        // init data variable
        $data  = [];
        $csv_header = ['Route No','Joey Name', 'No of drops', 'No of picked', 'No of drops completed', 'No of Returns', 'No of unattempted'];
        $data[0] =  $csv_header;

        $iteration = 1;
        foreach($montreal_route_data as $montreal_route)
        {
			$joey_name = ($montreal_route->joey)?$montreal_route->Joey->first_name.' '.$montreal_route->Joey->last_name:'';
            $data[$iteration] = [
                $montreal_route->id,
				$joey_name,
                $montreal_route->TotalOrderDropsCount(),
                $montreal_route->TotalOrderPickedCount(),
                $montreal_route->TotalOrderDropsCompletedCount(),
                $montreal_route->TotalOrderReturnCount(),
                $montreal_route->TotalOrderUnattemptedCount()
            ];
            $iteration++;
        }
        return $data;
    }

    /**
     * Get Montreal Route
     */
	public function getMontrealRoute($routeId,$hubId)
    {

        return backend_view('montrealdashboard.montreal-hub-route',['routeId'=>$routeId,'hubId'=>$hubId]);
    }

    /**
     * Yajra call after Montreal Route
     */
    public function montrealRouteData(Datatables $datatables, Request $request)
    {

        date_default_timezone_set('America/Toronto');
        $routeid =  $request->get('routeid');
        $query = JoeyRouteLocations::join('sprint__tasks','joey_route_locations.task_id','=','sprint__tasks.id')
            ->leftJoin('merchantids','merchantids.task_id','=','sprint__tasks.id')
            ->join('locations','location_id','=','locations.id')
            ->join('sprint__sprints','sprint_id','=','sprint__sprints.id')
            ->whereNull('sprint__sprints.deleted_at')
            ->where('route_id','=',$routeid)
            ->whereNull('joey_route_locations.deleted_at')
            ->whereNotNull('merchantids.tracking_id')
            ->orderBy('joey_route_locations.ordinal','asc')
            ->select(['joey_route_locations.id','merchantids.merchant_order_num as merchant_order_num','joey_route_locations.task_id as task_id','merchantids.tracking_id as tracking_id',
                'sprint_id','type','start_time','end_time','address','postal_code'
                ,'joey_route_locations.arrival_time as arrival_time','joey_route_locations.finish_time as finish_time',
                'joey_route_locations.distance']);
        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
        ->editColumn('sprint_id', static function ($record) {
            return "CR-".$record->sprint_id;
        })
        ->addColumn('time', static function ($record) {
                return $record->arrival_time."-".$record->finish_time;
            })
            ->addColumn('duration', static function ($record) {

                if ($count=true) {
                    $count = false;
                    return "0";
                }
                else
                {
                    $date1 = new DateTime("2020-01-01 ".$record->finish_time.":00");
                    $date2 = new DateTime("2020-01-01 ".$record->arrival_time.":00");
                    $interval = $date1->diff($date2);

                    return $interval->format("%H:%I:%S");

                }
            })
            ->addColumn('address', static function ($record) {
                return $record->address.','.$record->postal_code;
            })
            ->addColumn('distance', static function ($record) {
                return round($record->distance/1000,2)."km";
            })




            /*->addColumn('action', static function ($record) {
                return backend_view('montrealdashboard.action', compact('record'));
            })*/
            ->make(true);
    }

    /**
     * Get Montreal HubRoute Edit
     */
    public function montrealHubRouteEdit(Request $request,$routeId,$hubId)
    {

        $tracking_id = null;
        $status =null;
        $route = JoeyRouteLocations::join('sprint__tasks','joey_route_locations.task_id','=','sprint__tasks.id')
            ->leftJoin('merchantids','merchantids.task_id','=','sprint__tasks.id')
            ->join('locations','location_id','=','locations.id')
            ->join('sprint__sprints','sprint_id','=','sprint__sprints.id')
            ->leftJoin('sprint__contacts','sprint__contacts.id','=','sprint__tasks.contact_id')
            ->whereNull('sprint__sprints.deleted_at')
            //->whereNotIn('sprint__sprints.status_id',[36,17])
            ->where('route_id','=',$routeId)
            ->whereNull('joey_route_locations.deleted_at')
            ->whereNotNull('merchantids.tracking_id')
            ->orderBy('joey_route_locations.ordinal','asc');
            if (!empty($request->get('tracking-id'))) {
                $tracking_id = $request->get('tracking-id');
                $route = $route->where('merchantids.tracking_id', '=', $request->get('tracking-id'))
                               ;
            }

        if (!empty($request->get('status'))) {
            $status = $request->get('status');
            $route = $route->where('sprint__sprints.status_id', '=', $request->get('status'));
        }
            $route =$route->get(['joey_route_locations.id','merchantids.merchant_order_num','joey_route_locations.task_id','merchantids.tracking_id',
                'sprint_id','type','start_time','end_time','address','postal_code'
                ,'joey_route_locations.arrival_time','joey_route_locations.finish_time', 'sprint__sprints.status_id', 'sprint__tasks.sprint_id',
                'joey_route_locations.distance','sprint__contacts.name','sprint__contacts.phone','joey_route_locations.route_id','joey_route_locations.ordinal']);

        return backend_view('montrealdashboard.edit-hub-route',['route'=>$route,'hub_id'=>$hubId,'tracking_id'=>$tracking_id,'status_select'=>$status]);
    }

    /**
     * Get Montreal Tracking Order
     */
    public function getMontrealtrackingorderdetails($sprintId)
    {
        $result= Sprint::join('sprint__tasks','sprint_id','=','sprint__sprints.id')
            ->leftJoin('merchantids','merchantids.task_id','=','sprint__tasks.id')
            ->leftJoin('joey_route_locations','joey_route_locations.task_id','=','sprint__tasks.id')
            ->leftJoin('joey_routes','joey_routes.id','=','joey_route_locations.route_id')
            ->leftJoin('joeys','joeys.id','=','joey_routes.joey_id')
            ->join('locations','sprint__tasks.location_id','=','locations.id')
            ->join('sprint__contacts','contact_id','=','sprint__contacts.id')
            ->leftJoin('vendors','creator_id','=','vendors.id')
            ->where('sprint__tasks.sprint_id','=',$sprintId)
            //->whereNull('joey_route_locations.deleted_at')
            ->orderBy('ordinal','DESC')->take(1)
            ->get(array('sprint__tasks.*','joey_routes.id as route_id','locations.address','locations.suite','locations.postal_code','sprint__contacts.name','sprint__contacts.phone','sprint__contacts.email',
                'joeys.first_name as joey_firstname','joeys.id as joey_id',
                'joeys.last_name as joey_lastname','vendors.first_name as merchant_firstname','vendors.last_name as merchant_lastname','merchantids.scheduled_duetime'
            ,'joeys.id as joey_id','merchantids.tracking_id','joeys.phone as joey_contact','joey_route_locations.ordinal as stop_number'));

        $i=0;

        $data = [];

        foreach($result as $tasks){
            $status2 = array();
            $status = array();
            $status1 = array();
            $data[$i] =  $tasks;
            $taskHistory= SprintTaskHistory::where('sprint_id','=',$tasks->sprint_id)->WhereNotIn('status_id',[17,38])->orderBy('id')
                //->where('active','=',1)
                ->get(['status_id','created_at']);

            $returnTOHubDate = SprintReattempt::
            where('sprint_reattempts.sprint_id','=' ,$tasks->sprint_id)->orderBy('created_at')
                ->first();

            if(!empty($returnTOHubDate))
            {
                $taskHistoryre= SprintTaskHistory::where('sprint_id','=', $returnTOHubDate->reattempt_of)->WhereNotIn('status_id',[17,38])->orderBy('id')
                    //->where('active','=',1)
                    ->get(['status_id','created_at']);

                foreach ($taskHistoryre as $history){

                    $status[$history->status_id]['id'] = $history->status_id;
                    if($history->status_id==13)
                    {
                        $status[$history->status_id]['description'] ='At hub - processing';
                    }
                    else
                    {
                        $status[$history->status_id]['description'] =$this->statusmap($history->status_id);
                    }
                    $status[$history->status_id]['created_at'] = date('Y-m-d H:i:s',strtotime($history->created_at)-14400);

                }

            }
            if(!empty($returnTOHubDate))
            {
                $returnTO2 = SprintReattempt::
                where('sprint_reattempts.sprint_id','=' , $returnTOHubDate->reattempt_of)->orderBy('created_at')
                    ->first();

                if(!empty($returnTO2))
                {
                    $taskHistoryre= SprintTaskHistory::where('sprint_id','=',$returnTO2->reattempt_of)->WhereNotIn('status_id',[17,38])->orderBy('id')
                        //->where('active','=',1)
                        ->get(['status_id','created_at']);

                    foreach ($taskHistoryre as $history){

                        $status2[$history->status_id]['id'] = $history->status_id;
                        if($history->status_id==13)
                        {
                            $status2[$history->status_id]['description'] ='At hub - processing';
                        }
                        else
                        {
                            $status2[$history->status_id]['description'] = $this->statusmap($history->status_id);
                        }
                        $status2[$history->status_id]['created_at'] = date('Y-m-d H:i:s',strtotime($history->created_at)-14400);

                    }

                }
            }

            //    dd($taskHistory);

            foreach ($taskHistory as $history){

                $status1[$history->status_id]['id'] = $history->status_id;

                if($history->status_id==13)
                {
                    $status1[$history->status_id]['description'] ='At hub - processing';
                }
                else
                {
                    $status1[$history->status_id]['description'] = $this->statusmap($history->status_id);
                }
                $status1[$history->status_id]['created_at'] = date('Y-m-d H:i:s',strtotime($history->created_at)-14400);

            }
            $data[$i]['status']= $status;
            $data[$i]['status1']= $status1;
            $data[$i]['status2']=$status2;
            $i++;
        }
        return backend_view('montrealdashboard.orderdetailswtracknigid',['data'=>$data,'sprintId' => $sprintId]);
    }
    /**
     * Get Montreal Tracking Order
     */

	    public function getTestMontrealtrackingorderdetails($sprintId)
    {
        $result= Sprint::join('sprint__tasks','sprint_id','=','sprint__sprints.id')
            ->leftJoin('merchantids','merchantids.task_id','=','sprint__tasks.id')
            ->leftJoin('joey_route_locations','joey_route_locations.task_id','=','sprint__tasks.id')
            ->leftJoin('joey_routes','joey_routes.id','=','joey_route_locations.route_id')
            ->leftJoin('joeys','joeys.id','=','joey_routes.joey_id')
            ->join('locations','sprint__tasks.location_id','=','locations.id')
            ->join('sprint__contacts','contact_id','=','sprint__contacts.id')
            ->leftJoin('vendors','creator_id','=','vendors.id')
            ->where('sprint__tasks.sprint_id','=',$sprintId)
            //->whereNull('joey_route_locations.deleted_at')
            ->orderBy('ordinal','DESC')->take(1)
            ->get(array('sprint__tasks.*','joey_routes.id as route_id','locations.address','locations.suite','locations.postal_code','sprint__contacts.name','sprint__contacts.phone','sprint__contacts.email',
                'joeys.first_name as joey_firstname','joeys.id as joey_id',
                'joeys.last_name as joey_lastname','vendors.first_name as merchant_firstname','vendors.last_name as merchant_lastname','merchantids.scheduled_duetime'
            ,'joeys.id as joey_id','merchantids.tracking_id','joeys.phone as joey_contact','joey_route_locations.ordinal as stop_number'));

        $i=0;

        $data = [];

        foreach($result as $tasks){
            $status2 = array();
            $status = array();
            $status1 = array();
            $data[$i] =  $tasks;
            $taskHistory= SprintTaskHistory::where('sprint_id','=',$tasks->sprint_id)->WhereNotIn('status_id',[17,38])->orderBy('id')
                //->where('active','=',1)
                ->get(['status_id','created_at']);

            $returnTOHubDate = SprintReattempt::
            where('sprint_reattempts.sprint_id','=' ,$tasks->sprint_id)->orderBy('created_at')
                ->first();

            if(!empty($returnTOHubDate))
            {
                $taskHistoryre= SprintTaskHistory::where('sprint_id','=', $returnTOHubDate->reattempt_of)->WhereNotIn('status_id',[17,38])->orderBy('id')
                    //->where('active','=',1)
                    ->get(['status_id','created_at']);

                foreach ($taskHistoryre as $history){

                    $status[$history->status_id]['id'] = $history->status_id;
                    if($history->status_id==13)
                    {
                        $status[$history->status_id]['description'] ='At hub - processing';
                    }
                    else
                    {
                        $status[$history->status_id]['description'] =$this->statusmap($history->status_id);
                    }
                    $status[$history->status_id]['created_at'] = date('Y-m-d H:i:s',strtotime($history->created_at)-14400);

                }

            }
            if(!empty($returnTOHubDate))
            {
                $returnTO2 = SprintReattempt::
                where('sprint_reattempts.sprint_id','=' , $returnTOHubDate->reattempt_of)->orderBy('created_at')
                    ->first();

                if(!empty($returnTO2))
                {
                    $taskHistoryre= SprintTaskHistory::where('sprint_id','=',$returnTO2->reattempt_of)->WhereNotIn('status_id',[17,38])->orderBy('id')
                        //->where('active','=',1)
                        ->get(['status_id','created_at']);

                    foreach ($taskHistoryre as $history){

                        $status2[$history->status_id]['id'] = $history->status_id;
                        if($history->status_id==13)
                        {
                            $status2[$history->status_id]['description'] ='At hub - processing';
                        }
                        else
                        {
                            $status2[$history->status_id]['description'] = $this->statusmap($history->status_id);
                        }
                        $status2[$history->status_id]['created_at'] = date('Y-m-d H:i:s',strtotime($history->created_at)-14400);

                    }

                }
            }

            //    dd($taskHistory);

            foreach ($taskHistory as $history){

                $status1[$history->status_id]['id'] = $history->status_id;

                if($history->status_id==13)
                {
                    $status1[$history->status_id]['description'] ='At hub - processing';
                }
                else
                {
                    $status1[$history->status_id]['description'] = $this->statusmap($history->status_id);
                }
                $status1[$history->status_id]['created_at'] = date('Y-m-d H:i:s',strtotime($history->created_at)-14400);

            }
            $data[$i]['status']= $status;
            $data[$i]['status1']= $status1;
            $data[$i]['status2']=$status2;
            $i++;
        }
        return backend_view('montrealdashboard.testorderdetailswtracknigid',['data'=>$data,'sprintId' => $sprintId]);
    }

	    public function getDirectMontreal(Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $amazon_montreal_count = Amazon_count::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"),'like',$today_date."%")
            ->where(['vendor_id' => 477260])
            ->orderBy('id','DESC')
            ->first();
			
        return backend_view('montrealdashboard.direct_montreal_dashboard', compact('amazon_montreal_count'));
    }

    public function directMontrealData(Datatables $datatables, Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");

       
	   
      /*  $query = DB::table(DB::raw("(SELECT DISTINCT sprint__tasks.id AS task_id,sprint__tasks.sprint_id,sprint__tasks.status_id,CONVERT_TZ(FROM_UNIXTIME(eta_time),'UTC','America/Toronto') AS dropoff_eta,location_id,ordinal FROM sprint__tasks WHERE TYPE='dropoff' AND sprint__tasks.deleted_at IS NULL 
            AND CONVERT_TZ(sprint__tasks.created_at,'UTC','America/Toronto') LIKE '".$today_date."%') AS sprint_Tasks"))
            ->select('sprint_Sprints.id as s_id','sprint_Sprints.status_id AS sprint_status','sprint_Tasks.sprint_id',
                DB::raw("CONCAT(sprint_Tasks.sprint_id,'-',sprint_Tasks.ordinal-1) AS order_id")
                ,'sprint_Tasks.status_id AS task_status','sprint_Tasks.dropoff_eta','Locations.address',
                DB::raw("CONVERT_TZ(FROM_UNIXTIME(Merchantids.scheduled_duetime),'UTC','America/Toronto')
            AS scheduled_duetime"),'Merchantids.merchant_order_num','Merchantids.tracking_id','Merchantids.start_time','Merchantids.end_time',
                DB::raw("CONCAT('R-',Joey_Route_Locations.route_id,'-',Joey_Route_Locations.ordinal) AS route"),
                DB::raw("CONCAT(Joeys.first_name,' ',Joeys.last_name,'(',Joey_Routes.joey_id,')') AS joey"),
                DB::raw("(SELECT attachment_path FROM sprint__confirmations WHERE task_id=sprint_Tasks.task_id  AND attachment_path IS NOT NULL LIMIT 1) AS image"),
                DB::raw("(SELECT CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto')  AS delivery_time FROM sprint__tasks_history WHERE sprint__tasks_history.status_id IN(17,113,114,116,117,118,138,139) AND sprint__tasks_history.active=1 AND sprint__tasks_id=sprint_Tasks.task_id LIMIT 1) AS delivery_time"),
                DB::raw("(SELECT CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto')  AS picked_hub_time FROM sprint__tasks_history WHERE sprint__tasks_history.status_id=121 AND ACTIVE=1 AND sprint__tasks_id=sprint_Tasks.task_id LIMIT 1) AS picked_hub_time"),
                DB::raw("(SELECT CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto')  AS sorter_time FROM sprint__tasks_history WHERE sprint__tasks_history.status_id=133 AND sprint__tasks_id=sprint_Tasks.task_id LIMIT 1) AS sorter_time"))
//

            ->join(DB::raw("(SELECT id,status_id FROM sprint__sprints WHERE sprint__sprints.creator_id=477260 AND sprint__sprints.deleted_at IS NULL 
            AND CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') LIKE '".$today_date."%' AND sprint__sprints.status_id NOT IN (36,35,38) ) AS sprint_Sprints")
                , function($join)
                {
                    $join->on('sprint_Tasks.sprint_id','=','sprint_Sprints.id');

                })*/
	
       $today_date = date('Y-m-d', strtotime($today_date. ' -1 days'));
        
        $query = DB::table(DB::raw("(SELECT id,status_id FROM sprint__sprints WHERE sprint__sprints.creator_id=477260 AND sprint__sprints.deleted_at IS NULL 
            AND CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') LIKE '".$today_date."%' AND sprint__sprints.status_id NOT IN (36,35,38) ) AS sprint_Sprints"))
            ->select('sprint_Sprints.id as s_id','sprint_Sprints.status_id AS sprint_status','sprint_Tasks.sprint_id',
                DB::raw("CONCAT(sprint_Tasks.sprint_id,'-',sprint_Tasks.ordinal-1) AS order_id")
                ,'sprint_Tasks.status_id AS task_status','sprint_Tasks.dropoff_eta',
                DB::raw("CONVERT_TZ(FROM_UNIXTIME(Merchantids.scheduled_duetime),'UTC','America/Toronto')
            AS scheduled_duetime"),'Merchantids.merchant_order_num','Merchantids.tracking_id','Merchantids.start_time','Merchantids.end_time',
                DB::raw("CONCAT('R-',Joey_Route_Locations.route_id,'-',Joey_Route_Locations.ordinal) AS route"),
                DB::raw("CONCAT(Joeys.first_name,' ',Joeys.last_name,'(',Joey_Routes.joey_id,')') AS joey"),
                DB::raw("(SELECT attachment_path FROM sprint__confirmations WHERE task_id=sprint_Tasks.task_id  AND attachment_path IS NOT NULL LIMIT 1) AS image"),
                DB::raw("(SELECT CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto')  AS delivery_time FROM sprint__tasks_history WHERE sprint__tasks_history.status_id IN(17,113,114,116,117,118,138,139) AND sprint__tasks_history.active=1 AND sprint__tasks_id=sprint_Tasks.task_id LIMIT 1) AS delivery_time"),
                DB::raw("(SELECT CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto')  AS picked_hub_time FROM sprint__tasks_history WHERE sprint__tasks_history.status_id=121 AND ACTIVE=1 AND sprint__tasks_id=sprint_Tasks.task_id LIMIT 1) AS picked_hub_time"),
                DB::raw("(SELECT CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto')  AS sorter_time FROM sprint__tasks_history WHERE sprint__tasks_history.status_id=133 AND ACTIVE=1 AND sprint__tasks_id=sprint_Tasks.task_id LIMIT 1) AS sorter_time")
                ,DB::raw("(SELECT address As address FROM locations where id = sprint_Tasks.location_id) AS address"))

            ->join(DB::raw("(SELECT DISTINCT sprint__tasks.id AS task_id,sprint__tasks.sprint_id,sprint__tasks.status_id,CONVERT_TZ(FROM_UNIXTIME(eta_time),'UTC','America/Toronto') AS dropoff_eta,location_id,ordinal FROM sprint__tasks WHERE ordinal=2 AND sprint__tasks.deleted_at IS NULL
            AND CONVERT_TZ(sprint__tasks.created_at,'UTC','America/Toronto') LIKE '".$today_date."%') AS sprint_Tasks")
                , function($join)
                {
                    $join->on('sprint_Tasks.sprint_id','=','sprint_Sprints.id');

                })

            ->leftjoin(DB::raw("(SELECT task_id,scheduled_duetime,merchant_order_num,tracking_id,start_time,end_time FROM merchantids) AS Merchantids"), function ($join) {
                $join->on('sprint_Tasks.task_id','=','Merchantids.task_id');
            })
            ->leftjoin(DB::raw("(SELECT task_id,route_id,ordinal FROM joey_route_locations WHERE joey_route_locations.deleted_at IS NULL) AS Joey_Route_Locations"), function ($join) {
                $join->on('sprint_Tasks.task_id','=','Joey_Route_Locations.task_id');
            })
            ->leftjoin(DB::raw("(SELECT id,joey_id FROM joey_routes WHERE joey_routes.deleted_at IS NULL) AS Joey_Routes"), function ($join) {
                $join->on('Joey_Route_Locations.route_id','=','Joey_Routes.id');
            })
            ->leftjoin(DB::raw("(SELECT id,first_name,last_name FROM joeys ) AS Joeys"), function ($join) {
                $join->on('Joey_Routes.joey_id','=','Joeys.id');
            })

            ->distinct()
            ->orderby('order_id')->offset(0)->limit(250)->get();
			dd($query);
		 /*$sprintIds = DB::table('sprint__Sprints')->where(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"),'like',$today_date."%")
            ->whereNull('deleted_at')
            ->whereNotIn('status_id',[36,35,38])
            ->where('creator_id', 477260)->pluck('id');
        $sprintIds = implode (",", $sprintIds);

        $taskIds = DB::table('sprint__tasks')->where(DB::raw("CONVERT_TZ(sprint__tasks.created_at,'UTC','America/Toronto')"),'like',$today_date."%")
        ->whereNull('deleted_at')
        ->where('ordinal',2)->pluck('id');
        $taskIds = implode (",", $taskIds);
        //dd($taskIds);
        //->join(DB::raw("(SELECT id,status_id FROM sprint__sprints WHERE sprint__sprints.creator_id=477260 AND sprint__sprints.deleted_at IS NULL
         //   AND CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') LIKE '".$today_date."%' AND sprint__sprints.status_id NOT IN (36,35,38) ) AS sprint_Sprints")

        //DB::raw("(SELECT DISTINCT sprint__tasks.id AS task_id,sprint__tasks.sprint_id,sprint__tasks.status_id,CONVERT_TZ(FROM_UNIXTIME(eta_time),'UTC','America/Toronto') AS dropoff_eta,location_id,ordinal FROM sprint__tasks WHERE ordinal=2 AND sprint__tasks.deleted_at IS NULL
        //    AND CONVERT_TZ(sprint__tasks.created_at,'UTC','America/Toronto') LIKE '".$today_date."%') AS sprint_Tasks"))
        $query = DB::table(DB::raw("(SELECT DISTINCT sprint__tasks.id AS task_id,sprint__tasks.sprint_id,sprint__tasks.status_id,CONVERT_TZ(FROM_UNIXTIME(eta_time),'UTC','America/Toronto') AS dropoff_eta,
        location_id,ordinal FROM sprint__tasks WHERE id IN (".$taskIds.") ) AS sprint_Tasks"))
            ->select('sprint_Sprints.id as s_id','sprint_Sprints.status_id AS sprint_status','sprint_Tasks.sprint_id',
                DB::raw("CONCAT(sprint_Tasks.sprint_id,'-',sprint_Tasks.ordinal-1) AS order_id")
                ,'sprint_Tasks.status_id AS task_status','sprint_Tasks.dropoff_eta','Locations.address',
                DB::raw("CONVERT_TZ(FROM_UNIXTIME(Merchantids.scheduled_duetime),'UTC','America/Toronto')
            AS scheduled_duetime"),'Merchantids.merchant_order_num','Merchantids.tracking_id','Merchantids.start_time','Merchantids.end_time',
                DB::raw("CONCAT('R-',Joey_Route_Locations.route_id,'-',Joey_Route_Locations.ordinal) AS route"),
                DB::raw("CONCAT(Joeys.first_name,' ',Joeys.last_name,'(',Joey_Routes.joey_id,')') AS joey"),
                DB::raw("(SELECT attachment_path FROM sprint__confirmations WHERE task_id=sprint_Tasks.task_id  AND attachment_path IS NOT NULL LIMIT 1) AS image"),
                DB::raw("(SELECT CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto')  AS delivery_time FROM sprint__tasks_history WHERE sprint__tasks_history.status_id IN(17,113,114,116,117,118,138,139) AND sprint__tasks_history.active=1 AND sprint__tasks_id=sprint_Tasks.task_id LIMIT 1) AS delivery_time"),
                DB::raw("(SELECT CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto')  AS picked_hub_time FROM sprint__tasks_history WHERE sprint__tasks_history.status_id=121 AND ACTIVE=1 AND sprint__tasks_id=sprint_Tasks.task_id LIMIT 1) AS picked_hub_time"),
                DB::raw("(SELECT CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto')  AS sorter_time FROM sprint__tasks_history WHERE sprint__tasks_history.status_id=133 AND sprint__tasks_id=sprint_Tasks.task_id LIMIT 1) AS sorter_time"))
//

            ->join(DB::raw("(SELECT id,status_id FROM sprint__sprints WHERE id IN (".$sprintIds.") ) AS sprint_Sprints")
                , function($join)
                {
                    $join->on('sprint_Tasks.sprint_id','=','sprint_Sprints.id');

                })
            ->join(DB::raw("(SELECT id,address FROM locations) AS Locations"), function ($join) {
                $join->on('sprint_Tasks.location_id','=','Locations.id');
            })
            ->leftjoin(DB::raw("(SELECT task_id,scheduled_duetime,merchant_order_num,tracking_id,start_time,end_time FROM merchantids) AS Merchantids"), function ($join) {
                $join->on('sprint_Tasks.task_id','=','Merchantids.task_id');
            })
            ->leftjoin(DB::raw("(SELECT task_id,route_id,ordinal FROM joey_route_locations WHERE joey_route_locations.deleted_at IS NULL) AS Joey_Route_Locations"), function ($join) {
                $join->on('sprint_Tasks.task_id','=','Joey_Route_Locations.task_id');
            })
            ->leftjoin(DB::raw("(SELECT id,joey_id FROM joey_routes WHERE joey_routes.deleted_at IS NULL) AS Joey_Routes"), function ($join) {
                $join->on('Joey_Route_Locations.route_id','=','Joey_Routes.id');
            })
            ->leftjoin(DB::raw("(SELECT id,first_name,last_name FROM joeys ) AS Joeys"), function ($join) {
                $join->on('Joey_Routes.joey_id','=','Joeys.id');
            })

            ->distinct()
            ->orderby('order_id');*/
      
        return $datatables->queryBuilder($query)


            ->editColumn('image', static function ($record) {
                if (isset($record->image)) {
                    return '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "' . $record->image . '" />';
                }
                 else {
                     return '';
                 }
            })

            ->editColumn('sprint_status', static function ($record) {
                return self::$status[$record->sprint_status];
            })
            ->addColumn('action', static function ($record) {
                //return backend_view('montrealdashboard.action', compact('record'));
            })
            ->make(true);
    }

}
