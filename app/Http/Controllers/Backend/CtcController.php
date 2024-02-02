<?php

namespace App\Http\Controllers\Backend;

use App\CustomerFlagCategories;
use App\CustomerRoutingTrackingId;
use App\FlagCategoryMetaData;
use App\FlagHistory;
use App\Http\Traits\BasicModelFunctions;
use App\JoeyRouteLocations;
use App\JoeyRoutes;
use App\Reason;
use App\Sprint;
use App\MerchantIds;
use App\SprintReattempt;
use App\SprintTaskHistory;
use App\TaskHistory;
use App\TrackingDelay;
use Illuminate\Http\Request;
use App\Ctc;
use App\Task;
use App\Notes;
use App\Ctc_count;
use App\CtcVendor;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

//use Illuminate\Database\Eloquent\Builder;

class CtcController extends BackendController
{

    use BasicModelFunctions;
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
        return $statusid[$id];
    }


    /**
     * Get CTC Dashboard
     */
    public function getCtcNewDashboard(Request $request)
    {
        $status_code = array_intersect_key(self::$status, [61 => '', 124 => '', 121 => '', 133 => '', 17 => '', 113 => '', 114 => '', 116 => '', 117 => '', 118 => '', 132 => '', 138 => '', 139 => '', 144 => '', 104 => '', 105 => '', 106 => '', 107 => '',
            108 => '', 109 => '', 110 => '', 111 => '', 112 => '', 131 => '', 135 => '', 136 => '']);
        return backend_view('ctc.ctc_new_dashboard_yajra', compact('status_code'));
    }

    /**
     * Yajra call after  CTC Dashboard
     */
    public function getCtcNewDashboardData(Datatables $datatables, Request $request)
    {
        $sprintId = 0;
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        if (!empty($request->get('tracking_id'))) {
            $task_id = MerchantIds::where('tracking_id', $request->get('tracking_id'))->where('deleted_at', null)->first();
            if ($task_id) {
                $sprint = Task::where('id', $task_id->task_id)->first();
                $sprintId = $sprint->sprint_id;
            }
        }
        if (!empty($request->get('route_id'))) {
            $task_ids = JoeyRouteLocations::where('route_id', $request->get('route_id'))->where('deleted_at', null)->pluck('task_id');

            if ($task_ids) {
                $sprintIds = Task::whereIn('id', $task_ids)->pluck('sprint_id');
            }
        }
        if (!empty($request->get('tracking_id'))) {
            $query = Sprint::where('id', $sprintId);//->where('deleted_at', null);
        } else if (!empty($request->get('route_id'))) {
            $query = Sprint::whereIn('id', $sprintIds);//->where('deleted_at', null);
        } else {
            $ctcVendorIds = CtcVendor::pluck('vendor_id');
            $sprintIds = Sprint::whereIn('creator_id', $ctcVendorIds)->where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $today_date . "%")
                ->whereNotIn('status_id', [38, 36])->pluck('id');
            $query = Sprint::whereIn('id', $sprintIds)->where('deleted_at', null)->where('is_reattempt','=',0);

        }

        if (!empty($request->get('status'))) {
            $sprint_status = new Sprint();
            if ($request->get('status') == 1) {
                $statusIds = $sprint_status->getStatusCodes('competed');
            } elseif ($request->get('status') == 2) {
                $statusIds = $sprint_status->getStatusCodes('return');
            } else {
                $statusIds = [$request->get('status')];
            }

            $query = $query->whereIn('status_id', $statusIds);
        }
        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('status_id', static function ($record) {
                $current_status = $record->status_id;
                if ($record->status_id == 17) {
                    $preStatus = \App\SprintTaskHistory
                        ::where('sprint_id', '=', $record->id)
                        ->where('status_id', '!=', '17')
                        ->orderBy('id', 'desc')->first();
                    if (!empty($preStatus)) {
                        $current_status = $preStatus->status_id;
                    }
                }
                if ($current_status == 13) {
                    return "At hub - processing";
                } else {
                    return self::$status[$current_status];
                }
            })
            ->addColumn('route_id', static function ($record) {
                if ($record->sprintCtcTasks) {
                    if ($record->sprintCtcTasks->taskRouteLocation) {
                        return 'R-' . $record->sprintCtcTasks->taskRouteLocation->route_id . '-' . $record->sprintCtcTasks->taskRouteLocation->ordinal;
                    } else {
                        " ";
                    }
                } else {
                    "";
                }
            })
            ->addColumn('joey', static function ($record) {
                if ($record->sprintCtcTasks) {
                    if ($record->sprintCtcTasks->taskRouteLocation) {
                        if ($record->sprintCtcTasks->taskRouteLocation->joeyRoute) {
                            if ($record->sprintCtcTasks->taskRouteLocation->joeyRoute->joey) {
                                return $record->sprintCtcTasks->taskRouteLocation->joeyRoute->joey->first_name . ' ' . $record->sprintCtcTasks->taskRouteLocation->joeyRoute->joey->last_name . ' (' . $record->sprintCtcTasks->taskRouteLocation->joeyRoute->joey_id . ')';
                            } else {
                                "";
                            }
                        } else {
                            "";
                        }
                    } else {
                        "";
                    }
                } else {
                    "";
                }
            })
            ->addColumn('tracking_id', static function ($record) {
                if ($record->sprintCtcTasks) {
                    if ($record->sprintCtcTasks->taskMerchants) {
                        return substr($record->sprintCtcTasks->taskMerchants->tracking_id, strrpos($record->sprintCtcTasks->taskMerchants->tracking_id, '_') + 0);
                    } else {
                        "";
                    }
                } else {
                    "";
                }
            })
            ->addColumn('eta_time', static function ($record) {
                if ($record->sprintCtcTasks) {
                    return date('Y-m-d H:i:s', strtotime("+1 day", strtotime($record->sprintCtcTasks->eta_time)));
                } else {
                    "";
                }
            })
            ->addColumn('store_name', static function ($record) {
                if ($record->sprintVendor) {
                    return $record->sprintVendor->name;
                } else {
                    "";
                }
            })
            ->addColumn('customer_name', static function ($record) {
                if ($record->sprintCtcTasks) {
                    if ($record->sprintCtcTasks->taskContact) {
                        return $record->sprintCtcTasks->taskContact->name;
                    } else {
                        "";
                    }
                } else {
                    "";
                }
            })
            ->addColumn('weight', static function ($record) {
                if ($record->sprintCtcTasks) {
                    if ($record->sprintCtcTasks->taskMerchants) {
                        return $record->sprintCtcTasks->taskMerchants->weight . $record->sprintCtcTasks->taskMerchants->weight_unit;
                    } else {
                        "";
                    }
                } else {
                    "";
                }
            })
            ->addColumn('address', static function ($record) {
                if ($record->sprintCtcTasks) {
                    if ($record->sprintCtcTasks->taskMerchants) {
                        return $record->sprintCtcTasks->taskMerchants->address_line2;
                    } else {
                        "";
                    }
                } else {
                    "";
                }
            })
            ->addColumn('action', static function ($record) {
                return backend_view('ctc.new_action', compact('record'));
            })
            ->make(true);

    }

    /**
     * Get CTC Order detail
     */
    public function ctcNewProfile(Request $request, $id)
    {
        //$ctc_data = Sprint::where('id', $id)->where('deleted_at', null)->first();
        $ctc_data = $this->get_trackingorderdetails($id);
        $sprintId = $ctc_data['sprintId'];
        $data = $ctc_data['data'];
        return backend_view('ctc.ctc_new_profile', compact('data', 'sprintId'));
    }

    /**
     * Get CTC Dashboard Excel Report
     */
    public function CtcNewDashboardExcel($date = null)
    {
        if ($date == null) {
            $date = date('Y-m-d');
        }
        $ctcVendorIds = CtcVendor::pluck('vendor_id');
        /* $ctc_data = Sprint::whereIn('creator_id', $ctcVendorIds)->where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $date . "%")
             ->where('deleted_at', null)->whereNotIn('status_id',[38,36])->where('is_reattempt',0)->get();
         */
        $sprintIds = Sprint::whereIn('creator_id', $ctcVendorIds)->where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $date . "%")
            ->whereNotIn('status_id', [38, 36])->pluck('id');
        $ctc_data = Sprint::whereIn('id', $sprintIds)->where('deleted_at', null)->where('is_reattempt','=', 0)->get();
        // header info for browser


        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=Canadian Tire Data.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo "Joeyco Order\tRoute\tJoey\tStore Name\tCustomer Name\tCustomer Address\tPostal Code\tCity Name\tWeight\tPickup From Store\tAt Hub Processing\tOut For Delivery\tEstimated Customer delivery time\tActual Customer delivery time\tShipment tracking #\tShipment tracking link\tShipment Delivery Status\tJoyeCo Notes / Comments\tReturned to HUB 2\t2nd Attempt Pick up\t2nd Attempt Delivery\tReturned to HUB 3\t3rd Attempt Pick up\t3rd Attempt Delivery\t\n";

        // $ctc_array[] = ['Joeyco Order', 'Route', 'Joey', 'Store Name', 'Customer Name', 'Customer Address', 'Postal Code', 'City Name', 'Weight', 'Pickup From Store', 'At Hub Processing', 'Out For Delivery', 'Estimated Customer delivery time', 'Actual Customer delivery time', 'Shipment tracking #', 'Shipment tracking link', 'Shipment Delivery Status', 'JoyeCo Notes / Comments', 'Returned to HUB 2', '2nd Attempt Pick up', '2nd Attempt Delivery', 'Returned to HUB 3', '3rd Attempt Pick up', '3rd Attempt Delivery'];

        foreach ($ctc_data as $ctc) {
            $pickup3 = "";
            $hubreturned3 = "";
            $hubpickup3 = "";
            $deliver3 = "";
            $pickup2 = "";
            $hubreturned2 = "";
            $hubpickup2 = "";
            $deliver2 = "";
            // $hubreturned = "";
            // $hubpickup = "";
            // $deliver = "";
            $notes = '';
            $actual_delivery = "";
            $pickup = $ctc->pickupFromStore()->pickup;
            $hubreturned = $ctc->atHubProcessing()->athub;
            $hubpickup = $ctc->outForDelivery()->outdeliver;
            $deliver = $ctc->deliveryTime()->delivery_time;
            $notes1 = Notes::where('object_id', $ctc->id)->pluck('note');
            $i = 0;
            foreach ($notes1 as $note) {
                if ($i == 0)
                    $notes = $notes . $note;
                else
                    $notes = $notes . ', ' . $note;
            }
            if ($ctc->sprintReattempts) {
                if ($ctc->sprintReattempts->reattempts_left == 1) {

                    $hubreturned3 = $ctc->atHubProcessing()->athub;
                    $hubpickup3 = $ctc->outForDelivery()->outdeliver;
                    $deliver3 = $ctc->deliveryTime()->delivery_time;

                    $secondAttempt = \App\SprintTaskHistory::where('sprint_id', '=', $ctc->sprintReattempts->reattempt_of)->orderBy('created_at', 'ASC')
                        ->get(['status_id', \DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') as created_at")]);
                    if (!empty($secondAttempt)) {

                        foreach ($secondAttempt as $secAttempt) {

                            /* if ($secAttempt->status_id == 125) {
                                 $pickup = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                             }*/
                            if (in_array($secAttempt->status_id, [124, 13])) {
                                $hubreturned2 = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }
                            if ($secAttempt->status_id == 121) {
                                $hubpickup2 = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }
                            if (in_array($secAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 131, 101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 131, 135, 136, 143])) {
                                $deliver2 = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }
                        }
                    }

                    $firstSprint = \App\SprintReattempt::where('sprint_id', '=', $ctc->sprintReattempts->reattempt_of)->first();
                    if (!empty($firstSprint)) {
                        $firstAttempt = \App\SprintTaskHistory::where('sprint_id', '=', $firstSprint->reattempt_of)->orderBy('created_at', 'ASC')->
                        get(['status_id', \DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') as created_at")]);
                        if (!empty($firstAttempt)) {

                            foreach ($firstAttempt as $firstAttempt) {
                                if ($firstAttempt->status_id == 125) {
                                    $pickup = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                }
                                if (in_array($firstAttempt->status_id, [124])) {
                                    $hubreturned = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                }
                                if ($firstAttempt->status_id == 121) {
                                    $hubpickup = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                }
                                if (in_array($firstAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 131, 101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 131, 135, 136, 143])) {
                                    $deliver = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                }
                            }

                        }
                    }
                }
                if ($ctc->sprintReattempts->reattempts_left == 2) {

                    $hubreturned2 = $ctc->atHubProcessing()->athub;
                    $hubpickup2 = $ctc->outForDelivery()->outdeliver;
                    $deliver2 = $ctc->deliveryTime()->delivery_time;

                    $secondAttempt = \App\SprintTaskHistory::where('sprint_id', '=', $ctc->sprintReattempts->reattempt_of)->orderBy('created_at', 'ASC')->
                    get(['status_id', \DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') as created_at")]);
                    if (!empty($secondAttempt)) {
                        date_default_timezone_set('America/Toronto');
                        foreach ($secondAttempt as $secAttempt) {
                            if ($secAttempt->status_id == 125) {
                                $pickup = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }
                            if (in_array($secAttempt->status_id, [124])) {
                                $hubreturned = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }
                            if ($secAttempt->status_id == 121) {
                                $hubpickup = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }
                            if (in_array($secAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 131, 101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 131, 135, 136, 143])) {
                                $deliver = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }
                        }
                    }
                }
            }


            $current_status = $ctc->status_id;
            if ($ctc->status_id == 17) {
                $preStatus = \App\SprintTaskHistory
                    ::where('sprint_id', '=', $ctc->id)
                    ->where('status_id', '!=', '17')
                    ->orderBy('id', 'desc')->first();
                if (!empty($preStatus)) {
                    $current_status = $preStatus->status_id;
                }
            }
            echo $ctc->id . "\t";

            if ($ctc->sprintCtcTasks) {
                if ($ctc->sprintCtcTasks->taskRouteLocation) {
                    echo 'R-' . $ctc->sprintCtcTasks->taskRouteLocation->route_id . '-' . $ctc->sprintCtcTasks->taskRouteLocation->ordinal . "\t";
                } else {
                    echo " " . "\t";
                }
            } else {
                echo " " . "\t";
            }

            if ($ctc->sprintCtcTasks) {
                if ($ctc->sprintCtcTasks->taskRouteLocation) {
                    if ($ctc->sprintCtcTasks->taskRouteLocation->joeyRoute) {
                        if ($ctc->sprintCtcTasks->taskRouteLocation->joeyRoute->joey) {
                            echo $ctc->sprintCtcTasks->taskRouteLocation->joeyRoute->joey->first_name . ' ' . $ctc->sprintCtcTasks->taskRouteLocation->joeyRoute->joey->last_name . ' (' . $ctc->sprintCtcTasks->taskRouteLocation->joeyRoute->joey_id . ')' . "\t";
                        } else {
                            echo "" . "\t";
                        }
                    } else {
                        echo "" . "\t";
                    }
                } else {
                    echo "" . "\t";
                }
            } else {
                echo "" . "\t";
            }

            if ($ctc->sprintVendor) {
                echo $ctc->sprintVendor->name . "\t";
            } else {
                echo "" . "\t";
            }

            if ($ctc->sprintCtcTasks) {
                if ($ctc->sprintCtcTasks->taskContact) {
                    echo $ctc->sprintCtcTasks->taskContact->name . "\t";
                } else {
                    echo "" . "\t";
                }
            } else {
                echo "" . "\t";
            }

            if ($ctc->sprintCtcTasks) {
                if ($ctc->sprintCtcTasks->taskMerchants) {
                    echo $ctc->sprintCtcTasks->taskMerchants->address_line2 . "\t";
                } else {
                    echo "" . "\t";
                }
            } else {
                echo "" . "\t";
            }

            if ($ctc->sprintCtcTasks) {
                if ($ctc->sprintCtcTasks->task_Location) {
                    echo $ctc->sprintCtcTasks->task_Location->postal_code . "\t";
                } else {
                    echo "" . "\t";
                }
            } else {
                echo "" . "\t";
            }
            if ($ctc->sprintCtcTasks) {
                if ($ctc->sprintCtcTasks->task_Location) {
                    if ($ctc->sprintCtcTasks->task_Location->city) {
                        echo $ctc->sprintCtcTasks->task_Location->city->name . "\t";
                    } else {
                        echo "" . "\t";
                    }
                } else {
                    echo "" . "\t";
                }
            } else {
                echo "" . "\t";
            }

            if ($ctc->sprintCtcTasks) {
                if ($ctc->sprintCtcTasks->taskMerchants) {
                    echo $ctc->sprintCtcTasks->taskMerchants->weight . $ctc->sprintCtcTasks->taskMerchants->weight_unit . "\t";
                } else {
                    echo "" . "\t";
                }
            } else {
                echo "" . "\t";
            }

            echo $pickup . "\t";
            if (!empty($hubreturned)) {
                echo $hubreturned . "\t";
            } else {
                echo $ctc->atHubProcessing()->athub . "\t";
            }

            if (!empty($hubpickup)) {
                echo $hubpickup . "\t";
            } else {
                echo $ctc->outForDelivery()->outdeliver . "\t";
            }

            if ($ctc->sprintCtcTasks) {
                echo date('Y-m-d H:i:s', strtotime("+1 day", strtotime($ctc->sprintCtcTasks->eta_time))) . "\t";
            } else {
                echo "" . "\t";
            }


            if (!empty($deliver)) {
                echo $deliver . "\t";
            } else {
                echo $ctc->deliveryTime()->delivery_time . "\t";
            }

            if ($ctc->sprintCtcTasks) {
                if ($ctc->sprintCtcTasks->taskMerchants) {
                    echo substr($ctc->sprintCtcTasks->taskMerchants->tracking_id, strrpos($ctc->sprintCtcTasks->taskMerchants->tracking_id, '_') + 0) . "\t";
                } else {
                    echo "" . "\t";
                }
            } else {
                echo "" . "\t";
            }

            if ($ctc->sprintCtcTasks) {
                if ($ctc->sprintCtcTasks->taskMerchants) {
                    echo "https://www.joeyco.com/track-order/" . substr($ctc->sprintCtcTasks->taskMerchants->tracking_id, strrpos($ctc->sprintCtcTasks->taskMerchants->tracking_id, '_') + 0) . "\t";
                } else {
                    echo '' . "\t";
                }
            } else {
                echo '' . "\t";
            }


            echo ($current_status == 13) ? "At hub - processing" : self::$status[$current_status] . "\t";
            echo $notes . "\t";
            echo $hubreturned2 . "\t";
            echo $hubpickup2 . "\t";
            echo $deliver2 . "\t";
            echo $hubreturned3 . "\t";
            echo $hubpickup3 . "\t";
            echo $deliver3 . "\t";

            echo "\n";
        }

        /*            $ctc_array[] = [
                        'Joeyco Order' => strval($ctc->id),
                        'Route' => strval($route_id),
                        'Joey' => $joey,
                        'Store Name' => $store_name,
                        'Customer Name' => $customer_name,
                        'Customer Address' => $address,
                        'Postal Code' => strval($postal_code),
                        'City Name' => $city_name,
                        'Weight' => strval($weight),
                        'Pickup From Store' => $pickup,
                        'At Hub Processing' => $athub,
                        'Out For Delivery' => $outdeliver,
                        'Estimated Customer delivery time' => $eta_time,
                        'Actual Customer delivery time' => $delivery_time,
                        'Shipment tracking #' => strval($tracking_id),
                        'Shipment tracking link' => strval($trackingid_link),
                        'Shipment Delivery Status' => ($current_status == 13) ? "At hub Processing" : self::$status[$current_status],
                        'JoyeCo Notes / Comments' => $notes,
                        'Returned to HUB 2' => $hubreturned2,
                        '2nd Attempt Pick up' => $hubpickup2,
                        '2nd Attempt Delivery' => $deliver2,
                        'Returned to HUB 3' => $hubreturned3,
                        '3rd Attempt Pick up' => $hubpickup3,
                        '3rd Attempt Delivery' => $deliver3
                    ];
                }
                Excel::create("Canadian Tire Data $date", function ($excel) use ($ctc_array) {
                    $excel->setTitle('Canadian Tire Data');
                    $excel->sheet('Canadian Tire Data', function ($sheet) use ($ctc_array) {
                        $sheet->fromArray($ctc_array, null, 'A1', false, false);
                    });
                })->download('csv');*/
    }


    /**
     * Get CTC Dashboard Excel Report
     */
    public function CtcNewDashboardExcelTest($date = null)
    {
        if ($date == null) {
            $date = date('Y-m-d');

        }
        $file_name = new \DateTime($date);
        $file_name = $file_name->format("M d, Y");
        $file_name = "CTC Tracking File " . $file_name . ".csv";

        $ctcVendorIds = CtcVendor::pluck('vendor_id');

        $sprintIds = Sprint::whereIn('creator_id', $ctcVendorIds)->where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $date . "%")
            ->whereNotIn('status_id', [38, 36])->pluck('id');
        $ctc_data = Sprint::whereIn('id', $sprintIds)->where('deleted_at', null)->where('is_reattempt','=', 0)->get();
        // header info for browser


        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="'.$file_name);
        header('Pragma: no-cache');
        header('Expires: 0');

        // echo "JoeyCo Order\tRoute\tJoey\tStore Name\tCustomer Name\tCustomer Address\tPostal Code\tCity Name\tWeight\tPickup From Store\t1st Attempt - At Hub Processing\t1st Attempt - Out For Delivery\t1st Attempt - Estimated Customer Delivery Time\t1st Attempt - Delivery\t1st Attempt - Shipment Delivery Status\t2nd Attempt - At Hub Processing\t2nd Attempt - Out For Delivery\t2nd Attempt - Estimated Customer Delivery Time\t2nd Attempt - Delivery\t2nd Attempt - Shipment Delivery Status\t3rd Attempt - At Hub Processing\t3rd Attempt - Out For Delivery\t3rd Attempt - Estimated Customer Delivery Time\t3rd Attempt - Delivery\t3rd Attempt - Shipment Delivery Status\tShipment Tracking #\tActual Delivery Status\tActual Delivery\tShipment Tracking Link\tJoyeCo Notes / Comments\t\n";
        echo "JoeyCo Order,Route,Joey,Store Name,Customer Name,Customer Address,Postal Code,City Name,Weight,Pickup From Store,1st Attempt - At Hub Processing,1st Attempt - Out For Delivery,1st Attempt - Estimated Customer Delivery Time,1st Attempt - Delivery,1st Attempt - Shipment Delivery Status,2nd Attempt - At Hub Processing,2nd Attempt - Out For Delivery,2nd Attempt - Estimated Customer Delivery Time,2nd Attempt - Delivery,2nd Attempt - Shipment Delivery Status,3rd Attempt - At Hub Processing,3rd Attempt - Out For Delivery,3rd Attempt - Estimated Customer Delivery Time,3rd Attempt - Delivery,3rd Attempt - Shipment Delivery Status,Shipment Tracking #,Actual Delivery Status,Actual Delivery,Shipment Tracking Link,JoyeCo Notes / Comments,\n";

        // $ctc_array[] = ['Joeyco Order', 'Route', 'Joey', 'Store Name', 'Customer Name', 'Customer Address', 'Postal Code', 'City Name', 'Weight', 'Pickup From Store', 'At Hub Processing', 'Out For Delivery', 'Estimated Customer delivery time', 'Actual Customer delivery time', 'Shipment tracking #', 'Shipment tracking link', 'Shipment Delivery Status', 'JoyeCo Notes / Comments', 'Returned to HUB 2', '2nd Attempt Pick up', '2nd Attempt Delivery', 'Returned to HUB 3', '3rd Attempt Pick up', '3rd Attempt Delivery'];

        foreach ($ctc_data as $ctc_rec) {
            $ctc = null;
            if ($ctc_rec->sprintReattempts) {
                if ($ctc_rec->sprintReattempts->reattempts_left == 0) {
                    $ctc =  $firstSprint = Sprint::where('id', '=', $ctc_rec->sprintReattempts->reattempt_of)->first();
                }
                else
                {
                    $ctc = $ctc_rec;
                }
            }
            else
            {
                $ctc = $ctc_rec;
            }
            $pickup3 = "";
            $hubreturned3 = "";
            $hubpickup3 = "";
            $deliver3 = "";
            $eta_time3 = "";
            $status3 = "";
            $pickup2 = "";
            $hubreturned2 = "";
            $hubpickup2 = "";
            $deliver2 = "";
            $eta_time2 = "";
            $status2 = "";
            $notes = '';
            $check_actual = false;
            $pickup = $ctc->pickupFromStore()->pickup;
            $hubreturned = "";//$ctc->atHubProcessing()->athub;
            $hubpickup = "";// $ctc->outForDelivery()->outdeliver;
            $deliver = "";//$ctc->deliveryTime()->delivery_time;
            $actual_delivery = $ctc->actualDeliveryTime()->actual_delivery;
            $actual_delivery_status = '';

            $eta_time = "";
            if ($pickup) {
                $eta_time = date('Y-m-d', strtotime("+1 day", strtotime($pickup))).' 21:00:00';
            }
            $status = $ctc->status_id;
            if ($ctc->status_id == 17) {
                $preStatus = \App\SprintTaskHistory::where('sprint_id', '=', $ctc->id)
                    ->where('status_id', '!=', '17')
                    ->orderBy('id', 'desc')->first();
                if (!empty($preStatus)) {
                    $status = $preStatus->status_id;
                }
            }
            if ($ctc->actualDeliveryTime()->actual_delivery != null) {
                $check_actual = true;
                $actual_delivery_status = $ctc->actualDeliveryTime()->status_id;

            }
            $notes1 = Notes::where('object_id', $ctc->id)->pluck('note');
            $i = 0;
            foreach ($notes1 as $note) {
                if ($i == 0)
                    $notes = $notes . $note;
                else
                    $notes = $notes . ', ' . $note;
            }
            if ($ctc->sprintReattempts) {
                if ($ctc->sprintReattempts->reattempts_left == 0) {

                    $hubreturned3 = $ctc->atHubProcessing()->athub;
                    $hubpickup3 = $ctc->outForDelivery()->outdeliver;
                    $deliver3 = $ctc->deliveryTime()->delivery_time;
                    if ($hubreturned3) {
                        $eta_time3 = date('Y-m-d', strtotime("+1 day", strtotime($hubreturned3))).' 21:00:00';
                    }
                    $status3 = $ctc->status_id;
                    if ($ctc->status_id == 17) {
                        $preStatus = \App\SprintTaskHistory
                            ::where('sprint_id', '=', $ctc->id)
                            ->where('status_id', '!=', '17')
                            ->orderBy('id', 'desc')->first();
                        if (!empty($preStatus)) {
                            $status3 = $preStatus->status_id;
                        }
                    }

                    $secondAttempt = \App\SprintTaskHistory::where('sprint_id', '=', $ctc->sprintReattempts->reattempt_of)->orderBy('created_at', 'ASC')
                        ->get(['status_id', \DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') as created_at")]);
                    if (!empty($secondAttempt)) {

                        foreach ($secondAttempt as $secAttempt) {

                            if (in_array($secAttempt->status_id, [133])) {
                                $hubreturned2 = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }
                            if ($secAttempt->status_id == 121) {
                                $hubpickup2 = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }
                            if (in_array($secAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 131, 101, 102, 103, 104, 105, 106, 107, 108, 109, 111, 112, 131, 135, 136, 143,141])) {
                                $deliver2 = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }

                            $eta = Sprint::where('id', $ctc->sprintReattempts->reattempt_of)->first();
                            if ($hubreturned2) {
                                $eta_time2 = date('Y-m-d', strtotime("+1 day", strtotime($hubreturned2))).' 21:00:00';
                            }
                            $status2 = $eta->status_id;
                            if ($eta->status_id == 17) {
                                $preStatus = \App\SprintTaskHistory
                                    ::where('sprint_id', '=', $eta->id)
                                    ->where('status_id', '!=', '17')
                                    ->orderBy('id', 'desc')->first();
                                if (!empty($preStatus)) {
                                    $status2 = $preStatus->status_id;
                                }
                            }

                            if (in_array($secAttempt->status_id, [17,113, 114, 116, 117, 118, 132, 138, 139, 144])) {
                                if (!$check_actual) {
                                    $actual_delivery = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                                }
                            }
                            if (in_array($secAttempt->status_id, [113, 114, 116, 117, 118, 132, 138, 139, 144])) {
                                if (!$check_actual) {
                                    $actual_delivery_status = $secAttempt->status_id;

                                }
                            }

                        }
                    }

                    $firstSprint = \App\SprintReattempt::where('sprint_id', '=', $ctc->sprintReattempts->reattempt_of)->first();
                    if (!empty($firstSprint)) {
                        $firstAttempt = \App\SprintTaskHistory::where('sprint_id', '=', $firstSprint->reattempt_of)->orderBy('created_at', 'ASC')->
                        get(['status_id', \DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') as created_at")]);
                        if (!empty($firstAttempt)) {

                            foreach ($firstAttempt as $firstAttempt) {
                                if ($firstAttempt->status_id == 125) {
                                    $pickup = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                }
                                if (in_array($firstAttempt->status_id, [124])) {
                                    $hubreturned = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                }
                                if ($firstAttempt->status_id == 121) {
                                    $hubpickup = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                }
                                if (in_array($firstAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 131, 101, 102, 103, 104, 105, 106, 107, 108, 109, 111, 112, 131, 135, 136, 143,141])) {
                                    $deliver = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                }
                                /* if (in_array($firstAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 104, 105, 140, 110])) {
                                     $actual_delivery = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                 }*/
                                $eta = Sprint::where('id', $firstSprint->reattempt_of)->first();
                                if ($pickup) {
                                    $eta_time = date('Y-m-d', strtotime("+1 day", strtotime($pickup))).' 21:00:00';
                                }
                                $status = $eta->status_id;
                                if ($eta->status_id == 17) {
                                    $preStatus = \App\SprintTaskHistory
                                        ::where('sprint_id', '=', $eta->id)
                                        ->where('status_id', '!=', '17')
                                        ->orderBy('id', 'desc')->first();
                                    if (!empty($preStatus)) {
                                        $status = $preStatus->status_id;
                                    }
                                }
                            }

                        }
                    }
                }
                if ($ctc->sprintReattempts->reattempts_left == 1) {

                    $hubreturned3 = $ctc->atHubProcessing()->athub;
                    $hubpickup3 = $ctc->outForDelivery()->outdeliver;
                    $deliver3 = $ctc->deliveryTime()->delivery_time;
                    if ($hubreturned3) {
                        $eta_time3 = date('Y-m-d', strtotime("+1 day", strtotime($hubreturned3))).' 21:00:00';
                    }
                    $status3 = $ctc->status_id;
                    if ($ctc->status_id == 17) {
                        $preStatus = \App\SprintTaskHistory
                            ::where('sprint_id', '=', $ctc->id)
                            ->where('status_id', '!=', '17')
                            ->orderBy('id', 'desc')->first();
                        if (!empty($preStatus)) {
                            $status3 = $preStatus->status_id;
                        }
                    }

                    $secondAttempt = \App\SprintTaskHistory::where('sprint_id', '=', $ctc->sprintReattempts->reattempt_of)->orderBy('created_at', 'ASC')
                        ->get(['status_id', \DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') as created_at")]);
                    if (!empty($secondAttempt)) {

                        foreach ($secondAttempt as $secAttempt) {

                            if (in_array($secAttempt->status_id, [133])) {
                                $hubreturned2 = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }
                            if ($secAttempt->status_id == 121) {
                                $hubpickup2 = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }
                            if (in_array($secAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 131, 101, 102, 103, 104, 105, 106, 107, 108, 109, 111, 112, 131, 135, 136, 143,141])) {
                                $deliver2 = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }

                            $eta = Sprint::where('id', $ctc->sprintReattempts->reattempt_of)->first();
                            if ($hubreturned2) {
                                $eta_time2 = date('Y-m-d', strtotime("+1 day", strtotime($hubreturned2))).' 21:00:00';
                            }
                            $status2 = $eta->status_id;
                            if ($eta->status_id == 17) {
                                $preStatus = \App\SprintTaskHistory
                                    ::where('sprint_id', '=', $eta->id)
                                    ->where('status_id', '!=', '17')
                                    ->orderBy('id', 'desc')->first();
                                if (!empty($preStatus)) {
                                    $status2 = $preStatus->status_id;
                                }
                            }

                            if (in_array($secAttempt->status_id, [17,113, 114, 116, 117, 118, 132, 138, 139, 144])) {
                                if (!$check_actual) {
                                    $actual_delivery = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                                }
                            }
                            if (in_array($secAttempt->status_id, [113, 114, 116, 117, 118, 132, 138, 139, 144])) {
                                if (!$check_actual) {
                                    $actual_delivery_status = $secAttempt->status_id;

                                }
                            }

                        }
                    }

                    $firstSprint = \App\SprintReattempt::where('sprint_id', '=', $ctc->sprintReattempts->reattempt_of)->first();
                    if (!empty($firstSprint)) {
                        $firstAttempt = \App\SprintTaskHistory::where('sprint_id', '=', $firstSprint->reattempt_of)->orderBy('created_at', 'ASC')->
                        get(['status_id', \DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') as created_at")]);
                        if (!empty($firstAttempt)) {

                            foreach ($firstAttempt as $firstAttempt) {
                                if ($firstAttempt->status_id == 125) {
                                    $pickup = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                }
                                if (in_array($firstAttempt->status_id, [124])) {
                                    $hubreturned = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                }
                                if ($firstAttempt->status_id == 121) {
                                    $hubpickup = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                }
                                if (in_array($firstAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 131, 101, 102, 103, 104, 105, 106, 107, 108, 109, 111, 112, 131, 135, 136, 143,141])) {
                                    $deliver = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                }
                                /* if (in_array($firstAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 104, 105, 140, 110])) {
                                     $actual_delivery = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                 }*/
                                $eta = Sprint::where('id', $firstSprint->reattempt_of)->first();
                                if ($pickup) {
                                    $eta_time = date('Y-m-d', strtotime("+1 day", strtotime($pickup))).' 21:00:00';
                                }
                                $status = $eta->status_id;
                                if ($eta->status_id == 17) {
                                    $preStatus = \App\SprintTaskHistory
                                        ::where('sprint_id', '=', $eta->id)
                                        ->where('status_id', '!=', '17')
                                        ->orderBy('id', 'desc')->first();
                                    if (!empty($preStatus)) {
                                        $status = $preStatus->status_id;
                                    }
                                }
                            }

                        }
                    }
                }
                if ($ctc->sprintReattempts->reattempts_left == 2) {

                    $hubreturned2 = $ctc->atHubProcessing()->athub;
                    $hubpickup2 = $ctc->outForDelivery()->outdeliver;
                    $deliver2 = $ctc->deliveryTime()->delivery_time;

                    if ($hubreturned2) {
                        $eta_time2 = date('Y-m-d', strtotime("+1 day", strtotime($hubreturned2))).' 21:00:00';
                    }
                    $status2 = $ctc->status_id;
                    if ($ctc->status_id == 17) {
                        $preStatus = \App\SprintTaskHistory
                            ::where('sprint_id', '=', $ctc->id)
                            ->where('status_id', '!=', '17')
                            ->orderBy('id', 'desc')->first();
                        if (!empty($preStatus)) {
                            $status2 = $preStatus->status_id;
                        }
                    }

                    $secondAttempt = \App\SprintTaskHistory::where('sprint_id', '=', $ctc->sprintReattempts->reattempt_of)->orderBy('created_at', 'ASC')->
                    get(['status_id', \DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') as created_at")]);
                    if (!empty($secondAttempt)) {
                        date_default_timezone_set('America/Toronto');
                        foreach ($secondAttempt as $secAttempt) {
                            if ($secAttempt->status_id == 125) {
                                $pickup = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }
                            if (in_array($secAttempt->status_id, [124])) {
                                $hubreturned = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }
                            if ($secAttempt->status_id == 121) {
                                $hubpickup = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }
                            if (in_array($secAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 131, 101, 102, 103, 104, 105, 106, 107, 108, 109, 111, 112, 131, 135, 136, 143,141])) {
                                $deliver = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }

                            $eta = Sprint::where('id', $ctc->sprintReattempts->reattempt_of)->first();
                            if ($pickup) {
                                $eta_time = date('Y-m-d', strtotime("+1 day", strtotime($pickup))).' 21:00:00';
                            }
                            $status = $eta->status_id;
                            if ($eta->status_id == 17) {
                                $preStatus = \App\SprintTaskHistory
                                    ::where('sprint_id', '=', $eta->id)
                                    ->where('status_id', '!=', '17')
                                    ->orderBy('id', 'desc')->first();
                                if (!empty($preStatus)) {
                                    $status = $preStatus->status_id;
                                }
                            }
                            if (in_array($secAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144])) {
                                if (!$check_actual) {
                                    $actual_delivery = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                                }
                            }
                            if (in_array($secAttempt->status_id, [113, 114, 116, 117, 118, 132, 138, 139, 144])) {
                                if (!$check_actual) {
                                    $actual_delivery_status = $secAttempt->status_id;
                                }
                            }
                        }
                    }
                }
            } else {
                $hubreturned = $ctc->atHubProcessingFirst()->athub;
                $hubpickup = $ctc->outForDelivery()->outdeliver;
                $deliver = $ctc->deliveryTime()->delivery_time;
            }

            echo $ctc->id . ",";

            if ($ctc->sprintCtcTasks) {
                if ($ctc->sprintCtcTasks->taskRouteLocation) {
                    echo 'R-' . $ctc->sprintCtcTasks->taskRouteLocation->route_id . '-' . $ctc->sprintCtcTasks->taskRouteLocation->ordinal . ",";
                } else {
                    echo " " . ",";
                }
            } else {
                echo " " . ",";
            }

            if ($ctc->sprintCtcTasks) {
                if ($ctc->sprintCtcTasks->taskRouteLocation) {
                    if ($ctc->sprintCtcTasks->taskRouteLocation->joeyRoute) {
                        if ($ctc->sprintCtcTasks->taskRouteLocation->joeyRoute->joey) {
                            echo str_replace(",","-", $ctc->sprintCtcTasks->taskRouteLocation->joeyRoute->joey->first_name . ' ' . $ctc->sprintCtcTasks->taskRouteLocation->joeyRoute->joey->last_name . ' (' . $ctc->sprintCtcTasks->taskRouteLocation->joeyRoute->joey_id . ')' ) . ",";
                        } else {
                            echo "" . ",";
                        }
                    } else {
                        echo "" . ",";
                    }
                } else {
                    echo "" . ",";
                }
            } else {
                echo "" . ",";
            }

            if ($ctc->sprintVendor) {
                echo str_replace(",","-",$ctc->sprintVendor->name ) . ",";
            } else {
                echo "" . ",";
            }

            if ($ctc->sprintCtcTasks) {
                if ($ctc->sprintCtcTasks->taskContact) {
                    echo str_replace(",","-",$ctc->sprintCtcTasks->taskContact->name ) . ",";
                } else {
                    echo "" . ",";
                }
            } else {
                echo "" . ",";
            }

            if ($ctc->sprintCtcTasks) {
                if ($ctc->sprintCtcTasks->taskMerchants) {
                    echo str_replace(",","-",$ctc->sprintCtcTasks->taskMerchants->address_line2 ) . ",";
                } else {
                    echo "" . ",";
                }
            } else {
                echo "" . ",";
            }

            if ($ctc->sprintCtcTasks) {
                if ($ctc->sprintCtcTasks->task_Location) {
                    echo str_replace(",","-",$ctc->sprintCtcTasks->task_Location->postal_code )  . ",";
                } else {
                    echo "" . ",";
                }
            } else {
                echo "" . ",";
            }
            if ($ctc->sprintCtcTasks) {
                if ($ctc->sprintCtcTasks->task_Location) {
                    if ($ctc->sprintCtcTasks->task_Location->city) {
                        echo str_replace(",","-",$ctc->sprintCtcTasks->task_Location->city->name )  . ",";
                    } else {
                        echo "" . ",";
                    }
                } else {
                    echo "" . ",";
                }
            } else {
                echo "" . ",";
            }

            if ($ctc->sprintCtcTasks) {
                if ($ctc->sprintCtcTasks->taskMerchants) {
                    echo $ctc->sprintCtcTasks->taskMerchants->weight . $ctc->sprintCtcTasks->taskMerchants->weight_unit . ",";
                } else {
                    echo "" . ",";
                }
            } else {
                echo "" . ",";
            }

            echo $pickup . ",";

            echo $hubreturned . ",";


            echo $hubpickup . ",";


            echo $eta_time . ",";


            echo $deliver . ",";

            if (!empty($status)) {
                echo ($status == 13) ? "At hub - processing" . "," : str_replace(",","-",self::$status[$status])  . ",";
            } else {
                echo "" . ",";
            }
            echo $hubreturned2 . ",";
            echo $hubpickup2 . ",";
            echo $eta_time2 . ",";
            echo $deliver2 . ",";
            if (!empty($status2)) {
                echo ($status2 == 13) ? "At hub - processing" . "," : str_replace(",","-",self::$status[$status2] ) . ",";
            } else {
                echo "" . ",";
            }
            echo $hubreturned3 . ",";
            echo $hubpickup3 . ",";
            echo $eta_time3 . ",";
            echo $deliver3 . ",";
            if (!empty($status3)) {
                echo ($status3 == 13) ? "At hub - processing" . "," : str_replace(",","-",self::$status[$status3] ) . ",";
            } else {
                echo "" . ",";
            }


            if ($ctc->sprintCtcTasks) {
                if ($ctc->sprintCtcTasks->taskMerchants) {
                    if (str_contains($ctc->sprintCtcTasks->taskMerchants->tracking_id, 'old_')) {
                        echo substr($ctc->sprintCtcTasks->taskMerchants->tracking_id, strrpos($ctc->sprintCtcTasks->taskMerchants->tracking_id, '_') + 1) . ",";
                    }
                    else
                    {
                        echo $ctc->sprintCtcTasks->taskMerchants->tracking_id . ",";
                    }
                } else {
                    echo "" . ",";
                }
            } else {
                echo "" . ",";
            }
//            echo ($actual_delivery_status == 13) ? "At hub - processing"."\t" : self::$status[$actual_delivery_status] . "\t";
            if (!empty($actual_delivery_status)) {
                echo ($actual_delivery_status == 13) ? "At hub - processing" . "," : str_replace(",","-",self::$status[$actual_delivery_status])  . ",";
            } else {
                echo "" . ",";
            }
            echo $actual_delivery . ",";
            if ($ctc->sprintCtcTasks) {
                if ($ctc->sprintCtcTasks->taskMerchants) {
                    if (str_contains($ctc->sprintCtcTasks->taskMerchants->tracking_id, 'old_')) {
                        echo "https://www.joeyco.com/track-order/" . substr($ctc->sprintCtcTasks->taskMerchants->tracking_id, strrpos($ctc->sprintCtcTasks->taskMerchants->tracking_id, '_') + 1) . ",";
                    }
                    else{
                        echo "https://www.joeyco.com/track-order/" .$ctc->sprintCtcTasks->taskMerchants->tracking_id. ",";
                    }
                } else {
                    echo '' . ",";
                }
            } else {
                echo '' . ",";
            }


            echo $notes . ",";
            echo "\n";

        }

    }

    /**
     * Get CTC Dashboard Excel OTD Report
     */
    public function CtcNewDashboardExcelOtdReport($date = null)
    {
        if ($date == null) {
            $date = date('Y-m-d');
            $otd_date = date('Y-m-d');
            $otd_date = date('Y-m-d', strtotime($otd_date . ' -1 days'));
        } else {
            $otd_date = $date;
            $otd_date = date('Y-m-d', strtotime($otd_date . ' -1 days'));
        }
        $file_name = new \DateTime($date);
        $file_name = $file_name->format("M d, Y");
        $file_name = "CTC OTD Report " . $file_name . ".csv";
        $sprint_id = SprintTaskHistory::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $otd_date . "%")->where('status_id', 125)->pluck('sprint_id');

        $ctcVendorIds = CtcVendor::pluck('vendor_id');
        $ctc_data = Sprint::whereIn('creator_id', $ctcVendorIds)->whereIn('id', $sprint_id)->whereNotIn('status_id', [38, 36])->get();
        //$ctc_data = Sprint::whereIn('id',$sprintIds)->where('deleted_at', null)->where('is_reattempt','=',0)->get();
        // header info for browser


        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="'.$file_name);
        header('Pragma: no-cache');
        header('Expires: 0');

        // echo "Shipment Tracking #\tPickup From Store\t1st Attempt - At Hub Processing\t1st Attempt - Out For Delivery\t1st Attempt - Estimated Customer Delivery Time\t1st Attempt - Delivery\t1st Attempt - Shipment Delivery Status\t2nd Attempt - At Hub Processing\t2nd Attempt - Out For Delivery\t2nd Attempt - Estimated Customer Delivery Time\t2nd Attempt - Delivery\t2nd Attempt - Shipment Delivery Status\t3rd Attempt - At Hub Processing\t3rd Attempt - Out For Delivery\t3rd Attempt - Estimated Customer Delivery Time\t3rd Attempt - Delivery\t3rd Attempt - Shipment Delivery Status\tActual Delivery Status\tActual Delivery\tShipment Tracking Link\tJoyeCo Notes / Comments\t\n";

        echo "Shipment Tracking #,Pickup From Store,1st Attempt - At Hub Processing,1st Attempt - Out For Delivery,1st Attempt - Estimated Customer Delivery Time,1st Attempt - Delivery,1st Attempt - Shipment Delivery Status,2nd Attempt - At Hub Processing,2nd Attempt - Out For Delivery,2nd Attempt - Estimated Customer Delivery Time,2nd Attempt - Delivery,2nd Attempt - Shipment Delivery Status,3rd Attempt - At Hub Processing,3rd Attempt - Out For Delivery,3rd Attempt - Estimated Customer Delivery Time,3rd Attempt - Delivery,3rd Attempt - Shipment Delivery Status,Actual Delivery Status,Actual Delivery,Shipment Tracking Link,JoyeCo Notes / Comments,\n";

        // $ctc_array[] = ['Joeyco Order', 'Route', 'Joey', 'Store Name', 'Customer Name', 'Customer Address', 'Postal Code', 'City Name', 'Weight', 'Pickup From Store', 'At Hub Processing', 'Out For Delivery', 'Estimated Customer delivery time', 'Actual Customer delivery time', 'Shipment tracking #', 'Shipment tracking link', 'Shipment Delivery Status', 'JoyeCo Notes / Comments', 'Returned to HUB 2', '2nd Attempt Pick up', '2nd Attempt Delivery', 'Returned to HUB 3', '3rd Attempt Pick up', '3rd Attempt Delivery'];

        foreach ($ctc_data as $ctc) {
            $trackingid = '';
            if ($ctc->sprintCtcTasks) {
                if ($ctc->sprintCtcTasks->taskMerchants) {
                    if (strpos($ctc->sprintCtcTasks->taskMerchants, 'old') !== false) {
                        $trackingid = substr($ctc->sprintCtcTasks->taskMerchants->tracking_id, strrpos($ctc->sprintCtcTasks->taskMerchants->tracking_id, '_') + 1);
                    } else {
                        $trackingid = $ctc->sprintCtcTasks->taskMerchants->tracking_id;
                    }
                }
            }
            if(!$ctc->sprintReattempts) {
                //$customer_route = CustomerRoutingTrackingId::where('tracking_id', $trackingid)->first();
                //if (!$customer_route) {
                    if (date("Y-m-d", strtotime($ctc->pickupFromStoreOtd($otd_date)->pickup)) == $otd_date) {
                        $pickup3 = "";
                        $hubreturned3 = "";
                        $hubpickup3 = "";
                        $deliver3 = "";
                        $eta_time3 = "";
                        $status3 = "";
                        $pickup2 = "";
                        $hubreturned2 = "";
                        $hubpickup2 = "";
                        $deliver2 = "";
                        $eta_time2 = "";
                        $status2 = "";
                        $notes = '';
                        $pickup = $ctc->pickupFromStoreOtd($otd_date)->pickup;
                        $hubreturned = $ctc->atHubProcessingOtd()->athub;
                        $hubpickup = $ctc->outForDelivery()->outdeliver;
                        $deliver = $ctc->deliveryTimeOTD()->delivery_time;
                        $actual_delivery = $ctc->actualDeliveryTime()->actual_delivery;
                        $actual_delivery_status = '';

                        $eta_time = "";
                        if ($pickup) {
                            $eta_time = date('Y-m-d', strtotime("+1 day", strtotime($pickup))).' 21:00:00';
                        }
                        $status = $ctc->status_id;
                        if ($ctc->status_id == 17) {
                            $preStatus = \App\SprintTaskHistory
                                ::where('sprint_id', '=', $ctc->id)
                                ->where('status_id', '!=', '17')
                                ->orderBy('id', 'desc')->first();
                            if (!empty($preStatus)) {
                                $status = $preStatus->status_id;
                            }
                        }
                        if ($ctc->actualDeliveryTime()->actual_delivery != null) {
                            $actual_delivery_status = $ctc->actualDeliveryTime()->status_id;
                        }
                        $notes1 = Notes::where('object_id', $ctc->id)->pluck('note');
                        $i = 0;
                        foreach ($notes1 as $note) {
                            if ($i == 0)
                                $notes = $notes . $note;
                            else
                                $notes = $notes . ', ' . $note;
                        }
                        if ($ctc->sprintReattemptsOTD) {


                            $secondAttempt = \App\SprintTaskHistory::where('sprint_id', '=', $ctc->sprintReattemptsOTD->sprint_id)->orderBy('created_at', 'ASC')
                                ->get(['status_id', \DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') as created_at")]);
                            if (!empty($secondAttempt)) {

                                foreach ($secondAttempt as $secAttempt) {

                                    /* if ($secAttempt->status_id == 125) {
                                         $pickup = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                                     }*/
                                    if (in_array($secAttempt->status_id, [133])) {
                                        $hubreturned2 = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                                    }
                                    if ($secAttempt->status_id == 121) {
                                        $hubpickup2 = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                                    }
                                    if (in_array($secAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 131, 101, 102, 103, 104, 105, 106, 107, 108, 109, 111, 112, 131, 135, 136, 143,141])) {
                                        $deliver2 = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                                    }
                                    if (in_array($secAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144])) {
                                        $actual_delivery = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                                    }
                                    $eta = Sprint::where('id', $ctc->sprintReattemptsOTD->sprint_id)->first();
                                    if ($hubreturned2) {
                                        $eta_time2 = date('Y-m-d', strtotime("+1 day", strtotime($hubreturned2))).' 21:00:00';
                                    }
                                    $status2 = $eta->status_id;
                                    if ($eta->status_id == 17) {
                                        $preStatus = \App\SprintTaskHistory
                                            ::where('sprint_id', '=', $eta->id)
                                            ->where('status_id', '!=', '17')
                                            ->orderBy('id', 'desc')->first();
                                        if (!empty($preStatus)) {
                                            $status2 = $preStatus->status_id;
                                        }
                                    }
                                    if (in_array($secAttempt->status_id, [113, 114, 116, 117, 118, 132, 138, 139, 144])) {
                                        $actual_delivery_status = $secAttempt->status_id;
                                    }

                                }
                            }

                            $firstSprint = \App\SprintReattempt::where('reattempt_of', '=', $ctc->sprintReattemptsOTD->sprint_id)->first();
                            if (!empty($firstSprint)) {
                                $firstAttempt = \App\SprintTaskHistory::where('sprint_id', '=', $firstSprint->sprint_id)->orderBy('created_at', 'ASC')->
                                get(['status_id', \DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') as created_at")]);
                                if (!empty($firstAttempt)) {

                                    foreach ($firstAttempt as $firstAttempt) {
                                        /* if ($firstAttempt->status_id == 125) {
                                             $pickup3 = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                         }*/
                                        if (in_array($firstAttempt->status_id, [133])) {
                                            $hubreturned3 = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                        }
                                        if ($firstAttempt->status_id == 121) {
                                            $hubpickup3 = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                        }
                                        if (in_array($firstAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 131, 101, 102, 103, 104, 105, 106, 107, 108, 109, 111, 112, 131, 135, 136, 143,141])) {
                                            $deliver3 = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                        }
                                        if (in_array($firstAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144])) {
                                            $actual_delivery = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                        }
                                        $eta = Sprint::where('id', $firstSprint->sprint_id)->first();
                                        if ($hubreturned3) {
                                            $eta_time3 = date('Y-m-d', strtotime("+1 day", strtotime($hubreturned3))).' 21:00:00';
                                        }
                                        $status3 = $eta->status_id;
                                        if ($eta->status_id == 17) {
                                            $preStatus = \App\SprintTaskHistory
                                                ::where('sprint_id', '=', $eta->id)
                                                ->where('status_id', '!=', '17')
                                                ->orderBy('id', 'desc')->first();
                                            if (!empty($preStatus)) {
                                                $status3 = $preStatus->status_id;
                                            }
                                        }
                                        if (in_array($firstAttempt->status_id, [ 113, 114, 116, 117, 118, 132, 138, 139, 144])) {
                                            $actual_delivery_status = $firstAttempt->status_id;
                                        }
                                    }

                                }
                            }


                        } else {
                            $hubreturned = $ctc->atHubProcessingFirst()->athub;
                        }
                        if ($ctc->sprintCtcTasks) {
                            if ($ctc->sprintCtcTasks->taskMerchants) {
                                if (strpos($ctc->sprintCtcTasks->taskMerchants, 'old') !== false) {
                                    echo substr($ctc->sprintCtcTasks->taskMerchants->tracking_id, strrpos($ctc->sprintCtcTasks->taskMerchants->tracking_id, '_') + 1) . ",";
                                } else {
                                    echo $ctc->sprintCtcTasks->taskMerchants->tracking_id . ",";
                                }
                            } else {
                                echo "" . ",";
                            }
                        } else {
                            echo "" . ",";
                        }

                        echo $pickup . ",";
                        if (!empty($hubreturned)) {
                            echo $hubreturned . ",";
                        } else {
                            echo $ctc->atHubProcessingOtd()->athub . ",";
                        }

                        if (!empty($hubpickup)) {
                            echo $hubpickup . ",";
                        } else {
                            echo $ctc->outForDelivery()->outdeliver . ",";
                        }

                        echo $eta_time . ",";


                        if (!empty($deliver)) {
                            echo $deliver . ",";
                        } else {
                            echo $ctc->deliveryTimeOTD()->delivery_time . ",";
                        }
                        if (!empty($status)) {
                            echo ($status == 13) ? "At hub - processing" . "," : str_replace(",", "-", self::$status[$status]) . ",";
                        } else {
                            echo "" . ",";
                        }
                        echo $hubreturned2 . ",";
                        echo $hubpickup2 . ",";
                        echo $eta_time2 . ",";
                        echo $deliver2 . ",";
                        if (!empty($status2)) {
                            echo ($status2 == 13) ? "At hub - processing" . "," : str_replace(",", "-", self::$status[$status2]) . ",";
                        } else {
                            echo "" . ",";
                        }
                        echo $hubreturned3 . ",";
                        echo $hubpickup3 . ",";
                        echo $eta_time3 . ",";
                        echo $deliver3 . ",";
                        if (!empty($status3)) {
                            echo ($status3 == 13) ? "At hub - processing" . "," : str_replace(",", "-", self::$status[$status3]) . ",";
                        } else {
                            echo "" . ",";
                        }
                        if (!empty($actual_delivery_status)) {
                            echo ($actual_delivery_status == 13) ? "At hub - processing" . "," : str_replace(",", "-", self::$status[$actual_delivery_status]) . ",";
                        } else {
                            echo "" . ",";
                        }
                        echo $actual_delivery . ",";
                        if ($ctc->sprintCtcTasks) {
                            if ($ctc->sprintCtcTasks->taskMerchants) {
                                if (strpos($ctc->sprintCtcTasks->taskMerchants, 'old') !== false) {
                                    echo "https://www.joeyco.com/track-order/" . substr($ctc->sprintCtcTasks->taskMerchants->tracking_id, strrpos($ctc->sprintCtcTasks->taskMerchants->tracking_id, '_') + 1) . ",";
                                } else {
                                    echo "https://www.joeyco.com/track-order/" . $ctc->sprintCtcTasks->taskMerchants->tracking_id . ",";
                                }
                            } else {
                                echo '' . ",";
                            }
                        } else {
                            echo '' . ",";
                        }


                        echo $notes . ",";
                        echo "\n";

                    }
                }
            }
      //  }
    }


    /**
     * Get CTC Dashboard
     */
    public function getCtc(Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        /*$ctc_count = Ctc_count::Where('created_at','like',$today_date."%")
            ->first();*/
        $ctc_count = Ctc_count::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $today_date . "%")
            ->orderBy('id', 'DESC')
            ->first();
        return backend_view('ctc.ctc_dashboard', compact('ctc_count'));
    }

    /**
     * Yajra call after  CTC Dashboard
     */
    public function ctcData(Datatables $datatables, Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $query = Ctc::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $today_date . "%");
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
            ->addColumn('route_id', static function ($record) {
                return $record->route_id;
            })
            ->addColumn('action', static function ($record) {
                return backend_view('ctc.action', compact('record'));
            })
            ->addColumn('tracking_id_link', static function ($record) {
                return backend_view('ctc.action_tracking_link', compact('record'));
            })
            ->make(true);
    }

    /**
     * Get CTC Order detail
     */
    public function ctcProfile(Request $request, $id)
    {
        $ctc_id = base64_decode($id);
        $ctc_dash = Ctc::where(['id' => $ctc_id])->get();
        $ctc_dash = $ctc_dash[0];

        return backend_view('ctc.ctc_profile', compact('ctc_dash'));
    }

    /**
     * Get CTC Dashboard Excel Report
     */
    public function CtcExcel($date = null)
    {
        if ($date == null) {
            $date = date('Y-m-d');
        }
        $ctc_data = Ctc::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $date . "%")->get();
        $ctc_array[] = array('Joeyco Order', 'Route', 'Joey', 'Store Name', 'Customer Name', 'Customer Address', 'Postal Code', 'City Name', 'Weight', 'Pickup From Store', 'At Hub Processing', 'Out For Delivery', 'Estimated Customer delivery time', 'Actual Customer delivery time', 'Shipment tracking #', 'Shipment tracking link', 'Shipment Delivery Status', 'JoyeCo Notes / Comments', 'Returned to HUB 2', '2nd Attempt Pick up', '2nd Attempt Delivery', 'Returned to HUB 3', '3rd Attempt Pick up', '3rd Attempt Delivery');
        foreach ($ctc_data as $ctc) {
            if ($ctc->tracking_id != NULL) {
                $trackingid_link = "https://www.joeyco.com/track-order/" . $ctc->tracking_id;
            } else {
                $trackingid_link = '';
            }
            $ctc_array[] = array(
                'Joeyco Order' => strval($ctc->sprint_id),
                'Route' => strval($ctc->route_id),
                'Joey' => $ctc->joey,
                'Store Name' => $ctc->store_name,
                'Customer Name' => $ctc->customers_name,
                'Customer Address' => $ctc->address,
                'Postal Code' => $ctc->postal_code,
                'City Name' => $ctc->city_name,
                'Weight' => strval($ctc->weight),
                'Pickup From Store' => $ctc->pickup_from_store,
                'At Hub Processing' => $ctc->at_hub_processing,
                'Out For Delivery' => $ctc->out_for_delivery,
                'Estimated Customer delivery time' => $ctc->dropoff_eta,
                'Actual Customer delivery time' => $ctc->delivery_time,
                'Shipment tracking #' => $ctc->tracking_id,
                'Shipment tracking link' => strval($trackingid_link),
                'Shipment Delivery Status' => self::$status[$ctc->sprint_status],
                'JoyeCo Notes / Comments' => $ctc->notes,
                'Returned to HUB 2' => $ctc->hubreturned2,
                '2nd Attempt Pick up' => $ctc->hubpickup2,
                '2nd Attempt Delivery' => $ctc->deliver2,
                'Returned to HUB 3' => $ctc->hubreturned3,
                '3rd Attempt Pick up' => $ctc->hubpickup3,
                '3rd Attempt Delivery' => $ctc->deliver3
            );
        }
        Excel::create("Canadian Tire Data $date", function ($excel) use ($ctc_array) {
            $excel->setTitle('Canadian Tire Data');
            $excel->sheet('Canadian Tire Data', function ($sheet) use ($ctc_array) {
                $sheet->fromArray($ctc_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    /**
     * Get CTC Sorted
     */
    public function getCtcSort(Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $ctc_count = Ctc_count::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $today_date . "%")
            ->orderBy('id', 'DESC')
            ->first();

        $title_name = 'Canadian Tire';
        return backend_view('ctc.sorted_order', compact('ctc_count', 'title_name'));
    }

    /**
     * Yajra call after CTC Dashboard
     */
    public function ctcSortedData(Datatables $datatables, Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $query = Ctc::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $today_date . "%")->where(['sprint_status' => 133]);

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
            ->addColumn('route_id', static function ($record) {
                return $record->route_id;
            })
            ->addColumn('action', static function ($record) {
                return backend_view('ctc.action_sorted', compact('record'));
            })
            ->addColumn('tracking_id_link', static function ($record) {
                return backend_view('ctc.action_tracking_link', compact('record'));
            })
            ->make(true);
    }

    /**
     * Get CTC sorted order detail
     */
    public function ctcsortedDetail(Request $request, $id)
    {
        $ctc_id = base64_decode($id);
        $ctc_dash = Ctc::where(['id' => $ctc_id])->get();
        $ctc_dash = $ctc_dash[0];

        return backend_view('ctc.ctc_sorted_detail', compact('ctc_dash'));
    }

    /**
     * Get CTC sorted order excel report
     */
    public function otcSortedExcel($date = null)
    {
        if ($date == null) {
            $date = date('Y-m-d');
        }
        $ctc_data = Ctc::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $date . "%")->where(['sprint_status' => 133])->get();
        $ctc_array[] = array('Joeyco Order', 'Route', 'Joey', 'Store Name', 'Customer Name', 'Customer Address', 'Postal Code', 'City Name', 'Weight', 'Pickup From Store', 'At Hub Processing', 'Out For Delivery', 'Estimated Customer delivery time', 'Actual Customer delivery time', 'Shipment tracking #', 'Shipment tracking link', 'Shipment Delivery Status', 'JoyeCo Notes / Comments', 'Returned to HUB 2', '2nd Attempt Pick up', '2nd Attempt Delivery', 'Returned to HUB 3', '3rd Attempt Pick up', '3rd Attempt Delivery');
        foreach ($ctc_data as $ctc) {
            if ($ctc->tracking_id != NULL) {
                $trackingid_link = "https://www.joeyco.com/track-order/" . $ctc->tracking_id;
            } else {
                $trackingid_link = '';
            }
            $ctc_array[] = array(
                'Joeyco Order' => strval($ctc->sprint_id),
                'Route' => strval($ctc->route_id),
                'Joey' => $ctc->joey,
                'Store Name' => $ctc->store_name,
                'Customer Name' => $ctc->customers_name,
                'Customer Address' => $ctc->address,
                'Postal Code' => $ctc->postal_code,
                'City Name' => $ctc->city_name,
                'Weight' => strval($ctc->weight),
                'Pickup From Store' => $ctc->pickup_from_store,
                'At Hub Processing' => $ctc->at_hub_processing,
                'Out For Delivery' => $ctc->out_for_delivery,
                'Estimated Customer delivery time' => $ctc->dropoff_eta,
                'Actual Customer delivery time' => $ctc->delivery_time,
                'Shipment tracking #' => $ctc->tracking_id,
                'Shipment tracking link' => strval($trackingid_link),
                'Shipment Delivery Status' => self::$status[$ctc->sprint_status],
                'JoyeCo Notes / Comments' => $ctc->notes,
                'Returned to HUB 2' => $ctc->hubreturned2,
                '2nd Attempt Pick up' => $ctc->hubpickup2,
                '2nd Attempt Delivery' => $ctc->deliver2,
                'Returned to HUB 3' => $ctc->hubreturned3,
                '3rd Attempt Pick up' => $ctc->hubpickup3,
                '3rd Attempt Delivery' => $ctc->deliver3
            );
        }
        Excel::create('Ctc Sorted Data ' . $date . '', function ($excel) use ($ctc_array) {
            $excel->setTitle('Ctc Sorted Data');
            $excel->sheet('Ctc Sorted Data', function ($sheet) use ($ctc_array) {
                $sheet->fromArray($ctc_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    /**
     * Get CTC Picked up
     */
    public function getCtcthub(Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $ctc_count = Ctc_count::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $today_date . "%")
            ->orderBy('id', 'DESC')
            ->first();
        $title_name = 'Canadian Tire';
        return backend_view('ctc.pickup_hub', compact('ctc_count', 'title_name'));
    }

    /**
     * Yajra call after CTC Picked up
     */
    public function ctcPickedUpData(Datatables $datatables, Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $query = Ctc::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $today_date . "%")->where(['sprint_status' => 121]);
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
            ->addColumn('route_id', static function ($record) {
                return $record->route_id;
            })
            ->addColumn('action', static function ($record) {
                return backend_view('ctc.action_pickup', compact('record'));
            })
            ->addColumn('tracking_id_link', static function ($record) {
                return backend_view('ctc.action_tracking_link', compact('record'));
            })
            ->make(true);
    }

    /**
     * Get CTC Picked up order detail
     */
    public function ctcpickupDetail(Request $request, $id)
    {
        $ctc_id = base64_decode($id);
        $ctc_dash = Ctc::where(['id' => $ctc_id])->get();
        $ctc_dash = $ctc_dash[0];

        return backend_view('ctc.ctc_pickup_detail', compact('ctc_dash'));
    }

    /**
     * Get CTC Picked up orders excel report
     */
    public function ctcPickedUpExcel($date = null)
    {
        if ($date == null) {
            $date = date('Y-m-d');
        }
        $ctc_data = Ctc::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $date . "%")->where(['sprint_status' => 121])->get();
        $ctc_array[] = array('Joeyco Order', 'Route', 'Joey', 'Store Name', 'Customer Name', 'Customer Address', 'Postal Code', 'City Name', 'Weight', 'Pickup From Store', 'At Hub Processing', 'Out For Delivery', 'Estimated Customer delivery time', 'Actual Customer delivery time', 'Shipment tracking #', 'Shipment tracking link', 'Shipment Delivery Status', 'JoyeCo Notes / Comments', 'Returned to HUB 2', '2nd Attempt Pick up', '2nd Attempt Delivery', 'Returned to HUB 3', '3rd Attempt Pick up', '3rd Attempt Delivery');
        foreach ($ctc_data as $ctc) {
            if ($ctc->tracking_id != NULL) {
                $trackingid_link = "https://www.joeyco.com/track-order/" . $ctc->tracking_id;
            } else {
                $trackingid_link = '';
            }
            $ctc_array[] = array(
                'Joeyco Order' => strval($ctc->sprint_id),
                'Route' => strval($ctc->route_id),
                'Joey' => $ctc->joey,
                'Store Name' => $ctc->store_name,
                'Customer Name' => $ctc->customers_name,
                'Customer Address' => $ctc->address,
                'Postal Code' => $ctc->postal_code,
                'City Name' => $ctc->city_name,
                'Weight' => strval($ctc->weight),
                'Pickup From Store' => $ctc->pickup_from_store,
                'At Hub Processing' => $ctc->at_hub_processing,
                'Out For Delivery' => $ctc->out_for_delivery,
                'Estimated Customer delivery time' => $ctc->dropoff_eta,
                'Actual Customer delivery time' => $ctc->delivery_time,
                'Shipment tracking #' => $ctc->tracking_id,
                'Shipment tracking link' => strval($trackingid_link),
                'Shipment Delivery Status' => self::$status[$ctc->sprint_status],
                'JoyeCo Notes / Comments' => $ctc->notes,
                'Returned to HUB 2' => $ctc->hubreturned2,
                '2nd Attempt Pick up' => $ctc->hubpickup2,
                '2nd Attempt Delivery' => $ctc->deliver2,
                'Returned to HUB 3' => $ctc->hubreturned3,
                '3rd Attempt Pick up' => $ctc->hubpickup3,
                '3rd Attempt Delivery' => $ctc->deliver3
            );
        }
        Excel::create('CTC Picked Up Data ' . $date . '', function ($excel) use ($ctc_array) {
            $excel->setTitle('CTC Picked Up Data');
            $excel->sheet('CTC Picked Up Data', function ($sheet) use ($ctc_array) {
                $sheet->fromArray($ctc_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    /**
     * Get CTC Not Scan
     */
    public function getCtcnotscan(Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $ctc_count = Ctc_count::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $today_date . "%")
            ->orderBy('id', 'DESC')
            ->first();
        $title_name = 'Canadian Tire';
        return backend_view('ctc.not_scanned_orders', compact('ctc_count', 'title_name'));
    }

    /**
     * Yajra call after CTC Not Scan
     */
    public function ctcNotScanData(Datatables $datatables, Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $query = Ctc::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $today_date . "%")->where(['sprint_status' => 61]);
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
            ->addColumn('route_id', static function ($record) {
                return $record->route_id;
            })
            ->addColumn('action', static function ($record) {
                return backend_view('ctc.action_notscan', compact('record'));
            })
            ->addColumn('tracking_id_link', static function ($record) {
                return backend_view('ctc.action_tracking_link', compact('record'));
            })
            ->make(true);
    }

    /**
     * Get CTC Not Scan order detail
     */
    public function ctcnotscanDetail(Request $request, $id)
    {
        $ctc_id = base64_decode($id);
        $ctc_dash = Ctc::where(['id' => $ctc_id])->get();
        $ctc_dash = $ctc_dash[0];

        return backend_view('ctc.ctc_notscan_detail', compact('ctc_dash'));
    }

    /**
     * Get CTC Not Scan orders excel report
     */
    public function ctcNotscanExcel($date = null)
    {
        if ($date == null) {
            $date = date('Y-m-d');
        }
        $ctc_data = Ctc::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $date . "%")->where(['sprint_status' => 61])->get();
        $ctc_array[] = array('Joeyco Order', 'Route', 'Joey', 'Store Name', 'Customer Name', 'Customer Address', 'Postal Code', 'City Name', 'Weight', 'Pickup From Store', 'At Hub Processing', 'Out For Delivery', 'Estimated Customer delivery time', 'Actual Customer delivery time', 'Shipment tracking #', 'Shipment tracking link', 'Shipment Delivery Status', 'JoyeCo Notes / Comments', 'Returned to HUB 2', '2nd Attempt Pick up', '2nd Attempt Delivery', 'Returned to HUB 3', '3rd Attempt Pick up', '3rd Attempt Delivery');
        foreach ($ctc_data as $ctc) {
            if ($ctc->tracking_id != NULL) {
                $trackingid_link = "https://www.joeyco.com/track-order/" . $ctc->tracking_id;
            } else {
                $trackingid_link = '';
            }
            $ctc_array[] = array(
                'Joeyco Order' => strval($ctc->sprint_id),
                'Route' => strval($ctc->route_id),
                'Joey' => $ctc->joey,
                'Store Name' => $ctc->store_name,
                'Customer Name' => $ctc->customers_name,
                'Customer Address' => $ctc->address,
                'Postal Code' => $ctc->postal_code,
                'City Name' => $ctc->city_name,
                'Weight' => strval($ctc->weight),
                'Pickup From Store' => $ctc->pickup_from_store,
                'At Hub Processing' => $ctc->at_hub_processing,
                'Out For Delivery' => $ctc->out_for_delivery,
                'Estimated Customer delivery time' => $ctc->dropoff_eta,
                'Actual Customer delivery time' => $ctc->delivery_time,
                'Shipment tracking #' => $ctc->tracking_id,
                'Shipment tracking link' => strval($trackingid_link),
                'Shipment Delivery Status' => self::$status[$ctc->sprint_status],
                'JoyeCo Notes / Comments' => $ctc->notes,
                'Returned to HUB 2' => $ctc->hubreturned2,
                '2nd Attempt Pick up' => $ctc->hubpickup2,
                '2nd Attempt Delivery' => $ctc->deliver2,
                'Returned to HUB 3' => $ctc->hubreturned3,
                '3rd Attempt Pick up' => $ctc->hubpickup3,
                '3rd Attempt Delivery' => $ctc->deliver3
            );
        }
        Excel::create('CTC Not Scan Data ' . $date . '', function ($excel) use ($ctc_array) {
            $excel->setTitle('CTC Not Scan Data');
            $excel->sheet('CTC Not Scan Data', function ($sheet) use ($ctc_array) {
                $sheet->fromArray($ctc_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    /**
     * Get CTC Delivered
     */
    public function getCtcDelivered(Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $ctc_count = Ctc_count::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $today_date . "%")
            ->orderBy('id', 'DESC')
            ->first();
        $title_name = 'Canadian Tire';
        return backend_view('ctc.delivered_orders', compact('ctc_count', 'title_name'));
    }

    /**
     * Yajra call after CTC Delivered
     */
    public function ctcDeliveredData(Datatables $datatables, Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $query = Ctc::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $today_date . "%")->whereNotIn('sprint_status', [133, 121, 61]);
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
            ->addColumn('route_id', static function ($record) {
                return $record->route_id;
            })
            ->addColumn('action', static function ($record) {
                return backend_view('ctc.action_delivered', compact('record'));
            })
            ->addColumn('tracking_id_link', static function ($record) {
                return backend_view('ctc.action_tracking_link', compact('record'));
            })
            ->make(true);
    }

    /**
     * Get CTC Delivered order detail
     */
    public function ctcdeliveredDetail(Request $request, $id)
    {
        $ctc_id = base64_decode($id);
        $ctc_dash = Ctc::where(['id' => $ctc_id])->get();
        $ctc_dash = $ctc_dash[0];

        return backend_view('ctc.ctc_delivered_detail', compact('ctc_dash'));
    }

    /**
     * Get CTC Delivered order excel report
     */
    public function ctcDeliveredExcel($date = null)
    {
        if ($date == null) {
            $date = date('Y-m-d');
        }
        $ctc_data = Ctc::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $date . "%")->whereNotIn('sprint_status', [133, 121, 61])->get();
        $ctc_array[] = array('Joeyco Order', 'Route', 'Joey', 'Store Name', 'Customer Name', 'Customer Address', 'Postal Code', 'City Name', 'Weight', 'Pickup From Store', 'At Hub Processing', 'Out For Delivery', 'Estimated Customer delivery time', 'Actual Customer delivery time', 'Shipment tracking #', 'Shipment tracking link', 'Shipment Delivery Status', 'JoyeCo Notes / Comments', 'Returned to HUB 2', '2nd Attempt Pick up', '2nd Attempt Delivery', 'Returned to HUB 3', '3rd Attempt Pick up', '3rd Attempt Delivery');
        foreach ($ctc_data as $ctc) {
            if ($ctc->tracking_id != NULL) {
                $trackingid_link = "https://www.joeyco.com/track-order/" . $ctc->tracking_id;
            } else {
                $trackingid_link = '';
            }
            $ctc_array[] = array(
                'Joeyco Order' => strval($ctc->sprint_id),
                'Route' => strval($ctc->route_id),
                'Joey' => $ctc->joey,
                'Store Name' => $ctc->store_name,
                'Customer Name' => $ctc->customers_name,
                'Customer Address' => $ctc->address,
                'Postal Code' => $ctc->postal_code,
                'City Name' => $ctc->city_name,
                'Weight' => strval($ctc->weight),
                'Pickup From Store' => $ctc->pickup_from_store,
                'At Hub Processing' => $ctc->at_hub_processing,
                'Out For Delivery' => $ctc->out_for_delivery,
                'Estimated Customer delivery time' => $ctc->dropoff_eta,
                'Actual Customer delivery time' => $ctc->delivery_time,
                'Shipment tracking #' => $ctc->tracking_id,
                'Shipment tracking link' => strval($trackingid_link),
                'Shipment Delivery Status' => self::$status[$ctc->sprint_status],
                'JoyeCo Notes / Comments' => $ctc->notes,
                'Returned to HUB 2' => $ctc->hubreturned2,
                '2nd Attempt Pick up' => $ctc->hubpickup2,
                '2nd Attempt Delivery' => $ctc->deliver2,
                'Returned to HUB 3' => $ctc->hubreturned3,
                '3rd Attempt Pick up' => $ctc->hubpickup3,
                '3rd Attempt Delivery' => $ctc->deliver3
            );
        }
        Excel::create('CTC Delivered Data ' . $date . '', function ($excel) use ($ctc_array) {
            $excel->setTitle('CTC Delivered Data');
            $excel->sheet('CTC Delivered Data', function ($sheet) use ($ctc_array) {
                $sheet->fromArray($ctc_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    /**
     * Get CTC Route Info
     */
    public function getRouteinfo(Request $request)
    {
        $show_message = $request->message;
        if(!is_null($show_message))
        {
            $current_url  = $request->url();
            $query_string = http_build_query( $request->except(['message'] ) );
            return redirect($current_url.'?'.$query_string)
                ->with('alert-success', $show_message);
        }

        $date = $request->input('datepicker');
        // dd($date);
        if ($date == null) {
            $date = date("Y-m-d");
        }


        $ctc_info = JoeyRoutes::join('joey_route_locations', 'joey_routes.id', '=', 'joey_route_locations.route_id')
            ->where('joey_routes.date', 'like', $date . "%")
            ->where('joey_routes.hub', 17)
            ->where('joey_routes.deleted_at', null)
            ->where('joey_route_locations.deleted_at', null)
            ->orderBy('joey_routes.id', 'ASC')
            ->groupBy('joey_routes.id')
            ->select('joey_routes.*')
            ->get();

        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();

        //getting flag categories
        $flagCategories =  CustomerFlagCategories::where('parent_id', 0)
            ->where('is_enable', 1)
            ->whereNull('deleted_at')
            ->get();

        return backend_view('ctc.ctc_route_info', compact('ctc_info','flagCategories','ctcVendorIds'));
    }

    /**
     * Route Mark Delay
     */
    public function routeMarkDelay(Request $request)
    {
        $data = $request->all();

        $route = JoeyRouteLocations::join('sprint__tasks', 'joey_route_locations.task_id', '=', 'sprint__tasks.id')
            ->leftJoin('merchantids', 'merchantids.task_id', '=', 'sprint__tasks.id')
            ->where('route_id', '=', $data['route_id'])
            ->whereNull('joey_route_locations.deleted_at')
            ->whereNotNull('merchantids.tracking_id')
            ->get(['merchantids.tracking_id', 'sprint__tasks.id', 'sprint__tasks.sprint_id']);
        $deliver_status = $this->getStatusCodes('competed');
        $return_status = $this->getStatusCodes('return');
        $createRecord = [];
        foreach ($route as $rot) {

            $status_array = array_merge($deliver_status, $return_status);
            $sprint = Sprint::where('id', '=', $rot->sprint_id)->whereNotIn('status_id', $status_array)->first();

            if ($sprint) {
                $createRecord[] = [
                    'tracking_id' => $rot->tracking_id,
                    'date' => $data['date'],
                ];
                Sprint::where('id', '=', $rot->sprint_id)->update(['status_id' => 255]);
                $task = Task::where('id', '=', $rot->id)->where('type', 'dropoff')->whereNotIn('status_id', [$deliver_status, $return_status])->first();
                if ($task) {
                    Task::where('id', '=', $rot->id)->where('type', 'dropoff')->update(['status_id' => 255]);
                }
                $taskHistoryRecord = [
                    'sprint__tasks_id' => $rot->id,
                    'sprint_id' => $rot->sprint_id,
                    'status_id' => 255,
                    'date' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'active' => 1
                ];
                SprintTaskHistory::insert($taskHistoryRecord);
            }

        }
        if (count($createRecord) > 0) {
            TrackingDelay::insert($createRecord);
        }
        return response()->json(['status' => '1']);
    }


    /**
     * Get CTC Route Info excel report
     */
    public function ctcRouteinfoExcel($date = null)
    {
        //setting up current date if null
        if ($date == null) {
            $date = date('Y-m-d');
        }

        /*getting csv file data*/
        $ctc_route_data = JoeyRoutes::join('joey_route_locations', 'joey_routes.id', '=', 'joey_route_locations.route_id')
            ->where('joey_routes.date', 'like', $date . "%")
            ->where('joey_routes.hub', 17)
            ->where('joey_routes.deleted_at', null)
            ->where('joey_route_locations.deleted_at', null)
            ->orderBy('joey_routes.id', 'ASC')
            ->groupBy('joey_routes.id')
            ->select('joey_routes.*')
            ->get();
        //JoeyRoutes::where(\DB::raw("CONVERT_TZ(date,'UTC','America/Toronto')"),'like',$date."%")->where('hub',17)->get();

        //checking if data is null then return null
        if (count($ctc_route_data) <= 0) {
            // if the data null ten return empty array
            return [];
        }

        // init data variable
        $data = [];
        $csv_header = ['Route No', 'Joey Name', 'No of drops', 'No of picked', 'No of drops completed', 'No of Returns', 'No of unattempted'];
        $data[0] = $csv_header;

        $iteration = 1;
        foreach ($ctc_route_data as $ctc_route) {
            $joey_name = ($ctc_route->joey) ? $ctc_route->Joey->first_name . ' ' . $ctc_route->Joey->last_name : '';
            $data[$iteration] = [
                $ctc_route->id,
                $joey_name,
                $ctc_route->TotalOrderDropsCount(),
                $ctc_route->TotalOrderPickedCount(),
                $ctc_route->TotalOrderDropsCompletedCount(),
                $ctc_route->TotalOrderReturnCount(),
                $ctc_route->TotalOrderUnattemptedCount()
            ];
            $iteration++;
        }
        return $data;
    }

    /**
     * Get CTC Hub Route edit
     */
    public function ctcHubRouteEdit(Request $request, $routeId, $hubId)
    {

        $tracking_id = null;

        $status = null;
        $route = JoeyRouteLocations::join('sprint__tasks', 'joey_route_locations.task_id', '=', 'sprint__tasks.id')
            ->leftJoin('merchantids', 'merchantids.task_id', '=', 'sprint__tasks.id')
            ->join('locations', 'location_id', '=', 'locations.id')
            ->join('sprint__sprints', 'sprint_id', '=', 'sprint__sprints.id')
            ->leftJoin('sprint__contacts', 'sprint__contacts.id', '=', 'sprint__tasks.contact_id')
            ->whereNull('sprint__sprints.deleted_at')
            ->where('route_id', '=', $routeId)
            ->whereNull('joey_route_locations.deleted_at')
            ->whereNotNull('merchantids.tracking_id')
            ->orderBy('joey_route_locations.ordinal', 'asc');
        if (!empty($request->get('tracking-id'))) {
            $tracking_id = $request->get('tracking-id');
            $route = $route->where('merchantids.tracking_id', '=', $request->get('tracking-id'));
        }

        if (!empty($request->get('status'))) {
            $status = $request->get('status');
            $route = $route->where('sprint__sprints.status_id', '=', $request->get('status'));
        }
        $route = $route->get(['joey_route_locations.id', 'merchantids.merchant_order_num', 'joey_route_locations.task_id', 'merchantids.tracking_id',
            'sprint_id', 'type', 'start_time', 'end_time', 'address', 'postal_code'
            , 'joey_route_locations.arrival_time', 'joey_route_locations.finish_time', 'sprint__sprints.status_id', 'sprint__tasks.sprint_id',
            'joey_route_locations.distance', 'sprint__contacts.name', 'sprint__contacts.phone', 'joey_route_locations.route_id', 'joey_route_locations.ordinal']);
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();

        return backend_view('ctc.edit-hub-route', ['route' => $route, 'hub_id' => $hubId, 'tracking_id' => $tracking_id, 'status_select' => $status,'ctcVendorIds'=> $ctcVendorIds]);
    }

    /**
     * Render Model flag history table view
     */
    public function flagHistoryModelHtmlRender(Request $request)
    {
        $request_data = $request->all();

        $joey_flags_history = FlagHistory::where('sprint_id',$request->sprint)
            ->orderBy('id', 'DESC')
            ->where('unflaged_by','=',0)
            ->get();

        //getting flag categories
        $flagCategories =  CustomerFlagCategories::where('parent_id', 0)
            ->where('is_enable', 1)
            ->whereNull('deleted_at')
            ->get();

        $html =  view('backend.ctc.sub-views.ajax-render-view-edit-hub-route-flag-model',
            compact(
                'joey_flags_history',
                'flagCategories',
                'request_data'
            )
        )->render();

        return response()->json(['status' => true,'html'=>$html]);
    }

    /**
     * Get CTC Tracking Order Detail
     */
    public function getCtctrackingorderdetails($sprintId)
    {
        $result = Sprint::join('sprint__tasks', 'sprint_id', '=', 'sprint__sprints.id')
            ->leftJoin('merchantids', 'merchantids.task_id', '=', 'sprint__tasks.id')
            ->leftJoin('joey_route_locations', 'joey_route_locations.task_id', '=', 'sprint__tasks.id')
            ->leftJoin('joey_routes', 'joey_routes.id', '=', 'joey_route_locations.route_id')
            ->leftJoin('joeys', 'joeys.id', '=', 'joey_routes.joey_id')
            ->join('locations', 'sprint__tasks.location_id', '=', 'locations.id')
            ->join('sprint__contacts', 'contact_id', '=', 'sprint__contacts.id')
            ->leftJoin('vendors', 'creator_id', '=', 'vendors.id')
            ->where('sprint__tasks.sprint_id', '=', $sprintId)
            //->whereNull('joey_route_locations.deleted_at')
            ->orderBy('ordinal', 'DESC')->take(1)
            ->get(array('sprint__tasks.*', 'joey_routes.id as route_id', 'locations.address', 'locations.suite', 'locations.postal_code', 'sprint__contacts.name', 'sprint__contacts.phone', 'sprint__contacts.email',
                'joeys.first_name as joey_firstname', 'joeys.id as joey_id',
                'joeys.last_name as joey_lastname', 'vendors.first_name as merchant_firstname', 'vendors.last_name as merchant_lastname', 'merchantids.scheduled_duetime'
            , 'joeys.id as joey_id', 'merchantids.tracking_id', 'joeys.phone as joey_contact', 'joey_route_locations.ordinal as stop_number'));

        $i = 0;

        $data = [];

        foreach ($result as $tasks) {
            $status2 = array();
            $status = array();
            $status1 = array();
            $data[$i] = $tasks;
            $taskHistory = SprintTaskHistory::where('sprint_id', '=', $tasks->sprint_id)->WhereNotIn('status_id', [17, 38])->orderBy('id')
                //->where('active','=',1)
                ->get(['status_id', 'created_at']);

            $returnTOHubDate = SprintReattempt::
            where('sprint_reattempts.sprint_id', '=', $tasks->sprint_id)->orderBy('created_at')
                ->first();

            if (!empty($returnTOHubDate)) {
                $taskHistoryre = SprintTaskHistory::where('sprint_id', '=', $returnTOHubDate->reattempt_of)->WhereNotIn('status_id', [17, 38])->orderBy('id')
                    //->where('active','=',1)
                    ->get(['status_id', 'created_at']);

                foreach ($taskHistoryre as $history) {

                    $status[$history->status_id]['id'] = $history->status_id;
                    if ($history->status_id == 13) {
                        $status[$history->status_id]['description'] = 'At hub - processing';
                    } else {
                        $status[$history->status_id]['description'] = $this->statusmap($history->status_id);
                    }
                    $status[$history->status_id]['created_at'] = date('Y-m-d H:i:s', strtotime($history->created_at) - 14400);

                }

            }
            if (!empty($returnTOHubDate)) {
                $returnTO2 = SprintReattempt::
                where('sprint_reattempts.sprint_id', '=', $returnTOHubDate->reattempt_of)->orderBy('created_at')
                    ->first();

                if (!empty($returnTO2)) {
                    $taskHistoryre = SprintTaskHistory::where('sprint_id', '=', $returnTO2->reattempt_of)->WhereNotIn('status_id', [17, 38])->orderBy('id')
                        //->where('active','=',1)
                        ->get(['status_id', 'created_at']);

                    foreach ($taskHistoryre as $history) {

                        $status2[$history->status_id]['id'] = $history->status_id;
                        if ($history->status_id == 13) {
                            $status2[$history->status_id]['description'] = 'At hub - processing';
                        } else {
                            $status2[$history->status_id]['description'] = $this->statusmap($history->status_id);
                        }
                        $status2[$history->status_id]['created_at'] = date('Y-m-d H:i:s', strtotime($history->created_at) - 14400);

                    }

                }
            }

            //    dd($taskHistory);

            foreach ($taskHistory as $history) {

                $status1[$history->status_id]['id'] = $history->status_id;

                if ($history->status_id == 13) {
                    $status1[$history->status_id]['description'] = 'At hub - processing';
                } else {
                    $status1[$history->status_id]['description'] = $this->statusmap($history->status_id);
                }
                $status1[$history->status_id]['created_at'] = date('Y-m-d H:i:s', strtotime($history->created_at) - 14400);

            }
            $data[$i]['status'] = $status;
            $data[$i]['status1'] = $status1;
            $data[$i]['status2'] = $status2;
            $i++;
        }
        return backend_view('ctc.orderdetailswtracknigid', ['data' => $data, 'sprintId' => $sprintId]);
    }

    /**
     * Get CTC Reporting
     */
    public function getCtcReporting(Request $request)
    {

        $from_date = !empty($request->get('fromdatepicker')) ? $request->get('fromdatepicker') : date("Y-m-d");
        $to_date = !empty($request->get('todatepicker')) ? $request->get('todatepicker') : date("Y-m-d");
        $interval = date_diff(date_create($from_date), date_create($to_date));

        if ($interval->days > 14) {
            session()->flash('alert-danger', 'The date range selected must be less then or equal to 15 days');
            return redirect('ctcreporting');
        }
        $all_dates = array();
        $range_from_date = new Carbon($from_date);
        $range_to_date = new Carbon($to_date);
        while ($range_from_date->lte($range_to_date)) {
            $all_dates[] = $range_from_date->toDateString();

            $range_from_date->addDay();
        }

        $from_date = $from_date . " 00:00:00";
        $to_date = $to_date . " 23:59:59";

        $ctcVendorIds = CtcVendor::pluck('vendor_id');
        $sprintids = Sprint::whereIn('creator_id', $ctcVendorIds)->whereBetween(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), [$from_date, $to_date])
            ->whereNotIn('status_id', [38, 36])->pluck('id');

        $sprint_ids = Sprint::whereIn('id', $sprintids)->where('deleted_at', null)->where('is_reattempt','=', 0)->pluck('id');
        $sprint = new Sprint();
        $ctc_count = $sprint->getSprintCounts($sprint_ids);

        foreach ($all_dates as $range_date) {
            $sprintids = Sprint::whereIn('creator_id', $ctcVendorIds)->where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $range_date . "%")
                ->whereNotIn('status_id', [38, 36])->pluck('id');
            $sprint_ids = Sprint::whereIn('id', $sprintids)->where('deleted_at', null)->where('is_reattempt','=', 0)->pluck('id');
            $sprint = new Sprint();
            $ctc_range_count[$range_date] = $sprint->getSprintCounts($sprint_ids);
        }


        return backend_view('Ctc-reporting.ctc_reporting', compact('ctc_count', 'ctc_range_count'));
    }

    /**
     * Get CTC Route Info
     */
    public function getCtcReportingData(Datatables $datatables, Request $request)
    {
        if ($request->ajax()) {


            $from_date = $request->from_date;
            $to_date = $request->to_date;
            $data_for = $request->data_for;


            $ctcVendorIds = CtcVendor::pluck('vendor_id');
            $sprintids = Sprint::whereIn('creator_id', $ctcVendorIds)->where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $from_date . "%")
                ->whereNotIn('status_id', [38, 36])->pluck('id');

            $query = Sprint::whereIn('id', $sprintids)->where('deleted_at', null)->where('is_reattempt','=', 0);

            //$query = DB::table('ctc_dashboard')->whereBetween(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), [$from_date, $to_date]);
            $sprint = new Sprint();
            // useing fillters
            ($data_for == 'picked-up') ? $query->where('status_id', 125) : $query;
            ($data_for == 'at-hub') ? $query->whereIn('status_id', [124, 13]) : $query;
            ($data_for == 'at-store') ? $query->where('status_id', 61) : $query;
            ($data_for == 'sorted-order') ? $query->where('status_id', 133) : $query;
            ($data_for == 'out-for-delivery') ? $query->where('status_id', 121) : $query;
            ($data_for == 'delivered-order') ? $query->whereIn('status_id', $sprint->getStatusCodes('competed')) : $query;
            ($data_for == 'returned') ? $query->whereIn('status_id', $sprint->getStatusCodes('return'))->where('status_id', '!=', 111) : $query;
            ($data_for == 'returned-to-merchant') ? $query->where('status_id', 111) : $query;

            // selecting the columns
            /* $query->select([
                 'id',
                 'sprint_status',
                 'created_at',
                 'updated_at',
                 'tracking_id'
             ]);*/

            return $datatables->eloquent($query)
                ->editColumn('status_id', static function ($record) {
                    return self::$status[$record->status_id];
                })
                ->addColumn('tracking_id', static function ($record) {
                    if ($record->sprintCtcTasks) {
                        if ($record->sprintCtcTasks->taskMerchants) {
                            return substr($record->sprintCtcTasks->taskMerchants->tracking_id, strrpos($record->sprintCtcTasks->taskMerchants->tracking_id, '_') + 0);
                        } else {
                            "";
                        }
                    } else {
                        "";
                    }
                })
                ->addColumn('store_name', static function ($record) {
                    if ($record->sprintVendor) {
                        return $record->sprintVendor->name;
                    } else {
                        "";
                    }
                })
                ->addColumn('action', static function ($record) {
                    return backend_view('ctc.new_action', compact('record'));
                })
                ->editColumn('created_at', static function ($record) {
                    return (new \DateTime($record->created_at))->setTimezone(new \DateTimeZone('America/Toronto'))->format('Y-m-d H:i:s');
                })
                ->editColumn('updated_at', static function ($record) {
                    return (new \DateTime($record->updated_at))->setTimezone(new \DateTimeZone('America/Toronto'))->format('Y-m-d H:i:s');
                })
                ->make(true);

        }
    }

    /**
     * Get CTC OTD Graph
     */
    public function statistics_otd_index(Request $request)
    {
        return backend_view('ctc-otd.statistics_otd_dashboard');
    }

    /**
     * Get Day CTC OTD Graph
     */
    public function ajax_render_ctc_otd_day(Request $request)
    {
        $date = date("Y-m-d");
        $sprint = new Sprint();

        $ctcVendorIds = CtcVendor::pluck('vendor_id');
        $sprintIds = Sprint::whereIn('creator_id', $ctcVendorIds)->where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $date . "%")
            ->whereIn('status_id', $sprint->getStatusCodes('competed'))->pluck('id');
        $query = Sprint::whereIn('id', $sprintIds)->where('deleted_at', null)->where('is_reattempt','=', 0)->get();

        $totalcount = 0;
        $totallates = 0;
        if (!empty($query)) {
            foreach ($query as $record) {
                $createdTimestamp = strtotime($record->created_at);
                $day = date('D', $createdTimestamp);
                if ($day == 'Sat') {
                    if ($record->deliveryTime()->delivery_time != NULL && $record->ctcAtHubProcessingFirst()->athub && date('d-m-Y', strtotime("+2 day", strtotime($record->ctcAtHubProcessingFirst()->athub))) . " 21:00:00" < date('d-m-Y H:i:s', strtotime($record->deliveryTime()->delivery_time))) {
                        $totallates++;
                    }
                } else {
                    if ($record->deliveryTime()->delivery_time != NULL && $record->ctcAtHubProcessingFirst()->athub && date('d-m-Y', strtotime("+1 day", strtotime($record->ctcAtHubProcessingFirst()->athub))) . " 21:00:00" < date('d-m-Y H:i:s', strtotime($record->deliveryTime()->delivery_time))) {
                        $totallates++;
                    }
                }
                $totalcount++;
            }
            if ($totalcount == 0) {
                $totalcount = 1;
            }
            $odt_data_1 = ['y1' => round(($totallates / $totalcount) * 100, 0), 'y2' => 100 - round((($totallates / $totalcount) * 100), 0)];
        } else {
            $odt_data_1 = ['y1' => 100, 'y2' => 0];
        }
        return response()->json(array('status' => true, 'for' => 'pie_chart1', 'data' => [$odt_data_1]));
    }

    /**
     * Get Week CTC OTD Graph
     */
    public function ajax_render_ctc_otd_week(Request $request)
    {
        $date = date("Y-m-d");
        $sprint = new Sprint();

        $ctcVendorIds = CtcVendor::pluck('vendor_id');
        $sprintIds = Sprint::whereIn('creator_id', $ctcVendorIds)->whereIn('status_id', $sprint->getStatusCodes('competed'))
            ->whereBetween(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), [date('y-m-d', strtotime('-6 day', strtotime($date))) . ' 20:00:00', $date . " 19:59:59"])->pluck('id');

        $query = Sprint::whereIn('id', $sprintIds)->where('deleted_at', null)->where('is_reattempt','=', 0)->get();

        $totalcount = 0;
        $totallates = 0;
        if (!empty($query)) {
            foreach ($query as $record) {
                $createdTimestamp = strtotime($record->created_at);
                $day = date('D', $createdTimestamp);
                if ($day == 'Sat') {
                    if ($record->deliveryTime()->delivery_time != NULL && $record->ctcAtHubProcessingFirst()->athub && date('d-m-Y', strtotime("+2 day", strtotime($record->ctcAtHubProcessingFirst()->athub))) . " 21:00:00" < date('d-m-Y H:i:s', strtotime($record->deliveryTime()->delivery_time))) {
                        $totallates++;
                    }
                } else {
                    if ($record->deliveryTime()->delivery_time != NULL && $record->ctcAtHubProcessingFirst()->athub && date('d-m-Y', strtotime("+1 day", strtotime($record->ctcAtHubProcessingFirst()->athub))) . " 21:00:00" < date('d-m-Y H:i:s', strtotime($record->deliveryTime()->delivery_time))) {
                        $totallates++;
                    }
                }
                $totalcount++;
            }
            if ($totalcount == 0) {
                $totalcount = 1;
            }
            $odt_data_1 = ['y1' => round(($totallates / $totalcount) * 100, 0), 'y2' => 100 - round((($totallates / $totalcount) * 100), 0)];
        } else {
            $odt_data_1 = ['y1' => 100, 'y2' => 0];
        }
        return response()->json(array('status' => true, 'for' => 'pie_chart2', 'data' => [$odt_data_1]));
    }

    /**
     * Get Month CTC OTD Graph
     */
    public function ajax_render_ctc_otd_month(Request $request)
    {
        $date = date("Y-m-d");
        $sprint = new Sprint();
        $ctcVendorIds = CtcVendor::pluck('vendor_id');

        $sprintIds = Sprint::whereIn('creator_id', $ctcVendorIds)->whereIn('status_id', $sprint->getStatusCodes('competed'))
            ->whereBetween(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), [date('y-m-d', strtotime('-1 month', strtotime($date))) . ' 20:00:00', $date . " 19:59:59"])->pluck('id');
        $query = Sprint::whereIn('id', $sprintIds)->where('deleted_at', null)->where('is_reattempt','=', 0)->get();

        $totalcount = 0;
        $totallates = 0;
        if (!empty($query)) {
            foreach ($query as $record) {
                $createdTimestamp = strtotime($record->created_at);
                $day = date('D', $createdTimestamp);
                if ($day == 'Sat') {
                    if ($record->deliveryTime()->delivery_time != NULL && $record->ctcAtHubProcessingFirst()->athub && date('d-m-Y', strtotime("+2 day", strtotime($record->ctcAtHubProcessingFirst()->athub))) . " 21:00:00" < date('d-m-Y H:i:s', strtotime($record->deliveryTime()->delivery_time))) {
                        $totallates++;
                    }
                } else {
                    if ($record->deliveryTime()->delivery_time != NULL && $record->ctcAtHubProcessingFirst()->athub && date('d-m-Y', strtotime("+1 day", strtotime($record->ctcAtHubProcessingFirst()->athub))) . " 21:00:00" < date('d-m-Y H:i:s', strtotime($record->deliveryTime()->delivery_time))) {
                        $totallates++;
                    }
                }
                $totalcount++;
            }
            if ($totalcount == 0) {
                $totalcount = 1;
            }
            $odt_data_1 = ['y1' => round(($totallates / $totalcount) * 100, 0), 'y2' => 100 - round((($totallates / $totalcount) * 100), 0)];
        } else {
            $odt_data_1 = ['y1' => 100, 'y2' => 0];
        }
        return response()->json(array('status' => true, 'for' => 'pie_chart3', 'data' => [$odt_data_1]));
    }

    public function get_trackingorderdetails($sprintId)
    {
        $result = Sprint::join('sprint__tasks', 'sprint_id', '=', 'sprint__sprints.id')
            ->leftJoin('merchantids', 'merchantids.task_id', '=', 'sprint__tasks.id')
            ->leftJoin('joey_route_locations', 'joey_route_locations.task_id', '=', 'sprint__tasks.id')
            ->leftJoin('joey_routes', 'joey_routes.id', '=', 'joey_route_locations.route_id')
            ->leftJoin('joeys', 'joeys.id', '=', 'joey_routes.joey_id')
            ->join('locations', 'sprint__tasks.location_id', '=', 'locations.id')
            ->join('sprint__contacts', 'contact_id', '=', 'sprint__contacts.id')
            ->leftJoin('vendors', 'creator_id', '=', 'vendors.id')
            ->where('sprint__tasks.sprint_id', '=', $sprintId)
            ->whereNull('joey_route_locations.deleted_at')
            ->orderBy('ordinal', 'DESC')->take(1)
            ->get(array('sprint__tasks.*', 'joey_routes.id as route_id', 'locations.address', 'locations.suite', 'locations.postal_code', 'sprint__contacts.name', 'sprint__contacts.phone', 'sprint__contacts.email',
                'joeys.first_name as joey_firstname', 'joeys.id as joey_id',
                'joeys.last_name as joey_lastname', 'vendors.first_name as merchant_firstname', 'vendors.last_name as merchant_lastname', 'merchantids.scheduled_duetime'
            , 'joeys.id as joey_id', 'merchantids.tracking_id', 'joeys.phone as joey_contact', 'joey_route_locations.ordinal as stop_number', 'merchantids.merchant_order_num', 'merchantids.address_line2', 'sprint__sprints.creator_id'));

        $i = 0;

        $data = [];

        foreach ($result as $tasks) {
            $status2 = array();
            $status = array();
            $status1 = array();
            $data[$i] = $tasks;
            $taskHistory = TaskHistory::where('sprint_id', '=', $tasks->sprint_id)->WhereNotIn('status_id', [17, 38])->orderBy('date')
                //->where('active','=',1)
                ->get(['status_id', \DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto') as created_at")]);

            $returnTOHubDate = SprintReattempt::
            where('sprint_reattempts.sprint_id', '=', $tasks->sprint_id)->orderBy('created_at')
                ->first();

            if (!empty($returnTOHubDate)) {
                $taskHistoryre = TaskHistory::where('sprint_id', '=', $returnTOHubDate->reattempt_of)->WhereNotIn('status_id', [17, 38])->orderBy('date')
                    //->where('active','=',1)
                    ->get(['status_id', \DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto') as created_at")]);

                foreach ($taskHistoryre as $history) {

                    $status[$history->status_id]['id'] = $history->status_id;
                    if ($history->status_id == 13) {
                        $status[$history->status_id]['description'] = 'At hub - processing';
                    } else {
                        $status[$history->status_id]['description'] = $this->statusmap($history->status_id);
                    }
                    $status[$history->status_id]['created_at'] = $history->created_at;

                }

            }
            if (!empty($returnTOHubDate)) {
                $returnTO2 = SprintReattempt::
                where('sprint_reattempts.sprint_id', '=', $returnTOHubDate->reattempt_of)->orderBy('created_at')
                    ->first();

                if (!empty($returnTO2)) {
                    $taskHistoryre = TaskHistory::where('sprint_id', '=', $returnTO2->reattempt_of)->WhereNotIn('status_id', [17, 38])->orderBy('date')
                        //->where('active','=',1)
                        ->get(['status_id', \DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto') as created_at")]);

                    foreach ($taskHistoryre as $history) {

                        $status2[$history->status_id]['id'] = $history->status_id;
                        if ($history->status_id == 13) {
                            $status2[$history->status_id]['description'] = 'At hub - processing';
                        } else {
                            $status2[$history->status_id]['description'] = $this->statusmap($history->status_id);
                        }
                        $status2[$history->status_id]['created_at'] = $history->created_at;

                    }

                }
            }

            //    dd($taskHistory);

            foreach ($taskHistory as $history) {

                $status1[$history->status_id]['id'] = $history->status_id;

                if ($history->status_id == 13) {
                    $status1[$history->status_id]['description'] = 'At hub - processing';
                } else {
                    $status1[$history->status_id]['description'] = $this->statusmap($history->status_id);
                }
                $status1[$history->status_id]['created_at'] = $history->created_at;

            }

            if ($status != null) {
                $sort_key = array_column($status, 'created_at');
                array_multisort($sort_key, SORT_ASC, $status);
            }
            if ($status1 != null) {
                $sort_key = array_column($status1, 'created_at');
                array_multisort($sort_key, SORT_ASC, $status1);
            }
            if ($status2 != null) {
                $sort_key = array_column($status2, 'created_at');
                array_multisort($sort_key, SORT_ASC, $status2);
            }


            $data[$i]['status'] = $status;
            $data[$i]['status1'] = $status1;
            $data[$i]['status2'] = $status2;
            $i++;
        }


        return ['data' => $data, 'sprintId' => $sprintId];
        // return backend_view('orderdetailswtracknigid',['data'=>$data,'sprintId' => $sprintId,'reasons' => $reasons]);
    }

    /**
     * Get CTC Dashboard Excel Report
     */
    public function CtcMissingExcelReport($date = null)
    {
        if ($date == null) {
            $date = date('Y-m-d');

        }
        $file_name = new \DateTime($date);
        $file_name = $file_name->format("M d, Y");
        $file_name = "CTC Tracking File " . $file_name . ".csv";

        $ctcVendorIds = CtcVendor::pluck('vendor_id');

        $taskIds = MerchantIds::whereIn('tracking_id',['JY97100468382','JY14200468054','JY27000468417','JY04700468161','JY68100468401','JY39100468627','JY74000468586','JY80600468486','JY69600468760','JY98300467887','JY15800467696','JY31200467829','JY44300467820','JY45600467922','JY90600468017','JY28000467518','JY59900467670','JY85000467687','JY65900467672','JY54800465674','JY75600466492','JY72500465942','JY90000465834','JY44200466053','JY44200466052','JY54000466089','JY70800466403','JY60300466031','JY09000466294','JY75800466574','JY37200466561','JY93300467215','JY45800466556','JY92800467053','JY40700466985','JY39900465300','JY07600465330','JY14600465381','JY01000465526','JY79800465567','JY90300465493','JY45300465697','JY05600465692','JY89100465378','JY34300465500','JY08100465226','JY77300465059','JY46000464945','JY38800465069','JY10900464984','JY97100465244','JY44800464566','JY11500464504','JY44100464637','JY73700464765','JY53300464774','JY66700464655','JY51200464735','JY36400463990','JY47100463914','JY82400463930','JY82500463931','JY25200464128','JY48800464105','JY94800464245','JY47500462937','JY13800462538','JY47700462765','JY70300462791','JY01700463036','JY74400462267','JY43200462640','JY76700462755','JY21800462638','JY69600462697','JY40900462827','JY75600462910','JY28500463716','JY29500463634','JY02800463154','JY17100463328','JY09300463371','JY67300463712','JY09600463738','JY52200463799','JY14300463768','JY02700462261','JY92000462176','JY85900462100','JY68700462375','JY00100461746','JY81600461597','JY33500461305','JY40000461362','JY27400461436','JY39200461370','JY75300461508','JY38300461542','JY71500460792','JY94300460811','JY36600460878','JY07100460995','JY73100461134','JY89500461105','JY57500460479','JY10300459712','JY14800459781','JY36700459989','JY36700459990','JY36700459991','JY36700459992','JY53800459996','JY53700459995','JY04900460129','JY70800460234','JY06900460243','JY24200460247','JY20900460216','JY50300460374','JY93100460461','JY82300460727','JY61500459246','JY04300459154','JY69000459342','JY60400459336','JY87900459205','JY71500458979','JY77100458743','JY56500458864','JY83000458366','JY91600458491','JY07200458552','JY91800457960','JY04300457975','JY70600456470','JY82300456707','JY95600456678','JY04200456736','JY61700456691','JY01300456837','JY01100457034','JY32300457014','JY13500457090','JY06300457195','JY69300457352','JY64400457385','JY39500457782','JY07800457787','JY32200456205','JY68000456346','JY27300456140','JY71100456063','JY53500454757','JY03200455031','JY62200455101','JY49300455305','JY91000453536','JY74600453639','JY56600453721','JY47100453960','JY69800454021','JY02300454637','JY14400454713','JY81600454926','JY28700453013','JY71200453055','JY44200453163','JY32700453085','JY49600452679','JY66900452539','JY77700452510','JY21400452620','JY47000452687','JY24800452621','JY50700452818','JY76800452858','JY76400452870','JY61700452206','JY53700452050','JY86600452298','JY68200452372','JY63800451961','JY69100452177','JY49700452263','JY69800452171','JY95900452115','JY38600452232','JY38600452233','JY38500452230','JY38500452231','JY29800452422','JY14700451310','JY41600451213','JY39800451135','JY33800451241','JY16200451217','JY35200451210','JY19900451359','JY79600451260','JY31400451269','JY11600451328','JY69700451941','JY80600451920','JY02800451208','JY54900451370','JY57000451458','JY69800451767','JY69800451768','JY14500451644','JY98200451901','JY07600451839','JY07600451838','JY89800451970','JY89800451971','JY17300449846','JY90300449196','JY74100450655','JY01500449488','JY19400449884','JY64300450015','JY65000450330','JY59400450172','JY52400450065','JY90300450255','JY44400450563','JY29600451092','JY48500451055','JY70400451082','JY94900450759','JY06600450552','JY37900451157','JY29400450971','JY78300451083','JY06800449929','JY06800449930','JY30300448677','JY99000448672','JY04400448488','JY94100448698','JY73100448666','JY76600448667','JY43100448681','JY46300448683','JY48700448685','JY50100448686','JY51600448687','JY92900448697','JY06600448703','JY81400448693','JY94600448699','JY95200448700','JY95700448701','JY79500448725','JY52000448816','JY54500449144','JY10700448339','JY96300447938','JY16300448227','JY43800447623','JY72700447339','JY23700447079','JY68200446201','JY37100445946','JY01600445959','JY74600445994','JY47400446261','JY47400446262','JY47500446263','JY47400446260','JY47500446264','JY29000446304','JY41100446852','JY19800446693','JY54800446899','JY36500446759','JY60600445351','JY88500445681','JY53600445753','JY91300445615','JY51400445762','JY69300444967','JY39700445270','JY84600445307','JY77000444713','JY97300444946','JY43100444871','JY96700444917','JY96700444918','JY96700444919','JY96700444920','JY11900444930','JY28700444525','JY43200444387','JY42400444332','JY53500444402','JY18800444465','JY62000444428','JY71200444431','JY66000444487','JY40300444605','JY03500444169','JY57200443215','JY57200443216','JY31300443434','JY93900443580','JY03700443835','JY13800444172','JY13900444173','JY17800442890','JY58100443063','JY58100443062','JY07700442603','JY60700442142','JY05800442156','JY88000442430','JY14500442238','JY37600442348','JY51700441737','JY86200442040','JY97900441146','JY86600441378','JY74500441578','JY46100440513','JY17600440605','JY92400440960','JY79700440835','JY81800440977','JY26900441193','JY01100441220','JY26900440765','JY65500439224','JY24100439442','JY68600439586','JY42500439537','JY44700439524','JY44700439525','JY57300439612','JY57300439613','JY49200439673','JY04800440030','JY40200440078','JY19200439218','JY81700438980','JY42900438881','JY72600438966','JY62800438963','JY29200438083','JY64700438087','JY47800438246','JY84400437685','JY73700437194','JY60000436986','JY86000437188','JY51400437271','JY16500437471','JY21400437946','JY73000438076','JY21300438048','JY69000437704','JY37700438052','JY51400438055','JY93500436460','JY53000436435','JY34400436714','JY46600435847','JY62500436119','JY51500436090','JY92300435441','JY79000435501','JY79000435502','JY45000435586','JY62600435670','JY17900435700','JY19000434714','JY41600434427','JY73100434897','JY92500434951','JY74500434948','JY63900434984','JY72900435104','JY20600435071','JY57200433945','JY57700433211','JY16000432459','JY21800432460','JY84200432471','JY59500432465','JY98200432610','JY09900432812','JY22100433550','JY85200433197','JY28000433937','JY42100433235','JY17500434117','JY52200433630','JY80200433634','JY33200434381','JY06700433769','JY78200433669','JY66200433916','JY83700434081','JY92000434083','JY00400434309','JY05500434517','JY61800434215','JY60800434483','JY40800434497','JY82800434651'])->where('deleted_at', null)->groupBy('tracking_id')->pluck('task_id')->toArray();
        $sprintIds = Task::whereIn('id',$taskIds)->where('deleted_at', null)->pluck('sprint_id')->toArray();
        $ctc_data = Sprint::whereIn('id', $sprintIds)->where('deleted_at', null)->where('is_reattempt','=', 0)->get();
        // header info for browser


        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="'.$file_name);
        header('Pragma: no-cache');
        header('Expires: 0');

        // echo "JoeyCo Order\tRoute\tJoey\tStore Name\tCustomer Name\tCustomer Address\tPostal Code\tCity Name\tWeight\tPickup From Store\t1st Attempt - At Hub Processing\t1st Attempt - Out For Delivery\t1st Attempt - Estimated Customer Delivery Time\t1st Attempt - Delivery\t1st Attempt - Shipment Delivery Status\t2nd Attempt - At Hub Processing\t2nd Attempt - Out For Delivery\t2nd Attempt - Estimated Customer Delivery Time\t2nd Attempt - Delivery\t2nd Attempt - Shipment Delivery Status\t3rd Attempt - At Hub Processing\t3rd Attempt - Out For Delivery\t3rd Attempt - Estimated Customer Delivery Time\t3rd Attempt - Delivery\t3rd Attempt - Shipment Delivery Status\tShipment Tracking #\tActual Delivery Status\tActual Delivery\tShipment Tracking Link\tJoyeCo Notes / Comments\t\n";
        echo "JoeyCo Order,Route,Joey,Store Name,Customer Name,Customer Address,Postal Code,City Name,Weight,Pickup From Store,1st Attempt - At Hub Processing,1st Attempt - Out For Delivery,1st Attempt - Estimated Customer Delivery Time,1st Attempt - Delivery,1st Attempt - Shipment Delivery Status,2nd Attempt - At Hub Processing,2nd Attempt - Out For Delivery,2nd Attempt - Estimated Customer Delivery Time,2nd Attempt - Delivery,2nd Attempt - Shipment Delivery Status,3rd Attempt - At Hub Processing,3rd Attempt - Out For Delivery,3rd Attempt - Estimated Customer Delivery Time,3rd Attempt - Delivery,3rd Attempt - Shipment Delivery Status,Shipment Tracking #,Actual Delivery Status,Actual Delivery,Shipment Tracking Link,JoyeCo Notes / Comments,\n";

        // $ctc_array[] = ['Joeyco Order', 'Route', 'Joey', 'Store Name', 'Customer Name', 'Customer Address', 'Postal Code', 'City Name', 'Weight', 'Pickup From Store', 'At Hub Processing', 'Out For Delivery', 'Estimated Customer delivery time', 'Actual Customer delivery time', 'Shipment tracking #', 'Shipment tracking link', 'Shipment Delivery Status', 'JoyeCo Notes / Comments', 'Returned to HUB 2', '2nd Attempt Pick up', '2nd Attempt Delivery', 'Returned to HUB 3', '3rd Attempt Pick up', '3rd Attempt Delivery'];

        foreach ($ctc_data as $ctc_rec) {
            $ctc = null;
            if ($ctc_rec->sprintReattempts) {
                if ($ctc_rec->sprintReattempts->reattempts_left == 0) {
                    $ctc =  $firstSprint = Sprint::where('id', '=', $ctc_rec->sprintReattempts->reattempt_of)->first();
                }
                else
                {
                    $ctc = $ctc_rec;
                }
            }
            else
            {
                $ctc = $ctc_rec;
            }
            $pickup3 = "";
            $hubreturned3 = "";
            $hubpickup3 = "";
            $deliver3 = "";
            $eta_time3 = "";
            $status3 = "";
            $pickup2 = "";
            $hubreturned2 = "";
            $hubpickup2 = "";
            $deliver2 = "";
            $eta_time2 = "";
            $status2 = "";
            $notes = '';
            $check_actual = false;
            $pickup = $ctc->pickupFromStore()->pickup;
            $hubreturned = "";//$ctc->atHubProcessing()->athub;
            $hubpickup = "";// $ctc->outForDelivery()->outdeliver;
            $deliver = "";//$ctc->deliveryTime()->delivery_time;
            $actual_delivery = $ctc->actualDeliveryTime()->actual_delivery;
            $actual_delivery_status = '';

            $eta_time = "";
            if ($pickup) {
                $eta_time = date('Y-m-d', strtotime("+1 day", strtotime($pickup))).' 21:00:00';
            }
            $status = $ctc->status_id;
            if ($ctc->status_id == 17) {
                $preStatus = \App\SprintTaskHistory::where('sprint_id', '=', $ctc->id)
                    ->where('status_id', '!=', '17')
                    ->orderBy('id', 'desc')->first();
                if (!empty($preStatus)) {
                    $status = $preStatus->status_id;
                }
            }
            if ($ctc->actualDeliveryTime()->actual_delivery != null) {
                $check_actual = true;
                $actual_delivery_status = $ctc->actualDeliveryTime()->status_id;

            }
            $notes1 = Notes::where('object_id', $ctc->id)->pluck('note');
            $i = 0;
            foreach ($notes1 as $note) {
                if ($i == 0)
                    $notes = $notes . $note;
                else
                    $notes = $notes . ', ' . $note;
            }
            if ($ctc->sprintReattempts) {
                if ($ctc->sprintReattempts->reattempts_left == 0) {

                    $hubreturned3 = $ctc->atHubProcessing()->athub;
                    $hubpickup3 = $ctc->outForDelivery()->outdeliver;
                    $deliver3 = $ctc->deliveryTime()->delivery_time;
                    if ($hubreturned3) {
                        $eta_time3 = date('Y-m-d', strtotime("+1 day", strtotime($hubreturned3))).' 21:00:00';
                    }
                    $status3 = $ctc->status_id;
                    if ($ctc->status_id == 17) {
                        $preStatus = \App\SprintTaskHistory
                            ::where('sprint_id', '=', $ctc->id)
                            ->where('status_id', '!=', '17')
                            ->orderBy('id', 'desc')->first();
                        if (!empty($preStatus)) {
                            $status3 = $preStatus->status_id;
                        }
                    }

                    $secondAttempt = \App\SprintTaskHistory::where('sprint_id', '=', $ctc->sprintReattempts->reattempt_of)->orderBy('created_at', 'ASC')
                        ->get(['status_id', \DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') as created_at")]);
                    if (!empty($secondAttempt)) {

                        foreach ($secondAttempt as $secAttempt) {

                            if (in_array($secAttempt->status_id, [133])) {
                                $hubreturned2 = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }
                            if ($secAttempt->status_id == 121) {
                                $hubpickup2 = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }
                            if (in_array($secAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 131, 101, 102, 103, 104, 105, 106, 107, 108, 109, 111, 112, 131, 135, 136, 143,141])) {
                                $deliver2 = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }

                            $eta = Sprint::where('id', $ctc->sprintReattempts->reattempt_of)->first();
                            if ($hubreturned2) {
                                $eta_time2 = date('Y-m-d', strtotime("+1 day", strtotime($hubreturned2))).' 21:00:00';
                            }
                            $status2 = $eta->status_id;
                            if ($eta->status_id == 17) {
                                $preStatus = \App\SprintTaskHistory
                                    ::where('sprint_id', '=', $eta->id)
                                    ->where('status_id', '!=', '17')
                                    ->orderBy('id', 'desc')->first();
                                if (!empty($preStatus)) {
                                    $status2 = $preStatus->status_id;
                                }
                            }

                            if (in_array($secAttempt->status_id, [17,113, 114, 116, 117, 118, 132, 138, 139, 144])) {
                                if (!$check_actual) {
                                    $actual_delivery = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                                }
                            }
                            if (in_array($secAttempt->status_id, [113, 114, 116, 117, 118, 132, 138, 139, 144])) {
                                if (!$check_actual) {
                                    $actual_delivery_status = $secAttempt->status_id;

                                }
                            }

                        }
                    }

                    $firstSprint = \App\SprintReattempt::where('sprint_id', '=', $ctc->sprintReattempts->reattempt_of)->first();
                    if (!empty($firstSprint)) {
                        $firstAttempt = \App\SprintTaskHistory::where('sprint_id', '=', $firstSprint->reattempt_of)->orderBy('created_at', 'ASC')->
                        get(['status_id', \DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') as created_at")]);
                        if (!empty($firstAttempt)) {

                            foreach ($firstAttempt as $firstAttempt) {
                                if ($firstAttempt->status_id == 125) {
                                    $pickup = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                }
                                if (in_array($firstAttempt->status_id, [124])) {
                                    $hubreturned = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                }
                                if ($firstAttempt->status_id == 121) {
                                    $hubpickup = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                }
                                if (in_array($firstAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 131, 101, 102, 103, 104, 105, 106, 107, 108, 109, 111, 112, 131, 135, 136, 143,141])) {
                                    $deliver = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                }
                                /* if (in_array($firstAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 104, 105, 140, 110])) {
                                     $actual_delivery = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                 }*/
                                $eta = Sprint::where('id', $firstSprint->reattempt_of)->first();
                                if ($pickup) {
                                    $eta_time = date('Y-m-d', strtotime("+1 day", strtotime($pickup))).' 21:00:00';
                                }
                                $status = $eta->status_id;
                                if ($eta->status_id == 17) {
                                    $preStatus = \App\SprintTaskHistory
                                        ::where('sprint_id', '=', $eta->id)
                                        ->where('status_id', '!=', '17')
                                        ->orderBy('id', 'desc')->first();
                                    if (!empty($preStatus)) {
                                        $status = $preStatus->status_id;
                                    }
                                }
                            }

                        }
                    }
                }
                if ($ctc->sprintReattempts->reattempts_left == 1) {

                    $hubreturned3 = $ctc->atHubProcessing()->athub;
                    $hubpickup3 = $ctc->outForDelivery()->outdeliver;
                    $deliver3 = $ctc->deliveryTime()->delivery_time;
                    if ($hubreturned3) {
                        $eta_time3 = date('Y-m-d', strtotime("+1 day", strtotime($hubreturned3))).' 21:00:00';
                    }
                    $status3 = $ctc->status_id;
                    if ($ctc->status_id == 17) {
                        $preStatus = \App\SprintTaskHistory
                            ::where('sprint_id', '=', $ctc->id)
                            ->where('status_id', '!=', '17')
                            ->orderBy('id', 'desc')->first();
                        if (!empty($preStatus)) {
                            $status3 = $preStatus->status_id;
                        }
                    }

                    $secondAttempt = \App\SprintTaskHistory::where('sprint_id', '=', $ctc->sprintReattempts->reattempt_of)->orderBy('created_at', 'ASC')
                        ->get(['status_id', \DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') as created_at")]);
                    if (!empty($secondAttempt)) {

                        foreach ($secondAttempt as $secAttempt) {

                            if (in_array($secAttempt->status_id, [133])) {
                                $hubreturned2 = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }
                            if ($secAttempt->status_id == 121) {
                                $hubpickup2 = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }
                            if (in_array($secAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 131, 101, 102, 103, 104, 105, 106, 107, 108, 109, 111, 112, 131, 135, 136, 143,141])) {
                                $deliver2 = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }

                            $eta = Sprint::where('id', $ctc->sprintReattempts->reattempt_of)->first();
                            if ($hubreturned2) {
                                $eta_time2 = date('Y-m-d', strtotime("+1 day", strtotime($hubreturned2))).' 21:00:00';
                            }
                            $status2 = $eta->status_id;
                            if ($eta->status_id == 17) {
                                $preStatus = \App\SprintTaskHistory
                                    ::where('sprint_id', '=', $eta->id)
                                    ->where('status_id', '!=', '17')
                                    ->orderBy('id', 'desc')->first();
                                if (!empty($preStatus)) {
                                    $status2 = $preStatus->status_id;
                                }
                            }

                            if (in_array($secAttempt->status_id, [17,113, 114, 116, 117, 118, 132, 138, 139, 144])) {
                                if (!$check_actual) {
                                    $actual_delivery = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                                }
                            }
                            if (in_array($secAttempt->status_id, [113, 114, 116, 117, 118, 132, 138, 139, 144])) {
                                if (!$check_actual) {
                                    $actual_delivery_status = $secAttempt->status_id;

                                }
                            }

                        }
                    }

                    $firstSprint = \App\SprintReattempt::where('sprint_id', '=', $ctc->sprintReattempts->reattempt_of)->first();
                    if (!empty($firstSprint)) {
                        $firstAttempt = \App\SprintTaskHistory::where('sprint_id', '=', $firstSprint->reattempt_of)->orderBy('created_at', 'ASC')->
                        get(['status_id', \DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') as created_at")]);
                        if (!empty($firstAttempt)) {

                            foreach ($firstAttempt as $firstAttempt) {
                                if ($firstAttempt->status_id == 125) {
                                    $pickup = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                }
                                if (in_array($firstAttempt->status_id, [124])) {
                                    $hubreturned = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                }
                                if ($firstAttempt->status_id == 121) {
                                    $hubpickup = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                }
                                if (in_array($firstAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 131, 101, 102, 103, 104, 105, 106, 107, 108, 109, 111, 112, 131, 135, 136, 143,141])) {
                                    $deliver = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                }
                                /* if (in_array($firstAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 104, 105, 140, 110])) {
                                     $actual_delivery = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                 }*/
                                $eta = Sprint::where('id', $firstSprint->reattempt_of)->first();
                                if ($pickup) {
                                    $eta_time = date('Y-m-d', strtotime("+1 day", strtotime($pickup))).' 21:00:00';
                                }
                                $status = $eta->status_id;
                                if ($eta->status_id == 17) {
                                    $preStatus = \App\SprintTaskHistory
                                        ::where('sprint_id', '=', $eta->id)
                                        ->where('status_id', '!=', '17')
                                        ->orderBy('id', 'desc')->first();
                                    if (!empty($preStatus)) {
                                        $status = $preStatus->status_id;
                                    }
                                }
                            }

                        }
                    }
                }
                if ($ctc->sprintReattempts->reattempts_left == 2) {

                    $hubreturned2 = $ctc->atHubProcessing()->athub;
                    $hubpickup2 = $ctc->outForDelivery()->outdeliver;
                    $deliver2 = $ctc->deliveryTime()->delivery_time;

                    if ($hubreturned2) {
                        $eta_time2 = date('Y-m-d', strtotime("+1 day", strtotime($hubreturned2))).' 21:00:00';
                    }
                    $status2 = $ctc->status_id;
                    if ($ctc->status_id == 17) {
                        $preStatus = \App\SprintTaskHistory
                            ::where('sprint_id', '=', $ctc->id)
                            ->where('status_id', '!=', '17')
                            ->orderBy('id', 'desc')->first();
                        if (!empty($preStatus)) {
                            $status2 = $preStatus->status_id;
                        }
                    }

                    $secondAttempt = \App\SprintTaskHistory::where('sprint_id', '=', $ctc->sprintReattempts->reattempt_of)->orderBy('created_at', 'ASC')->
                    get(['status_id', \DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') as created_at")]);
                    if (!empty($secondAttempt)) {
                        date_default_timezone_set('America/Toronto');
                        foreach ($secondAttempt as $secAttempt) {
                            if ($secAttempt->status_id == 125) {
                                $pickup = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }
                            if (in_array($secAttempt->status_id, [124])) {
                                $hubreturned = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }
                            if ($secAttempt->status_id == 121) {
                                $hubpickup = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }
                            if (in_array($secAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 131, 101, 102, 103, 104, 105, 106, 107, 108, 109, 111, 112, 131, 135, 136, 143,141])) {
                                $deliver = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }

                            $eta = Sprint::where('id', $ctc->sprintReattempts->reattempt_of)->first();
                            if ($pickup) {
                                $eta_time = date('Y-m-d', strtotime("+1 day", strtotime($pickup))).' 21:00:00';
                            }
                            $status = $eta->status_id;
                            if ($eta->status_id == 17) {
                                $preStatus = \App\SprintTaskHistory
                                    ::where('sprint_id', '=', $eta->id)
                                    ->where('status_id', '!=', '17')
                                    ->orderBy('id', 'desc')->first();
                                if (!empty($preStatus)) {
                                    $status = $preStatus->status_id;
                                }
                            }
                            if (in_array($secAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144])) {
                                if (!$check_actual) {
                                    $actual_delivery = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                                }
                            }
                            if (in_array($secAttempt->status_id, [113, 114, 116, 117, 118, 132, 138, 139, 144])) {
                                if (!$check_actual) {
                                    $actual_delivery_status = $secAttempt->status_id;
                                }
                            }
                        }
                    }
                }
            } else {
                $hubreturned = $ctc->atHubProcessingFirst()->athub;
                $hubpickup = $ctc->outForDelivery()->outdeliver;
                $deliver = $ctc->deliveryTime()->delivery_time;
            }

            echo $ctc->id . ",";

            if ($ctc->sprintCtcTasks) {
                if ($ctc->sprintCtcTasks->taskRouteLocation) {
                    echo 'R-' . $ctc->sprintCtcTasks->taskRouteLocation->route_id . '-' . $ctc->sprintCtcTasks->taskRouteLocation->ordinal . ",";
                } else {
                    echo " " . ",";
                }
            } else {
                echo " " . ",";
            }

            if ($ctc->sprintCtcTasks) {
                if ($ctc->sprintCtcTasks->taskRouteLocation) {
                    if ($ctc->sprintCtcTasks->taskRouteLocation->joeyRoute) {
                        if ($ctc->sprintCtcTasks->taskRouteLocation->joeyRoute->joey) {
                            echo str_replace(",","-", $ctc->sprintCtcTasks->taskRouteLocation->joeyRoute->joey->first_name . ' ' . $ctc->sprintCtcTasks->taskRouteLocation->joeyRoute->joey->last_name . ' (' . $ctc->sprintCtcTasks->taskRouteLocation->joeyRoute->joey_id . ')' ) . ",";
                        } else {
                            echo "" . ",";
                        }
                    } else {
                        echo "" . ",";
                    }
                } else {
                    echo "" . ",";
                }
            } else {
                echo "" . ",";
            }

            if ($ctc->sprintVendor) {
                echo str_replace(",","-",$ctc->sprintVendor->name ) . ",";
            } else {
                echo "" . ",";
            }

            if ($ctc->sprintCtcTasks) {
                if ($ctc->sprintCtcTasks->taskContact) {
                    echo str_replace(",","-",$ctc->sprintCtcTasks->taskContact->name ) . ",";
                } else {
                    echo "" . ",";
                }
            } else {
                echo "" . ",";
            }

            if ($ctc->sprintCtcTasks) {
                if ($ctc->sprintCtcTasks->taskMerchants) {
                    echo str_replace(",","-",$ctc->sprintCtcTasks->taskMerchants->address_line2 ) . ",";
                } else {
                    echo "" . ",";
                }
            } else {
                echo "" . ",";
            }

            if ($ctc->sprintCtcTasks) {
                if ($ctc->sprintCtcTasks->task_Location) {
                    echo str_replace(",","-",$ctc->sprintCtcTasks->task_Location->postal_code )  . ",";
                } else {
                    echo "" . ",";
                }
            } else {
                echo "" . ",";
            }
            if ($ctc->sprintCtcTasks) {
                if ($ctc->sprintCtcTasks->task_Location) {
                    if ($ctc->sprintCtcTasks->task_Location->city) {
                        echo str_replace(",","-",$ctc->sprintCtcTasks->task_Location->city->name )  . ",";
                    } else {
                        echo "" . ",";
                    }
                } else {
                    echo "" . ",";
                }
            } else {
                echo "" . ",";
            }

            if ($ctc->sprintCtcTasks) {
                if ($ctc->sprintCtcTasks->taskMerchants) {
                    echo $ctc->sprintCtcTasks->taskMerchants->weight . $ctc->sprintCtcTasks->taskMerchants->weight_unit . ",";
                } else {
                    echo "" . ",";
                }
            } else {
                echo "" . ",";
            }

            echo $pickup . ",";

            echo $hubreturned . ",";


            echo $hubpickup . ",";


            echo $eta_time . ",";


            echo $deliver . ",";

            if (!empty($status)) {
                echo ($status == 13) ? "At hub - processing" . "," : str_replace(",","-",self::$status[$status])  . ",";
            } else {
                echo "" . ",";
            }
            echo $hubreturned2 . ",";
            echo $hubpickup2 . ",";
            echo $eta_time2 . ",";
            echo $deliver2 . ",";
            if (!empty($status2)) {
                echo ($status2 == 13) ? "At hub - processing" . "," : str_replace(",","-",self::$status[$status2] ) . ",";
            } else {
                echo "" . ",";
            }
            echo $hubreturned3 . ",";
            echo $hubpickup3 . ",";
            echo $eta_time3 . ",";
            echo $deliver3 . ",";
            if (!empty($status3)) {
                echo ($status3 == 13) ? "At hub - processing" . "," : str_replace(",","-",self::$status[$status3] ) . ",";
            } else {
                echo "" . ",";
            }


            if ($ctc->sprintCtcTasks) {
                if ($ctc->sprintCtcTasks->taskMerchants) {
                    if (str_contains($ctc->sprintCtcTasks->taskMerchants->tracking_id, 'old_')) {
                        echo substr($ctc->sprintCtcTasks->taskMerchants->tracking_id, strrpos($ctc->sprintCtcTasks->taskMerchants->tracking_id, '_') + 1) . ",";
                    }
                    else
                    {
                        echo $ctc->sprintCtcTasks->taskMerchants->tracking_id . ",";
                    }
                } else {
                    echo "" . ",";
                }
            } else {
                echo "" . ",";
            }
//            echo ($actual_delivery_status == 13) ? "At hub - processing"."\t" : self::$status[$actual_delivery_status] . "\t";
            if (!empty($actual_delivery_status)) {
                echo ($actual_delivery_status == 13) ? "At hub - processing" . "," : str_replace(",","-",self::$status[$actual_delivery_status])  . ",";
            } else {
                echo "" . ",";
            }
            echo $actual_delivery . ",";
            if ($ctc->sprintCtcTasks) {
                if ($ctc->sprintCtcTasks->taskMerchants) {
                    if (str_contains($ctc->sprintCtcTasks->taskMerchants->tracking_id, 'old_')) {
                        echo "https://www.joeyco.com/track-order/" . substr($ctc->sprintCtcTasks->taskMerchants->tracking_id, strrpos($ctc->sprintCtcTasks->taskMerchants->tracking_id, '_') + 1) . ",";
                    }
                    else{
                        echo "https://www.joeyco.com/track-order/" .$ctc->sprintCtcTasks->taskMerchants->tracking_id. ",";
                    }
                } else {
                    echo '' . ",";
                }
            } else {
                echo '' . ",";
            }


            echo $notes . ",";
            echo "\n";

        }

    }

}
