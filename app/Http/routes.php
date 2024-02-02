<?php
/*

  |--------------------------------------------------------------------------

  | Application Routes


  |--------------------------------------------------------------------------

  | 

  | Here is where you can register all of the routes for an application.

  | It's a breeze. Simply tell Laravel the URIs it should respond to

  | and give it the controller to call when that URI is requested.

  |

 */
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');


Route::get('/', function () {
   return redirect( 'login' );
});

Route::get('testnewmontreal/totalcards/{date?}/{type?}', 'Backend\NewMontrealController@montrealTotalCardsForLoop')->name('testnewmontreal.totalcards');

###Right Permissions###
Route::get('dashboard-permissions', 'Backend\DashboardPermissionController@getDashboardPermissions')->name('dashboard-permissions');

###Right Permissions###
Route::get('domain-right', 'Backend\DashboardPermissionController@getAllDomainRights')->name('domain-right');

Route::get('termsconditions', 'UserController@termsConditions');

Route::group(['middleware' => 'web', 'namespace' => 'Backend'], function ()
{

    ### ajax based file download route
    Route::get('/download-file', function () {
        // getting file path
        $file_path  = public_path().'/'.request()->file_path;
        // getting file name
        $file_name =explode('/',$file_path);
        $file_name = explode('-',end($file_name))[0];
        // getting file extension
        $file_extension = explode('.',$file_path);
        $file_extension = end($file_extension);
        return response()->download($file_path, $file_name.'.'.$file_extension);
    })->name('download-file');
  
    Route::match(['GET', 'POST'], 'login', 'Auth\AuthController@adminLogin')->name('login');

    Route::match(['GET', 'POST'], 'reset-password/{token?}', 'Auth\PasswordController@resetPasswordAction');

    Route::post('reset-password-finally', 'Auth\PasswordController@reset');


    ###Reset Password###
    Route::post('/password/email', 'Auth\PasswordController@send_reset_link_email')->name('password.email');
    Route::post('/password/reset', 'Auth\PasswordController@reset_password_update')->name('reset.password.update');
    Route::get('/password/reset', 'Auth\PasswordController@showLinkRequestForm')->name('password.request');
    Route::get('/password/reset/{email}/{token}/{role_id}', 'Auth\PasswordController@reset_password_from_show')->name('password.reset');

    Route::post('google/auth','Auth\AuthController@postgoogleAuth');
    Route::get('google-auth','Auth\AuthController@getgoogleAuth');
    Route::post('verify/code','Auth\AuthController@postverifycode');
    Route::get('verify-code','Auth\AuthController@getverifycode');
    Route::post('type/auth','Auth\AuthController@posttypeauth');
    Route::get( 'type-auth','Auth\AuthController@getType');

    Route::group(['middleware' => ['admin']], function () 
    {
        Route::post('logout', 'Auth\AuthController@logout');

        ###Amazone Statistics###
        Route::get('dashboard/{graph?}', 'DashboardController@getIndex');

        ### Statistics ###
        Route::get('statistics', 'StatisticsController@getStatistics')->name('statistics.index');
        Route::get('statistics/day/otd', 'StatisticsController@getDayOtd')->name('statistics-day-otd.index');
        Route::get('statistics/week/otd', 'StatisticsController@getWeekOtd')->name('statistics-week-otd.index');
        Route::get('statistics/month/otd', 'StatisticsController@getMonthOtd')->name('statistics-month-otd.index');
        Route::get('statistics/year/otd', 'StatisticsController@getYearOtd')->name('statistics-year-otd.index');
        Route::get('statistics/all/counts', 'StatisticsController@getAllCounts')->name('statistics-all-counts.index');
        Route::get('statistics/inprogress', 'StatisticsController@getInprogress')->name('statistics-inprogress.index');
        Route::get('statistics/failed/counts', 'StatisticsController@getFailedCounts')->name('statistics-failed-counts.index');
        Route::get('statistics/custom/counts', 'StatisticsController@getCustomCounts')->name('statistics-custom-counts.index');
        Route::get('statistics/manual/counts', 'StatisticsController@getManualCounts')->name('statistics-manual-counts.index');
		 Route::get('statistics/route/counts', 'StatisticsController@getRouteDataCounts')->name('statistics-route-counts.index');
        Route::get('statistics/on-time/counts', 'StatisticsController@getOnTimeCounts')->name('statistics-on-time-counts.index');
        Route::get('statistics/top-ten/joeys', 'StatisticsController@getTopTenJoeys')->name('statistics-top-ten-joeys.index');
        Route::get('statistics/least-ten/joeys', 'StatisticsController@getLeastTenJoeys')->name('statistics-least-ten-joeys.index');
        Route::get('statistics/graph', 'StatisticsController@getGraph')->name('statistics-graph.index');
        Route::get('statistics/brooker', 'StatisticsController@getBroker')->name('statistics-brooker.index');


        ### Vendor Mangement View ###
        Route::get('vendor-statics', 'VendorManagementController@getStatistics')->name('vendor-statics.index');
        Route::get('vendor-statics/day/otd', 'VendorManagementController@getDayOtd')->name('vendor-statics-day-otd.index');
        Route::get('vendor-statics/week/otd', 'VendorManagementController@getWeekOtd')->name('vendor-statics-week-otd.index');
        Route::get('vendor-statics/month/otd', 'VendorManagementController@getMonthOtd')->name('vendor-statics-month-otd.index');
        Route::get('vendor-statics/year/otd', 'VendorManagementController@getYearOtd')->name('vendor-statics-year-otd.index');
        Route::get('vendor-statics/all/counts', 'VendorManagementController@getAllCounts')->name('vendor-statics-all-counts.index');
        Route::get('vendor-statics/inprogress', 'VendorManagementController@getInprogress')->name('vendor-statics-inprogress.index');
        Route::get('vendor-statics/failed/counts', 'VendorManagementController@getFailedCounts')->name('vendor-statics-failed-counts.index');
        Route::get('vendor-statics/custom/counts', 'VendorManagementController@getCustomCounts')->name('vendor-statics-custom-counts.index');
        Route::get('vendor-statics/manual/counts', 'VendorManagementController@getManualCounts')->name('vendor-statics-manual-counts.index');
        Route::get('vendor-statics/route/counts', 'VendorManagementController@getRouteDataCounts')->name('vendor-statics-route-counts.index');
        Route::get('vendor-statics/on-time/counts', 'VendorManagementController@getOnTimeCounts')->name('vendor-statics-on-time-counts.index');
        Route::get('vendor-statics/top-ten/joeys', 'VendorManagementController@getTopTenJoeys')->name('vendor-statics-top-ten-joeys.index');
        Route::get('vendor-statics/least-ten/joeys', 'VendorManagementController@getLeastTenJoeys')->name('vendor-statics-least-ten-joeys.index');
        Route::get('vendor-statics/graph', 'VendorManagementController@getGraph')->name('vendor-statics-graph.index');
        Route::get('vendor-statics/brooker', 'VendorManagementController@getBroker')->name('vendor-statics-brooker.index');

        Route::get('statistics/orders', 'StatisticsController@getOrders')->name('statistics-order.index');
        Route::get('statistics/failed/orders', 'StatisticsController@getFailedOrders')->name('statistics-failed-order.index');
        Route::get('statistics/route/detail', 'StatisticsController@getRouteDataDetail')->name('statistics-route-detail.index');

        ### Joey Management ###
        Route::get('joey-management', 'JoeyController@getJoeyManagement')->name('joey-management.index');
        Route::get('joey-management/joey-count', 'JoeyController@getAllJoeyCounts')->name('joey-management-joey-count.index');
        Route::post('joey-management/joey-count-onduty', 'JoeyController@getOnDutyJoeyCounts')->name('joey-management-joey-count.onduty');
        Route::get('joey-management/orders-count', 'JoeyController@getAllOrderCounts')->name('joey-management-orders-count.index');
        Route::get('joey-management/otd-day', 'JoeyController@getJoeyManagementOtdDay')->name('joey-management-otd-day.index');
        Route::get('joey-management/otd-week', 'JoeyController@getJoeyManagementOtdWeek')->name('joey-management-otd-week.index');
        Route::get('joey-management/otd-month', 'JoeyController@getJoeyManagementOtdMonth')->name('joey-management-otd-month.index');
        Route::get('joey-management/list', 'JoeyController@getAllJoeysList')->name('joey-management-list.index');
        Route::get('joey-management/order-list', 'JoeyController@getAllOrderList')->name('joey-management-order-list.index');
        Route::get('joey-management/all-joeys-otd', 'JoeyController@getAllJoeysOTD')->name('joey-management-all-joeys-otd.index');

        ### Brooker Management ###
        Route::get('brooker-management', 'BrookerController@getBrookerManagement')->name('brooker-management.index');
        Route::get('brooker-management/brooker-count', 'BrookerController@getAllBrookerCounts')->name('brooker-management-brooker-count.index');
        Route::get('brooker-management/joey-count', 'BrookerController@getAllJoeyCounts')->name('brooker-management-joey-count.index');
        Route::post('brooker-management/joey-count-onduty', 'BrookerController@getOnDutyJoeyCounts')->name('brooker-management-joey-count.onduty');
        Route::get('brooker-management/orders-count', 'BrookerController@getAllOrderCounts')->name('brooker-management-orders-count.index');
        Route::get('brooker-management/otd-day', 'BrookerController@getBrookerManagementOtdDay')->name('brooker-management-otd-day.index');
        Route::get('brooker-management/otd-week', 'BrookerController@getBrookerManagementOtdWeek')->name('brooker-management-otd-week.index');
        Route::get('brooker-management/otd-month', 'BrookerController@getBrookerManagementOtdMonth')->name('brooker-management-otd-month.index');
        Route::get('brooker-management/list', 'BrookerController@getAllJoeysList')->name('brooker-management-list.index');
        Route::get('brooker-management/brooker-list', 'BrookerController@getAllBrookerList')->name('brooker-management-brooker-list.index');
		Route::get('brooker-management/all-brooker-otd', 'BrookerController@getAllBrookerOTD')->name('brooker-management-all-brooker-otd.index');

        ### Brooker Statistics ###
        Route::get('statistics/brooker-detail', 'BrookerController@getStatistics')->name('statistics-brooker-detail.index');
        Route::get('statistics/brooker-detail/day/otd', 'BrookerController@getDayOtd')->name('statistics-brooker-detail-day-otd.index');
        Route::get('statistics/brooker-detail/week/otd', 'BrookerController@getWeekOtd')->name('statistics-brooker-detail-week-otd.index');
        Route::get('statistics/brooker-detail/month/otd', 'BrookerController@getMonthOtd')->name('statistics-brooker-detail-month-otd.index');
        Route::get('statistics/brooker-detail/year/otd', 'BrookerController@getYearOtd')->name('statistics-brooker-detail-year-otd.index');
        Route::get('statistics/brooker-detail/all/counts', 'BrookerController@getAllCounts')->name('statistics-brooker-detail-all-counts.index');
        Route::get('statistics/brooker-detail/failed/counts', 'BrookerController@getFailedCounts')->name('statistics-brooker-detail-failed-counts.index');
        Route::get('statistics/brooker-detail/custom/counts', 'BrookerController@getCustomCounts')->name('statistics-brooker-detail-custom-counts.index');
        Route::get('statistics/brooker-detail/manual/counts', 'BrookerController@getManualCounts')->name('statistics-brooker-detail-manual-counts.index');
		Route::get('statistics/brooker-detail/route/counts', 'BrookerController@getRouteDataCounts')->name('statistics-brooker-detail-route-counts.index');
        Route::get('statistics/brooker-detail/on-time/counts', 'BrookerController@getOnTimeCounts')->name('statistics-brooker-detail-on-time-counts.index');
        Route::get('statistics/brooker-detail/top-ten/joeys', 'BrookerController@getTopTenJoeys')->name('statistics-brooker-detail-top-ten-joeys.index');
        Route::get('statistics/brooker-detail/least-ten/joeys', 'BrookerController@getLeastTenJoeys')->name('statistics-brooker-detail-least-ten-joeys.index');
        Route::get('statistics/brooker-detail/graph', 'BrookerController@getGraph')->name('statistics-brooker-detail-graph.index');
        Route::get('statistics/brooker-detail/brooker', 'BrookerController@getBroker')->name('statistics-brooker-detail-brooker.index');
        Route::get('statistics/brooker-detail/orders', 'BrookerController@getOrders')->name('statistics-brooker-detail-order.index');
        Route::get('statistics/brooker-detail/failed/orders', 'BrookerController@getFailedOrders')->name('statistics-brooker-detail-failed-order.index');
		 Route::get('statistics/brooker-detail/all-joeys-otd', 'BrookerController@getAllJoeysOTD')->name('statistics-brooker-detail-all-joeys-otd.index');

        ### Joey Statistics ###
        Route::get('statistics/joey-detail/', 'JoeyController@getStatistics')->name('statistics-joey-detail.index');
        Route::get('statistics/joey-detail/day/otd', 'JoeyController@getDayOtd')->name('statistics-joey-detail-day-otd.index');
        Route::get('statistics/joey-detail/week/otd', 'JoeyController@getWeekOtd')->name('statistics-joey-detail-week-otd.index');
        Route::get('statistics/joey-detail/month/otd', 'JoeyController@getMonthOtd')->name('statistics-joey-detail-month-otd.index');
        Route::get('statistics/joey-detail/year/otd', 'JoeyController@getYearOtd')->name('statistics-joey-detail-year-otd.index');
        Route::get('statistics/joey-detail/all/counts', 'JoeyController@getAllCounts')->name('statistics-joey-detail-all-counts.index');
        Route::get('statistics/joey-detail/manual/counts', 'JoeyController@getManualCounts')->name('statistics-joey-detail-manual-counts.index');
        Route::get('statistics/joey-detail/joey/time', 'JoeyController@getJoeysTime')->name('statistics-joey-detail-joey-time.index');
        Route::get('statistics/joey-detail/graph', 'JoeyController@getGraph')->name('statistics-joey-detail-graph.index');
        Route::get('statistics/joey-detail/orders', 'JoeyController@getOrders')->name('statistics-joey-detail-order.index');
        Route::get('statistics/joey-detail/failed/orders', 'JoeyController@getFailedOrders')->name('statistics-joey-detail-failed-order.index');

         Route::get('inbound/', 'InboundController@getInbound')->name('statistics-inbound.index');
        Route::get('inbound/data', 'InboundController@getInboundData')->name('statistics-inbound-data.index');
        Route::get('inbound/setup-time', 'InboundController@inboundSetupTime')->name('statistics-setup-time.index');
        Route::get('inbound/sorting-time', 'InboundController@inboundSortingTime')->name('statistics-sorting-time.index');
        Route::post('inbound/warehousesorter/update', 'InboundController@wareHouseSorterUpdate')->name('statistics-inbound.wareHouseSorterUpdate');

        Route::get('outbound/', 'OutboundController@getOutbound')->name('statistics-outbound.index');
        Route::get('outbound/data', 'OutboundController@getOutboundData')->name('statistics-outbound-data.index');
        Route::get('outbound/dispensing-time', 'OutboundController@outboundDispensingTime')->name('statistics-dispensing-time.index');
        Route::post('outbound/warehousesorter/update', 'OutboundController@wareHouseSorterUpdate')->name('statistics-outbound.wareHouseSorterUpdate');

        Route::get('warehouse/summary', 'SummaryController@getSummary')->name('warehouse-summary.index');
        Route::get('warehouse/summary/data', 'SummaryController@getSummaryData')->name('warehouse-summary-data.index');


        Route::get('newmontreal/totalcards/{date?}/{type?}', 'NewMontrealController@montrealTotalCards')->name('newmontreal.totalcards');
        Route::get('newmontreal/inprogress/{date?}/{type?}', 'NewMontrealController@montrealInProgressOrders')->name('newmontreal.inprogress');
        Route::get('newmontreal/mainfestcards/{date?}', 'NewMontrealController@getMainfestOrderData')->name('newmontreal.mainfestcards');
        Route::get('newmontreal/failedcards/{date?}', 'NewMontrealController@getFailedOrderData')->name('newmontreal.failedcards');
        Route::get('newmontreal/customroutecards/{date?}', 'NewMontrealController@getCustomRouteData')->name('newmontreal.customroutecards');
        Route::get('newmontreal/yesterdaycards/{date?}', 'NewMontrealController@getYesterdayOrderData')->name('newmontreal.yesterdaycards');

        Route::get('newottawa/totalcards/{date?}/{type?}', 'NewOttawaController@ottawaTotalCards')->name('newottawa.totalcards');
        Route::get('newottawa/inprogress/{date?}/{type?}', 'NewOttawaController@ottawaInProgressOrders')->name('newottawa.inprogress');
        Route::get('newottawa/mainfestcards/{date?}', 'NewOttawaController@getMainfestOrderData')->name('newottawa.mainfestcards');
        Route::get('newottawa/failedcards/{date?}', 'NewOttawaController@getFailedOrderData')->name('newottawa.failedcards');
        Route::get('newottawa/customroutecards/{date?}', 'NewOttawaController@getCustomRouteData')->name('newottawa.customroutecards');
        Route::get('newottawa/yesterdaycards/{date?}', 'NewOttawaController@getYesterdayOrderData')->name('newottawa.yesterdaycards');

        Route::get('new/ctc/totalcards/{date?}/{type?}', 'CtcEntriesController@ctcTotalCards')->name('new-ctc.totalcards');
        Route::get('new/ctc/inprogress/{date?}/{type?}', 'CtcEntriesController@ctcInProgressOrders')->name('new-ctc.inprogress');
        Route::get('new/ctc/customroutecards/{date?}', 'CtcEntriesController@getCtcCustomRouteData')->name('new-ctc.customroutecards');
        Route::get('new/ctc/yesterdaycards/{date?}', 'CtcEntriesController@getCtcYesterdayOrderData')->name('new-ctc.yesterdaycards');

		Route::get('toronto/totalcards/{startdate?}/{enddate?}/{type?}/{vendor_id?}', 'BorderlessController@boradlessTotalCards')->name('borderless.totalcards');
        Route::get('toronto/inprogress/{date?}/{type?}', 'BorderlessController@boradlessInProgressOrders')->name('borderless.inprogress');
        Route::get('toronto/customroutecards/{date?}/{vendor_id?}', 'BorderlessController@getBoradlessCustomRouteData')->name('borderless.customroutecards');
        Route::get('toronto/yesterdaycards/{date?}', 'BorderlessController@getBoradlessYesterdayOrderData')->name('borderless.yesterdaycards');

		 Route::get('vancouver/totalcards/{date?}/{type?}', 'VancouverController@vancouverTotalCards')->name('vancouver.totalcards');
        Route::get('vancouver/customroutecards/{date?}', 'VancouverController@getVancovuerCustomRouteData')->name('vancouver.customroutecards');
        Route::get('vancouver/inprogress/{date?}/{type?}', 'VancouverController@vancouverInProgressOrders')->name('vancouver.inprogress');

        //Ottawa Walmart Dashboard
        Route::get('ottawa-dashboard/totalcards/{date?}/{type?}', 'OttawaDashboardController@ottawaTotalCards')->name('ottawa-dashboard.totalcards');
        Route::get('ottawa-dashboard/customroutecards/{date?}', 'OttawaDashboardController@getOttawaCustomRouteData')->name('ottawa-dashboard.customroutecards');
        Route::get('ottawa-dashboard/inprogress/{date?}/{type?}', 'OttawaDashboardController@ottawaInProgressOrders')->name('ottawa-dashboard.inprogress');

		Route::get('newyork-dashboard/totalcards/{date?}/{type?}', 'NewYorkController@newyorkTotalCards')->name('ottawa-dashboard.totalcards');
        Route::get('newyork-dashboard/customroutecards/{date?}', 'NewYorkController@getnewyorkCustomRouteData')->name('ottawa-dashboard.customroutecards');
        Route::get('newyork-dashboard/inprogress/{date?}/{type?}', 'NewYorkController@newyorkInProgressOrders')->name('ottawa-dashboard.inprogress');

        Route::get('scarborough/totalcards/{startdate?}/{enddate?}/{type?}', 'ScarBoroughController@ScarBoroughTotalCards')->name('scarborough.totalcards');
        Route::get('scarborough/customroutecards/{date?}', 'ScarBoroughController@getScarBoroughCustomRouteData')->name('scarborough.customroutecards');
        Route::get('scarborough/inprogress/{date?}/{type?}', 'ScarBoroughController@ScarBoroughInProgressOrders')->name('scarborough.inprogress');

		Route::get('walmart/e-commerce/totalcards/{date?}/{type?}/{vendor_id?}', 'WalmartController@walmartEcommerceTotalCards')->name('walmart-ecommerce.totalcards');



        Route::group(['middleware' => ['PermissionHandler']], function () 
        {


			   Route::get('montreal-dashboard', 'MontrealController@getNewMontreal')->name('montreal-dashboard.index');
             Route::get('montreal-dashboard/data', 'MontrealController@montrealNewData')->name('montreal-dashboard.data');
             Route::get('montreal/new/detail/{id}', 'MontrealController@montrealNewProfile')->name('montreal-new.profile');

             /*Route::get('newmontreal/totalcards/{date?}/{type?}', 'NewMontrealController@montrealTotalCards')->name('newmontreal.totalcards');
             Route::get('newmontreal/mainfestcards/{date?}', 'NewMontrealController@getMainfestOrderData')->name('newmontreal.mainfestcards');
             Route::get('newmontreal/failedcards/{date?}', 'NewMontrealController@getFailedOrderData')->name('newmontreal.failedcards');
             Route::get('newmontreal/customroutecards/{date?}', 'NewMontrealController@getCustomRouteData')->name('newmontreal.customroutecards');
             Route::get('newmontreal/yesterdaycards/{date?}', 'NewMontrealController@getYesterdayOrderData')->name('newmontreal.yesterdaycards');*/

			Route::get('route-list/{date?}/{type?}', 'NewMontrealController@getRoutes')->name('newmontreal.route-list');
            Route::get('joey-list/{date?}/{type?}', 'NewMontrealController@getJoeys')->name('newmontreal.joey-list');

             ### Montreal Cards ###
             Route::get('newmontreal-dashboard', 'NewMontrealController@getMontrealCards')->name('newmontreal-dashboard.index');
             ### New Montreal Dashboard ###
             Route::get('newmontreal/data', 'NewMontrealController@montrealData')->name('newmontreal.data');
             Route::get('newmontreal', 'NewMontrealController@getMontreal')->name('newmontreal.index');
             Route::get('newmontreal/list/{date?}', 'NewMontrealController@montrealExcel')->name('newexport_Montreal.excel');
             ### Montreal Sorted ###
             Route::get('newmontreal/sorted', 'NewMontrealController@getSorter')->name('newmontreal-sort.index');
             Route::get('newmontreal/sorted/data', 'NewMontrealController@montrealSortedData')->name('newmontrealSorted.data');
             Route::get('newmontreal/sorted/list/{date?}', 'NewMontrealController@montrealSortedExcel')->name('newexport_MontrealSorted.excel');
             ### Montreal Hub ###
             Route::get('newmontreal/picked/up', 'NewMontrealController@getMontrealhub')->name('newmontreal-pickup.index');
             Route::get('newmontreal/picked/up/data', 'NewMontrealController@montrealPickedUpData')->name('newmontrealPickedUp.data');
             Route::get('newmontreal/picked/up/list/{date?}', 'NewMontrealController@montrealPickedupExcel')->name('newexport_MontrealPickedUp.excel');
             ### Montreal Not Scan ###
             Route::get('newmontreal/not/scan', 'NewMontrealController@getMontnotscan')->name('newmontreal-not-scan.index');
             Route::get('newmontreal/not/scan/data', 'NewMontrealController@montrealNotScanData')->name('newmontrealNotScan.data');
             Route::get('newmontreal/not/scan/list/{date?}', 'NewMontrealController@montrealNotscanExcel')->name('newexport_MontrealNotScan.excel');
             ### Montreal Delivered ###
             Route::get('newmontreal/delivered', 'NewMontrealController@getMontdelivered')->name('newmontreal-delivered.index');
             Route::get('newmontreal/delivered/data', 'NewMontrealController@montrealDeliveredData')->name('newmontrealDelivered.data');
             Route::get('newmontreal/delivered/list/{date?}', 'NewMontrealController@montrealDeliveredExcel')->name('newexport_MontrealDelivered.excel');
             ### Montreal Returned ###
             Route::get('newmontreal/returned', 'NewMontrealController@getMontreturned')->name('newmontreal-returned.index');
             Route::get('newmontreal/returned/data', 'NewMontrealController@montrealReturnedData')->name('newmontrealReturned.data');
             Route::get('newmontreal/returned/list/{date?}', 'NewMontrealController@montrealReturnedExcel')->name('newexport_MontrealReturned.excel');
             ### Montreal Not Returned At Hub ###
             Route::get('newmontreal/returned-not-hub', 'NewMontrealController@getMontNotreturned')->name('newmontreal-notreturned.index');
             Route::get('newmontreal/returned-not-hub/data', 'NewMontrealController@montrealNotReturnedData')->name('newmontrealNotReturned.data');
             Route::get('newmontreal/returned-not-hub/list/{date?}', 'NewMontrealController@montrealNotReturnedExcel')->name('newexport_MontrealNotReturned.excel');
             Route::get('newmontreal/returned-not-hub/tracking/list/{date?}', 'NewMontrealController@montrealNotReturnedExcelTrackingIds')->name('newexport_MontrealNotReturned_Tracking.excel');

             ### Montreal Custom Route ###
             Route::get('newmontreal/custom-route', 'NewMontrealController@getMontCustomRoute')->name('newmontreal-custom-route.index');
             Route::get('newmontreal/custom-route/data', 'NewMontrealController@montrealCustomRouteData')->name('newmontrealCustomRoute.data');
             Route::get('newmontreal/custom-route/list/{date?}', 'NewMontrealController@montrealCustomRouteExcel')->name('newexport_MontrealCustomRoute.excel');

             ### Montreal Route Information ###
             Route::get('newmontreal/route-info', 'NewMontrealController@getRouteinfo')->name('newmontreal-route-info.index');
             Route::get('newmontreal/route-info/list/{date?}', 'NewMontrealController@montrealRouteinfoExcel')->name('newexport_MontrealRouteInfo.excel');
             Route::get('newmontreal/route/{di}/edit/hub/{id}', 'NewMontrealController@montrealHubRouteEdit')->name('newmontreal_route.detail');
             Route::post('newmontreal/route-details/flag-history-model-html-render', 'NewMontrealController@flagHistoryModelHtmlRender')->name('newmontreal_route.route-details.flag-history-model-html-render');
             Route::get('newmontreal/route/orders/trackingid/{id}/details','NewMontrealController@getMontrealtrackingorderdetails')->name('newmontrealinfo_route.detail');
			 Route::post('newmontreal/route-info/add-note', 'NewMontrealController@addNote')->name('newmontreal-route-info.addNote');
             Route::get('newmontreal/route-info/get-notes', 'NewMontrealController@getNotes')->name('newmontreal-route-info.getNotes');

             ### Montreal Profile ###
             Route::get('newmontreal/detail/{id}', 'NewMontrealController@montrealProfile')->name('newmontreal.profile');
             Route::get('newmontreal/sorted/detail/{id}', 'NewMontrealController@montrealsortedDetail')->name('newmontreal_sorted.profile');
             Route::get('newmontreal/pickup/detail/{id}', 'NewMontrealController@montrealpickupDetail')->name('newmontreal_pickup.profile');
             Route::get('newmontreal/notscan/detail/{id}', 'NewMontrealController@montrealnotscanDetail')->name('newmontreal_notscan.profile');
             Route::get('newmontreal/delivered/detail/{id}', 'NewMontrealController@montrealdeliveredDetail')->name('newmontreal_delivered.profile');
             Route::get('newmontreal/returned/detail/{id}', 'NewMontrealController@montrealreturnedDetail')->name('newmontreal_returned.profile');
             Route::get('newmontreal/returned-not-hub/detail/{id}', 'NewMontrealController@montrealNotReturnedDetail')->name('newmontreal_notreturned.profile');
             Route::get('newmontreal/custom-route/detail/{id}', 'NewMontrealController@montrealCustomRouteDetail')->name('newmontreal_customroute.profile');

        ### Montreal Dashboard ###
        Route::get('montreal/data', 'MontrealController@montrealData')->name('montreal.data');
        Route::get('montreal', 'MontrealController@getMontreal')->name('montreal.index');
        Route::get('montreal/list/{date?}', 'MontrealController@montrealExcel')->name('export_Montreal.excel');
        ### Montreal Sorted ###
        Route::get('montreal/sorted', 'MontrealController@getSorter')->name('montreal-sort.index');
        Route::get('montreal/sorted/data', 'MontrealController@montrealSortedData')->name('montrealSorted.data');
        Route::get('montreal/sorted/list/{date?}', 'MontrealController@montrealSortedExcel')->name('export_MontrealSorted.excel');
        ### Montreal Hub ###
        Route::get('montreal/picked/up', 'MontrealController@getMontrealhub')->name('montreal-pickup.index');
        Route::get('montreal/picked/up/data', 'MontrealController@montrealPickedUpData')->name('montrealPickedUp.data');
        Route::get('montreal/picked/up/list/{date?}', 'MontrealController@montrealPickedupExcel')->name('export_MontrealPickedUp.excel');
        ### Montreal Not Scan ###
        Route::get('montreal/not/scan', 'MontrealController@getMontnotscan')->name('montreal-not-scan.index');
        Route::get('montreal/not/scan/data', 'MontrealController@montrealNotScanData')->name('montrealNotScan.data');
        Route::get('montreal/not/scan/list/{date?}', 'MontrealController@montrealNotscanExcel')->name('export_MontrealNotScan.excel');
        ### Montreal Delivered ###
        Route::get('montreal/delivered', 'MontrealController@getMontdelivered')->name('montreal-delivered.index');
        Route::get('montreal/delivered/data', 'MontrealController@montrealDeliveredData')->name('montrealDelivered.data');
        Route::get('montreal/delivered/list/{date?}', 'MontrealController@montrealDeliveredExcel')->name('export_MontrealDelivered.excel');
        ### Montreal Profile ###
        Route::get('montreal/detail/{id}', 'MontrealController@montrealProfile')->name('montreal.profile');
        Route::get('montreal/sorted/detail/{id}', 'MontrealController@montrealsortedDetail')->name('montreal_sorted.profile');
        Route::get('montreal/pickup/detail/{id}', 'MontrealController@montrealpickupDetail')->name('montreal_pickup.profile');
        Route::get('montreal/notscan/detail/{id}', 'MontrealController@montrealnotscanDetail')->name('montreal_notscan.profile');
        Route::get('montreal/delivered/detail/{id}', 'MontrealController@montrealdeliveredDetail')->name('montreal_delivered.profile');
        ### Montreal Route Information ###
        Route::get('montreal/route-info', 'MontrealController@getRouteinfo')->name('montreal-route-info.index');
        Route::get('montreal/route-info/list/{date?}', 'MontrealController@montrealRouteinfoExcel')->name('export_MontrealRouteInfo.excel');

        Route::get('montreal/route/{di}/edit/hub/{id}', 'MontrealController@montrealHubRouteEdit')->name('montreal_route.detail');
        //Route::get('route/{id}/delete/hub','MontrealController@montrealDeleteRoute');
        Route::get('montreal/route/orders/trackingid/{id}/details','MontrealController@getMontrealtrackingorderdetails')->name('montrealinfo_route.detail');
 Route::get('testmontreal/route/orders/trackingid/{id}/details','MontrealController@getTestMontrealtrackingorderdetails')->name('testmontrealinfo_route.detail');



             ### Montreal Returned ###
             Route::get('montreal/returned', 'MontrealController@getMontreturned')->name('montreal-returned.index');
             Route::get('montreal/returned/data', 'MontrealController@montrealReturnedData')->name('montrealReturned.data');
             Route::get('montreal/returned/list/{date?}', 'MontrealController@montrealReturnedExcel')->name('export_MontrealReturned.excel');
             Route::get('montreal/returned/detail/{id}', 'MontrealController@montrealreturnedDetail')->name('montreal_returned.profile');



             Route::get('direct/montreal/data', 'MontrealController@directMontrealData')->name('directmontreal.data');
             Route::get('direct/montreal', 'MontrealController@getDirectMontreal')->name('directmontreal.index');



           /*  Route::get('newottawa/totalcards/{date?}/{type?}', 'NewOttawaController@ottawaTotalCards')->name('newottawa.totalcards');
             Route::get('newottawa/mainfestcards/{date?}', 'NewOttawaController@getMainfestOrderData')->name('newottawa.mainfestcards');
             Route::get('newottawa/failedcards/{date?}', 'NewOttawaController@getFailedOrderData')->name('newottawa.failedcards');
             Route::get('newottawa/customroutecards/{date?}', 'NewOttawaController@getCustomRouteData')->name('newottawa.customroutecards');
             Route::get('newottawa/yesterdaycards/{date?}', 'NewOttawaController@getYesterdayOrderData')->name('newottawa.yesterdaycards');*/

			Route::get('ottawa-route-list/{date?}/{type?}', 'NewOttawaController@getRoutes')->name('newottawa.ottawa-route-list');
             Route::get('ottawa-joey-list/{date?}/{type?}', 'NewOttawaController@getJoeys')->name('newottawa.ottawa-joey-list');

             ### Ottawa Cards ###
             Route::get('newottawa-dashboard', 'NewOttawaController@getOttawaCards')->name('newottawa-dashboard.index');
             ### New Ottawa Dashboard ###
             Route::get('newottawa', 'NewOttawaController@getOttawa')->name('newottawa.index');
             Route::get('newottawa/data', 'NewOttawaController@ottawaData')->name('newottawa.data');
             Route::get('newottawa/list/{date?}', 'NewOttawaController@ottawaExcel')->name('newexport_Ottawa.excel');
             ### Ottawa Sorted ###
             Route::get('newottawa/sorted', 'NewOttawaController@getOttawatsort')->name('newottawa-sort.index');
             Route::get('newottawa/sorted/data', 'NewOttawaController@ottawaSortedData')->name('newottawaSorted.data');
             Route::get('newottawa/sorted/list/{date?}', 'NewOttawaController@ottawaSortedExcel')->name('newexport_OttawaSorted.excel');
             ### Ottawa Picked Up ###
             Route::get('newottawa/picked/up', 'NewOttawaController@getOttawathub')->name('newottawa-pickup.index');
             Route::get('newottawa/picked/up/data', 'NewOttawaController@ottawaPickedUpData')->name('newottawaPickedUp.data');
             Route::get('newottawa/picked/up/list/{date?}', 'NewOttawaController@ottawaPickedUpExcel')->name('newexport_OttawaPickedUp.excel');
             ### Ottawa Not Scan ###
             Route::get('newottawa/not/scan', 'NewOttawaController@getOttawatnotscan')->name('newottawa-not-scan.index');
             Route::get('newottawa/not/scan/data', 'NewOttawaController@ottawaNotScanData')->name('newottawaNotScan.data');
             Route::get('newottawa/not/scan/list/{date?}', 'NewOttawaController@ottawaNotscanExcel')->name('newexport_OttawaNotScan.excel');
             ### Ottawa Delivered ###
             Route::get('newottawa/delivered', 'NewOttawaController@getOttawadelivered')->name('newottawa-delivered.index');
             Route::get('newottawa/delivered/data', 'NewOttawaController@ottawaDeliveredData')->name('newottawaDelivered.data');
             Route::get('newottawa/delivered/list/{date?}', 'NewOttawaController@ottawaDeliveredExcel')->name('newexport_OttawaDelivered.excel');
             ### Ottawa Returned ###
             Route::get('newottawa/returned', 'NewOttawaController@getOttawareturned')->name('newottawa-returned.index');
             Route::get('newottawa/returned/data', 'NewOttawaController@ottawaReturnedData')->name('newottawaReturned.data');
             Route::get('newottawa/returned/list/{date?}', 'NewOttawaController@ottawaReturnedExcel')->name('newexport_OttawaReturned.excel');
             ### Ottawa Not Returned At Hub ###
             Route::get('newottawa/returned-not-hub', 'NewOttawaController@getOttawaNotReturned')->name('newottawa-notreturned.index');
             Route::get('newottawa/returned-not-hub/data', 'NewOttawaController@ottawaNotReturnedData')->name('newottawaNotReturned.data');
             Route::get('newottawa/returned-not-hub/list/{date?}', 'NewOttawaController@ottawaNotReturnedExcel')->name('newexport_OttawaNotReturned.excel');
             Route::get('newottawa/returned-not-hub/tracking/list/{date?}', 'NewOttawaController@ottawaNotReturnedExcelTrackingIds')->name('newexport_OttawaNotReturned_tracking.excel');
             ### Ottawa Custom Route ###
             Route::get('newottawa/custom-route', 'NewOttawaController@getOttawaCustomRoute')->name('newottawa-custom-route.index');
             Route::get('newottawa/custom-route/data', 'NewOttawaController@ottawaCustomRouteData')->name('newottawaCustomRoute.data');
             Route::get('newottawa/custom-route/list/{date?}', 'NewOttawaController@ottawaCustomRouteExcel')->name('newexport_OttawaCustomRoute.excel');

             ### Ottawa Route Information ###
             Route::get('newottawa/route-info', 'NewOttawaController@getRouteinfo')->name('newottawa-route-info.index');
             Route::get('newottawa/route-info/list/{date?}', 'NewOttawaController@ottawaRouteinfoExcel')->name('newexport_OttawaRouteInfo.excel');
             Route::get('newottawa/route/{di}/edit/hub/{id}', 'NewOttawaController@ottawaHubRouteEdit')->name('newottawa_route.detail');
             Route::post('newottawa/route-details/flag-history-model-html-render', 'NewOttawaController@flagHistoryModelHtmlRender')->name('newottawainfo_route.route-details.flag-history-model-html-render');
             Route::get('newottawa/route/orders/trackingid/{id}/details','NewOttawaController@getOttawatrackingorderdetails')->name('newottawainfo_route.detail');
             Route::post('newottawa/route-info/add-note', 'NewOttawaController@addNote')->name('newottawa-route-info.addNote');
             Route::get('newottawa/route-info/get-notes', 'NewOttawaController@getNotes')->name('newottawa-route-info.getNotes');

             ### Ottawa Profile ###
             Route::get('newottawa/detail/{id}', 'NewOttawaController@ottawaProfile')->name('newottawa.profile');
             Route::get('newottawa/sorted/detail/{id}', 'NewOttawaController@ottawasortedDetail')->name('newottawa_sorted.profile');
             Route::get('newottawa/pickup/detail/{id}', 'NewOttawaController@ottawapickupDetail')->name('newottawa_pickup.profile');
             Route::get('newottawa/notscan/detail/{id}', 'NewOttawaController@ottawanotscanDetail')->name('newottawa_notscan.profile');
             Route::get('newottawa/delivered/detail/{id}', 'NewOttawaController@ottawadeliveredDetail')->name('newottawa_delivered.profile');
             Route::get('newottawa/returned/detail/{id}', 'NewOttawaController@ottawareturnedDetail')->name('newottawa_returned.profile');
             Route::get('newottawa/returned-not-hub/detail/{id}', 'NewOttawaController@ottawaNotReturnedDetail')->name('newottawa_notreturned.profile');
             Route::get('newottawa/custom-route/detail/{id}', 'NewOttawaController@ottawaCustomRouteDetail')->name('newottawa_CustomRoute.profile');

             /*rights module routes*/
             Route::resource('right', 'RightsController');
			  Route::post('right/duplicate', 'RightsController@rightDuplicate')->name('right.duplicate');

        ### Ottawa Dashboard ###
        Route::get('ottawa', 'OttawaController@getOttawa')->name('ottawa.index');
        Route::get('ottawa/data', 'OttawaController@ottawaData')->name('ottawa.data');
        Route::get('ottawa/list/{date?}', 'OttawaController@ottawaExcel')->name('export_Ottawa.excel');
        ### Ottawa Sorted ###
        Route::get('ottawa/sorted', 'OttawaController@getOttawatsort')->name('ottawa-sort.index');
        Route::get('ottawa/sorted/data', 'OttawaController@ottawaSortedData')->name('ottawaSorted.data');
        Route::get('ottawa/sorted/list/{date?}', 'OttawaController@ottawaSortedExcel')->name('export_OttawaSorted.excel');
        ### Ottawa Picked Up ###
        Route::get('ottawa/picked/up', 'OttawaController@getOttawathub')->name('ottawa-pickup.index');
        Route::get('ottawa/picked/up/data', 'OttawaController@ottawaPickedUpData')->name('ottawaPickedUp.data');
        Route::get('ottawa/picked/up/list/{date?}', 'OttawaController@ottawaPickedUpExcel')->name('export_OttawaPickedUp.excel');
        ### Ottawa Not Scan ###
        Route::get('ottawa/not/scan', 'OttawaController@getOttawatnotscan')->name('ottawa-not-scan.index');
        Route::get('ottawa/not/scan/data', 'OttawaController@ottawaNotScanData')->name('ottawaNotScan.data');
        Route::get('ottawa/not/scan/list/{date?}', 'OttawaController@ottawaNotscanExcel')->name('export_OttawaNotScan.excel');
        ### Ottawa Delivered ###
        Route::get('ottawa/delivered', 'OttawaController@getOttawadelivered')->name('ottawa-delivered.index');
        Route::get('ottawa/delivered/data', 'OttawaController@ottawaDeliveredData')->name('ottawaDelivered.data');
        Route::get('ottawa/delivered/list/{date?}', 'OttawaController@ottawaDeliveredExcel')->name('export_OttawaDelivered.excel');
        ### Ottawa Profile ###
        Route::get('ottawa/detail/{id}', 'OttawaController@ottawaProfile')->name('ottawa.profile');
        Route::get('ottawa/sorted/detail/{id}', 'OttawaController@ottawasortedDetail')->name('ottawa_sorted.profile');
        Route::get('ottawa/pickup/detail/{id}', 'OttawaController@ottawapickupDetail')->name('ottawa_pickup.profile');
        Route::get('ottawa/notscan/detail/{id}', 'OttawaController@ottawanotscanDetail')->name('ottawa_notscan.profile');
        Route::get('ottawa/delivered/detail/{id}', 'OttawaController@ottawadeliveredDetail')->name('ottawa_delivered.profile');
        ### Ottawa Route Information ###
        Route::get('ottawa/route-info', 'OttawaController@getRouteinfo')->name('ottawa-route-info.index');
        Route::get('ottawa/route-info/list/{date?}', 'OttawaController@ottawaRouteinfoExcel')->name('export_OttawaRouteInfo.excel');

		//Route::get('montreal/montreal/route/{di}/edit/hub/{id}', 'MontrealController@getMontrealRoute');
        //Route::get('montreal/montreal/route/edit/hub', 'MontrealController@montrealRouteData')->name('montrealRouteData.data');

             ### Ottawa Returned ###
             Route::get('ottawa/returned', 'OttawaController@getOttawareturned')->name('ottawa-returned.index');
             Route::get('ottawa/returned/data', 'OttawaController@ottawaReturnedData')->name('ottawaReturned.data');
             Route::get('ottawa/returned/list/{date?}', 'OttawaController@ottawaReturnedExcel')->name('export_OttawaReturned.excel');
             Route::get('ottawa/returned/detail/{id}', 'OttawaController@ottawareturnedDetail')->name('ottawa_returned.profile');


             Route::get('ottawa/route/{di}/edit/hub/{id}', 'OttawaController@ottawaHubRouteEdit')->name('ottawa_route.detail');
       //Route::get('route/{id}/delete/hub','MontrealController@montrealDeleteRoute');
        Route::get('ottawa/route/orders/trackingid/{id}/details','OttawaController@getOttawatrackingorderdetails')->name('ottawainfo_route.detail');

			 ### Vancouver Dashboard ###
            Route::get('vancouver-dashboard', 'VancouverController@getVancouverDashboard')->name('vancouver-dashboard.index');
            Route::get('vancouver-dashboard/data', 'VancouverController@getVancouverDashboardData')->name('vancouver-dashboard.data');
            Route::get('vancouver/order/detail/{id}', 'VancouverController@vancouverProfile')->name('vancouver-order.profile');
            Route::get('vancouver/returned/detail/{id}', 'VancouverController@vancouverreturnedDetail')->name('vancouver-returned-detail.profile');
            Route::get('vancouver/returned-not-hub/detail/{id}', 'VancouverController@vancouverNotReturnedDetail')->name('Vancouver-notreturned-detail.profile');
            Route::get('vancouver/delivered/detail/{id}', 'VancouverController@vancouverdeliveredDetail')->name('vancouver-delivered-detail.profile');
            Route::get('vancouver/dashboard/list/{date?}/{vendor?}', 'VancouverController@vancouverDashboardExcel')->name('vancouver-dashboard-export.excel');
            Route::get('vancouver/dashboard/otd/report/{date?}/{vendor?}', 'VancouverController@vancouverDashboardExcelOtdReport')->name('vancouver-dashboard-export-otd-report.excel');
            Route::get('vancouver/card-dashboard', 'VancouverController@getVancouverCards')->name('vancouver-card-dashboard.index');
            ### Vancouver Card ###
            Route::get('vancouver/card-dashboard', 'VancouverController@getVancouverCards')->name('vancouver-card-dashboard.index');
            ### Vancouver Orders ###
            Route::get('vancouver/order/data', 'VancouverController@getVancouverData')->name('new-order-vancouver.data');
            Route::get('vancouver/order', 'VancouverController@getVancouver')->name('new-order-vancouver.index');
            Route::get('vancouver/order/list/{date?}/{vendor?}', 'VancouverController@getVancouverExcel')->name('new-order-vancouver-export.excel');
            Route::get('vancouver/detail/{id}', 'VancouverController@getVancouverProfile')->name('vancouver-detail-detail.profile');
            ### Vancouver Returned ###
            Route::get('vancouver/returned', 'VancouverController@getVancouvereturned')->name('new-returned-vancouver.index');
            Route::get('vancouver/returned/data', 'VancouverController@vancouverReturnedData')->name('new-returned-vancouver.data');
            Route::get('vancouver/returned/list/{date?}/{vendor?}', 'VancouverController@VancouverReturnedExcel')->name('new-returned-vancouver-export.excel');
            ### Vancouver Retunred not hub ###
            Route::get('vancouver/returned-not-hub', 'VancouverController@getVancouverNotreturned')->name('new-notreturned-vancouver.index');
            Route::get('vancouver/returned-not-hub/data', 'VancouverController@vancouverNotReturnedData')->name('new-notreturned-vancouver.data');
            Route::get('vancouver/returned-not-hub/list/{date?}', 'VancouverController@VancouverNotReturnedExcel')->name('new-notreturned-vancouver-export.excel');
            Route::get('vancouver/returned-not-hub/tracking/list/{date?}', 'VancouverController@VancouverNotReturnedExcelTrackingIds')->name('new-notreturned-vancouver-tracking-export.excel');

            ### Vancouver sorted ###
            Route::get('vancouver/sorted/detail/{id}', 'VancouverController@vacnouversortedDetail')->name('vancouver-sorted-detail.profile');
            Route::get('vancouver/sorted', 'VancouverController@getVancouverSorter')->name('new-sort-vancouver.index');
            Route::get('vancouver/sorted/data', 'VancouverController@vancouverSortedData')->name('new-sort-vancouver.data');
            Route::get('vancouver/sorted/list/{date?}/{vendor?}', 'VancouverController@VancouverSortedExcel')->name('new-sort-vancouver-export.excel');
            ### Vancouver Out for delivery ###
            Route::get('vancouver/picked/up', 'VancouverController@getVancouverhub')->name('new-pickup-vancouver.index');
            Route::get('vancouver/picked/up/data', 'VancouverController@vancouverPickedUpData')->name('new-pickup-vancouver.data');
            Route::get('vancouver/picked/up/list/{date?}/{vendor?}', 'VancouverController@boradlessPickedupExcel')->name('new-pickup-vancouver-export.excel');
            Route::get('vancouver/pickup/detail/{id}', 'VancouverController@vancouverpickupDetail')->name('vancouver-pickup-detail.profile');
            ### Vancouver Delivered ###
            Route::get('vancouver/delivered', 'VancouverController@getVancouverdelivered')->name('new-delivered-vancouver.index');
            Route::get('vancouver/delivered/data', 'VancouverController@vancouverDeliveredData')->name('new-delivered-vancouver.data');
            Route::get('vancouver/delivered/list/{date?}/{vendor?}', 'VancouverController@vancouverDeliveredExcel')->name('new-delivered-vancouver-export.excel');
            ### Vancouver Not Scan ###
            Route::get('vancouver/notscan/detail/{id}', 'VancouverController@vancouvernotscanDetail')->name('vancouver-notscan-detail.profile');
            Route::get('vancouver/not/scan', 'VancouverController@getVancouverscan')->name('new-not-scan-vancouver.index');
            Route::get('vancouver/not/scan/data', 'VancouverController@vancouverNotScanData')->name('new-not-scan-vancouver.data');
            Route::get('vancouver/not/scan/list/{date?}/{vendor?}', 'VancouverController@vancouverNotscanExcel')->name('new-not-scan-vancouver-export.excel');
            ### Vancouver Custom Route ###
            Route::get('vancouver/custom-route', 'VancouverController@getVancouverCustomRoute')->name('new-custom-route-vancouver.index');
            Route::get('vancouver/custom-route/data', 'VancouverController@vancouverCustomRouteData')->name('new-custom-route-vancouver.data');
            Route::get('vancouver/custom-route/list/{date?}/{vendor?}', 'VancouverController@vancouverCustomRouteExcel')->name('new-custom-route-vancouver-export.excel');
            Route::get('vancouver/custom-route/detail/{id}', 'VancouverController@vancouverCustomRouteDetail')->name('vancouver-CustomRoute-detail.profile');
            ### Vancouver Reporting###
            Route::get('/vancouver/reporting', 'VancouverController@getVancouverReporting')->name('vancouver_reporting.index');
            Route::get('yajra/vancouver/reporting', 'VancouverController@getVancouverReportingData')->name('new_vancouver_reporting_data.data');
            ### Vancouver OTD ###
            Route::get('vancouver/graph', 'VancouverController@statistics_otd_index')->name('vancouver-graph.index');
            Route::get('vancouver/dashboard/statistics/ajax/otd-day', 'VancouverController@ajax_render_vancouver_otd_day')->name('vancouver-otd-day.index');
            Route::get('vancouver/dashboard/statistics/ajax/otd-week', 'VancouverController@ajax_render_vancouver_otd_week')->name('vancouver-otd-week.index');
            Route::get('vancouver/dashboard/statistics/ajax/otd-month', 'VancouverController@ajax_render_vancouver_otd_month')->name('vancouver-otd-month.index');
            ### Vancouver Route Info ###
            Route::get('vancouver/route-info', 'VancouverController@getRouteinfo')->name('vancouver-route-info.index');
            Route::get('vancouver/route-info/list/{date?}', 'VancouverController@vancouverRouteinfoExcel')->name('new-export_VancouverRouteInfo.excel');
            Route::get('vancouver/route/{di}/edit/hub/{id}', 'VancouverController@vancouverHubRouteEdit')->name('vancouver_route.detail');
            Route::post('vancouver/route-details/flag-history-model-html-render', 'vancouverController@flagHistoryModelHtmlRender')->name('vancouverinfo_route.route-details.flag-history-model-html-render');
            Route::get('vancouver/route/orders/trackingid/{id}/details', 'VancouverController@getVancouvertrackingorderdetails')->name('vancouverinfo_route.detail');
            Route::post('vancouver/route/mark/delay', 'VancouverController@routeMarkDelay')->name('vancouver-route-mark-delay');
            Route::post('vancouver/route-info/add-note', 'VancouverController@addNote')->name('vancouver-route-info.addNote');
            Route::get('vancouver/route-info/get-notes', 'VancouverController@getNotes')->name('vancouver-route-info.getNotes');
            Route::post('vancouver/route-details/flag-history-model-html-render', 'VancouverController@flagHistoryModelHtmlRender')->name('vancouverinfo_route.route-details.flag-history-model-html-render');

             Route::get('vancouver/pickup/from/store', 'VancouverController@getVancouverPickedUpFromStore')->name('PickedUpFrom-vancouver.index');
             Route::get('vancouver/sorted-at', 'VancouverController@getVancouverSortedAt')->name('SortedAt-vancouver.index');
             Route::get('vancouver/at/store', 'VancouverController@getVancouverAtStore')->name('AtStore-vancouver.index');
			 Route::get('vancouver/at/hub', 'VancouverController@getVancouverAtHub')->name('AtHub-vancouver.index');
             Route::get('vancouver/delivered-orders', 'VancouverController@getVancouverDeliveredOrders')->name('delivered-orders-vancouver.index');
             Route::get('vancouver/at/store/data', 'VancouverController@vancouverAtStoreData')->name('AtStore-vancouver.data');

             ###Borderless Dashboard###
             Route::get('toronto-dashboard', 'BorderlessController@getBoradlessDashboard')->name('borderless-dashboard.index');
             Route::get('toronto-dashboard/data', 'BorderlessController@getBoradlessDashboardData')->name('borderless-dashboard.data');
             Route::get('toronto/order/detail/{id}', 'BorderlessController@boradlessProfile')->name('borderless-order.profile');
             Route::get('toronto/dashboard/list/{date?}/{vendor_id?}', 'BorderlessController@boradlessDashboardExcel')->name('borderless-dashboard-export.excel');
             Route::get('toronto/dashboard/otd/report/{date?}/{vendor_id?}', 'BorderlessController@boradlessDashboardExcelOtdReport')->name('borderless-dashboard-export-otd-report.excel');

             ### Borderless  Cards ###
             Route::get('toronto/card-dashboard', 'BorderlessController@getBoradlessCards')->name('new-borderless-card-dashboard.index');

             ### New Borderless  Dashboard ###
             Route::get('toronto/order/data', 'BorderlessController@getBoradlessData')->name('new-order-borderless.data');
             Route::get('toronto/order', 'BorderlessController@getBoradless')->name('new-order-borderless.index');
             Route::get('toronto/order/list/{date?}/{vendor_id?}', 'BorderlessController@getBoradlessExcel')->name('new-order-borderless-export.excel');
             ### Borderless Sorted ###
             Route::get('toronto/sorted', 'BorderlessController@getBoradlessSorter')->name('new-sort-borderless.index');
             Route::get('toronto/sorted/data', 'BorderlessController@boradlessSortedData')->name('new-sort-borderless.data');
             Route::get('toronto/sorted/list/{date?}/{vendor_id?}', 'BorderlessController@boradlessSortedExcel')->name('new-sort-borderless-export.excel');
             ### Borderless Hub ###
             Route::get('toronto/picked/up', 'BorderlessController@getBoradlesshub')->name('new-pickup-borderless.index');
             Route::get('toronto/picked/up/data', 'BorderlessController@boradlessPickedUpData')->name('new-pickup-borderless.data');
             Route::get('toronto/picked/up/list/{date?}/{vendor_id?}', 'BorderlessController@boradlessPickedupExcel')->name('new-pickup-borderless-export.excel');
             ### Borderless Not Scan ###
             Route::get('toronto/not/scan', 'BorderlessController@getBoradlessscan')->name('new-not-scan-borderless.index');
             Route::get('toronto/not/scan/data', 'BorderlessController@boradlessNotScanData')->name('new-not-scan-borderless.data');
             Route::get('toronto/not/scan/list/{date?}/{vendor_id?}', 'BorderlessController@boradlessNotscanExcel')->name('new-not-scan-borderless-export.excel');
             ### Borderless Delivered ###
             Route::get('toronto/delivered', 'BorderlessController@getBoradlessdelivered')->name('new-delivered-borderless.index');
             Route::get('toronto/delivered/data', 'BorderlessController@boradlessDeliveredData')->name('new-delivered-borderless.data');
             Route::get('toronto/delivered/list/{date?}/{vendor_id?}', 'BorderlessController@boradlessDeliveredExcel')->name('new-delivered-borderless-export.excel');
             ### Borderless Returned ###
             Route::get('toronto/returned', 'BorderlessController@getBoradlessreturned')->name('new-returned-borderless.index');
             Route::get('toronto/returned/data', 'BorderlessController@boradlessReturnedData')->name('new-returned-borderless.data');
             Route::get('toronto/returned/list/{startdate?}/{enddate?}/{vendor_id?}', 'BorderlessController@boradlessReturnedExcel')->name('new-returned-borderless-export.excel');
             ### Borderless Not Returned At Hub ###
             Route::get('toronto/returned-not-hub', 'BorderlessController@getBoradlessNotreturned')->name('new-notreturned-borderless.index');
             Route::get('toronto/returned-not-hub/data', 'BorderlessController@boradlessNotReturnedData')->name('new-notreturned-borderless.data');
             Route::get('toronto/returned-not-hub/list/{date?}/{vendor_id?}', 'BorderlessController@boradlessNotReturnedExcel')->name('new-notreturned-borderless-export.excel');
             Route::get('toronto/returned-not-hub/tracking/list/{startdate?}/{enddate?}/{vendor_id?}', 'BorderlessController@boradlessNotReturnedExcelTrackingIds')->name('new-notreturned-borderless-tracking-export.excel');
             ### Borderless Received At Hub ###
             Route::get('toronto/received-at-hub', 'BorderlessController@getBoradlessRecievedAtHub')->name('new-receivedathub-borderless.index');
             Route::get('toronto/received-at-hub/data', 'BorderlessController@boradlessRecievedAtHubData')->name('new-receivedathub-borderless.data');
             Route::get('toronto/received-at-hub/list/{date?}/{vendor_id?}', 'BorderlessController@boradlessRecievedAtHubExcel')->name('new-receivedathub-borderless-export.excel');
             Route::get('toronto/received-at-hub/tracking/list/{startdate?}/{enddate?}/{vendor_id?}', 'BorderlessController@boradlessRecievedAtHubExcelTrackingIds')->name('new-receivedathub-borderless-tracking-export.excel');

             #### Borderless reattemptted orders
             Route::get('toronto/to-be-reattempted-orders', 'BorderlessController@getBoradlessToBeReattemptedOrders')->name('to-be-reattemptedorders-borderless.index');
             Route::get('toronto/to-be-reattempted-orders/data', 'BorderlessController@boradlessToBeReattemptedOrdersData')->name('to-be-reattemptedorders-borderless.data');

             #### returned-to-hub-for-re-delivery ####
             Route::get('toronto/returned-to-hub-for-re-delivery', 'BorderlessController@getBoradlessReturnedToHubForReDelivery')->name('re-delivery-orders-borderless.index');
             Route::get('toronto/returned-to-hub-for-re-delivery/data', 'BorderlessController@boradlessReturnedToHubForReDeliveryData')->name('re-delivery-orders-borderless.data');

             ### Borderless Custom Route ###
             Route::get('toronto/custom-route', 'BorderlessController@getBoradlessCustomRoute')->name('new-custom-route-borderless.index');
             Route::get('toronto/custom-route/data', 'BorderlessController@boradlessCustomRouteData')->name('new-custom-route-borderless.data');
             Route::get('toronto/custom-route/list/{date?}/{vendor_id?}', 'BorderlessController@boradlessCustomRouteExcel')->name('new-custom-route-borderless-export.excel');

             ### Borderless Reporting###
             Route::get('/toronto/reporting', 'BorderlessController@getBoradlessReporting')->name('borderless_reporting.index');
             Route::get('yajra/toronto/reporting', 'BorderlessController@getBoradlessReportingData')->name('new_borderless_reporting_data.data');

             ### Borderless OTD ###
             Route::get('toronto/graph', 'BorderlessController@statistics_otd_index')->name('borderless-graph.index');
             Route::get('toronto/dashboard/statistics/ajax/otd-day', 'BorderlessController@ajax_render_boradless_otd_day')->name('borderless-otd-day.index');
             Route::get('toronto/dashboard/statistics/ajax/otd-week', 'BorderlessController@ajax_render_boradless_otd_week')->name('borderless-otd-week.index');
             Route::get('toronto/dashboard/statistics/ajax/otd-month', 'BorderlessController@ajax_render_boradless_otd_month')->name('borderless-otd-month.index');

             ### Borderless Route Info ###
             Route::get('toronto/route-info', 'BorderlessController@getRouteinfo')->name('borderless-route-info.index');
             Route::get('toronto/route-info/list/{date?}/{vendor_id?}', 'BorderlessController@boradlessRouteinfoExcel')->name('new-export_BorderlessRouteInfo.excel');
             Route::get('toronto/route/{di}/edit/hub/{id}', 'BorderlessController@boradlessHubRouteEdit')->name('borderless_route.detail');
             Route::post('toronto/route-details/flag-history-model-html-render', 'BorderlessController@flagHistoryModelHtmlRender')->name('borderlessinfo_route.route-details.flag-history-model-html-render');
             Route::get('toronto/route/orders/trackingid/{id}/details','BorderlessController@getBoradlesstrackingorderdetails')->name('borderlessinfo_route.detail');
             Route::post('toronto/route/mark/delay','BorderlessController@routeMarkDelay')->name('borderless-route-mark-delay');
             Route::post('toronto/route-info/add-note', 'BorderlessController@addNote')->name('borderless-route-info.addNote');
             Route::get('toronto/route-info/get-notes', 'BorderlessController@getNotes')->name('borderless-route-info.getNotes');
 			 Route::get('toronto/route/delivered_permission/{id}', 'BorderlessController@deliveredPermission')->name('borderless_route.delivered_permission');
             Route::get('toronto/route/delivered_permission_denied/{id}', 'BorderlessController@deliveredPermissionDenied')->name('borderless_route.delivered_permission_denied');
             //total/order/notinroute
             Route::post('total/order/notinroute', 'BorderlessController@totalOrderNotinroute')->name('total-order.notinroute');

             Route::get('not/routed/order/list', 'BorderlessController@notRoutedOrdersList')->name('not.routed.orders.list');
             Route::get('not/routed/order/list/data', 'BorderlessController@notRoutedOrdersListData')->name('not.routed.orders.list.data');

//             Route::get('not/routed/order/list/{id}', 'BorderlessController@notRoutedOrdersList')->name('not.routed.orders.list');
             ### Borderless Profile ###
             Route::get('toronto/detail/{id}', 'BorderlessController@getBoradlessProfile')->name('borderless-detail-detail.profile');
             Route::get('toronto/sorted/detail/{id}', 'BorderlessController@boradlesssortedDetail')->name('borderless-sorted-detail.profile');
             Route::get('toronto/pickup/detail/{id}', 'BorderlessController@boradlesspickupDetail')->name('borderless-pickup-detail.profile');
             Route::get('toronto/notscan/detail/{id}', 'BorderlessController@boradlessnotscanDetail')->name('borderless-notscan-detail.profile');
             Route::get('toronto/delivered/detail/{id}', 'BorderlessController@boradlessdeliveredDetail')->name('borderless-delivered-detail.profile');
             Route::get('toronto/returned/detail/{id}', 'BorderlessController@boradlessreturnedDetail')->name('borderless-returned-detail.profile');
             Route::get('toronto/returned-not-hub/detail/{id}', 'BorderlessController@boradlessNotReturnedDetail')->name('borderless-notreturned-detail.profile');
             Route::get('toronto/custom-route/detail/{id}', 'BorderlessController@boradlessCustomRouteDetail')->name('borderless-CustomRoute-detail.profile');
             Route::get('toronto/received-at-hub/detail/{id}', 'BorderlessController@boradlessReceivedAtHubDetail')->name('borderless-receivedathub-detail.profile');
             Route::get('toronto/reattmpted-orders/detail/{id}', 'BorderlessController@boradlessReattemptedOrdersDetail')->name('borderless-reattempted-detail.profile');
             Route::get('toronto/reattempted-orders/tracking/list/{startdate?}/{enddate?}/{vendor_id?}', 'BorderlessController@boradlessReattemptedOrdersExcelTrackingIds')->name('new-reattemptedorders-borderless-tracking-export.excel');
             Route::get('toronto/re-delivery-orders/tracking/list/{startdate?}/{enddate?}/{vendor_id?}', 'BorderlessController@boradlessReDeliveryOrdersExcelTrackingIds')->name('re-delivery-orders-borderless-tracking-export.excel');

             ###CTC Entries ###
             Route::get('new/ctc-dashboard', 'CtcEntriesController@getCtcDashboard')->name('new-ctc-dashboard.index');
             Route::get('new/ctc-dashboard/data', 'CtcEntriesController@getCtcDashboardData')->name('new-ctc-dashboard.data');
             Route::get('new/ctc/order/detail/{id}', 'CtcEntriesController@ctcProfile')->name('new-ctc-order.profile');
             Route::get('new/ctc/dashboard/list/{date?}', 'CtcEntriesController@ctcDashboardExcel')->name('new-ctc-dashboard-export.excel');
             Route::get('new/ctc/dashboard/otd/report/{date?}', 'CtcEntriesController@ctcDashboardExcelOtdReport')->name('new-ctc-dashboard-export-otd-report.excel');
             Route::get('new/ctc/missing/id/{date?}', 'CtcEntriesController@ctcMissingExcelReport')->name('new-ctc-missing-id-export.excel');

             ### CTC Entries Cards ###
             Route::get('new/ctc/card-dashboard', 'CtcEntriesController@getCtcCards')->name('new-ctc-card-dashboard.index');

             ### New CTC Entries Dashboard ###
             Route::get('new/ctc/order/data', 'CtcEntriesController@getCtcData')->name('new-order-ctc.data');
             Route::get('new/ctc/order', 'CtcEntriesController@getCtc')->name('new-order-ctc.index');
             Route::get('new/ctc/order/list/{date?}', 'CtcEntriesController@getCtcExcel')->name('new-order-ctc-export.excel');
             ### CTC Entries Sorted ###
             Route::get('new/ctc/sorted', 'CtcEntriesController@getCtcSorter')->name('new-sort-ctc.index');
             Route::get('new/ctc/sorted/data', 'CtcEntriesController@ctcSortedData')->name('new-sort-ctc.data');
             Route::get('new/ctc/sorted/list/{date?}', 'CtcEntriesController@ctcSortedExcel')->name('new-sort-ctc-export.excel');
             ### CTC Entries Hub ###
             Route::get('new/ctc/picked/up', 'CtcEntriesController@getCtchub')->name('new-pickup-ctc.index');
             Route::get('new/ctc/picked/up/data', 'CtcEntriesController@ctcPickedUpData')->name('new-pickup-ctc.data');
             Route::get('new/ctc/picked/up/list/{date?}', 'CtcEntriesController@ctcPickedupExcel')->name('new-pickup-ctc-export.excel');
             ### CTC Entries Not Scan ###
             Route::get('new/ctc/not/scan', 'CtcEntriesController@getCtcscan')->name('new-not-scan-ctc.index');
             Route::get('new/ctc/not/scan/data', 'CtcEntriesController@ctcNotScanData')->name('new-not-scan-ctc.data');
             Route::get('new/ctc/not/scan/list/{date?}', 'CtcEntriesController@ctcNotscanExcel')->name('new-not-scan-ctc-export.excel');
             ### CTC Entries Delivered ###
             Route::get('new/ctc/delivered', 'CtcEntriesController@getCtcdelivered')->name('new-delivered-ctc.index');
             Route::get('new/ctc/delivered/data', 'CtcEntriesController@ctcDeliveredData')->name('new-delivered-ctc.data');
             Route::get('new/ctc/delivered/list/{date?}', 'CtcEntriesController@ctcDeliveredExcel')->name('new-delivered-ctc-export.excel');
             ### CTC Entries Returned ###
             Route::get('new/ctc/returned', 'CtcEntriesController@getCtcreturned')->name('new-returned-ctc.index');
             Route::get('new/ctc/returned/data', 'CtcEntriesController@ctcReturnedData')->name('new-returned-ctc.data');
             Route::get('new/ctc/returned/list/{date?}', 'CtcEntriesController@ctcReturnedExcel')->name('new-returned-ctc-export.excel');
             ### CTC Entries Not Returned At Hub ###
             Route::get('new/ctc/returned-not-hub', 'CtcEntriesController@getCtcNotreturned')->name('new-notreturned-ctc.index');
             Route::get('new/ctc/returned-not-hub/data', 'CtcEntriesController@ctcNotReturnedData')->name('new-notreturned-ctc.data');
             Route::get('new/ctc/returned-not-hub/list/{date?}', 'CtcEntriesController@ctcNotReturnedExcel')->name('new-notreturned-ctc-export.excel');
             Route::get('new/ctc/returned-not-hub/tracking/list/{date?}', 'CtcEntriesController@ctcNotReturnedExcelTrackingIds')->name('new-notreturned-ctc-tracking-export.excel');
             ### CTC Entries Custom Route ###
             Route::get('new/ctc/custom-route', 'CtcEntriesController@getCtcCustomRoute')->name('new-custom-route-ctc.index');
             Route::get('new/ctc/custom-route/data', 'CtcEntriesController@ctcCustomRouteData')->name('new-custom-route-ctc.data');
             Route::get('new/ctc/custom-route/list/{date?}', 'CtcEntriesController@ctcCustomRouteExcel')->name('new-custom-route-ctc-export.excel');

             ### CTC Entries Profile ###
             Route::get('new/ctc/detail/{id}', 'CtcEntriesController@getCtcProfile')->name('new-ctc-detail-detail.profile');
             Route::get('new/ctc/sorted/detail/{id}', 'CtcEntriesController@ctcsortedDetail')->name('new-ctc-sorted-detail.profile');
             Route::get('new/ctc/pickup/detail/{id}', 'CtcEntriesController@ctcpickupDetail')->name('new-ctc-pickup-detail.profile');
             Route::get('new/ctc/notscan/detail/{id}', 'CtcEntriesController@ctcnotscanDetail')->name('new-ctc-notscan-detail.profile');
             Route::get('new/ctc/delivered/detail/{id}', 'CtcEntriesController@ctcdeliveredDetail')->name('new-ctc-delivered-detail.profile');
             Route::get('new/ctc/returned/detail/{id}', 'CtcEntriesController@ctcreturnedDetail')->name('new-ctc-returned-detail.profile');
             Route::get('new/ctc/returned-not-hub/detail/{id}', 'CtcEntriesController@ctcNotReturnedDetail')->name('new-ctc-notreturned-detail.profile');
             Route::get('new/ctc/custom-route/detail/{id}', 'CtcEntriesController@ctcCustomRouteDetail')->name('new-ctc-CustomRoute-detail.profile');

             ### CTC Entries Reporting###
             Route::get('new/ctcreporting', 'CtcEntriesController@getCtcReporting')->name('new-ctc_reporting.index');
             Route::get('new/yajractcreporting', 'CtcEntriesController@getCtcReportingData')->name('new-ctc_reporting_data.data');
             ### CTC Entries OTD ###
             Route::get('new/ctc/graph', 'CtcEntriesController@statistics_otd_index')->name('new-ctc-graph.index');
             Route::get('new/ctc/dashboard/statistics/ajax/otd-day', 'CtcEntriesController@ajax_render_ctc_otd_day')->name('new-ctc-otd-day.index');
             Route::get('new/ctc/dashboard/statistics/ajax/otd-week', 'CtcEntriesController@ajax_render_ctc_otd_week')->name('new-ctc-otd-week.index');
             Route::get('new/ctc/dashboard/statistics/ajax/otd-month', 'CtcEntriesController@ajax_render_ctc_otd_month')->name('new-ctc-otd-month.index');

             ###CTC Entries Route Info ###
             Route::get('new/ctc/route-info', 'CtcEntriesController@getRouteinfo')->name('new-ctc-route-info.index');
             Route::get('new/ctc/route-info/list/{date?}', 'CtcEntriesController@ctcRouteinfoExcel')->name('new-export_CTCRouteInfo.excel');
             Route::get('new/ctc/route/{di}/edit/hub/{id}', 'CtcEntriesController@ctcHubRouteEdit')->name('new-ctc_route.detail');
             Route::post('new/ctc/route-details/flag-history-model-html-render', 'CtcEntriesController@flagHistoryModelHtmlRender')->name('new-ctcinfo_route.route-details.flag-history-model-html-render');
             Route::get('new/ctc/route/orders/trackingid/{id}/details','CtcEntriesController@getCtctrackingorderdetails')->name('new-ctcinfo_route.detail');
             Route::post('new/route/mark/delay','CtcEntriesController@routeMarkDelay')->name('new-route-mark-delay');
             Route::post('new/ctc/route-info/add-note', 'CtcEntriesController@addNote')->name('new-ctc-route-info.addNote');
             Route::get('new/ctc/route-info/get-notes', 'CtcEntriesController@getNotes')->name('new-ctc-route-info.getNotes');


            ###New CTC ###
             Route::get('newctc-dashboard', 'NewCtcController@getCtcNewDashboard')->name('newctc-dashboard.index');
             Route::get('newctc-dashboard/data', 'NewCtcController@getCtcNewDashboardData')->name('newctc-dashboard.data');
             Route::get('newctc/new/detail/{id}', 'NewCtcController@ctcNewProfile')->name('newctc-new.profile');
             Route::get('newctc/new/dashboard/list/{date?}', 'NewCtcController@CtcNewDashboardExcel')->name('newexport_ctc_new_dashboard.excel');

             ###Current CTC ###
             Route::get('ctc-dashboard', 'CtcEntriesController@getCtcDashboard')->name('ctc-dashboard.index');
             Route::get('ctc-dashboard/data', 'CtcEntriesController@getCtcDashboardData')->name('ctc-dashboard.data');
             Route::get('ctc/new/detail/{id}', 'CtcEntriesController@ctcProfile')->name('ctc-new.profile');
             Route::get('ctc/new/dashboard/list/{date?}', 'CtcEntriesController@ctcDashboardExcel')->name('export_ctc_new_dashboard.excel');
             Route::get('ctc/new/dashboard/list/test/{date?}', 'CtcController@CtcNewDashboardExcelTest')->name('export_ctc_new_dashboard_test.excel');
             Route::get('ctc/new/dashboard/otd/report/{date?}', 'CtcEntriesController@ctcDashboardExcelOtdReport')->name('export_ctc_new_dashboard_otd_report.excel');
             Route::get('ctc/missing/id/{date?}', 'CtcEntriesController@CtcMissingExcelReport')->name('export_ctc_missing_id.excel');

        ###CTC Dashboard###
        Route::get('ctc', 'CtcController@getCtc')->name('ctc.index');
        Route::get('ctc/data', 'CtcController@ctcData')->name('ctc.data');
        Route::get('ctc/list/{date?}', 'CtcController@CtcExcel')->name('export_ctc.excel');
        ###CTC Sorted###
        Route::get('ctc/sorted', 'CtcController@getCtcSort')->name('ctc-sort.index');
        Route::get('ctc/sorted/data', 'CtcController@ctcSortedData')->name('ctcSorted.data');
        Route::get('ctc/sorted/list/{date?}', 'CtcController@otcSortedExcel')->name('export_CTCSorted.excel');
        ###CTC Picked Up###
        Route::get('ctc/picked/up', 'CtcController@getCtcthub')->name('ctc-pickup.index');
        Route::get('ctc/picked/up/data', 'CtcController@ctcPickedUpData')->name('ctcPickedUp.data');
        Route::get('ctc/picked/up/list/{date?}', 'CtcController@ctcPickedUpExcel')->name('export_CTCPickedUp.excel');
        ### CTC Not Scan ###
        Route::get('ctc/not/scan', 'CtcController@getCtcnotscan')->name('ctc-not-scan.index');
        Route::get('ctc/not/scan/data', 'CtcController@ctcNotScanData')->name('ctcNotScan.data');
        Route::get('ctc/not/scan/list/{date?}', 'CtcController@ctcNotscanExcel')->name('export_CTCNotScan.excel');
        ### CTC Delivered ###
        Route::get('ctc/delivered', 'CtcController@getCtcDelivered')->name('ctc-delivered.index');
        Route::get('ctc/delivered/data', 'CtcController@ctcDeliveredData')->name('ctcDelivered.data');
        Route::get('ctc/delivered/list/{date?}', 'CtcController@ctcDeliveredExcel')->name('export_CTCDelivered.excel');
        ### Ottawa Route Information ###
        Route::get('ctc/route-info', 'CtcEntriesController@getRouteinfo')->name('ctc-route-info.index');
        Route::get('ctc/route-info/list/{date?}', 'CtcEntriesController@ctcRouteinfoExcel')->name('export_CTCRouteInfo.excel');
        Route::get('ctc/route/{di}/edit/hub/{id}', 'CtcEntriesController@ctcHubRouteEdit')->name('ctc_route.detail');
        Route::post('ctc/route-details/flag-history-model-html-render', 'CtcEntriesController@flagHistoryModelHtmlRender')->name('ctcinfo_route.route-details.flag-history-model-html-render');
        Route::get('ctc/route/orders/trackingid/{id}/details','CtcEntriesController@getCtctrackingorderdetails')->name('ctcinfo_route.detail');
        Route::post('route/mark/delay','CtcEntriesController@routeMarkDelay')->name('route-mark-delay');

        ###CTC Order Detail###
        Route::get('ctc/detail/{id}', 'CtcController@ctcProfile')->name('ctc.profile');
        Route::get('ctc/sorted/detail/{id}', 'CtcController@ctcsortedDetail')->name('ctc_sorted.profile');
        Route::get('ctc/pickup/detail/{id}', 'CtcController@ctcpickupDetail')->name('ctc_pickup.profile');
        Route::get('ctc/notscan/detail/{id}', 'CtcController@ctcnotscanDetail')->name('ctc_notscan.profile');
        Route::get('ctc/delivered/detail/{id}', 'CtcController@ctcdeliveredDetail')->name('ctc_delivered.profile');

             ### Return Route Information ###
             Route::get('return/route-info', 'ReturnDashboardController@getReturnRouteinfo')->name('return-route-info.index');
             Route::get('return/route/{di}/{type}', 'ReturnDashboardController@returnRouteOrder')->name('return-route-order.detail');
             Route::get('return/route/orders/trackingid/{id}/details','ReturnDashboardController@getReturnTrackingOrderDetails')->name('return-route-info-order.detail');



             ### Toronto Flower company ###
             Route::get('toronto/flower/route-info', 'FlowerController@getRouteinfo')->name('toronto-flower-route-info.index');
             Route::get('toronto/flower/route-info/list/{date?}', 'FlowerController@torontoFlowerRouteInfoExcel')->name('export_toronto_flower_route_info.excel');
             Route::get('toronto/flower/route/{di}/edit/hub/{id}', 'FlowerController@torontoFlowerHubRouteEdit')->name('toronto_flower_route.detail');
             Route::get('toronto/flower/route/orders/trackingid/{id}/details','FlowerController@getTorontoFlowerTrackingOrderDetails')->name('toronto_flower_info_route.detail');



            ###CTC Reporting###
             Route::get('ctcreporting', 'CtcEntriesController@getCtcReporting')->name('ctc_reporting.index');
             Route::get('yajractcreporting', 'CtcEntriesController@getCtcReportingData')->name('ctc_reporting_data.data');
			  Route::get('ctc/summary/detail/{id}', 'CtcController@ctcNewProfile')->name('ctc-summary.profile');
			 Route::get('ctc/reporting/excel/{data_for?}/{fromdate?}/{todate?}', 'CtcController@ctcReportingExcel')->name('export_ctc_reporting.excel');


			  ### CTC OTD ###
             Route::get('ctc/graph', 'CtcEntriesController@statistics_otd_index')->name('ctc-graph.index');
             Route::get('dashboard/statistics/ajax/otd-day', 'CtcEntriesController@ajax_render_ctc_otd_day')->name('ctc-otd-day.index');
             Route::get('dashboard/statistics/ajax/otd-week', 'CtcEntriesController@ajax_render_ctc_otd_week')->name('ctc-otd-week.index');
             Route::get('dashboard/statistics/ajax/otd-month', 'CtcEntriesController@ajax_render_ctc_otd_month')->name('ctc-otd-month.index');

             ### WareHouse Performance ###
             Route::get('warehouse-performance', 'WarehousePerformanceController@getWarehousePerformance')->name('warehouse-performance.index');
             Route::get('warehouse-performance/data', 'WarehousePerformanceController@getWarehousePerformanceData')->name('warehouse-performance.data');


		//Customer Order Confirmation
        Route::get('order/under-review', 'CustomerSupportController@getIndex')->name('order-confirmation-list.index');
        Route::get('order/history', 'CustomerSupportController@getOrderHistory')->name('order-confirmation.history');
        Route::post('order/approval', 'CustomerSupportController@orderConfirtmation')->name('orderConfirmation.transfer');
        ###Update Column###
        Route::get('reattempt/order/column/update', 'CustomerSupportController@reattemptOrderColumnUpdate')->name('Column.Update');
		###Expired Order###
        Route::get('return/order', 'CustomerSupportController@expiredOrder')->name('expired-order.history');
        ###Return Order###
        Route::get('Return/order/{id}', 'CustomerSupportController@returnOrder')->name('return-order.update');
        ###Add Notes###
        Route::post('add/notes', 'CustomerSupportController@addNotes')->name('add-notes');
        Route::get('notes/{id}', 'CustomerSupportController@showNotes')->name('show-notes');
		###Returned Order###
        Route::get('returned/order', 'CustomerSupportController@returnedOrder')->name('returned-order.index');

		###Walmart Dashboard###
        Route::get('walmart/dashboard', 'WalmartController@statistics_wm')->name('walmartdashboard.index');
        // Route::get('dashboard/statistics/walmart', 'WalmartController@statistics_wm');
        Route::get('dashboard/statistics/ajax/otd', 'WalmartController@ajax_render_otd_charts')->name('walmartotdajax.index');
        Route::get('dashboard/statistics/ajax/short-summary', 'WalmartController@ajax_render_short_summary')->name('walmartshortsummary.index');
        Route::get('dashboard/statistics/ajax/walmart-orders', 'WalmartController@ajax_render_walmart_order')->name('walmartrenderorder.index');
        Route::get('dashboard/statistics/ajax/walmart-on-time-orders', 'WalmartController@ajax_render_walmart_on_time_orders')->name('walmartontimeorder.index');;
        Route::get('dashboard/statistics/ajax/walmart-stores-data', 'WalmartController@ajax_render_walmart_stores_data')->name('walmartstoresdata.index');
        Route::get('dashboard/statistics/ajax/total-orders-summary', 'WalmartController@ajax_render_total_orders_summary')->name('walmartordersummary.index');
        Route::get('report/export','WalmartController@getWmExport')->name('walmartdashboard.excel');
 Route::get('walmart/new-count', 'WalmartController@walmartNewCount')->name('walmart-new-count');
 Route::get('generate/walmart/orders/report/csv ','WalmartController@download_walmart_report_csv_view')->name('download-walmart-report-csv-view');
             Route::post('generate/walmart/orders/report/csv/ajax','WalmartController@generate_walmart_report_csv')->name('generate-walmart-report-csv');


			### New Walmart Dashboard###
             Route::get('new/walmart/dashboard', 'NewWalmartController@statistics_wm')->name('new-walmartdashboard.index');
             Route::get('new/dashboard/statistics/ajax/otd', 'NewWalmartController@ajax_render_otd_charts')->name('new-walmartotdajax.index');
             Route::get('new/dashboard/statistics/ajax/short-summary', 'NewWalmartController@ajax_render_short_summary')->name('new-walmartshortsummary.index');
             Route::get('new/dashboard/statistics/ajax/walmart-orders', 'NewWalmartController@ajax_render_walmart_order')->name('new-walmartrenderorder.index');
             Route::get('new/dashboard/statistics/ajax/walmart-on-time-orders', 'NewWalmartController@ajax_render_walmart_on_time_orders')->name('new-walmartontimeorder.index');;
             Route::get('new/dashboard/statistics/ajax/walmart-stores-data', 'NewWalmartController@ajax_render_walmart_stores_data')->name('new-walmartstoresdata.index');
             Route::get('new/dashboard/statistics/ajax/total-orders-summary', 'NewWalmartController@ajax_render_total_orders_summary')->name('new-walmartordersummary.index');
             Route::get('new/report/export','NewWalmartController@getWmExport')->name('new-walmartdashboard.excel');



        Route::get('walmart', 'WalmartController@getwalmart')->name('walmart.index');
        Route::get('walmart/data', 'WalmartController@walmartdata')->name('walmart.data');
        Route::get('walmart/list/{date?}', 'WalmartController@walmartexcel')->name('export_walmart.excel');
        Route::get('walmart/profile/{id}', 'WalmartController@walmartprofile')->name('walmart.profile');


          /*   ###Grocery Dashboard###
             Route::get('grocery/dashboard', 'GroceryController@statistics_grocery_index')->name('grocerydashboard.index');
             Route::get('dashboard/statistics/ajax/grocery-orders', 'GroceryController@ajax_render_grocery_orders')->name('groceryajaxorder.index');
             Route::get('dashboard/statistics/ajax/grocery-otd', 'GroceryController@ajax_render_otd_charts')->name('groceryotdcharts.index');*/

                ###Grocery Dashboard###
             Route::get('grocery/dashboard', 'GroceryDashboardController@statistics_grocery_index')->name('grocerydashboard.index');
             Route::get('dashboard/statistics/ajax/grocery-orders', 'GroceryDashboardController@ajax_render_grocery_orders')->name('groceryajaxorder.index');
             Route::get('dashboard/statistics/ajax/grocery-otd', 'GroceryDashboardController@ajax_render_otd_charts')->name('groceryotdcharts.index');
				Route::get('grocery/new-count', 'GroceryDashboardController@groceryNewCount')->name('grocery-new-count');

       /*       ###Loblaws Dashboard###
        Route::get('loblaws/dashboard', 'LoblawsController@statistics_loblaws_index')->name('loblawsdashboard.index');
        Route::get('dashboard/statistics/ajax/loblaws-orders', 'LoblawsController@ajax_render_loblaws_orders')->name('loblawsajaxorder.index');
        Route::get('dashboard/statistics/ajax/loblaws-otd', 'LoblawsController@ajax_render_otd_charts')->name('loblawsotdcharts.index');
        Route::get('dashboard/statistics/ajax/loblaws-ota', 'LoblawsController@ajax_render_ota_charts')->name('loblawsajaxotacharts.index');
        Route::get('dashboard/statistics/ajax/loblaws-total-order', 'LoblawsController@ajax_render_total_order')->name('loblawstotalorder.index');*/

### New Loblaws Dashboard###
             Route::get('loblaws/dashboard', 'NewLoblawsController@statistics_loblaws_index')->name('loblawsdashboard.index');
             Route::get('dashboard/statistics/ajax/loblaws-orders', 'NewLoblawsController@ajax_render_loblaws_orders')->name('loblawsajaxorder.index');
             Route::get('dashboard/statistics/ajax/loblaws-otd', 'NewLoblawsController@ajax_render_otd_charts')->name('loblawsotdcharts.index');
             Route::get('dashboard/statistics/ajax/loblaws-ota', 'NewLoblawsController@ajax_render_ota_charts')->name('loblawsajaxotacharts.index');
             Route::get('dashboard/statistics/ajax/loblaws-total-order', 'NewLoblawsController@ajax_render_total_order')->name('loblawstotalorder.index');
             Route::get('loblaws/new-count', 'NewLoblawsController@loblawsNewCount')->name('loblaws-new-count');

           /*  ###Loblaws Calgary Dashboard###
        Route::get('loblawscalgary/dashboard', 'LoblawsCalgaryController@statistics_loblaws_index')->name('loblawscalgary.index');
        Route::get('dashboard/statistics/ajax/loblawscalgary-orders', 'LoblawsCalgaryController@ajax_render_loblaws_orders')->name('loblawscalgary_orders.index');
        Route::get('dashboard/statistics/ajax/loblawscalgary-otd', 'LoblawsCalgaryController@ajax_render_otd_charts')->name('loblawscalgary_otd_charts.index');
        Route::get('dashboard/statistics/ajax/loblawscalgary-ota', 'LoblawsCalgaryController@ajax_render_ota_charts')->name('loblawscalgary_ota_charts.index');
        Route::get('dashboard/statistics/ajax/loblawscalgary-total-order', 'LoblawsCalgaryController@ajax_render_total_order')->name('loblawscalgary_total_order.index');*/

###New Loblaws Calgary Dashboard###
             Route::get('loblawscalgary/dashboard', 'NewLoblawsCalgaryController@statistics_loblaws_index')->name('loblawscalgary.index');
             Route::get('dashboard/statistics/ajax/loblawscalgary-orders', 'NewLoblawsCalgaryController@ajax_render_loblaws_orders')->name('loblawscalgary_orders.index');
             Route::get('dashboard/statistics/ajax/loblawscalgary-otd', 'NewLoblawsCalgaryController@ajax_render_otd_charts')->name('loblawscalgary_otd_charts.index');
             Route::get('dashboard/statistics/ajax/loblawscalgary-ota', 'NewLoblawsCalgaryController@ajax_render_ota_charts')->name('loblawscalgary_ota_charts.index');
             Route::get('dashboard/statistics/ajax/loblawscalgary-total-order', 'NewLoblawsCalgaryController@ajax_render_total_order')->name('loblawscalgary_total_order.index');
             Route::get('loblawscalgary/new-count', 'NewLoblawsCalgaryController@loblawsNewCount')->name('loblawscalgary-new-count');

         /*    ###Loblaws Home delivery Dashboard###
        Route::get('loblawshomedelivery/dashboard', 'LoblawsHomeDeliveryController@statistics_loblaws_index')->name('loblawshome.index');
        Route::get('dashboard/statistics/ajax/loblawshomedelivery-orders', 'LoblawsHomeDeliveryController@ajax_render_loblaws_orders')->name('loblawshome_order.index');
        Route::get('dashboard/statistics/ajax/loblawshomedelivery-otd', 'LoblawsHomeDeliveryController@ajax_render_otd_charts')->name('loblawshome_otd_charts.index');
        Route::get('dashboard/statistics/ajax/loblawshomedelivery-ota', 'LoblawsHomeDeliveryController@ajax_render_ota_charts')->name('loblawshome_ota_charts.index');
        Route::get('dashboard/statistics/ajax/loblawshomedelivery-total-order', 'LoblawsHomeDeliveryController@ajax_render_total_order')->name('loblawshome_total_order.index');*/


             ### New Loblaws Home delivery Dashboard###
             Route::get('loblawshomedelivery/dashboard', 'NewLoblawsHomeDeliveryController@statistics_loblaws_index')->name('loblawshome.index');
             Route::get('dashboard/statistics/ajax/loblawshomedelivery-orders', 'NewLoblawsHomeDeliveryController@ajax_render_loblaws_orders')->name('loblawshome_order.index');
             Route::get('dashboard/statistics/ajax/loblawshomedelivery-otd', 'NewLoblawsHomeDeliveryController@ajax_render_otd_charts')->name('loblawshome_otd_charts.index');
             Route::get('dashboard/statistics/ajax/loblawshomedelivery-ota', 'NewLoblawsHomeDeliveryController@ajax_render_ota_charts')->name('loblawshome_ota_charts.index');
             Route::get('dashboard/statistics/ajax/loblawshomedelivery-total-order', 'NewLoblawsHomeDeliveryController@ajax_render_total_order')->name('loblawshome_total_order.index');
             Route::get('loblawshomedelivery/new-count', 'NewLoblawsHomeDeliveryController@loblawsNewCount')->name('loblawshomedelivery-new-count');


			### Loblaws Dashboard Order Reporting Csv ###
             Route::get('loblaws/dashboard/reporting', 'NewLoblawsController@loblaws_dashboard_csv_index')->name('loblaws-dashboard-reporting-csv');
             Route::get('loblaws/dashboard/reporting/csv', 'NewLoblawsController@loblaws_dashboard_csv_download')->name('generate-loblaws-report-csv');
             ### Loblaws Calgary Dashboard Order Reporting Csv ###
             Route::get('loblaws/calgary/dashboard/reporting', 'NewLoblawsCalgaryController@loblaws_calgary_dashboard_csv_index')->name('loblaws-calgary-dashboard-reporting-csv');
             Route::get('loblaws/calgary/dashboard/reporting/csv', 'NewLoblawsCalgaryController@loblaws_calgary_dashboard_csv_download')->name('generate-calgary-loblaws-report-csv');
             ### Loblaws Dashboard Order Reporting Csv ###
             Route::get('loblaws/homedelivery/dashboard/reporting', 'NewLoblawsHomeDeliveryController@loblaws_homedelivery_dashboard_csv_index')->name('loblaws-homedelivery-dashboard-reporting-csv');
             Route::get('loblaws/homedelivery/dashboard/reporting/csv', 'NewLoblawsHomeDeliveryController@loblaws_homedelivery_dashboard_csv_download')->name('generate-loblaws-homedelivery-report-csv');

  ### Good Food Dashboard###
             Route::get('good-food/dashboard', 'NewGoodFoodController@statistics_goodfood_index')->name('goodfood.index');
             Route::get('dashboard/statistics/ajax/good-food-orders', 'NewGoodFoodController@ajax_render_goodfood_orders')->name('goodfood_order.index');
             Route::get('dashboard/statistics/ajax/good-food-otd', 'NewGoodFoodController@ajax_render_goodfood_otd_charts')->name('goodfood_otd_charts.index');
             Route::get('dashboard/statistics/ajax/good-food-ota', 'NewGoodFoodController@ajax_render_goodfood_ota_charts')->name('goodfood_ota_charts.index');
             Route::get('good-food/new-count', 'NewGoodFoodController@goodFoodCount')->name('goodfood-new-count');
             Route::get('good-food/dashboard/reporting', 'NewGoodFoodController@goodfood_dashboard_csv_index')->name('goodfood-dashboard-reporting-csv');
             Route::get('good-food/dashboard/reporting/csv', 'NewGoodFoodController@goodfood_dashboard_csv_download')->name('generate-goodfood-report-csv');


			//WarehouseSorterController routes
             Route::get('alert-system', 'WarehouseSorterController@getindex')->name('alert-system.index');
             Route::get('warehouse/sorter', 'WarehouseSorterController@getindex')->name('warehousesorter.index');
             Route::get('warehouse/sorter/data', 'WarehouseSorterController@warehousesorterlist')->name('warehousesorter.data');
             Route::get('warehouse/sorter/add', 'WarehouseSorterController@add')->name('warehousesorter.add');
             Route::post('warehouse/sorter/create', 'WarehouseSorterController@create')->name('warehousesorter.create');
             Route::get('warehouse/sorter/{id}', 'WarehouseSorterController@profile')->name('warehousesorter.profile');
             Route::get('warehouse/sorter/edit/{id}', 'WarehouseSorterController@edit')->name('warehousesorter.edit');
             Route::put('warehouse/sorter/update/{id}', 'WarehouseSorterController@update')->name('warehousesorter.update');
             Route::delete('warehouse/sorter/{id}', 'WarehouseSorterController@destroy')->name('warehousesorter.delete');


             //Setting routes
             Route::get('setting', 'SettingController@getIndex')->name('setting.index');
             Route::get('setting/data', 'SettingController@getListData')->name('setting.data');
             Route::get('setting/add', 'SettingController@add')->name('setting.add');
             Route::post('setting/create', 'SettingController@create')->name('setting.create');
             Route::get('setting/{id}', 'SettingController@profile')->name('setting.profile');
             Route::get('setting/edit/{id}', 'SettingController@edit')->name('setting.edit');
             Route::put('setting/update/{id}', 'SettingController@update')->name('setting.update');

        ###Other Action###
       Route::post('hub/routific/updatestatus','RoutificController@poststatusupdate')->name('hub-routific-update.Update');
        Route::get('hub/routific/status', 'RoutificController@getstatus')->name('hub-routific.index');

        /* Route::post('update/multiple/trackingid','SearchOrdersController@post_multiOrderUpdates')->name('multiple-tracking-id.update');
        Route::get('update/multiple/trackingid','SearchOrdersController@get_multiOrderUpdates')->name('multiple-tracking-id.index');

        Route::get('searchorder/trackingid', 'SearchOrdersController@get_trackingid')->name('searchorder.index');
        Route::get('search/orders/trackingid/{id}/details','SearchOrdersController@get_trackingorderdetails')->name('searchorder.show');
        Route::post('update/order/status','SearchOrdersController@updatestatus')->name('update-order.update');

        Route::get('search/trackingid/multiple', 'SearchOrdersController@get_multipletrackingid')->name('search-multiple-tracking.index');

        Route::get('route/delete', 'RoutificController@getdeleteRouteview')->name('route.index');
        Route::post('route/delete', 'RoutificController@deleteRouteId')->name('route.destroy');*/

        route::post('update/multiple/trackingid','SearchOrdersController@post_multiorderupdates')->name('multiple-tracking-id.update');
        route::get('update/multiple/trackingid','SearchOrdersController@get_multiorderupdates')->name('multiple-tracking-id.index');

		Route::get('test/search/trackingid/multiple', 'SearchOrdersController@get_multipletrackingidTest')->name('test-search-multiple-tracking.index');
Route::post('update/order/status/test','SearchOrdersController@updatestatustest')->name('test-update-order.update');

Route::post('update/order/status','SearchOrdersController@updatestatus')->name('update-order.update');
route::get('search/orders/trackingid/{id}/details','SearchOrdersController@get_trackingorderdetails')->name('searchorder.show');
route::get('search/ordersid/{id}/details','SearchOrdersController@get_orderIdDetails')->name('searchorderid.show');
             Route::get('search/trackingid/multiple', 'SearchOrdersController@get_multipletrackingid')->name('search-multiple-tracking.index');
Route::post('sprint/image/upload','SearchOrdersController@sprintImageUpload')->name('sprint-image-upload');
             Route::post('sprint/image/edit','SearchOrdersController@sprintImageUpdate')->name('sprint-image-update');
			 ###Create Flags###
             Route::get('flag/create/{id}', 'FlagOrdersController@createFlag')->name('flag.create');
             ###Un-Flag Flags###
             Route::get('un-flag/{id}', 'FlagOrdersController@unFlag')->name('un-flag');
             ###Flag orders list###
             Route::get('flag-order-list/data', 'FlagOrdersController@FlagOrderListData')->name('flag-order-list.data');
             Route::get('flag-order-list-pie-chart-data', 'FlagOrdersController@FlagOrderListPieChartData')->name('flag-order-list-pie-chart-data');
             Route::get('flag-order-list', 'FlagOrdersController@FlagOrderList')->name('flag-order-list.index');
             Route::get('flag-orders-list/details/{id}', 'FlagOrdersController@FlagOrderDetails')->name('flag-order.details');

             Route::get('approved-flag-list/data', 'FlagOrdersController@ApprovedFlagListData')->name('approved-flag-list.data');
             Route::get('approved-flag-list', 'FlagOrdersController@ApprovedFlagList')->name('approved-flag-list.index');

             Route::get('un-approved-flag-list/data', 'FlagOrdersController@UnApprovedFlagListData')->name('un-approved-flag-list.data');
             Route::get('un-approved-flag-list', 'FlagOrdersController@UnApprovedFlagList')->name('un-approved-flag-list.index');
             Route::get('multiple/approved/flag', 'FlagOrdersController@multipleApprovedFlag')->name('multiple.approved.flag');

             ###Blocked Joey List To Un-blocked###
             Route::get('block-joey-flag-list/data', 'FlagOrdersController@BlockJoeyFlagListData')->name('block-joey-flag-list.data');
             Route::get('block-joey-flag-list', 'FlagOrdersController@BlockJoeyFlagList')->name('block-joey-flag-list.index');
             Route::get('block-joey-flag/unblock/{id}', 'FlagOrdersController@UnblockJoeyFlag')->name('unblock-joey-flag.update');
             Route::get('joey-flag/performance/{id}', 'FlagOrdersController@JoeyPerformanceStatus')->name('joey-performance-status.update');

			 ###Grocery Create Flags###
             Route::get('grocery/flag/create/{id}', 'FlagOrdersController@groceryCreateFlag')->name('grocery-flag.create');
             ###Grocery Un-Flag Flags###
             Route::get('grocery/un-flag/{id}', 'FlagOrdersController@groceryUnFlag')->name('grocery-un-flag');
             ###Mark Flag Approved###
             Route::get('grocery/joey-flag/performance/{id}', 'FlagOrdersController@groceryJoeyPerformanceStatus')->name('grocery-joey-performance-status.update');
             ###Grocery Flag orders list###
             Route::get('grocery/flag-order-list/data', 'FlagOrdersController@groceryFlagOrderListData')->name('grocery-flag-order-list.data');
             Route::get('grocery/flag-order-list-pie-chart-data', 'FlagOrdersController@groceryFlagOrderListPieChartData')->name('grocery-flag-order-list-pie-chart-data');
             Route::get('grocery/flag-order-list', 'FlagOrdersController@groceryFlagOrderList')->name('grocery-flag-order-list.index');
             Route::get('grocery/flag-orders-list/details/{id}', 'FlagOrdersController@groceryFlagOrderDetails')->name('grocery-flag-order.details');
             ###Grocery Approved Flag Order ###
             Route::get('grocery/approved-flag-list/data', 'FlagOrdersController@groceryApprovedFlagListData')->name('grocery-approved-flag-list.data');
             Route::get('grocery/approved-flag-list', 'FlagOrdersController@groceryApprovedFlagList')->name('grocery-approved-flag-list.index');
             ###Grocery Un-Approved Order###
             Route::get('grocery/un-approved-flag-list/data', 'FlagOrdersController@groceryUnApprovedFlagListData')->name('grocery-un-approved-flag-list.data');
             Route::get('grocery/un-approved-flag-list', 'FlagOrdersController@groceryUnApprovedFlagList')->name('grocery-un-approved-flag-list.index');

             ### mark approved flag multiple
             Route::get('multiple/approved/flag/grocery', 'FlagOrdersController@multipleApprovedFlagGrocery')->name('multiple.approved.flag.grocery');
             ###Grocery Blocked Joey List To Un-blocked###
             Route::get('grocery-block-joey-flag-list/data', 'FlagOrdersController@groceryBlockJoeyFlagListData')->name('grocery-block-joey-flag-list.data');
             Route::get('grocery-block-joey-flag-list', 'FlagOrdersController@groceryBlockJoeyFlagList')->name('grocery-block-joey-flag-list.index');
             Route::get('grocery-block-joey-flag/unblock/{id}', 'FlagOrdersController@groceryUnblockJoeyFlag')->name('grocery-unblock-joey-flag.update');

   ### Manual Status Update ###
             Route::get('manual/status', 'ManualStatusController@getManualStatus')->name('manual-status.index');
             Route::get('manual/status/data', 'ManualStatusController@ManualStatusData')->name('manual-status.data');

             ## Joey Order Instruction
             Route::get('joey/orders', 'UpdateJoeyOrderInstructionController@index')->name('joey.orders.index');
             Route::post('joey/order/instruction', 'UpdateJoeyOrderInstructionController@addJoeyOrderInstruction')->name('joey.orders.instruction');

			 ### Manual Tracking Report ###
             Route::get('manual/tracking/report', 'ManualTrackingReportController@getManualTrackingReport')->name('manual-tracking-report.index');
             Route::get('manual/tracking/data', 'ManualTrackingReportController@ManualTrackingData')->name('manual-tracking.data');
             //Route::get('manual/tracking/{date?}', 'ManualTrackingReportController@manualTrackingExcel')->name('manual-tracking.excel');
//Route::get('manual/tracking/{date?}/{todate?}', 'ManualTrackingReportController@manualTrackingExcel')->name('manual-tracking.excel');

Route::post('manual/tracking/csv-download', 'ManualTrackingReportController@downloadCsv')->name('manual-tracking.excel');
            Route::get('/download-file-tracking', function () {
                // getting file path
                $file_path  = public_path().'/'.request()->file_path;
                // getting file name
                $file_name =explode('/',$file_path);
                $file_name = explode('-',end($file_name))[0];
                // getting file extension
                $file_extension = explode('.',$file_path);
                $file_extension = end($file_extension);
                return response()->download($file_path, $file_name.'.'.$file_extension);
            })->name('download-file-tracking');

            ## CTC Reporting order ##
            Route::get('reporting', 'ReportingController@getReporting')->name('reporting.index');
            Route::get('reporting/data', 'ReportingController@reportingdata')->name('reporting.data');
            Route::get('reporting/excel/{vendor?}/{fromdate?}/{todate?}', 'ReportingController@reportingexcel')->name('export_reporting.excel');


            ## DNR Report ##
            Route::get('dnr/reporting', 'DnrController@getDnr')->name('dnr.index');
            Route::get('dnr/data', 'DnrController@dnrData')->name('dnr.data');
            Route::get('dnr/excel/{tracking_id?}', 'DnrController@dnrExcel')->name('dnr.export');


             ###Ctc Sub Admins###
             Route::get('ctc/subadmins', 'CtcSubAdminController@getIndex')->name('ctc-subadmin.index');
             Route::get('ctc/subadmin/list', 'CtcSubAdminController@subAdminList')->name('ctc-subadmin.data');
             Route::get('ctc/subadmin/add', 'CtcSubAdminController@add')->name('ctc-subadmin.add');
             Route::post('ctc/subadmin/create', 'CtcSubAdminController@create')->name('ctc-subadmin.create');
             Route::get('ctc/subadmin/profile/{id}', 'CtcSubAdminController@profile')->name('ctc-subadmin.profile');
             Route::get('ctc/subadmin/edit/{user}', 'CtcSubAdminController@edit')->name('ctc-subadmin.edit');
             Route::put('ctc/subadmin/update/{user}', 'CtcSubAdminController@update')->name('ctc-subadmin.update');
             Route::delete('ctc/subadmin/{user}', 'CtcSubAdminController@destroy')->name('ctc-subadmin.delete');
             Route::get('ctc/subadmin/active/{record}', 'CtcSubAdminController@active')->name('ctc-subadmin.active');
             Route::get('ctc/subadmin/inactive/{record}', 'CtcSubAdminController@inactive')->name('ctc-subadmin.inactive');


             ###Mark Delay Reason###
             Route::get('reason', 'ReasonController@getIndex')->name('reason.index');
             Route::get('reason/list', 'ReasonController@ReasonList')->name('reason.data');
             Route::get('reason/add', 'ReasonController@add')->name('reason.add');
             Route::post('reason/create', 'ReasonController@create')->name('reason.create');
             Route::get('reason/edit/{reason}', 'ReasonController@edit')->name('reason.edit');
             Route::put('reason/update/{reason}', 'ReasonController@update')->name('reason.update');
             Route::delete('reason/{reason}', 'ReasonController@destroy')->name('reason.delete');

             ###Sub Admins###
        Route::get('subadmins', 'SubadminController@getIndex')->name('sub-admin.index');
        Route::get('sub/admins/list', 'SubadminController@subAdminList')->name('subAdmin.data');
        Route::get('subadmin/add', 'SubadminController@add')->name('subAdmin.add');

        Route::post('subadmin/create', 'SubadminController@create')->name('subAdmin.create');

        Route::get('sub/admin/profile/{id}', 'SubadminController@profile')->name('subAdmin.profile');

        Route::get('subadmin/edit/{user}', 'SubadminController@edit')->name('subAdmin.edit');

        Route::put('subadmin/update/{user}', 'SubadminController@update')->name('subAdmin.update');

        Route::delete('sub/admin/{user}', 'SubadminController@destroy')->name('subAdmin.delete');
        Route::delete('changeStatus', 'SubadminController@changeStatus');
        Route::get('sub-admin/active/{record}', 'SubadminController@active')->name('sub-admin.active');
        Route::get('sub-admin/inactive/{record}', 'SubadminController@inactive')->name('sub-admin.inactive');

		 Route::get('account/security/edit/{user}', 'SubadminController@accountSecurityEdit')->name('account-security.edit');

             Route::put('account/security/{user}', 'SubadminController@accountSecurityUpdate')->name('account-security.update');

        Route::get('changepwd', 'SubadminController@getChangePwd')->name('sub-admin-change.password');

		//add route name for change password issue
        Route::post('changepwd/create', 'SubadminController@changepwd')->name('sub-admin-create.password');

        //Admin Edit Route
        Route::get('adminedit/{user}', 'SubadminController@adminedit');

        Route::put('admin/update/{user}', 'SubadminController@adminupdate');

        /*role management routes*/
        Route::resource('role', 'RoleController');
        Route::get('role/set-permissions/{role}', 'RoleController@setpermissions')->name('role.set-permissions');
        Route::post('role/set-permissions/update/{role}', 'RoleController@setpermissionsupdate')->name('role.set-permissions.update');

//        Route::get('adminupdate/{user}', 'SubadminController@adminupdate');

        //Loblaws Batch Order Re-Processing
        Route::get('loblaws/order/reprocessing', 'LoblawsController@get_scheduleOrders')->name('loblaws.order-reprocessing');
        Route::post('loblaws/order/reprocessing', 'LoblawsController@post_resheduledOrder')->name('loblaws.order-reprocessing-update');

		   Route::resource('manager', 'ManagerController');
        Route::post('warehouse/check-for-hub', 'WarehouseSorterController@checkForHub')->name('check-for-hub');
        });

        Route::get('search/tracking', 'SearchOrdersController@SearchTracking')->name('search-tracking.index');
        Route::get('search/tracking-details/{id}', 'SearchOrdersController@SearchTrackingDetails')->name('searchtrackingdetails.show');

        ###CTC Client Dashboard Broker###
        Route::get('ctc-dashboard-broker', 'CtcEntriesController@getCtcDashboardBroker')->name('ctc-dashboard-broker.index');
        Route::get('ctc-dashboard-broker/data', 'CtcEntriesController@getCtcDashboardBrokerData')->name('ctc-dashboard-broker.data');
        Route::get('ctc-broker-detail/{id}', 'CtcEntriesController@ctcBrokerProfile')->name('ctc-broker.profile');



        ### Ottawa-Dashboard Walmart ###
        Route::get('ottawa-dashboard', 'OttawaDashboardController@getOttawaDashboard')->name('ottawa-dashboard.index');
        Route::get('ottawa-dashboard/data', 'OttawaDashboardController@getOttawaDashboardData')->name('ottawa-dashboard.data');
        Route::get('ottawa-dashboard/order/detail/{id}', 'OttawaDashboardController@ottawaProfile')->name('ottawa-order.profile');
        Route::get('ottawa-dashboard/returned/detail/{id}', 'OttawaDashboardController@ottawareturnedDetail')->name('ottawa-returned-detail.profile');
        Route::get('ottawa-dashboard/returned-not-hub/detail/{id}', 'OttawaDashboardController@ottawaNotReturnedDetail')->name('ottawa-notreturned-detail.profile');
        Route::get('ottawa-dashboard/delivered/detail/{id}', 'OttawaDashboardController@ottawadeliveredDetail')->name('ottawa-delivered-detail.profile');
        Route::get('ottawa-dashboard/list/{date?}/{vendor?}', 'OttawaDashboardController@ottawaDashboardExcel')->name('ottawa-dashboard-export.excel');
        Route::get('ottawa-dashboard/otd/report/{date?}/{vendor?}', 'OttawaDashboardController@ottawaDashboardExcelOtdReport')->name('ottawa-dashboard-export-otd-report.excel');
        ### Ottawa-Dashboard Card ###
        Route::get('ottawa-dashboard/card-dashboard', 'OttawaDashboardController@getOttawaCards')->name('ottawa-card-dashboard.index');
        ### Ottawa-Dashboard Orders ###
        Route::get('ottawa-dashboard/order/data', 'OttawaDashboardController@getOttawaData')->name('new-order-ottawa.data');
        Route::get('ottawa-dashboard/order', 'OttawaDashboardController@getOttawa')->name('new-order-ottawa.index');
        Route::get('ottawa-dashboard/order/list/{date?}/{vendor?}', 'OttawaDashboardController@getOttawaExcel')->name('new-order-ottawa-export.excel');
        Route::get('ottawa-dashboard/detail/{id}', 'OttawaDashboardController@getOttawaProfile')->name('ottawa-detail-detail.profile');
        ### Ottawa-Dashboard Returned ###
        Route::get('ottawa-dashboard/returned', 'OttawaDashboardController@getOttawaeturned')->name('new-returned-ottawa.index');
        Route::get('ottawa-dashboard/returned/data', 'OttawaDashboardController@ottawaReturnedData')->name('new-returned-ottawa.data');
        Route::get('ottawa-dashboard/returned/list/{date?}/{vendor?}', 'OttawaDashboardController@OttawaReturnedExcel')->name('new-returned-ottawa-export.excel');
        ### Ottawa-Dashboard Returned not hub ###
        Route::get('ottawa-dashboard/returned-not-hub', 'OttawaDashboardController@getOttawaNotreturned')->name('new-notreturned-ottawa.index');
        Route::get('ottawa-dashboard/returned-not-hub/data', 'OttawaDashboardController@ottawaNotReturnedData')->name('new-notreturned-ottawa.data');
        Route::get('ottawa-dashboard/returned-not-hub/list/{date?}', 'OttawaDashboardController@OttawaNotReturnedExcel')->name('new-notreturned-ottawa-export.excel');
        Route::get('ottawa-dashboard/returned-not-hub/tracking/list/{date?}', 'OttawaDashboardController@OttawaNotReturnedExcelTrackingIds')->name('new-notreturned-ottawa-tracking-export.excel');

        ### Ottawa-Dashboard sorted ###
        Route::get('ottawa-dashboard/sorted/detail/{id}', 'OttawaDashboardController@ottawasortedDetail')->name('ottawa-sorted-detail.profile');
        Route::get('ottawa-dashboard/sorted', 'OttawaDashboardController@getOttawaSorter')->name('new-sort-ottawa.index');
        Route::get('ottawa-dashboard/sorted/data', 'OttawaDashboardController@ottawaSortedData')->name('new-sort-ottawa.data');
        Route::get('ottawa-dashboard/sorted/list/{date?}/{vendor?}', 'OttawaDashboardController@ottawaSortedExcel')->name('new-sort-ottawa-export.excel');
        ### Ottawa-Dashboard Out for delivery ###
        Route::get('ottawa-dashboard/picked/up', 'OttawaDashboardController@getOttawahub')->name('new-pickup-ottawa.index');
        Route::get('ottawa-dashboard/picked/up/data', 'OttawaDashboardController@ottawaPickedUpData')->name('new-pickup-ottawa.data');
        Route::get('ottawa-dashboard/picked/up/list/{date?}/{vendor?}', 'OttawaDashboardController@boradlessPickedupExcel')->name('new-pickup-ottawa-export.excel');
        Route::get('ottawa-dashboard/pickup/detail/{id}', 'OttawaDashboardController@ottawapickupDetail')->name('ottawa-pickup-detail.profile');
        ### Ottawa-Dashboard Delivered ###
        Route::get('ottawa-dashboard/delivered', 'OttawaDashboardController@getOttawadelivered')->name('new-delivered-ottawa.index');
        Route::get('ottawa-dashboard/delivered/data', 'OttawaDashboardController@ottawaDeliveredData')->name('new-delivered-ottawa.data');
        Route::get('ottawa-dashboard/delivered/list/{date?}/{vendor?}', 'OttawaDashboardController@ottawaDeliveredExcel')->name('new-delivered-ottawa-export.excel');
        ### Ottawa-Dashboard Not Scan ###
        Route::get('ottawa-dashboard/notscan/detail/{id}', 'OttawaDashboardController@ottawanotscanDetail')->name('ottawa-notscan-detail.profile');
        Route::get('ottawa-dashboard/not/scan', 'OttawaDashboardController@getOttawascan')->name('new-not-scan-ottawa.index');
        Route::get('ottawa-dashboard/not/scan/data', 'OttawaDashboardController@ottawaNotScanData')->name('new-not-scan-ottawa.data');
        Route::get('ottawa-dashboard/not/scan/list/{date?}/{vendor?}', 'OttawaDashboardController@ottawaNotscanExcel')->name('new-not-scan-ottawa-export.excel');
        ### Ottawa-Dashboard Custom Route ###
        Route::get('ottawa-dashboard/custom-route', 'OttawaDashboardController@getOttawaCustomRoute')->name('new-custom-route-ottawa.index');
        Route::get('ottawa-dashboard/custom-route/data', 'OttawaDashboardController@ottawaCustomRouteData')->name('new-custom-route-ottawa.data');
        Route::get('ottawa-dashboard/custom-route/list/{date?}/{vendor?}', 'OttawaDashboardController@ottawaCustomRouteExcel')->name('new-custom-route-ottawa-export.excel');
        Route::get('ottawa-dashboard/custom-route/detail/{id}', 'OttawaDashboardController@ottawaCustomRouteDetail')->name('ottawa-CustomRoute-detail.profile');
        ### Ottawa-Dashboard Reporting###
        Route::get('/ottawa-dashboard/reporting', 'OttawaDashboardController@getOttawaReporting')->name('ottawa_reporting.index');
        Route::get('yajra/ottawa-dashboard/reporting', 'OttawaDashboardController@getOttawaReportingData')->name('new_ottawa_reporting_data.data');
        ### Ottawa-Dashboard OTD ###
        Route::get('ottawa-dashboard/graph', 'OttawaDashboardController@statistics_otd_index')->name('ottawa-graph.index');
        Route::get('ottawa-dashboard/statistics/ajax/otd-day', 'OttawaDashboardController@ajax_render_ottawa_otd_day')->name('ottawa-otd-day.index');
        Route::get('ottawa-dashboard/statistics/ajax/otd-week', 'OttawaDashboardController@ajax_render_ottawa_otd_week')->name('ottawa-otd-week.index');
        Route::get('ottawa-dashboard/statistics/ajax/otd-month', 'OttawaDashboardController@ajax_render_ottawa_otd_month')->name('ottawa-otd-month.index');
        ### Ottawa-Dashboard Route Info ###
        Route::get('ottawa-dashboard/route-info', 'OttawaDashboardController@getRouteinfo')->name('ottawa-dashboard-route-info.index');
        Route::get('ottawa-dashboard/route-info/list/{date?}', 'OttawaDashboardController@ottawaRouteinfoExcel')->name('new-export_OttawaRouteInfo.excel');
        Route::get('ottawa-dashboard/route/{di}/edit/hub/{id}', 'OttawaDashboardController@ottawaHubRouteEdit')->name('ottawa_dashboard_route.detail');
        Route::post('ottawa-dashboard/route-details/flag-history-model-html-render', 'OttawaDashboardController@flagHistoryModelHtmlRender')->name('Ottawainfo_route.route-details.flag-history-model-html-render');
        Route::get('ottawa-dashboard/route/orders/trackingid/{id}/details', 'OttawaDashboardController@getOttawatrackingorderdetails')->name('ottawa-dashboard-info_route.detail');
        Route::post('ottawa-dashboard/route/mark/delay', 'OttawaDashboardController@routeMarkDelay')->name('ottawa-route-mark-delay');
        Route::post('ottawa-dashboard/route-info/add-note', 'OttawaDashboardController@addNote')->name('ottawa-route-info.addNote');
        Route::get('ottawa-dashboard/route-info/get-notes', 'OttawaDashboardController@getNotes')->name('ottawa-route-info.getNotes');

        Route::get('ottawa-dashboard/pickup/from/store', 'OttawaDashboardController@getOttawaPickedUpFromStore')->name('PickedUpFrom-ottawa.index');
        Route::get('ottawa-dashboard/at/store', 'OttawaDashboardController@getOttawaAtStore')->name('AtStore-ottawa.index');
        Route::get('ottawa-dashboard/at/hub', 'OttawaDashboardController@getOttawaAtHub')->name('AtHub-ottawa.index');
		Route::get('ottawa-dashboard/sorted-at', 'OttawaDashboardController@getOttawaSortedAt')->name('SortedAt-ottawa.index');
		Route::get('ottawa-dashboard/delivered-orders', 'OttawaDashboardController@getOttawaDeliveredOrder')->name('DeliveredOrder-ottawa.index');
        Route::get('ottawa-dashboard/at/store/data', 'OttawaDashboardController@ottawaAtStoreData')->name('AtStore-ottawa.data');


		### New York-Dashboard ###
        Route::get('newyork-dashboard', 'NewYorkController@getNewyorkDashboard')->name('newyork-dashboard.index');
        Route::get('newyork-dashboard/data', 'NewYorkController@getnewyorkDashboardData')->name('newyork-dashboard.data');
        Route::get('newyork-dashboard/order/detail/{id}', 'NewYorkController@newyorkProfile')->name('newyork-order.profile');
        Route::get('newyork-dashboard/returned/detail/{id}', 'NewYorkController@newyorkreturnedDetail')->name('newyork-returned-detail.profile');
        Route::get('newyork-dashboard/returned-not-hub/detail/{id}', 'NewYorkController@newyorkNotReturnedDetail')->name('newyork-notreturned-detail.profile');
        Route::get('newyork-dashboard/list/{date?}/{vendor?}', 'NewYorkController@newyorkDashboardExcel')->name('newyork-dashboard-export.excel');
        Route::get('newyork-dashboard/otd/report/{date?}/{vendor?}', 'NewYorkController@newyorkDashboardExcelOtdReport')->name('newyork-dashboard-export-otd-report.excel');
        ### New York-Dashboard Card ###
        Route::get('newyork/card-dashboard', 'NewYorkController@getNewyorkCards')->name('newyork-card-dashboard.index');
        ### New York-Dashboard Orders ###
        Route::get('newyork-dashboard/order/data', 'NewYorkController@getNewyorkData')->name('new-order-newyork.data');
        Route::get('newyork-dashboard/order', 'NewYorkController@getNewyork')->name('new-order-newyork.index');
        Route::get('newyork-dashboard/order/list/{date?}/{vendor?}', 'NewYorkController@getNewYorkExcel')->name('order-newyork-export.excel');
        Route::get('newyork-dashboard/detail/{id}', 'NewYorkController@getnewyorkProfile')->name('newyork-detail-detail.profile');
        ### New York-Dashboard Returned ###
        Route::get('newyork-dashboard/returned', 'NewYorkController@getnewyorkreturned')->name('returned-newyork.index');
        Route::get('newyork-dashboard/returned/data', 'NewYorkController@newyorkReturnedData')->name('returned-newyork.data');
        Route::get('newyork-dashboard/returned/list/{date?}/{vendor?}', 'NewYorkController@newyorkReturnedExcel')->name('returned-newyork-export.excel');
        ### New York-Dashboard Returned not hub ###
        Route::get('newyork-dashboard/returned-not-hub', 'NewYorkController@getNewyorkNotreturned')->name('notreturned-newyork.index');
        Route::get('newyork-dashboard/returned-not-hub/data', 'NewYorkController@newyorkNotReturnedData')->name('notreturned-newyork.data');
        Route::get('newyork-dashboard/returned-not-hub/list/{date?}', 'NewYorkController@newyorkNotReturnedExcel')->name('notreturned-newyork-export.excel');
        Route::get('newyork-dashboard/returned-not-hub/tracking/list/{date?}', 'NewYorkController@NewyorkNotReturnedExcelTrackingIds')->name('notreturned-newyork-tracking-export.excel');
        ### New York-Dashboard sorted ###
        Route::get('newyork-dashboard/sorted/detail/{id}', 'NewYorkController@newyorksortedDetail')->name('newyork-sorted-detail.profile');
        Route::get('newyork-dashboard/sorted', 'NewYorkController@getNewyorkSorter')->name('sort-newyork.index');
        Route::get('newyork-dashboard/sorted/data', 'NewYorkController@newyorkSortedData')->name('sort-newyork.data');
        Route::get('newyork-dashboard/sorted/list/{date?}/{vendor?}', 'NewYorkController@newyorkSortedExcel')->name('sort-newyork-export.excel');
        ### New York-Dashboard Out for delivery ###
        Route::get('newyork-dashboard/picked/up', 'NewYorkController@getnewyorkhub')->name('pickup-newyork.index');
        Route::get('newyork-dashboard/picked/up/data', 'NewYorkController@newyorkPickedUpData')->name('pickup-newyork.data');
        Route::get('newyork-dashboard/picked/up/list/{date?}/{vendor?}', 'NewYorkController@newyorkPickedupExcel')->name('pickup-newyork-export.excel');
        Route::get('newyork-dashboard/pickup/detail/{id}', 'NewYorkController@newyorkpickupDetail')->name('newyork-pickup-detail.profile');
        ### New York-Dashboard Delivered ###
        Route::get('newyork-dashboard/delivered', 'NewYorkController@getnewyorkdelivered')->name('delivered-newyork.index');
        Route::get('newyork-dashboard/delivered/data', 'NewYorkController@newyorkDeliveredData')->name('delivered-newyork.data');
        Route::get('newyork-dashboard/delivered/detail/{id}', 'NewYorkController@newyorkdeliveredDetail')->name('newyork-delivered-detail.profile');
        Route::get('newyork-dashboard/delivered/list/{date?}/{vendor?}', 'NewYorkController@newyorkDeliveredExcel')->name('delivered-newyork-export.excel');
        ### New York-Dashboard Not Scan ###
        Route::get('newyork-dashboard/notscan/detail/{id}', 'NewYorkController@newyorknotscanDetail')->name('newyork-notscan-detail.profile');
        Route::get('newyork-dashboard/not/scan', 'NewYorkController@getnewyorkScan')->name('not-scan-newyork.index');
        Route::get('newyork-dashboard/not/scan/data', 'NewYorkController@newyorkNotScanData')->name('not-scan-newyork.data');
        Route::get('newyork-dashboard/not/scan/list/{date?}/{vendor?}', 'NewYorkController@newyorkNotscanExcel')->name('not-scan-newyork-export.excel');
        ### New York-Dashboard Custom Route ###
        Route::get('newyork-dashboard/custom-route', 'NewYorkController@getnewyorkCustomRoute')->name('custom-route-newyork.index');
        Route::get('newyork-dashboard/custom-route/data', 'NewYorkController@newyorkCustomRouteData')->name('custom-route-newyork.data');
        Route::get('newyork-dashboard/custom-route/list/{date?}/{vendor?}', 'NewYorkController@newyorkCustomRouteExcel')->name('custom-route-newyork-export.excel');
        Route::get('newyork-dashboard/custom-route/detail/{id}', 'NewYorkController@newyorkCustomRouteDetail')->name('newyork-CustomRoute-detail.profile');
        ### New York-Dashboard Reporting###
        Route::get('/newyork-dashboard/reporting', 'NewYorkController@getnewyorkReporting')->name('newyork_reporting.index');
        Route::get('yajra/newyork-dashboard/reporting', 'NewYorkController@getnewyorkReportingData')->name('newyork_reporting_data.data');
        ### New York-Dashboard OTD ###
        Route::get('newyork-dashboard/graph', 'NewYorkController@statistics_otd_index')->name('newyork-graph.index');
        Route::get('newyork-dashboard/statistics/ajax/otd-day', 'NewYorkController@ajax_render_newyork_otd_day')->name('newyork-otd-day.index');
        Route::get('newyork-dashboard/statistics/ajax/otd-week', 'NewYorkController@ajax_render_newyork_otd_week')->name('newyork-otd-week.index');
        Route::get('newyork-dashboard/statistics/ajax/otd-month', 'NewYorkController@ajax_render_newyork_otd_month')->name('newyork-otd-month.index');
        ### New York-Dashboard Route Info ###
        Route::get('newyork-dashboard/route-info', 'NewYorkController@getRouteinfo')->name('newyork-dashboard-route-info.index');
        Route::get('newyork-dashboard/route-info/list/{date?}', 'NewYorkController@newyorkRouteinfoExcel')->name('new-export_newyorkRouteInfo.excel');
        Route::get('newyork-dashboard/route/{di}/edit/hub/{id}', 'NewYorkController@newyorkHubRouteEdit')->name('newyork_dashboard_route.detail');
        Route::post('newyork-dashboard/route-details/flag-history-model-html-render', 'NewYorkController@flagHistoryModelHtmlRender')->name('newyorkinfo_route.route-details.flag-history-model-html-render');
        Route::get('newyork-dashboard/route/orders/trackingid/{id}/details', 'NewYorkController@getnewyorktrackingorderdetails')->name('newyork-dashboard-info_route.detail');
        Route::post('newyork-dashboard/route/mark/delay', 'NewYorkController@routeMarkDelay')->name('newyork-route-mark-delay');
        Route::post('newyork-dashboard/route-info/add-note', 'NewYorkController@addNote')->name('newyork-route-info.addNote');
        Route::get('newyork-dashboard/route-info/get-notes', 'NewYorkController@getNotes')->name('newyork-route-info.getNotes');

		### Walmart E-commerce Dashboard ###
		Route::get('walmart/e-commerce', 'WalmartController@getWalmartEcommerce')->name('e-commerce-dashboard.index');
		Route::get('e-commerce/data', 'WalmartController@WalmartEcommerceData')->name('e-commerce.data');
		Route::get('e-commerce/detail/{id}', 'WalmartController@Ecommerce_Profile')->name('e-commerce.profile');
        Route::get('e-commerce/dashboard/list/{date?}/{vendor_id?}', 'WalmartController@walmartDashboardExcel')->name('walmart-dashboard-export.excel');

		### New Walmart Ecommerce  Dashboard ###
        Route::get('walmart/e-commerce/order/data', 'WalmartController@getWalmartEcommerceDashboardData')->name('new-order-walmart-ecommerce.data');
        Route::get('walmart/e-commerce/order', 'WalmartController@getWalmartEcommerceDashboard')->name('new-order-walmart-ecommerce.index');
        Route::get('walmart/e-commerce/order/list/{date?}/{vendor_id?}', 'WalmartController@getBoradlessExcel')->name('new-order-walmart-ecommerce-export.excel');
        Route::get('walmart/e-commerce/order/list/{date?}/{vendor_id?}', 'WalmartController@getWalmartEcommerceDashboardExcel')->name('new-order-walmart-ecommerce-export.excel');
        ### Walmart Ecommerce Sorted ###
        Route::get('walmart/e-commerce/sorted', 'WalmartController@getWalmartEcommerceSorter')->name('new-sort-walmart-ecommerce.index');
        Route::get('walmart/e-commerce/sorted/data', 'WalmartController@walmartEcommerceSortedData')->name('new-sort-walmart-ecommerce.data');
        Route::get('walmart/e-commerce/sorted/list/{date?}/{vendor_id?}', 'WalmartController@walmartEcommerceSortedExcel')->name('new-sort-walmart-ecommerce-export.excel');
        ### Walmart Ecommerce Hub ###
        Route::get('walmart/e-commerce/picked/up', 'WalmartController@getWalmartEcommercehub')->name('new-pickup-walmart-ecommerce.index');
        Route::get('walmart/e-commerce/picked/up/data', 'WalmartController@walmartEcommercePickedUpData')->name('new-pickup-walmart-ecommerce.data');
        Route::get('walmart/e-commerce/picked/up/list/{date?}/{vendor_id?}', 'WalmartController@walmartEcommercePickedupExcel')->name('new-pickup-walmart-ecommerce-export.excel');
        ### Walmart Ecommerce Not Scan ###
        Route::get('walmart/e-commerce/not/scan', 'WalmartController@getWalmartEcommercescan')->name('new-not-scan-walmart-ecommerce.index');
        Route::get('walmart/e-commerce/not/scan/data', 'WalmartController@walmartEcommerceNotScanData')->name('new-not-scan-walmart-ecommerce.data');
        Route::get('walmart/e-commerce/not/scan/list/{date?}/{vendor_id?}', 'WalmartController@walmartEcommercescanExcel')->name('new-not-scan-walmart-ecommerce-export.excel');
        ### Walmart Ecommerce Delivered ###
        Route::get('walmart/e-commerce/delivered', 'WalmartController@getWalmartEcommercedelivered')->name('new-delivered-walmart-ecommerce.index');
        Route::get('walmart/e-commerce/delivered/data', 'WalmartController@walmartEcommerceDeliveredData')->name('new-delivered-walmart-ecommerce.data');
        Route::get('walmart/e-commerce/delivered/list/{date?}/{vendor_id?}', 'WalmartController@walmartEcommerceDeliveredExcel')->name('new-delivered-walmart-ecommerce-export.excel');
        ### Walmart Ecommerce Returned ###
        Route::get('walmart/e-commerce/returned', 'WalmartController@getWalmartEcommercereturned')->name('new-returned-walmart-ecommerce.index');
        Route::get('walmart/e-commerce/returned/data', 'WalmartController@walmartEcommerceReturnedData')->name('new-returned-walmart-ecommerce.data');
        Route::get('walmart/e-commerce/returned/list/{date?}/{vendor_id?}', 'WalmartController@walmartEcommerceReturnedExcel')->name('new-returned-walmart-ecommerce-export.excel');
        ### Walmart Ecommerce Not Returned At Hub ###
        Route::get('walmart/e-commerce/returned-not-hub', 'WalmartController@getWalmartEcommerceNotreturned')->name('new-notreturned-walmart-ecommerce.index');
        Route::get('walmart/e-commerce/returned-not-hub/data', 'WalmartController@walmartEcommerceNotReturnedData')->name('new-notreturned-walmart-ecommerce.data');
        Route::get('walmart/e-commerce/returned-not-hub/list/{date?}/{vendor_id?}', 'WalmartController@walmartEcommerceNotReturnedExcel')->name('new-notreturned-walmart-ecommerce-export.excel');
        Route::get('walmart/e-commerce/returned-not-hub/tracking/list/{date?}/{vendor_id?}', 'WalmartController@walmartEcommerceNotReturnedExcelTrackingIds')->name('new-notreturned-walmart-ecommerce-tracking-export.excel');
        ### Walmart Ecommerce Reporting###
        Route::get('/walmart/e-commerce/reporting', 'WalmartController@getWalmartEcommerceReporting')->name('walmart_ecommerce_reporting.index');
        Route::get('yajra/walmart/e-commerce/reporting', 'WalmartController@getWalmartEcommerceReportingData')->name('walmart_ecommerce_reporting_data.data');

        ### Walmart Ecommerce OTD ###
        Route::get('walmart/e-commerce/graph', 'WalmartController@statistics_otd_index')->name('walmart-ecommerce-graph.index');
        Route::get('walmart/e-commerce/dashboard/statistics/ajax/otd-day', 'WalmartController@ajax_render_boradless_otd_day')->name('walmart-ecommerce-otd-day.index');
        Route::get('walmart/e-commerce/dashboard/statistics/ajax/otd-week', 'WalmartController@ajax_render_boradless_otd_week')->name('walmart-ecommerce-otd-week.index');
        Route::get('walmart/e-commerce/dashboard/statistics/ajax/otd-month', 'WalmartController@ajax_render_boradless_otd_month')->name('walmart-ecommerce-otd-month.index');

        ###  Walmart Ecommerce Profile ###
        Route::get('walmart/e-commerce/detail/{id}', 'WalmartController@getWalmartEcommerceDashboardProfile')->name('walmart-ecommerce-detail.profile');
        Route::get('walmart/e-commerce/sorted/detail/{id}', 'WalmartController@getWalmartEcommerceSortedProfile')->name('walmart-ecommerce-sorted-detail.profile');
        Route::get('walmart/e-commerce/pickup/detail/{id}', 'WalmartController@walmartEcommercepickupDetail')->name('walmart-ecommerce-pickup-detail.profile');
        Route::get('walmart/e-commerce/notscan/detail/{id}', 'WalmartController@walmartEcommercenotscanDetail')->name('walmart-ecommerce-notscan-detail.profile');
        Route::get('walmart/e-commerce/delivered/detail/{id}', 'WalmartController@walmartEcommercedeliveredDetail')->name('walmart-ecommerce-delivered-detail.profile');
        Route::get('walmart/e-commerce/returned/detail/{id}', 'WalmartController@walmartEcommercereturnedDetail')->name('walmart-ecommerce-returned-detail.profile');
        Route::get('walmart/e-commerce/returned-not-hub/detail/{id}', 'WalmartController@walmartEcommerceNotReturnedDetail')->name('walmart-ecommerce-notreturned-detail.profile');
        ### Walmart Ecommerce  Cards ###
        Route::get('walmart/e-commerce/card-dashboard', 'WalmartController@getWalmartEcommerceCards')->name('new-walmart-ecommerce-card-dashboard.index');
        ### Walmart Ecommerce OTD ###
        Route::get('walmart/e-commerce/graph', 'WalmartController@statistics_otd_index')->name('walmart-ecommerce-graph.index');
        Route::get('walmart/e-commerce/dashboard/statistics/ajax/otd-day', 'WalmartController@ajax_render_walmart_ecommerce_otd_day')->name('walmart-ecommerce-otd-day.index');
        Route::get('walmart/e-commerce/dashboard/statistics/ajax/otd-week', 'WalmartController@ajax_render_walmart_ecommerce_otd_week')->name('walmart-ecommerce-otd-week.index');
        Route::get('walmart/e-commerce/dashboard/statistics/ajax/otd-month', 'WalmartController@ajax_render_walmart_ecommerce_otd_month')->name('walmart-ecommerce-otd-month.index');

        ### New WildFork Dashboard ###
        Route::get('wildfork/data', 'WildForkController@WildForkData')->name('new-wildfork.data');
        Route::get('wildfork', 'WildForkController@getWildFork')->name('new-wildfork.index');
        Route::get('wildfork/detail/{id}', 'WildForkController@WildForkProfile')->name('wildfork-detail.profile');
        Route::get('wildfork/list/{date?}/{vendor_id?}', 'WildForkController@getWildForkDashboardExcel')->name('new-wildfork-export.excel');

        ### New WildFork Card ###
        Route::get('wildfork/card-dashboard', 'WildForkController@getwildforkCards')->name('new-WildFork-card-dashboard.index');
        ### New WildFork Ecommerce  Dashboard ###
        Route::get('wildfork/e-commerce/order/data', 'WildForkController@getWildforkEcommerceDashboardData')->name('new-order-wildfork-ecommerce.data');
        Route::get('wildfork/e-commerce/order', 'WildForkController@getWildforkEcommerceDashboard')->name('new-order-wildfork-ecommerce.index');
        Route::get('wildfork/e-commerce/order/list/{date?}/{vendor_id?}', 'WildForkController@getWildforkExcel')->name('new-order-wildfork-ecommerce-export.excel');
        Route::get('wildfork/e-commerce/order/list/{date?}/{vendor_id?}', 'WildForkController@getWildforkEcommerceDashboardExcel')->name('new-order-wildfork-ecommerce-export.excel');
        //        ### WildFork Ecommerce Sorted ###
        Route::get('wildfork/e-commerce/sorted', 'WildForkController@getWildforkEcommerceSorter')->name('new-sort-wildfork-ecommerce.index');
        Route::get('wildfork/e-commerce/sorted/data', 'WildForkController@wildforkEcommerceSortedData')->name('new-sort-wildfork-ecommerce.data');
        Route::get('wildfork/e-commerce/sorted/list/{date?}/{vendor_id?}', 'WildForkController@wildforkEcommerceSortedExcel')->name('new-sort-wildfork-ecommerce-export.excel');
        //        ### WildFork Ecommerce Hub ###
        Route::get('wildfork/e-commerce/picked/up', 'WildForkController@getWildforkEcommercehub')->name('new-pickup-wildfork-ecommerce.index');
        Route::get('wildfork/e-commerce/picked/up/data', 'WildForkController@wildforkEcommercePickedUpData')->name('new-pickup-wildfork-ecommerce.data');
        Route::get('wildfork/e-commerce/picked/up/list/{date?}/{vendor_id?}', 'WildForkController@wildforkEcommercePickedupExcel')->name('new-pickup-wildfork-ecommerce-export.excel');
        //        ### WildFork Ecommerce Not Scan ###
        Route::get('wildfork/e-commerce/not/scan', 'WildForkController@getWildforkEcommercescan')->name('new-not-scan-wildfork-ecommerce.index');
        Route::get('wildfork/e-commerce/not/scan/data', 'WildForkController@wildforkEcommerceNotScanData')->name('new-not-scan-wildfork-ecommerce.data');
        Route::get('wildfork/e-commerce/not/scan/list/{date?}/{vendor_id?}', 'WildForkController@wildforkEcommercescanExcel')->name('new-not-scan-wildfork-ecommerce-export.excel');
        //        ### WildFork Ecommerce Delivered ###
        Route::get('wildfork/e-commerce/delivered', 'WildForkController@getWildforkEcommercedelivered')->name('new-delivered-wildfork-ecommerce.index');
        Route::get('wildfork/e-commerce/delivered/data', 'WildForkController@wildforkEcommerceDeliveredData')->name('new-delivered-wildfork-ecommerce.data');
        Route::get('wildfork/e-commerce/delivered/list/{date?}/{vendor_id?}', 'WildForkController@wildforkEcommerceDeliveredExcel')->name('new-delivered-wildfork-ecommerce-export.excel');
        //        ### WildFork Ecommerce Returned ###
        Route::get('wildfork/e-commerce/returned', 'WildForkController@getWildforkEcommercereturned')->name('new-returned-wildfork-ecommerce.index');
        Route::get('wildfork/e-commerce/returned/data', 'WildForkController@wildforkEcommerceReturnedData')->name('new-returned-wildfork-ecommerce.data');
        Route::get('wildfork/e-commerce/returned/list/{date?}/{vendor_id?}', 'WildForkController@wildforkEcommerceReturnedExcel')->name('new-returned-wildfork-ecommerce-export.excel');
        //        ### WildFork Ecommerce Not Returned At Hub ###
        Route::get('wildfork/e-commerce/returned-not-hub', 'WildForkController@getWildforkEcommerceNotreturned')->name('new-notreturned-wildfork-ecommerce.index');
        Route::get('wildfork/e-commerce/returned-not-hub/data', 'WildForkController@wildforkEcommerceNotReturnedData')->name('new-notreturned-wildfork-ecommerce.data');
        Route::get('wildfork/e-commerce/returned-not-hub/list/{date?}/{vendor_id?}', 'WildForkController@wildforkEcommerceNotReturnedExcel')->name('new-notreturned-wildfork-ecommerce-export.excel');
        Route::get('wildfork/e-commerce/returned-not-hub/tracking/list/{date?}/{vendor_id?}', 'WildForkController@wildforkEcommerceNotReturnedExcelTrackingIds')->name('new-notreturned-wildfork-ecommerce-tracking-export.excel');
        //        ### WildFork Ecommerce Reporting###
        Route::get('/wildfork/e-commerce/reporting', 'WildForkController@getWildforkEcommerceReporting')->name('wildfork_ecommerce_reporting.index');
        Route::get('yajra/wildfork/e-commerce/reporting', 'WildForkController@getWildforkEcommerceReportingData')->name('wildfork_ecommerce_reporting_data.data');
        //
        //        ### WildFork Ecommerce OTD ###
        Route::get('wildfork/e-commerce/graph', 'WildForkController@statistics_otd_index')->name('wildfork-ecommerce-graph.index');
        Route::get('wildfork/e-commerce/dashboard/statistics/ajax/otd-day', 'WildForkController@ajax_render_boradless_otd_day')->name('wildfork-ecommerce-otd-day.index');
        Route::get('wildfork/e-commerce/dashboard/statistics/ajax/otd-week', 'WildForkController@ajax_render_boradless_otd_week')->name('wildfork-ecommerce-otd-week.index');
        Route::get('wildfork/e-commerce/dashboard/statistics/ajax/otd-month', 'WildForkController@ajax_render_boradless_otd_month')->name('wildfork-ecommerce-otd-month.index');
        //        ###  WildFork Ecommerce Profile ###
        Route::get('wildfork/e-commerce/detail/{id}', 'WildForkController@getWildforkEcommerceDashboardProfile')->name('wildfork-ecommerce-detail.profile');
        Route::get('wildfork/e-commerce/sorted/detail/{id}', 'WildForkController@getWildforkEcommerceSortedProfile')->name('wildfork-ecommerce-sorted-detail.profile');
        Route::get('wildfork/e-commerce/pickup/detail/{id}', 'WildForkController@wildforkEcommercepickupDetail')->name('wildfork-ecommerce-pickup-detail.profile');
        Route::get('wildfork/e-commerce/notscan/detail/{id}', 'WildForkController@wildforkEcommercenotscanDetail')->name('wildfork-ecommerce-notscan-detail.profile');
        Route::get('wildfork/e-commerce/delivered/detail/{id}', 'WildForkController@wildforkEcommercedeliveredDetail')->name('wildfork-ecommerce-delivered-detail.profile');
        Route::get('wildfork/e-commerce/returned/detail/{id}', 'WildForkController@wildforkEcommercereturnedDetail')->name('wildfork-ecommerce-returned-detail.profile');
        Route::get('wildfork/e-commerce/returned-not-hub/detail/{id}', 'WildForkController@wildforkEcommerceNotReturnedDetail')->name('wildfork-ecommerce-notreturned-detail.profile');
        //        ### WildFork Ecommerce  Cards ###
        Route::get('wildfork/e-commerce/card-dashboard', 'WildForkController@getWildforkEcommerceCards')->name('new-wildfork-ecommerce-card-dashboard.index');
        Route::get('wildfork-dashboard/totalcards/{date?}/{type?}', 'WildForkController@wildforkTotalCards')->name('wildfork-dashboard.totalcards');
        Route::get('wildfork-dashboard/customroutecards/{date?}', 'WildForkController@getwildforkCustomRouteData')->name('wildfork-dashboard.customroutecards');
        Route::get('wildfork-dashboard/inprogress/{date?}/{type?}', 'WildForkController@wildforkInProgressOrders')->name('wildfork-dashboard.inprogress');
        Route::get('wildfork/e-commerce/totalcards/{date?}/{type?}/{vendor_id?}', 'WildForkController@wildforkEcommerceTotalCards')->name('wildfork-ecommerce.totalcards');



        ### New Logx Dashboard ###
        Route::get('logx/data', 'LogXController@LogXData')->name('new-logx.data');
        Route::get('logx', 'LogXController@getLogX')->name('new-logx.index');
        Route::get('logx/detail/{id}', 'LogXController@LogXProfile')->name('logx-detail.profile');
        Route::get('logx/list/{date?}/{vendor_id?}', 'LogXController@getLogXDashboardExcel')->name('new-logx-export.excel');

        ### New Logx Dashboard ###
        Route::get('logx/data', 'LogXController@logXData')->name('new-logx.data');
        Route::get('logx', 'LogXController@getLogX')->name('new-logx.index');
        Route::get('logx/detail/{id}', 'LogXController@logXProfile')->name('logx-detail.profile');
        Route::get('logx/list/{date?}/{vendor_id?}', 'LogXController@getLogXDashboardExcel')->name('new-logx-export.excel');
        ### New Logx Card ###
        Route::get('logx/card-dashboard', 'LogXController@getLogxCards')->name('new-logx-card-dashboard.index');
        ### New Logx Ecommerce  Dashboard ###
        Route::get('logx/e-commerce/order/data', 'LogXController@getLogxEcommerceDashboardData')->name('new-order-logx-ecommerce.data');
        Route::get('logx/e-commerce/order', 'LogXController@getLogxEcommerceDashboard')->name('new-order-logx-ecommerce.index');
        Route::get('logx/e-commerce/order/list/{date?}/{vendor_id?}', 'LogXController@getLogxExcel')->name('new-order-logx-ecommerce-export.excel');
        Route::get('logx/e-commerce/order/list/{date?}/{vendor_id?}', 'LogXController@getLogxEcommerceDashboardExcel')->name('new-order-logx-ecommerce-export.excel');
        ### Logx Ecommerce Sorted ###
        Route::get('logx/e-commerce/sorted', 'LogXController@getLogxEcommerceSorter')->name('new-sort-logx-ecommerce.index');
        Route::get('logx/e-commerce/sorted/data', 'LogXController@logxEcommerceSortedData')->name('new-sort-logx-ecommerce.data');
        Route::get('logx/e-commerce/sorted/list/{date?}/{vendor_id?}', 'LogXController@logxEcommerceSortedExcel')->name('new-sort-logx-ecommerce-export.excel');
        ### Logx Ecommerce Received At Hub ###
        Route::get('logx/e-commerce/received-at-hub', 'LogXController@getLogxEcommerceReceivedAtHub')->name('received-at-hub-logx-ecommerce.index');
        Route::get('logx/e-commerce/received-at-hub/data', 'LogXController@logxEcommerceReceivedAtHubData')->name('received-at-hub-logx-ecommerce.data');
        //Route::get('logx/e-commerce/sorted/list/{date?}/{vendor_id?}', 'LogXController@logxEcommerceSortedExcel')->name('new-sort-logx-ecommerce-export.excel');
        ### Logx Ecommerce Hub Return Scan ###
        Route::get('logx/e-commerce/hub-return-scan', 'LogXController@getLogxEcommerceHubReturnScan')->name('hub-return-scan-logx-ecommerce.index');
        Route::get('logx/e-commerce/hub-return-scan/data', 'LogXController@logxEcommerceHubReturnScanData')->name('hub-return-scan-logx-ecommerce.data');

        ### Logx Ecommerce Hub ###
        Route::get('logx/e-commerce/picked/up', 'LogXController@getLogxEcommercehub')->name('new-pickup-logx-ecommerce.index');
        Route::get('logx/e-commerce/picked/up/data', 'LogXController@logxEcommercePickedUpData')->name('new-pickup-logx-ecommerce.data');
        Route::get('logx/e-commerce/picked/up/list/{date?}/{vendor_id?}', 'LogXController@logxEcommercePickedupExcel')->name('new-pickup-logx-ecommerce-export.excel');
        ### Logx Ecommerce Not Scan ###
        Route::get('logx/e-commerce/not/scan', 'LogXController@getLogxEcommercescan')->name('new-not-scan-logx-ecommerce.index');
        Route::get('logx/e-commerce/not/scan/data', 'LogXController@logxEcommerceNotScanData')->name('new-not-scan-logx-ecommerce.data');
        Route::get('logx/e-commerce/not/scan/list/{date?}/{vendor_id?}', 'LogXController@logxEcommercescanExcel')->name('new-not-scan-logx-ecommerce-export.excel');
        ### Logx Ecommerce Delivered ###
        Route::get('logx/e-commerce/delivered', 'LogXController@getLogxEcommercedelivered')->name('new-delivered-logx-ecommerce.index');
        Route::get('logx/e-commerce/delivered/data', 'LogXController@logxEcommerceDeliveredData')->name('new-delivered-logx-ecommerce.data');
        Route::get('logx/e-commerce/delivered/list/{date?}/{vendor_id?}', 'LogXController@logxEcommerceDeliveredExcel')->name('new-delivered-logx-ecommerce-export.excel');
        ### Logx Ecommerce Returned ###
        Route::get('logx/e-commerce/returned', 'LogXController@getLogxEcommercereturned')->name('new-returned-logx-ecommerce.index');
        Route::get('logx/e-commerce/returned/data', 'LogXController@logxEcommerceReturnedData')->name('new-returned-logx-ecommerce.data');
        Route::get('logx/e-commerce/returned/list/{date?}/{vendor_id?}', 'LogXController@logxEcommerceReturnedExcel')->name('new-returned-logx-ecommerce-export.excel');
        ### Logx Ecommerce Not Returned At Hub ###
        Route::get('logx/e-commerce/returned-not-hub', 'LogXController@getLogxEcommerceNotreturned')->name('new-notreturned-logx-ecommerce.index');
        Route::get('logx/e-commerce/returned-not-hub/data', 'LogXController@logxEcommerceNotReturnedData')->name('new-notreturned-logx-ecommerce.data');
        Route::get('logx/e-commerce/returned-not-hub/list/{date?}/{vendor_id?}', 'LogXController@logxEcommerceNotReturnedExcel')->name('new-notreturned-logx-ecommerce-export.excel');
        Route::get('logx/e-commerce/returned-not-hub/tracking/list/{date?}/{vendor_id?}', 'LogXController@logxEcommerceNotReturnedExcelTrackingIds')->name('new-notreturned-logx-ecommerce-tracking-export.excel');
        ### Logx Ecommerce Reporting###
        Route::get('/logx/e-commerce/reporting', 'LogXController@getLogxEcommerceReporting')->name('logx_ecommerce_reporting.index');
        Route::get('yajra/logx/e-commerce/reporting', 'LogXController@getLogxEcommerceReportingData')->name('logx_ecommerce_reporting_data.data');
        ### Logx Ecommerce OTD ###
        Route::get('logx/e-commerce/graph', 'LogXController@statistics_otd_index')->name('logx-ecommerce-graph.index');
        Route::get('logx/e-commerce/dashboard/statistics/ajax/otd-day', 'LogXController@ajax_render_boradless_otd_day')->name('logx-ecommerce-otd-day.index');
        Route::get('logx/e-commerce/dashboard/statistics/ajax/otd-week', 'LogXController@ajax_render_boradless_otd_week')->name('logx-ecommerce-otd-week.index');
        Route::get('logx/e-commerce/dashboard/statistics/ajax/otd-month', 'LogXController@ajax_render_boradless_otd_month')->name('logx-ecommerce-otd-month.index');
        ###  Logx Ecommerce Profile ###
        Route::get('logx/e-commerce/detail/{id}', 'LogXController@getLogxEcommerceDashboardProfile')->name('logx-ecommerce-detail.profile');
        Route::get('logx/e-commerce/sorted/detail/{id}', 'LogXController@getLogxEcommerceSortedProfile')->name('logx-ecommerce-sorted-detail.profile');
        Route::get('logx/e-commerce/pickup/detail/{id}', 'LogXController@logxEcommercepickupDetail')->name('logx-ecommerce-pickup-detail.profile');
        Route::get('logx/e-commerce/notscan/detail/{id}', 'LogXController@logxEcommercenotscanDetail')->name('logx-ecommerce-notscan-detail.profile');
        Route::get('logx/e-commerce/delivered/detail/{id}', 'LogXController@logxEcommercedeliveredDetail')->name('logx-ecommerce-delivered-detail.profile');
        Route::get('logx/e-commerce/returned/detail/{id}', 'LogXController@logxEcommercereturnedDetail')->name('logx-ecommerce-returned-detail.profile');
        Route::get('logx/e-commerce/returned-not-hub/detail/{id}', 'LogXController@logxEcommerceNotReturnedDetail')->name('logx-ecommerce-notreturned-detail.profile');
        ### Logx Ecommerce  Cards ###
        Route::get('logx/e-commerce/card-dashboard', 'LogXController@getLogxEcommerceCards')->name('new-Logx-ecommerce-card-dashboard.index');
        Route::get('logx-dashboard/totalcards/{date?}/{type?}', 'LogXController@logxTotalCards')->name('logx-dashboard.totalcards');
        Route::get('logx-dashboard/customroutecards/{date?}', 'LogXController@getLogxCustomRouteData')->name('logx-dashboard.customroutecards');
        Route::get('logx-dashboard/inprogress/{date?}/{type?}', 'LogXController@logxInProgressOrders')->name('logx-dashboard.inprogress');
        Route::get('logx/e-commerce/totalcards/{date?}/{type?}/{vendor_id?}', 'LogXController@logxEcommerceTotalCards')->name('logx-ecommerce.totalcards');


        ### ScarBorough Dashboard ###
        Route::get('scarborough-dashboard', 'ScarBoroughController@getScarBoroughDashboard')->name('scarborough-dashboard.index');
        Route::get('scarborough-dashboard/data', 'ScarBoroughController@getScarBoroughDashboardData')->name('scarborough-dashboard.data');
        Route::get('scarborough/order/detail/{id}', 'ScarBoroughController@ScarBoroughProfile')->name('scarborough-order.profile');
        Route::get('scarborough/returned/detail/{id}', 'ScarBoroughController@ScarBoroughreturnedDetail')->name('scarborough-returned-detail.profile');
        Route::get('scarborough/returned-not-hub/detail/{id}', 'ScarBoroughController@ScarBoroughNotReturnedDetail')->name('scarborough-notreturned-detail.profile');
        Route::get('scarborough/delivered/detail/{id}', 'ScarBoroughController@ScarBoroughdeliveredDetail')->name('scarborough-delivered-detail.profile');
        Route::get('scarborough/dashboard/list/{date?}/{vendor?}', 'ScarBoroughController@ScarBoroughDashboardExcel')->name('scarborough-dashboard-export.excel');
        Route::get('scarborough/dashboard/otd/report/{date?}/{vendor?}', 'ScarBoroughController@ScarBoroughDashboardExcelOtdReport')->name('scarborough-dashboard-export-otd-report.excel');
        Route::get('scarborough/card-dashboard', 'ScarBoroughController@getScarBoroughCards')->name('scarborough-card-dashboard.index');
        ### ScarBorough Card ###
        Route::get('scarborough/card-dashboard', 'ScarBoroughController@getScarBoroughCards')->name('scarborough-card-dashboard.index');
        ### ScarBorough Orders ###
        Route::get('scarborough/order/data', 'ScarBoroughController@getScarBoroughData')->name('new-order-scarborough.data');
        Route::get('scarborough/order', 'ScarBoroughController@getScarBorough')->name('new-order-scarborough.index');
        Route::get('scarborough/order/list/{date?}/{vendor?}', 'ScarBoroughController@getScarBoroughExcel')->name('new-order-scarborough-export.excel');
        Route::get('scarborough/detail/{id}', 'ScarBoroughController@getScarBoroughProfile')->name('scarborough-detail-detail.profile');
        ### ScarBorough Returned ###
        Route::get('scarborough/returned', 'ScarBoroughController@getScarBoroughReturned')->name('new-returned-scarborough.index');
        Route::get('scarborough/returned/data', 'ScarBoroughController@ScarBoroughReturnedData')->name('new-returned-scarborough.data');
        Route::get('scarborough/returned/list/{startdate?}/{enddate?}', 'ScarBoroughController@ScarBoroughReturnedExcel')->name('new-returned-scarborough-export.excel');
        ### ScarBorough Retunred not hub ###
        Route::get('scarborough/returned-not-hub', 'ScarBoroughController@getScarBoroughNotreturned')->name('new-notreturned-scarborough.index');
        Route::get('scarborough/returned-not-hub/data', 'ScarBoroughController@ScarBoroughNotReturnedData')->name('new-notreturned-scarborough.data');
        Route::get('scarborough/returned-not-hub/list/{startdate?}/{enddate?}', 'ScarBoroughController@ScarBoroughNotReturnedExcel')->name('new-notreturned-scarborough-export.excel');
        Route::get('scarborough/returned-not-hub/tracking/list/{startdate?}/{enddate?}', 'ScarBoroughController@ScarBoroughNotReturnedExcelTrackingIds')->name('new-notreturned-scarborough-tracking-export.excel');

        ### ScarBorough sorted ###
        Route::get('scarborough/sorted/detail/{id}', 'ScarBoroughController@vacnouversortedDetail')->name('scarborough-sorted-detail.profile');
        Route::get('scarborough/sorted', 'ScarBoroughController@getScarBoroughSorter')->name('new-sort-scarborough.index');
        Route::get('scarborough/sorted/data', 'ScarBoroughController@ScarBoroughSortedData')->name('new-sort-scarborough.data');
        Route::get('scarborough/sorted/list/{date?}/{vendor?}', 'ScarBoroughController@ScarBoroughSortedExcel')->name('new-sort-scarborough-export.excel');
        ### ScarBorough Out for delivery ###
        Route::get('scarborough/picked/up', 'ScarBoroughController@getScarBoroughhub')->name('new-pickup-scarborough.index');
        Route::get('scarborough/picked/up/data', 'ScarBoroughController@ScarBoroughPickedUpData')->name('new-pickup-scarborough.data');
        Route::get('scarborough/picked/up/list/{date?}/{vendor?}', 'ScarBoroughController@ScarBoroughPickedupExcel')->name('new-pickup-scarborough-export.excel');
        Route::get('scarborough/pickup/detail/{id}', 'ScarBoroughController@ScarBoroughpickupDetail')->name('scarborough-pickup-detail.profile');
        ### ScarBorough Delivered ###
        Route::get('scarborough/delivered', 'ScarBoroughController@getScarBoroughdelivered')->name('new-delivered-scarborough.index');
        Route::get('scarborough/delivered/data', 'ScarBoroughController@ScarBoroughDeliveredData')->name('new-delivered-scarborough.data');
        Route::get('scarborough/delivered/list/{date?}/{vendor?}', 'ScarBoroughController@ScarBoroughDeliveredExcel')->name('new-delivered-scarborough-export.excel');
        ### ScarBorough Not Scan ###
        Route::get('scarborough/notscan/detail/{id}', 'ScarBoroughController@ScarBoroughnotscanDetail')->name('scarborough-notscan-detail.profile');
        Route::get('scarborough/not/scan', 'ScarBoroughController@getScarBoroughscan')->name('new-not-scan-scarborough.index');
        Route::get('scarborough/not/scan/data', 'ScarBoroughController@ScarBoroughNotScanData')->name('new-not-scan-scarborough.data');
        Route::get('scarborough/not/scan/list/{date?}/{vendor?}', 'ScarBoroughController@ScarBoroughNotscanExcel')->name('new-not-scan-scarborough-export.excel');
        ### ScarBorough Custom Route ###
        Route::get('scarborough/custom-route', 'ScarBoroughController@getScarBoroughCustomRoute')->name('new-custom-route-scarborough.index');
        Route::get('scarborough/custom-route/data', 'ScarBoroughController@ScarBoroughCustomRouteData')->name('new-custom-route-scarborough.data');
        Route::get('scarborough/custom-route/list/{date?}/{vendor?}', 'ScarBoroughController@ScarBoroughCustomRouteExcel')->name('new-custom-route-scarborough-export.excel');
        Route::get('scarborough/custom-route/detail/{id}', 'ScarBoroughController@ScarBoroughCustomRouteDetail')->name('scarborough-CustomRoute-detail.profile');
        ### ScarBorough Reporting###
        Route::get('/scarborough/reporting', 'ScarBoroughController@getScarBoroughReporting')->name('scarborough_reporting.index');
        Route::get('yajra/scarborough/reporting', 'ScarBoroughController@getScarBoroughReportingData')->name('new_scarborough_reporting_data.data');
        ### ScarBorough OTD ###
        Route::get('scarborough/graph', 'ScarBoroughController@statistics_otd_index')->name('scarborough-graph.index');
        Route::get('scarborough/dashboard/statistics/ajax/otd-day', 'ScarBoroughController@ajax_render_ScarBorough_otd_day')->name('scarborough-otd-day.index');
        Route::get('scarborough/dashboard/statistics/ajax/otd-week', 'ScarBoroughController@ajax_render_ScarBorough_otd_week')->name('scarborough-otd-week.index');
        Route::get('scarborough/dashboard/statistics/ajax/otd-month', 'ScarBoroughController@ajax_render_ScarBorough_otd_month')->name('scarborough-otd-month.index');
        ### ScarBorough Route Info ###
        Route::get('scarborough/route-info', 'ScarBoroughController@getRouteinfo')->name('scarborough-route-info.index');
        Route::get('scarborough/route-info/list/{date?}', 'ScarBoroughController@ScarBoroughRouteinfoExcel')->name('new-export_scarboroughRouteInfo.excel');
        Route::get('scarborough/route/{di}/edit/hub/{id}', 'ScarBoroughController@ScarBoroughHubRouteEdit')->name('scarborough_route.detail');
        Route::post('scarborough/route-details/flag-history-model-html-render', 'ScarBoroughController@flagHistoryModelHtmlRender')->name('scarboroughinfo_route.route-details.flag-history-model-html-render');
        Route::get('scarborough/route/orders/trackingid/{id}/details', 'ScarBoroughController@getScarBoroughtrackingorderdetails')->name('scarboroughinfo_route.detail');
        Route::post('scarborough/route/mark/delay', 'ScarBoroughController@routeMarkDelay')->name('scarborough-route-mark-delay');
        Route::post('scarborough/route-info/add-note', 'ScarBoroughController@addNote')->name('scarborough-route-info.addNote');
        Route::get('scarborough/route-info/get-notes', 'ScarBoroughController@getNotes')->name('scarborough-route-info.getNotes');
        Route::post('scarborough/route-details/flag-history-model-html-render', 'ScarBoroughController@flagHistoryModelHtmlRender')->name('scarboroughinfo_route.route-details.flag-history-model-html-render');
        ### ScarBorough Received At Hub ###
        Route::get('scarborough/received-at-hub', 'ScarBoroughController@getScarBoroughRecievedAtHub')->name('receivedathub-scarBorough.index');
        Route::get('scarborough/received-at-hub/data', 'ScarBoroughController@scarBoroughRecievedAtHubData')->name('receivedathub-scarBorough.data');
        Route::get('scarborough/received-at-hub/list/{date?}/{vendor_id?}', 'ScarBoroughController@scarBoroughRecievedAtHubExcel')->name('receivedathub-scarBorough-export.excel');
        Route::get('scarborough/received-at-hub/tracking/list/{startdate?}/{enddate?}/{vendor_id?}', 'ScarBoroughController@scarBoroughRecievedAtHubExcelTrackingIds')->name('receivedathub-scarBorough-tracking-export.excel');
        #### Borderless reattemptted orders
        Route::get('scarborough/to-be-reattempted-orders', 'ScarBoroughController@getScarBoroughToBeReattemptedOrders')->name('to-be-reattemptedorders-scarBorough.index');
        Route::get('scarborough/to-be-reattempted-orders/data', 'ScarBoroughController@scarBoroughToBeReattemptedOrdersData')->name('to-be-reattemptedorders-scarBorough.data');

        #### returned-to-hub-for-re-delivery ####
        Route::get('scarborough/returned-to-hub-for-re-delivery', 'ScarBoroughController@getScarBoroughReturnedToHubForReDelivery')->name('re-delivery-orders-scarBorough.index');
        Route::get('scarborough/returned-to-hub-for-re-delivery/data', 'ScarBoroughController@scarBoroughReturnedToHubForReDeliveryData')->name('re-delivery-orders-scarBorough.data');

        Route::get('scarborough/received-at-hub/detail/{id}', 'ScarBoroughController@scarBoroughReceivedAtHubDetail')->name('scarBorough-receivedathub-detail.profile');
        Route::get('scarborough/reattmpted-orders/detail/{id}', 'ScarBoroughController@scarBoroughReattemptedOrdersDetail')->name('scarBorough-reattempted-detail.profile');
        Route::get('scarborough/reattempted-orders/tracking/list/{startdate?}/{enddate?}/{vendor_id?}', 'ScarBoroughController@scarBoroughReattemptedOrdersExcelTrackingIds')->name('new-reattemptedorders-scarBorough-tracking-export.excel');
        Route::get('scarborough/re-delivery-orders/tracking/list/{startdate?}/{enddate?}/{vendor_id?}', 'ScarBoroughController@scarBoroughReDeliveryOrdersExcelTrackingIds')->name('re-delivery-orders-scarBorough-tracking-export.excel');

        // Maps on route info
        Route::get('route/{id}/map','BorderlessController@RouteMap');
        Route::get('route/{id}/remaining','BorderlessController@remainigrouteMap');
        Route::post('route/map/location','BorderlessController@getRouteMapLocation');
        Route::get('allroute/{id}/location/joey','BorderlessController@getLocationMap');

        ### Manual Route Update ###
        Route::get('manual/route', 'ManualRouteController@getManualRoute')->name('manual-route.index');
        Route::post('update/manual/route', 'ManualRouteController@postUpdateManualRoute')->name('manual-route.update');

		###Chat Thread###
		Route::get('threads', 'ChatThreadController@index')->name('thread.index');

		/*For Complains*/
		Route::get('complain', 'ComplainController@get_complains')->name('complain.index');
		Route::get('complain/register','ComplainController@index')->name('complain.register');
		Route::post('complain/add','ComplainController@create')->name('complain.create');

        Route::get('order/under-review/count', 'CustomerSupportController@getCustomerCount')->name('order-confirmation-list.count');

    });

});

Route::get('dashboard-logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::get('php-info', static function(){
    //phpinfo();
});

Route::get('reset-cache', static function(){
    Artisan::call('cache:clear');
    dd('Reset Cache');
});

Route::get('config-clear', static function(){
    Artisan::call('config:clear');
    dd('Config Clear');
});



Route::get('/test', function() 
{

    $userCreated = App\User::where('email', 'abk@gmail.com');

    $emailBody = 'test';

    \Mail::raw($emailBody, function($m) use($userCreated) {

        $m->to($userCreated->email)->from(env('MAIL_USERNAME'))->subject('Welcome on Board - ValuationApp');

    });

});





       