<?php

namespace App\Http\Controllers\Backend;

use App\Http\Traits\BasicModelFunctions;
use App\JoeyRouteLocations;
use App\Sprint;
use App\MerchantIds;
use Illuminate\Http\Request;
use App\Task;
use App\Notes;
use App\CtcVendor;
use Yajra\Datatables\Datatables;

class NewCtcController extends BackendController
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
            $query = Sprint::whereIn('id', $sprintIds)->where('deleted_at', null)->where('is_reattempt','=', 0);

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
                    return "At hub Processing";
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
                        }
                        else {
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
        $ctc_data = Sprint::where('id', $id)->where('deleted_at', null)->first();
        return backend_view('ctc.ctc_new_profile', compact('ctc_data'));
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
             ->where('deleted_at', null)->whereNotIn('status_id',[38,36])->where('is_reattempt','=',0)->get();
         */
        $sprintIds = Sprint::whereIn('creator_id', $ctcVendorIds)->where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $date . "%")
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

            $hubreturned3 = "";
            $hubpickup3 = "";
            $deliver3 = "";
            $hubreturned2 = "";
            $hubpickup2 = "";
            $deliver2 = "";
            $hubreturned = "";
            $hubpickup = "";
            $deliver = "";
            $notes = '';
            $pickup = $ctc->pickupFromStore()->pickup;
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

                            if ($secAttempt->status_id == 125) {
                                $pickup = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }
                            if (in_array($secAttempt->status_id, [124, 13])) {
                                $hubreturned = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }
                            if ($secAttempt->status_id == 121) {
                                $hubpickup = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }
                            if (in_array($secAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 131, 104, 105, 106, 107, 108, 109, 110, 111, 112, 131, 135, 136])) {
                                $deliver = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }
                        }
                    }

                    $firstSprint = \App\SprintReattempt::where('reattempt_of', '=', $ctc->sprintReattempts->reattempt_of)->first();
                    if (!empty($firstSprint)) {
                        $firstAttempt = \App\SprintTaskHistory::where('sprint_id', '=', $firstSprint->sprint_id)->orderBy('created_at', 'ASC')->
                        get(['status_id', \DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') as created_at")]);
                        if (!empty($firstAttempt)) {

                            foreach ($firstAttempt as $firstAttempt) {

                                if (in_array($firstAttempt->status_id, [124, 13])) {
                                    $hubreturned2 = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                }
                                if ($firstAttempt->status_id == 121) {
                                    $hubpickup2 = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                }
                                if (in_array($firstAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 131, 104, 105, 106, 107, 108, 109, 110, 111, 112, 131, 135, 136])) {
                                    $deliver2 = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
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
                            if (in_array($secAttempt->status_id, [124, 13])) {
                                $hubreturned = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }
                            if ($secAttempt->status_id == 121) {
                                $hubpickup = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }
                            if (in_array($secAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 131, 104, 105, 106, 107, 108, 109, 110, 111, 112, 131, 135, 136])) {
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


            echo ($current_status == 13) ? "At hub Processing" : self::$status[$current_status] . "\t";
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


}
