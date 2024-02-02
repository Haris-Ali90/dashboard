<?php

namespace App\Http\Controllers\Backend;


use App\MerchantIds;
use App\Sprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class DnrController extends BackendController
{

    /**
     * Get DNR Tracking Orders
     */
    public function getDnr(Request $request)
    {
        return backend_view('dnr-reporting.dnr_index');
    }

    /**
     * Yajra call after DNR Tracking Orders
     */
    public function dnrData(Datatables $datatables, Request $request)
    {

        $query = MerchantIds::select('merchantids.tracking_id', DB::raw("CONCAT(route_history.route_id,'-',joey_route_locations.ordinal) as route_id"),DB::raw("CONCAT(joeys.`first_name`,' ', joeys.`last_name`,' (',joeys.id,')') as joey"),
            DB::raw("CONCAT(locations.`address`,', ',locations.postal_code) as address"))
            ->Join('sprint__tasks','sprint__tasks.id','=','merchantids.task_id')
            ->Join('locations','locations.id','=','sprint__tasks.location_id')
            ->Join('joey_route_locations','joey_route_locations.task_id','=','merchantids.task_id')
            ->Join('route_history','route_history.route_location_id','=','joey_route_locations.id')
            ->leftJoin('joeys','joeys.id','=','route_history.joey_id')
            ->whereNull('joey_route_locations.deleted_at')
            ->distinct('merchantids.tracking_id');

        if(!empty($request->get('tracking_id')))
        {
            /*
                        if (strpos($request->get('tracking_id'),',') !== false) {

                            $id=explode(",",$request->get('tracking_id'));
                        }
                        else
                        {
                            $id=explode("\n",$request->get('tracking_id'));

                        }*/
            $id = preg_split('/[\ \r\n\,]+/', $request->get('tracking_id'));
            // dd($id);
            $i=0;
            $ids=[];
            foreach($id as $trackingid)
            {
                if(!empty(trim($trackingid)))
                {
                    $ids[$i]=trim($trackingid);
                    $i++;
                }

            }
            if(!empty($request->get('tracking_id')))
            {
                $query = $query->whereIn('merchantids.tracking_id',$ids);
            }
        }
        else
        {
            $query = $query->where('merchantids.tracking_id',0);
        }

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->addColumn('tracking_id', static function ($record) {
                return $record->tracking_id;
            })
            ->addColumn('route_id', static function ($record) {
                return $record->route_id;
            })
            ->addColumn('joey', static function ($record) {
                return $record->joey;
            })
            ->make(true);

    }

    /**
     * Get DNR Tracking Orders excel report
     */
    public function dnrExcel($tracking_id=null)
    {
        $query = MerchantIds::select('merchantids.tracking_id',DB::raw("CONCAT(route_history.route_id,'-',joey_route_locations.ordinal) as route_id"),DB::raw("CONCAT(joeys.`first_name`,' ', joeys.`last_name`,' (',joeys.id,')') as joey"),
            DB::raw("CONCAT(locations.`address`,', ',locations.postal_code) as address"))
            ->Join('sprint__tasks','sprint__tasks.id','=','merchantids.task_id')
            ->Join('locations','locations.id','=','sprint__tasks.location_id')
            ->Join('joey_route_locations','joey_route_locations.task_id','=','merchantids.task_id')
            ->Join('route_history','route_history.route_location_id','=','joey_route_locations.id')
            ->leftJoin('joeys','joeys.id','=','route_history.joey_id')
            ->whereNull('joey_route_locations.deleted_at')->distinct('merchantids.tracking_id');

        if(!empty($tracking_id))
        {

            /* if (strpos($tracking_id,',') !== false) {

                 $id=explode(",",$tracking_id);
             }
             else
             {
                 $id=explode("\n",$tracking_id);

             }*/
            $id = explode(",",$tracking_id);
            $i=0;
            $ids=[];
            foreach($id as $trackingid)
            {
                if(!empty(trim($trackingid)))
                {
                    $ids[$i]=trim($trackingid);
                    $i++;
                }

            }
            if(!empty($tracking_id))
            {
                $query = $query->whereIn('merchantids.tracking_id',$ids);

            }
        }
        else
        {
            $query = $query->where('merchantids.tracking_id',0);
        }
        $query= $query->get();

        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=DNR Report.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo "Tracking #\tRoute #\tJoey\tAddress\t\n";

        // $ctc_array[] = ['Joeyco Order', 'Route', 'Joey', 'Store Name', 'Customer Name', 'Customer Address', 'Postal Code', 'City Name', 'Weight', 'Pickup From Store', 'At Hub Processing', 'Out For Delivery', 'Estimated Customer delivery time', 'Actual Customer delivery time', 'Shipment tracking #', 'Shipment tracking link', 'Shipment Delivery Status', 'JoyeCo Notes / Comments', 'Returned to HUB 2', '2nd Attempt Pick up', '2nd Attempt Delivery', 'Returned to HUB 3', '3rd Attempt Pick up', '3rd Attempt Delivery'];

        foreach ($query as $data) {

            echo $data->tracking_id. "\t";
            echo $data->route_id . "\t";
            echo $data->joey . "\t";
            echo $data->address . "\t";
            echo "\n";
        }

    }



}
