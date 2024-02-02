<?php


/**
 * Permissions config
 *
 * @date   23/10/2020
 */

return [
        'Management Portal'=>
        [
            'Management View' => 'statistics.index|statistics-day-otd.index|statistics-week-otd.index|statistics-month-otd.index|statistics-year-otd.index|statistics-all-counts.index|statistics-failed-counts.index|statistics-custom-counts.index|statistics-manual-counts.index|statistics-route-counts.index|statistics-route-detail.index|statistics-on-time-counts.index|statistics-top-ten-joeys.index|statistics-least-ten-joeys.index|statistics-graph.index|statistics-brooker.index|statistics-order.index|statistics-failed-order.index|statistics-brooker-detail.index|statistics-brooker-detail-day-otd.index|statistics-brooker-detail-week-otd.index|statistics-brooker-detail-month-otd.index|statistics-brooker-detail-year-otd.index|statistics-brooker-detail-all-counts.index|statistics-brooker-detail-failed-counts.index|statistics-brooker-detail-custom-counts.index|statistics-brooker-detail-manual-counts.index|statistics-brooker-detail-route-counts.index|statistics-brooker-detail-on-time-counts.index|statistics-brooker-detail-top-ten-joeys.index|statistics-brooker-detail-least-ten-joeys.index|statistics-brooker-detail-graph.index|statistics-brooker-detail-brooker.index|statistics-brooker-detail-order.index|statistics-brooker-detail-failed-order.index|statistics-brooker-detail-all-joeys-otd.index|statistics-brooker-detail-all-joeys-otd.index|statistics-joey-detail.index|statistics-joey-detail-day-otd.index|statistics-joey-detail-week-otd.index|statistics-joey-detail-month-otd.index|statistics-joey-detail-year-otd.index|statistics-joey-detail-all-counts.index|statistics-joey-detail-manual-counts.index|statistics-joey-detail-joey-time.index|statistics-joey-detail-graph.index|statistics-joey-detail-order.index|statistics-joey-detail-failed-order.index',
            'Joey Management View' => 'joey-management.index|joey-management-joey-count.index|joey-management-joey-count.onduty|joey-management-orders-count.index|joey-management-otd-day.index|joey-management-otd-week.index|joey-management-otd-month.index|joey-management-list.index|joey-management-order-list.index|joey-management-all-joeys-otd.index|statistics-joey-detail.index|statistics-joey-detail-day-otd.index|statistics-joey-detail-week-otd.index|statistics-joey-detail-month-otd.index|statistics-joey-detail-year-otd.index|statistics-joey-detail-all-counts.index|statistics-joey-detail-manual-counts.index|statistics-joey-detail-joey-time.index|statistics-joey-detail-graph.index|statistics-joey-detail-order.index|statistics-joey-detail-failed-order.index',
            'Brooker Management View' => 'brooker-management.index|brooker-management-brooker-count.index|brooker-management-joey-count.index|brooker-management-joey-count.onduty|brooker-management-orders-count.index|brooker-management-otd-day.index|brooker-management-otd-week.index|brooker-management-otd-month.index|brooker-management-list.index|brooker-management-brooker-list.index|brooker-management-all-brooker-otd.index|joey-management-all-brooker-otd.index|statistics-brooker-detail.index|statistics-brooker-detail-day-otd.index|statistics-brooker-detail-week-otd.index|statistics-brooker-detail-month-otd.index|statistics-brooker-detail-year-otd.index|statistics-brooker-detail-all-counts.index|statistics-brooker-detail-failed-counts.index|statistics-brooker-detail-custom-counts.index|statistics-brooker-detail-manual-counts.index|statistics-brooker-detail-route-counts.index|statistics-brooker-detail-on-time-counts.index|statistics-brooker-detail-top-ten-joeys.index|statistics-brooker-detail-least-ten-joeys.index|statistics-brooker-detail-graph.index|statistics-brooker-detail-brooker.index|statistics-brooker-detail-order.index|statistics-brooker-detail-failed-order.index|statistics-brooker-detail-all-joeys-otd.index|statistics-brooker-detail-all-joeys-otd.index|statistics-joey-detail.index|statistics-joey-detail-day-otd.index|statistics-joey-detail-week-otd.index|statistics-joey-detail-month-otd.index|statistics-joey-detail-year-otd.index|statistics-joey-detail-all-counts.index|statistics-joey-detail-manual-counts.index|statistics-joey-detail-joey-time.index|statistics-joey-detail-graph.index|statistics-joey-detail-order.index|statistics-joey-detail-failed-order.index',
            'In Bound' =>'statistics-inbound.index|statistics-inbound-data.index|statistics-setup-time.index|statistics-sorting-time.index|statistics-inbound.wareHouseSorterUpdate',
            'Out Bound' =>'statistics-outbound.index|statistics-outbound-data.index|statistics-dispensing-time.index|statistics-outbound.wareHouseSorterUpdate',
            'Summary' =>'warehouse-summary.index|warehouse-summary-data.index',
            'Manager' =>'manager.index|manager.create|manager.store|manager.edit|manager.update|manager.show|check-for-hub',
            'Alert System' =>'alert-system.index|warehousesorter.index|warehousesorter.data|warehousesorter.add|warehousesorter.create|warehousesorter.profile|warehousesorter.edit|warehousesorter.update|warehousesorter.delete',

        ],
    'Roles'=>
        [
            'Roles List' => 'role.index',
            'Create' => 'role.create|role.store',
            'Edit' => 'role.edit|role.update',
            'View' => 'role.show',
            'Set permissions' => 'role.set-permissions|role.set-permissions.update',
        ],
    'Sub Admin'=>
        [
            'Sub Admins' => 'sub-admin.index|subAdmin.data',
            'Create' => 'subAdmin.add|subAdmin.create',
            'Edit' => 'subAdmin.edit|subAdmin.update',
            'Status change' => 'sub-admin.active|sub-admin.inactive',
            'View' => 'subAdmin.profile',
            'Change Password' => 'sub-admin-change.password|sub-admin-create.password',
			'Account Security' => 'account-security.edit|account-security.update',
            'Delete' => 'subAdmin.delete',
        ],

    'Ctc Sub Admin'=>
        [
            'Sub Admins' => 'ctc-subadmin.index|ctc-subadmin.data',
            'Create' => 'ctc-subadmin.add|ctc-subadmin.create',
            'Edit' => 'ctc-subadmin.edit|ctc-subadmin.update',
            'Status change' => 'ctc-subadmin.active|ctc-subadmin.inactive',
            'View' => 'ctc-subadmin.profile',
            'Delete' => 'ctc-subadmin.delete',
        ],

    'New Montreal Dashboard'=>
        [
            'New Montreal Dashboard' => 'newmontreal.index|newmontreal.data|newmontreal.totalcards|newmontreal.mainfestcards|newmontreal.failedcards|newmontreal.customroutecards|newmontreal.yesterdaycards|newmontreal.route-list|newmontreal.joey-list|newmontreal-dashboard.index',
            'Montreal View' => 'newmontreal.profile',
            'Montreal Excel' => 'newexport_Montreal.excel',
            'New Sorted Order' => 'newmontreal-sort.index|newmontrealSorted.data',
            'Montreal Sorted View' => 'newmontreal_sorted.profile',
            'Sorted Excel' => 'newexport_MontrealSorted.excel',
            'New Pickup From Hub' => 'newmontreal-pickup.index|newmontrealPickedUp.data',
            'Montreal Pickup View' => 'newmontreal_pickup.profile',
            'Pick Up Excel' => 'newexport_MontrealPickedUp.excel',
            'New Not Scan' => 'newmontreal-not-scan.index|newmontrealNotScan.data',
            'Montreal Not Scan View' => 'newmontreal_notscan.profile',
            'Not Scan Excel' => 'newexport_MontrealNotScan.excel',
            'New Delivered Orders' => 'newmontreal-delivered.index|newmontrealDelivered.data',
            'Montreal Delivered View' => 'newmontreal_delivered.profile',
            'Delivered Excel' => 'newexport_MontrealDelivered.excel',
            'New Returned Orders' => 'newmontreal-returned.index|newmontrealReturned.data|newmontreal-notreturned.index|newmontrealNotReturned.data',
            'Montreal Returned View' => 'newmontreal_returned.profile|newmontreal_notreturned.profile',
            'Returned Excel' => 'newexport_MontrealReturned.excel|newexport_MontrealNotReturned.excel|newexport_MontrealNotReturned_Tracking.excel',
            'New Custom Route Orders' => 'newmontreal-custom-route.index|newmontrealCustomRoute.data',
            'Montreal Custom Route View' => 'newmontreal_customroute.profile',
            'Custom Route Excel' => 'newexport_MontrealCustomRoute.excel',
            'Route Information' => 'newmontreal-route-info.index|newmontreal_route.route-details.flag-history-model-html-render|flag.create|un-flag',
            'Route Detail' => 'newmontreal_route.detail|newmontreal_route.route-details.flag-history-model-html-render|flag.create|un-flag',
            'Route Order Detail' => 'newmontrealinfo_route.detail',
            'Route Info Excel' => 'newexport_MontrealRouteInfo.excel',
            'Notes'=>'newmontreal-route-info.addNote|newmontreal-route-info.getNotes',
        ],


    /*'Montreal Dashboard'=>
        [
            'Montreal Dashboard' => 'montreal.index|montreal.data',
            'Montreal View' => 'montreal.profile',
            'Montreal Excel' => 'export_Montreal.excel',
            'Sorted Order' => 'montreal-sort.index|montrealSorted.data',
            'Montreal Sorted View' => 'montreal_sorted.profile',
            'Sorted Excel' => 'export_MontrealSorted.excel',
            'Pickup From Hub' => 'montreal-pickup.index|montrealPickedUp.data',
            'Montreal Pickup View' => 'montreal_pickup.profile',
            'Pick Up Excel' => 'export_MontrealPickedUp.excel',
            'Not Scan' => 'montreal-not-scan.index|montrealNotScan.data',
            'Montreal Not Scan View' => 'montreal_notscan.profile',
            'Not Scan Excel' => 'export_MontrealNotScan.excel',
            'Delivered Orders' => 'montreal-delivered.index|montrealDelivered.data',
            'Montreal Delivered View' => 'montreal_delivered.profile',
            'Delivered Excel' => 'export_MontrealDelivered.excel',
            'Returned Orders' => 'montreal-returned.index|montrealReturned.data',
            'Montreal Returned View' => 'montreal_returned.profile',
            'Returned Excel' => 'export_MontrealReturned.excel',
            'Route Information' => 'montreal-route-info.index',
            'Route Detail' => 'montreal_route.detail',
            'Route Order Detail' => 'montrealinfo_route.detail',
            'Route Info Excel' => 'export_MontrealRouteInfo.excel',
        ],*/

     /*'New Ottawa Dashboard'=>
        [
            'New Ottawa Dashboard' => 'newottawa.index|newottawa.data|newottawa.totalcards|newottawa.mainfestcards|newottawa.failedcards|newottawa.customroutecards|newottawa.yesterdaycards|newottawa.ottawa-route-list|newottawa.ottawa-joey-list|newottawa-dashboard.index',
            'Ottawa View' => 'newottawa.profile',
            'Ottawa Excel' => 'newexport_Ottawa.excel',
            'New Sorted Order' => 'newottawa-sort.index|newottawaSorted.data',
            'Ottawa Sorted View' => 'newottawa_sorted.profile',
            'Sorted Excel' => 'newexport_OttawaSorted.excel',
            'New Pickup From Hub' => 'newottawa-pickup.index|newottawaPickedUp.data',
            'Ottawa Pickup View' => 'newottawa_pickup.profile',
            'Pick Up Excel' => 'newexport_OttawaPickedUp.excel',
            'New Not Scan' => 'newottawa-not-scan.index|newottawaNotScan.data',
            'Ottawa Not Scan View' => 'newottawa_notscan.profile',
            'Not Scan Excel' => 'newexport_OttawaNotScan.excel',
            'New Delivered Orders' => 'newottawa-delivered.index|newottawaDelivered.data',
            'Ottawa Delivered View' => 'newottawa_delivered.profile',
            'Delivered Excel' => 'newexport_OttawaDelivered.excel',
            'New Returned Orders' => 'newottawa-returned.index|newottawaReturned.data|newottawa-notreturned.index|newottawaNotReturned.data',
            'Returned Excel' => 'newexport_OttawaReturned.excel|newexport_OttawaNotReturned.excel|newexport_OttawaNotReturned_tracking.excel',
            'Ottawa Returned View' => 'newottawa_returned.profile|newottawa_notreturned.profile',
            'New Custom Route Orders' => 'newottawa-custom-route.index|newottawaCustomRoute.data',
            'Custom Route Excel' => 'newexport_OttawaCustomRoute.excel',
            'Ottawa Custom Route View' => 'newottawa_CustomRoute.profile',
            'Route Information' => 'newottawa-route-info.index|newottawainfo_route.route-details.flag-history-model-html-render|flag.create|un-flag',
            'Route Detail' => 'newottawa_route.detail|newottawainfo_route.route-details.flag-history-model-html-render|flag.create|un-flag',
            'Route Order Detail' => 'newottawainfo_route.detail',
            'Route Info Excel' => 'newexport_OttawaRouteInfo.excel',
            'Notes'=>'newottawa-route-info.addNote|newottawa-route-info.getNotes',
        ],*/

   /* 'Ottawa Dashboard'=>
        [
            'Ottawa Dashboard' => 'ottawa.index|ottawa.data',
            'Ottawa View' => 'ottawa.profile',
            'Ottawa Excel' => 'export_Ottawa.excel',
            'Sorted Order' => 'ottawa-sort.index|ottawaSorted.data',
            'Ottawa Sorted View' => 'ottawa_sorted.profile',
            'Sorted Excel' => 'export_OttawaSorted.excel',
            'Pickup From Hub' => 'ottawa-pickup.index|ottawaPickedUp.data',
            'Ottawa Pickup View' => 'ottawa_pickup.profile',
            'Pick Up Excel' => 'export_OttawaPickedUp.excel',
            'Not Scan' => 'ottawa-not-scan.index|ottawaNotScan.data',
            'Ottawa Not Scan View' => 'ottawa_notscan.profile',
            'Not Scan Excel' => 'export_OttawaNotScan.excel',
            'Delivered Orders' => 'ottawa-delivered.index|ottawaDelivered.data',
            'Ottawa Delivered View' => 'ottawa_delivered.profile',
            'Delivered Excel' => 'export_OttawaDelivered.excel',
            'Returned Excel' => 'export_OttawaReturned.excel',
            'Ottawa Returned View' => 'ottawa_returned.profile',
            'Returned Orders' => 'ottawa-returned.index|ottawaReturned.data',
            'Route Information' => 'ottawa-route-info.index',
            'Route Detail' => 'ottawa_route.detail',
            'Route Order Detail' => 'ottawainfo_route.detail',
            'Route Info Excel' => 'export_OttawaRouteInfo.excel',
        ],*/
     'CTC Dashboard'=>
        [
            'CTC Dashboard' => 'ctc-dashboard.index|ctc-dashboard.data',
            'CTC View' => 'ctc-new.profile',
            'CTC Broker' => 'ctc-dashboard-broker.index|ctc-dashboard-broker.data',
            'CTC Broker View' => 'ctc-broker.profile',
            'CTC Excel' => 'export_ctc_new_dashboard.excel',
            'OTD Report' => 'export_ctc_new_dashboard_otd_report.excel',
            'CTC Summary' => 'ctc_reporting.index|ctc_reporting_data.data',
			'CTC Summary View' => 'ctc-summary.profile',
			'CTC Graph' => 'ctc-graph.index|ctc-otd-day.index|ctc-otd-week.index|ctc-otd-month.index',
            'Route Information' => 'ctc-route-info.index|ctcinfo_route.route-details.flag-history-model-html-render|flag.create|un-flag',
            'Route Detail' => 'ctc_route.detail|ctcinfo_route.route-details.flag-history-model-html-render|flag.create|un-flag',
            'Mark Delay' => 'route-mark-delay',
            'Route Order Detail' => 'ctcinfo_route.detail',
            'Route Info Excel' => 'export_CTCRouteInfo.excel',
            'New CTC Dashboard' => 'new-order-ctc.data|new-order-ctc.index|new-ctc-card-dashboard.index|new-ctc.totalcards|new-ctc.customroutecards|new-ctc.yesterdaycards',
            'New CTC View' => 'new-ctc-detail-detail.profile',
            'New CTC Excel' => 'new-order-ctc-export.excel',
            'Sorted Order' => 'new-sort-ctc.index|new-sort-ctc.data',
            'CTC Sorted View' => 'new-ctc-sorted-detail.profile',
            'Sorted Excel' => 'new-sort-ctc-export.excel',
            'Pickup From Hub' => 'new-pickup-ctc.index|new-pickup-ctc.data',
            'CTC Pickup View' => 'new-ctc-pickup-detail.profile',
            'Pick Up Excel' => 'new-pickup-ctc-export.excel',
            'Not Scan' => 'new-not-scan-ctc.index|new-not-scan-ctc.data',
            'CTC Not Scan View' => 'new-ctc-notscan-detail.profile',
            'Not Scan Excel' => 'new-not-scan-ctc-export.excel',
            'Delivered Orders' => 'new-delivered-ctc.index|new-delivered-ctc.data',
            'CTC Delivered View' => 'new-ctc-delivered-detail.profile',
            'Delivered Excel' => 'new-delivered-ctc-export.excel',
            'Returned Orders' => 'new-returned-ctc.index|new-returned-ctc.data|new-notreturned-ctc.index|new-notreturned-ctc.data',
            'Returned Excel' => 'new-returned-ctc-export.excel|new-notreturned-ctc-export.excel|new-notreturned-ctc-tracking-export.excel',
            'CTC Returned View' => 'new-ctc-returned-detail.profile|new-ctc-notreturned-detail.profile',
            'Custom Route Orders' => 'new-custom-route-ctc.index|new-custom-route-ctc.data',
            'Custom Route Excel' => 'new-custom-route-ctc-export.excel',
            'CTC Custom Route View' => 'new-ctc-CustomRoute-detail.profile',
            'Notes'=>'new-ctc-route-info.addNote|new-ctc-route-info.getNotes',
        ],

'Toronto Dashboard'=>
        [
            'Toronto Dashboard' => 'borderless-dashboard.index|borderless-dashboard.data',
            'Toronto View' => 'borderless-order.profile',
            'Toronto Excel' => 'borderless-dashboard-export.excel',
            'OTD Report' => 'borderless-dashboard-export-otd-report.excel',
            'Toronto Summary' => 'borderless_reporting.index|new_borderless_reporting_data.data',
            'Toronto Summary View' => 'borderless-summary.profile',
            'Toronto Graph' => 'borderless-graph.index|borderless-otd-day.index|borderless-otd-week.index|borderless-otd-month.index',
            'Route Information' => 'borderless-route-info.index|borderlessinfo_route.route-details.flag-history-model-html-render|flag.create|un-flag',
            'Route Detail' => 'borderless_route.detail|borderlessinfo_route.route-details.flag-history-model-html-render|flag.create|un-flag',
            'Mark Delay' => 'borderless-route-mark-delay',
            'Dispatch' => 'borderless_route.delivered_permission',
            'Un-Dispatch' => 'borderless_route.delivered_permission_denied',
            'Route Order Detail' => 'borderlessinfo_route.detail',
            'Route Info Excel' => 'export_BorderlessRouteInfo.excel',
            'New Toronto Dashboard' => 'new-order-borderless.data|new-order-borderless.index|new-borderless-card-dashboard.index|new-borderless.totalcards|new-borderless.customroutecards|new-borderless.yesterdaycards',
            'New Toronto View' => 'borderless-detail-detail.profile',
            'New Toronto Excel' => 'new-order-borderless-export.excel',
            'Sorted Order' => 'new-sort-borderless.index|new-sort-borderless.data',
            'Toronto Sorted View' => 'borderless-sorted-detail.profile',
            'Sorted Excel' => 'new-sort-borderless-export.excel',
            'Pickup From Hub' => 'new-pickup-borderless.index|new-pickup-borderless.data',
            'Toronto Pickup View' => 'borderless-pickup-detail.profile',
            'Pick Up Excel' => 'new-pickup-borderless-export.excel',
            'Not Scan' => 'new-not-scan-borderless.index|new-not-scan-borderless.data',
            'Toronto Not Scan View' => 'borderless-notscan-detail.profile',
            'Not Scan Excel' => 'new-not-scan-borderless-export.excel',
            'Delivered Orders' => 'new-delivered-borderless.index|new-delivered-borderless.data',
            'Toronto Delivered View' => 'borderless-delivered-detail.profile',
            'Delivered Excel' => 'new-delivered-borderless-export.excel',
            'Returned Orders' => 'new-returned-borderless.index|new-returned-borderless.data|new-notreturned-borderless.index|new-notreturned-borderless.data',
            'Returned Excel' => 'new-returned-borderless-export.excel|new-notreturned-borderless-export.excel|new-notreturned-borderless-tracking-export.excel',
            'Toronto Returned View' => 'borderless-returned-detail.profile|borderless-notreturned-detail.profile',
            'Custom Route Orders' => 'new-custom-route-borderless.index|new-custom-route-borderless.data',
            'Custom Route Excel' => 'new-custom-route-borderless-export.excel',
            'Toronto Custom Route View' => 'borderless-CustomRoute-detail.profile',
            'Notes'=>'new-borderless-route-info.addNote|new-borderless-route-info.getNotes',
			'Order Count' => 'total-order.notinroute|not.routed.orders.list|not.routed.orders.list.data',
            'Received At Hub' => 'new-receivedathub-borderless.index|new-receivedathub-borderless.data',
//            'Received At Hub Borderless Export Excel' => 'new-receivedathub-borderless-export.excel',
            'Received At Hub Tracking Excel' => 'new-receivedathub-borderless-tracking-export.excel',
            'Received At Hub Profile' => 'borderless-receivedathub-detail.profile',
            'Reattempt Orders' => 'to-be-reattemptedorders-borderless.index|to-be-reattemptedorders-borderless.data',
            'Redelivery Orders' => 're-delivery-orders-borderless.index|re-delivery-orders-borderless.data',
            'Reattemptted Order Details' => 'borderless-reattempted-detail.profile',
            'Reattempted Order Excel' => 'new-reattemptedorders-borderless-tracking-export.excel',
            'Redelivery Order Excel' => 're-delivery-orders-borderless-tracking-export.excel'
        ],

'Vancouver Dashboard' =>
        [
            'Vancouver Dashboard' => 'vancouver-dashboard.index|vancouver-dashboard.data',
            'Vancouver View' => 'vancouver-order.profile',
            'Vancouver Excel' => 'vancouver-dashboard-export.excel',
            'OTD Report' => 'vancouver-dashboard-export-otd-report.excel',
            'Vancouver Summary' => 'vancouver_reporting.index|new_vancouver_reporting_data.data|delivered-orders-vancouver.index|AtHub-vancouver.index|SortedAt-vancouver.index|PickedUpFrom-vancouver.index|AtStore-vancouver.index|AtStore-vancouver.data',
            'Vancouver Summary View' => 'vancouver-summary.profile',
            'Vancouver Graph' => 'vancouver-graph.index|vancouver-otd-day.index|vancouver-otd-week.index|vancouver-otd-month.index',
            'Route Information' => 'vancouver-route-info.index|vancouverinfo_route.route-details.flag-history-model-html-render|flag.create|un-flag',
            'Route Detail' => 'vancouver_route.detail|vancouverinfo_route.route-details.flag-history-model-html-render|flag.create|un-flag',
            'Mark Delay' => 'vancouver-route-mark-delay',
            'Route Order Detail' => 'vancouverinfo_route.detail',
            'Route Info Excel' => 'new-export_vancouverRouteInfo.excel',
            'New Vancouver Dashboard' => 'new-order-vancouver.data|new-order-vancouver.index',
            'New Vancouver View' => 'vancouver-detail-detail.profile',
            'New Vancouver Excel' => 'new-order-vancouver-export.excel',
            'Sorted Order' => 'new-sort-vancouver.index|new-sort-vancouver.data',
            'Vancouver Sorted View' => 'vancouver-sorted-detail.profile',
            'Sorted Excel' => 'new-sort-vancouver-export.excel',
            'Pickup From Hub' => 'new-pickup-vancouver.index|new-pickup-vancouver.data',
            'Vancouver Pickup View' => 'vancouver-pickup-detail.profile',
            'Pick Up Excel' => 'new-pickup-vancouver-export.excel',
            'Not Scan' => 'new-not-scan-vancouver.index|new-not-scan-vancouver.data',
            'Vancouver Not Scan View' => 'vancouver-notscan-detail.profile',
            'Not Scan Excel' => 'new-not-scan-vancouver-export.excel',
            'Delivered Orders' => 'new-delivered-vancouver.index|new-delivered-vancouver.data',
            'Vancouver Delivered View' => 'vancouver-delivered-detail.profile',
            'Delivered Excel' => 'new-delivered-vancouver-export.excel',
            'Returned Orders' => 'new-returned-vancouver.index|new-returned-vancouver.data|new-notreturned-vancouver.index|new-notreturned-vancouver.data',
            'Returned Excel' => 'new-returned-vancouver-export.excel|new-notreturned-vancouver-export.excel|new-notreturned-vancouver-tracking-export.excel',
            'Vancouver Returned View' => 'vancouver-returned-detail.profile|vancouver-notreturned-detail.profile',
            'Custom Route Orders' => 'new-custom-route-vancouver.index|new-custom-route-vancouver.data',
            'Custom Route Excel' => 'new-custom-route-vancouver-export.excel',
            'Vancouver Custom Route View' => 'vancouver-CustomRoute-detail.profile',
            'Notes'=>'vancouver-route-info.addNote|vancouver-route-info.getNotes',
        ],
		
		'Ottawa Dashboard' =>
        [
            'Ottawa Dashboard' => 'ottawa-dashboard.index|ottawa-dashboard.data',
            'Ottawa View' => 'ottawa-order.profile',
            'Ottawa Excel' => 'ottawa-dashboard-export.excel',
            'OTD Report' => 'ottawa-dashboard-export-otd-report.excel',
            'Ottawa Summary' => 'ottawa_reporting.index|new_ottawa_reporting_data.data|DeliveredOrder-ottawa.index|AtHub-ottawa.index|SortedAt-ottawa.index|PickedUpFrom-ottawa.index|AtStore-ottawa.index|AtStore-ottawa.data',
            'Ottawa Summary View' => 'ottawa-summary.profile',
            'Ottawa Graph' => 'ottawa-graph.index|ottawa-otd-day.index|ottawa-otd-week.index|ottawa-otd-month.index',
            'Route Information' => 'ottawa-dashboard-route-info.index|Ottawainfo_route.route-details.flag-history-model-html-render|flag.create|un-flag',
            'Route Detail' => 'ottawa_dashboard_route.detail|Ottawainfo_route.route-details.flag-history-model-html-render|flag.create|un-flag',
			'Route View' => 'ottawa-dashboard-info_route.detail',
            'Mark Delay' => 'ottawa-route-mark-delay',
            'Route Order Detail' => 'ottawa_dashboard_route.detail',
            'Route Info Excel' => 'new-export_ottawaRouteInfo.excel',
            'New Ottawa Dashboard' => 'new-order-ottawa.data|new-order-ottawa.index',
            'New Ottawa View' => 'ottawa-detail-detail.profile',
            'New Ottawa Excel' => 'new-order-ottawa-export.excel',
            'Sorted Order' => 'new-sort-ottawa.index|new-sort-ottawa.data',
            'Ottawa Sorted View' => 'ottawa-sorted-detail.profile',
            'Sorted Excel' => 'new-sort-ottawa-export.excel',
            'Pickup From Hub' => 'new-pickup-ottawa.index|new-pickup-ottawa.data',
            'Ottawa Pickup View' => 'ottawa-pickup-detail.profile',
            'Pick Up Excel' => 'new-pickup-ottawa-export.excel',
            'Not Scan' => 'new-not-scan-ottawa.index|new-not-scan-ottawa.data',
            'Ottawa Not Scan View' => 'ottawa-notscan-detail.profile',
            'Not Scan Excel' => 'new-not-scan-ottawa-export.excel',
            'Delivered Orders' => 'new-delivered-ottawa.index|new-delivered-ottawa.data',
            'Ottawa Delivered View' => 'ottawa-delivered-detail.profile',
            'Delivered Excel' => 'new-delivered-ottawa-export.excel',
            'Returned Orders' => 'new-returned-ottawa.index|new-returned-ottawa.data|new-notreturned-ottawa.index|new-notreturned-ottawa.data',
            'Returned Excel' => 'new-returned-ottawa-export.excel|new-notreturned-ottawa-export.excel|new-notreturned-ottawa-tracking-export.excel',
            'Ottawa Returned View' => 'ottawa-returned-detail.profile|ottawa-notreturned-detail.profile',
            'Custom Route Orders' => 'new-custom-route-ottawa.index|new-custom-route-ottawa.data',
            'Custom Route Excel' => 'new-custom-route-ottawa-export.excel',
            'Ottawa Custom Route View' => 'ottawa-CustomRoute-detail.profile',
            'Notes'=>'ottawa-route-info.addNote|ottawa-route-info.getNotes',
        ],
		
		 'New York Dashboard' =>
        [
             'New York Dashboard' => 'newyork-dashboard.index|newyork-dashboard.data',
            'New York View' => 'newyork-order.profile',
            'New York Excel' => 'newyork-dashboard-export.excel',
            'New New York Dashboard' => 'new-order-newyork.data|new-order-newyork.index',
            'New New York View' => 'newyork-detail-detail.profile',
            'New New York Excel' => 'order-newyork-export.excel',
            'OTD Report' => 'newyork-dashboard-export-otd-report.excel',
            'New York Summary' => 'newyork_reporting.index|newyork_reporting_data.data',
            'New York Summary View' => 'newyork-order.profile',
            'New York Graph' => 'newyork-graph.index|newyork-otd-day.index|newyork-otd-week.index|newyork-otd-month.index',
            'Route Information' => 'newyork-dashboard-route-info.index|newyorkinfo_route.route-details.flag-history-model-html-render|flag.create|un-flag',
            'Route Detail' => 'newyork_dashboard_route.detail|newyorkinfo_route.route-details.flag-history-model-html-render|flag.create|un-flag',
            'Mark Delay' => 'newyork-route-mark-delay',
            'Route Order Detail' => 'newyork-dashboard-info_route.detail',
            'Route Info Excel' => 'new-export_newyorkRouteInfo.excel',
            'Pickup From Hub' => 'pickup-newyork.index|pickup-newyork.data',
            'New York Pickup View' => 'newyork-pickup-detail.profile',
            'Pick Up Excel' => 'pickup-newyork-export.excel',
            'Delivered Orders' => 'delivered-newyork.index|delivered-newyork.data',
            'New York Delivered View' => 'newyork-delivered-detail.profile',
            'Delivered Excel' => 'delivered-newyork-export.excel',
            'Returned Orders' => 'returned-newyork.index|returned-newyork.data|notreturned-newyork.index|notreturned-newyork.data',
            'Returned Excel' => 'returned-newyork-export.excel|notreturned-newyork-export.excel|notreturned-newyork-tracking-export.excel',
            'New York Returned View' => 'newyork-returned-detail.profile|newyork-notreturned-detail.profile',
            'Notes'=>'newyork-route-info.addNote|newyork-route-info.getNotes',
        ],
		
		'Walmart E-commerce'=>
    [
        
        'Walmart EC Dashboard' => 'e-commerce-dashboard.index|e-commerce.data',
        'EC View' => 'e-commerce.profile',
        'Walmart EC Excel' => 'walmart-dashboard-export.excel',
		'Walmart EC Summary' => 'walmart_ecommerce_reporting.index|walmart_ecommerce_reporting_data.data',
        'Walmart EC Summary View' => 'e-commerce.profile',
        'Walmart EC Graph' => 'walmart-ecommerce-graph.index|walmart-ecommerce-otd-day.index|walmart-ecommerce-otd-week.index|walmart-ecommerce-otd-month.index',
        'New Walmart EC Dashboard' => 'new-order-walmart-ecommerce.data|new-order-walmart-ecommerce.index|new-walmart-ecommerce-card-dashboard.index|new-walmart-ecommerce.totalcards',
        'New Walmart EC View' => 'walmart-ecommerce-detail.profile',
        'New Walmart EC Excel' => 'new-order-walmart-ecommerce-export.excel',
        'Sorted Order' => 'new-sort-walmart-ecommerce.index|new-sort-walmart-ecommerce.data',
        'Walmart EC Sorted View' => 'walmart-ecommerce-sorted-detail.profile',
        'Sorted Excel' => 'new-sort-walmart-ecommerce-export.excel',
        'Pickup From Hub' => 'new-pickup-walmart-ecommerce.index|new-pickup-walmart-ecommerce.data',
        'Walmart EC Pickup View' => 'walmart-ecommerce-pickup-detail.profile',
        'Pick Up Excel' => 'new-pickup-walmart-ecommerce-export.excel',
        'Not Scan' => 'new-not-scan-walmart-ecommerce.index|new-not-scan-walmart-ecommerce.data',
        'Walmart EC Not Scan View' => 'walmart-ecommerce-notscan-detail.profile',
        'Not Scan Excel' => 'new-not-scan-walmart-ecommerce-export.excel',
        'Delivered Orders' => 'new-delivered-walmart-ecommerce.index|new-delivered-walmart-ecommerce.data',
        'Walmart EC Delivered View' => 'walmart-ecommerce-delivered-detail.profile',
        'Delivered Excel' => 'new-delivered-walmart-ecommerce-export.excel',
        'Returned Orders' => 'new-returned-walmart-ecommerce.index|new-returned-walmart-ecommerce.data|new-notreturned-walmart-ecommerce.index|new-notreturned-walmart-ecommerce.data',
        'Returned Excel' => 'new-returned-walmart-ecommerce-export.excel|new-notreturned-walmart-ecommerce-export.excel|new-notreturned-walmart-ecommerce-tracking-export.excel',
        'Walmart EC Returned View' => 'walmart-ecommerce-returned-detail.profile|walmart-ecommerce-notreturned-detail.profile',
    ],

    'WildFork Dashboard'=>
        [

            'WildFork Dashboard' => 'new-wildfork.index|new-wildfork.data',
            'WildFork View' => 'wildfork-detail.profile',
            'WildFork Excel' => 'new-wildfork-export.excel',

            'Wildfork Dashboard' => 'new-order-wildfork-ecommerce.data|new-order-wildfork-ecommerce.index',
            'Wildfork View' => 'wildfork-detail-detail.profile',
            'Wildfork Excel' => 'new-order-wildfork-ecommerce-export.excel|new-order-wildfork-ecommerce-export.excel',
            'OTD Report' => 'wildfork-graph.index',

            'Wildfork Summary' => 'wildfork_ecommerce_reporting.index|wildfork_ecommerce_reporting_data.data',
            'Wildfork Summary View' => 'wildfork-ecommerce-detail.profile',
            'Wildfork Graph' => 'wildfork-ecommerce-graph.index|wildfork-ecommerce-otd-day.index|wildfork-ecommerce-otd-week.index|wildfork-ecommerce-otd-month.index',

            'Sorted Order' => 'new-sort-wildfork-ecommerce.index|new-sort-wildfork-ecommerce.data',
            'Wildfork Sorted View' => 'wildfork-ecommerce-sorted-detail.profile',
            'Sorted Excel' => 'new-sort-wildfork-ecommerce-export.excel',

            'Pickup From Hub' => 'new-pickup-wildfork-ecommerce.index|new-pickup-wildfork-ecommerce.data',
            'Wildfork Pickup View' => 'wildfork-ecommerce-pickup-detail.profile',
            'Pick Up Excel' => 'new-pickup-wildfork-ecommerce-export.excel',

            'Not Scan' => 'new-not-scan-wildfork-ecommerce.index|new-not-scan-wildfork-ecommerce.data',
            'Wildfork Not Scan View' => 'wildfork-ecommerce-notscan-detail.profile',
            'Not Scan Excel' => 'new-not-scan-wildfork-ecommerce-export.excel',

            'Delivered Orders' => 'new-delivered-wildfork-ecommerce.index|new-delivered-wildfork-ecommerce.data',
            'Wildfork Delivered View' => 'wildfork-ecommerce-delivered-detail.profile',
            'Delivered Excel' => 'new-delivered-wildfork-ecommerce-export.excel',

            'Returned Orders' => 'new-returned-wildfork-ecommerce.index|new-returned-wildfork-ecommerce.data|new-notreturned-wildfork-ecommerce.index|new-notreturned-wildfork-ecommerce.data',
            'Returned Excel' => 'wildfork-returned-detail.profile|notreturned-wildfork-export.excel|notreturned-wildfork-tracking-export.excel',
            'Wildfork Returned View' => 'wildfork-ecommerce-returned-detail.profile|wildfork-ecommerce-notreturned-detail.profile',
			
			'Wildfork Statistics' => 'new-WildFork-card-dashboard.index',

        ],

    'LogX Dashboard'=>
        [

            'LogX Client Dashboard' => 'new-logx.index|new-logx.data',
            'LogX Client View' => 'logx-detail.profile',
            'LogX Client Excel' => 'new-logx-export.excel',

            'Logx Dashboard' => 'new-order-logx-ecommerce.data|new-order-logx-ecommerce.index',
            'Logx View' => 'logx-ecommerce-detail.profile',
            'Logx Excel' => 'new-order-logx-ecommerce-export.excel|new-order-logx-ecommerce-export.excel',

            'OTD Report' => 'logx-ecommerce-graph.index',
            'Logx Summary' => 'logx_ecommerce_reporting.index|logx_ecommerce_reporting_data.data',
            'Logx Summary View' => 'logx-ecommerce-detail.profile',
            'Logx Graph' => 'logx-ecommerce-graph.index|logx-ecommerce-otd-day.index|logx-ecommerce-otd-week.index|logx-ecommerce-otd-month.index',

            'Sorted Order' => 'new-sort-logx-ecommerce.index|new-sort-logx-ecommerce.data',
            'Logx Sorted View' => 'logx-ecommerce-sorted-detail.profile',
            'Sorted Excel' => 'new-sort-logx-ecommerce-export.excel',

            'Pickup From Hub' => 'new-pickup-logx-ecommerce.index|new-pickup-logx-ecommerce.data',
            'Logx Pickup View' => 'logx-ecommerce-pickup-detail.profile',
            'Pick Up Excel' => 'new-pickup-logx-ecommerce-export.excel',

            'Not Scan' => 'new-not-scan-logx-ecommerce.index|new-not-scan-logx-ecommerce.data',
            'Logx Not Scan View' => 'logx-ecommerce-notscan-detail.profile',
            'Not Scan Excel' => 'new-not-scan-logx-ecommerce-export.excel',

            'Delivered Orders' => 'new-delivered-logx-ecommerce.index|new-delivered-logx-ecommerce.data',
            'Logx Delivered View' => 'logx-ecommerce-delivered-detail.profile',
            'Delivered Excel' => 'new-delivered-logx-ecommerce-export.excel',

            'Returned Orders' => 'new-returned-logx-ecommerce.index|new-returned-logx-ecommerce.data|new-notreturned-logx-ecommerce.index|new-notreturned-logx-ecommerce.data',
            'Returned Excel' => 'logx-returned-detail.profile|new-notreturned-logx-ecommerce-export.excel|new-notreturned-logx-ecommerce-tracking-export.excel',
            'Logx Returned View' => 'logx-ecommerce-returned-detail.profile|logx-ecommerce-notreturned-detail.profile',
			
			'Logx Statistics' => 'new-logx-card-dashboard.index',

            'Management View' => 'vendor-statics.index|vendor-statics-day-otd.index|vendor-statics-week-otd.index|vendor-statics-month-otd.index|vendor-statics-year-otd.index|vendor-statics-all-counts.index|vendor-statics-failed-counts.index|vendor-statics-custom-counts.index|vendor-statics-manual-counts.index|vendor-statics-route-counts.index|vendor-statics-route-detail.index|vendor-statics-on-time-counts.index|vendor-statics-top-ten-joeys.index|vendor-statics-least-ten-joeys.index|vendor-statics-graph.index|vendor-statics-brooker.index|vendor-statics-order.index|vendor-statics-failed-order.index|vendor-statics-brooker-detail.index|vendor-statics-brooker-detail-day-otd.index|vendor-statics-brooker-detail-week-otd.index|vendor-statics-brooker-detail-month-otd.index|vendor-statics-brooker-detail-year-otd.index|vendor-statics-brooker-detail-all-counts.index|vendor-statics-brooker-detail-failed-counts.index|vendor-statics-brooker-detail-custom-counts.index|vendor-statics-brooker-detail-manual-counts.index|vendor-statics-brooker-detail-route-counts.index|vendor-statics-brooker-detail-on-time-counts.index|vendor-statics-brooker-detail-top-ten-joeys.index|vendor-statics-brooker-detail-least-ten-joeys.index|vendor-statics-brooker-detail-graph.index|vendor-statics-brooker-detail-brooker.index|vendor-statics-brooker-detail-order.index|vendor-statics-brooker-detail-failed-order.index|vendor-statics-brooker-detail-all-joeys-otd.index|vendor-statics-brooker-detail-all-joeys-otd.index|vendor-statics-joey-detail.index|vendor-statics-joey-detail-day-otd.index|vendor-statics-joey-detail-week-otd.index|vendor-statics-joey-detail-month-otd.index|vendor-statics-joey-detail-year-otd.index|vendor-statics-joey-detail-all-counts.index|vendor-statics-joey-detail-manual-counts.index|vendor-statics-joey-detail-joey-time.index|vendor-statics-joey-detail-graph.index|vendor-statics-joey-detail-order.index|vendor-statics-joey-detail-failed-order.index',
        ],


    'Scarborough Dashboard' =>
        [
            'Scarborough Dashboard' => 'scarborough-dashboard.index|scarborough-dashboard.data',
            'Scarborough View' => 'scarborough-order.profile',
            'Scarborough Excel' => 'scarborough-dashboard-export.excel',
            'OTD Report' => 'scarborough-dashboard-export-otd-report.excel',
            'Scarborough Summary' => 'scarborough_reporting.index|new_scarborough_reporting_data.data',
            'Scarborough Summary View' => 'scarborough-summary.profile',
            'Scarborough Graph' => 'scarborough-graph.index|scarborough-otd-day.index|scarborough-otd-week.index|scarborough-otd-month.index',
            'Route Information' => 'scarborough-route-info.index|scarboroughinfo_route.route-details.flag-history-model-html-render|flag.create|un-flag',
            'Route Detail' => 'scarborough_route.detail|scarboroughinfo_route.route-details.flag-history-model-html-render|flag.create|un-flag',
            'Mark Delay' => 'scarborough-route-mark-delay',
            'Route Order Detail' => 'scarboroughinfo_route.detail',
            'Route Info Excel' => 'new-export_scarboroughRouteInfo.excel|total-order.notinroute',
            'New Scarborough Dashboard' => 'new-order-scarborough.data|new-order-scarborough.index',
            'New Scarborough View' => 'scarborough-detail-detail.profile',
            'New Scarborough Excel' => 'new-order-scarborough-export.excel',
            'Sorted Order' => 'new-sort-scarborough.index|new-sort-scarborough.data',
            'Scarborough Sorted View' => 'scarborough-sorted-detail.profile',
            'Sorted Excel' => 'new-sort-scarborough-export.excel',
            'Pickup From Hub' => 'new-pickup-scarborough.index|new-pickup-scarborough.data',
            'Scarborough Pickup View' => 'scarborough-pickup-detail.profile',
            'Pick Up Excel' => 'new-pickup-scarborough-export.excel',
            'Not Scan' => 'new-not-scan-scarborough.index|new-not-scan-scarborough.data',
            'Scarborough Not Scan View' => 'scarborough-notscan-detail.profile',
            'Not Scan Excel' => 'new-not-scan-scarborough-export.excel',
            'Delivered Orders' => 'new-delivered-scarborough.index|new-delivered-scarborough.data',
            'Scarborough Delivered View' => 'scarborough-delivered-detail.profile',
            'Delivered Excel' => 'new-delivered-scarborough-export.excel',
            'Returned Orders' => 'new-returned-scarborough.index|new-returned-scarborough.data|new-notreturned-scarborough.index|new-notreturned-scarborough.data',
            'Returned Excel' => 'new-returned-scarborough-export.excel|new-notreturned-scarborough-export.excel|new-notreturned-scarborough-tracking-export.excel',
            'Scarborough Returned View' => 'scarborough-returned-detail.profile|scarborough-notreturned-detail.profile',
            'Custom Route Orders' => 'new-custom-route-scarborough.index|new-custom-route-scarborough.data',
            'Custom Route Excel' => 'new-custom-route-scarborough-export.excel',
            'Scarborough Custom Route View' => 'scarborough-CustomRoute-detail.profile',
            'Notes'=>'Scarborough-route-info.addNote|scarborough-route-info.getNotes',

            'Received At Hub' => 'receivedathub-scarBorough.index|receivedathub-scarBorough.data',
//            'Received At Hub scarBorough Export Excel' => 'new-receivedathub-scarBorough-export.excel',
            'Received At Hub Tracking Excel' => 'receivedathub-scarBorough-tracking-export.excel',
            'Received At Hub Profile' => 'scarBorough-receivedathub-detail.profile',
            'Reattempt Orders' => 'to-be-reattemptedorders-scarBorough.index|to-be-reattemptedorders-scarBorough.data',
            'Redelivery Orders' => 're-delivery-orders-scarBorough.index|re-delivery-orders-scarBorough.data',
            'Reattemptted Order Details' => 'scarBorough-reattempted-detail.profile',
            'Reattempted Order Excel' => 'new-reattemptedorders-scarBorough-tracking-export.excel',
            'Redelivery Order Excel' => 're-delivery-orders-scarBorough-tracking-export.excel'
        ],

    /*'New CTC Dashboard'=>
        [
            'CTC Dashboard' => 'new-ctc-dashboard.index|new-ctc-dashboard.data',
            'CTC View' => 'new-ctc-order.profile',
            'CTC Excel' => 'new-ctc-dashboard-export.excel',
            'OTD Report' => 'new-ctc-dashboard-export-otd-report.excel',
            'CTC Summary' => 'new-ctc_reporting.index|new-ctc_reporting_data.data',
            'CTC Summary View' => 'new-ctc-order.profile',
            'CTC Graph' => 'new-ctc-graph.index|new-ctc-otd-day.index|new-ctc-otd-week.index|new-ctc-otd-month.index',
            'Route Information' => 'new-ctc-route-info.index|new-ctcinfo_route.route-details.flag-history-model-html-render|flag.create|un-flag',
            'Route Detail' => 'new-ctc_route.detail|new-ctcinfo_route.route-details.flag-history-model-html-render|flag.create|un-flag',
            'Mark Delay' => 'new-route-mark-delay',
            'Route Order Detail' => 'new-ctcinfo_route.detail',
            'Route Info Excel' => 'new-export_CTCRouteInfo.excel',
            'New CTC Dashboard' => 'new-order-ctc.data|new-order-ctc.index|new-ctc-card-dashboard.index|new-ctc.totalcards|new-ctc.customroutecards|new-ctc.yesterdaycards',
            'New CTC View' => 'new-ctc-detail-detail.profile',
            'New CTC Excel' => 'new-order-ctc-export.excel',
            'Sorted Order' => 'new-sort-ctc.index|new-sort-ctc.data',
            'CTC Sorted View' => 'new-ctc-sorted-detail.profile',
            'Sorted Excel' => 'new-sort-ctc-export.excel',
            'Pickup From Hub' => 'new-pickup-ctc.index|new-pickup-ctc.data',
            'CTC Pickup View' => 'new-ctc-pickup-detail.profile',
            'Pick Up Excel' => 'new-pickup-ctc-export.excel',
            'Not Scan' => 'new-not-scan-ctc.index|new-not-scan-ctc.data',
            'CTC Not Scan View' => 'new-ctc-notscan-detail.profile',
            'Not Scan Excel' => 'new-not-scan-ctc-export.excel',
            'Delivered Orders' => 'new-delivered-ctc.index|new-delivered-ctc.data',
            'CTC Delivered View' => 'new-ctc-delivered-detail.profile',
            'Delivered Excel' => 'new-delivered-ctc-export.excel',
            'Returned Orders' => 'new-returned-ctc.index|new-returned-ctc.data|new-notreturned-ctc.index|new-notreturned-ctc.data',
            'Returned Excel' => 'new-returned-ctc-export.excel|new-notreturned-ctc-export.excel|new-notreturned-ctc-tracking-export.excel',
            'CTC Returned View' => 'new-ctc-returned-detail.profile|new-ctc-notreturned-detail.profile',
            'Custom Route Orders' => 'new-custom-route-ctc.index|new-custom-route-ctc.data',
            'Custom Route Excel' => 'new-custom-route-ctc-export.excel',
            'CTC Custom Route View' => 'new-ctc-CustomRoute-detail.profile',
        ],*/

    'Return Dashboard'=>
        [
            'Return Route Information' => 'return-route-info.index|return-route-order.detail|return-route-info-order.detail',
        ],
    'Toronto Flower Dashboard'=>
        [
            'Route Information' => 'toronto-flower-route-info.index',
            'Route Detail' => 'toronto_flower_route.detail',
            'Route Order Detail' => 'toronto_flower_info_route.detail',
            'Route Info Excel' => 'export_toronto_flower_route_info.excel',
        ],
    'Walmart Dashboard'=>
        [
            // 'Walmart' => 'walmart.index|walmart.data',
            // 'Walmart View' => 'walmart.profile',
            // 'Walmart Excel' => 'export_walmart.excel',
            'Walmart Dashboard' => 'walmartdashboard.index|walmartotdajax.index|walmartshortsummary.index|walmartrenderorder.index|walmartontimeorder.index|walmartstoresdata.index|walmartordersummary.index',
            'Walmart Dashboard Excel' => 'walmartdashboard.excel',
			'Walmart Dashboard Reporting' => 'download-walmart-report-csv-view|generate-walmart-report-csv',
        ],

        'Loblaws Dashboard'=>
        [
            'Loblaws Dashboard' => 'loblawsdashboard.index|loblawsajaxorder.index|loblawsotdcharts.index|loblawsajaxotacharts.index|loblawstotalorder.index',
            'Loblaws Calgary' => 'loblawscalgary.index|loblawscalgary_orders.index|loblawscalgary_otd_charts.index|loblawscalgary_ota_charts.index|loblawscalgary_total_order.index',
            'Loblaws Home Delivery' => 'loblawshome.index|loblawshome_order.index|loblawshome_otd_charts.index|loblawshome_ota_charts.index|loblawshome_total_order.index',
			'Loblaws Re-Processing' => 'loblaws.order-reprocessing|loblaws.order-reprocessing-update',
			 'Loblaws Home Delivery Reporting' => 'loblaws-homedelivery-dashboard-reporting-csv|generate-loblaws-homedelivery-report-csv',
			 'Loblaws Dashboard Reporting' => 'loblaws-dashboard-reporting-csv|generate-loblaws-report-csv',
			 'Loblaws Calgary Reporting' => 'loblaws-calgary-dashboard-reporting-csv|generate-calgary-loblaws-report-csv',
        ],
		 'Good Food Dashboard'=>
        [
            'Good Food Dashboard' => 'goodfood.index|goodfood_order.index|goodfood_otd_charts.index|goodfood_ota_charts.index|goodfood-new-count',
            'Good Food Reporting' => 'goodfood-dashboard-reporting-csv|generate-goodfood-report-csv',
        ],
    'Grocery Dashboard'=>
        [
            'Grocery Dashboard' => 'grocerydashboard.index|groceryajaxorder.index|groceryotdcharts.index',

        ],
   /* 'Other Action'=>
        [
            'Update Status' => 'hub-routific.index|hub-routific-update.Update',
            'Update Multiple Status' => 'multiple-tracking-id.index|multiple-tracking-id.update',
            'Search Order' => 'searchorder.index',
            'Search Order Update' => 'update-order.update',
            'Search By Multiple Order' => 'search-multiple-tracking.index',
            'Order Detail' => 'searchorder.show',
            'Delete Route' => 'route.index|route.destroy',
        ],*/
		 'Other Action'=>
        [


            'Update Orders' => 'multiple-tracking-id.index|multiple-tracking-id.update',
             'Search Order' => 'search-multiple-tracking.index|searchorder.show|update-order.update|searchorderid.show',
             'E-commerce Flag / Un-flag Orders' => 'flag.create|un-flag',
            'Grocery Flag / Un-flag Orders' => 'grocery-flag.create|grocery-un-flag',
            'Upload Image' => 'sprint-image-upload',
            'Update Image' => 'sprint-image-update',
			'Manual Status History' => 'manual-status.index|manual-status.data',
			
			'Generate Csv' => 'generate-csv',
			'Manual Tracking Report' => 'manual-tracking-report.index|manual-tracking.data',
			'Manual Tracking Report Excel' => 'manual-tracking.excel|download-file-tracking',
            'Tracking'=>'search-tracking.index|searchtrackingdetails.show',
            'Manual Route Update'=>'manual-route.index|manual-route.update',

        ],

        'Joey Orders' => [
            'joey Orders' => 'joey.orders.index|joey.orders.instruction',
        ],
		
		'DNR Reporting'=>
        [
            'DNR Reporting' => 'dnr.index|dnr.data',
            'DNR Excel' => 'dnr.export',
        ],
        'Customer Support'=>
        [
			'Customer Support' => 'order-confirmation-list.index|orderConfirmation.transfer|Column.Update|add-notes|show-notes',
            'History' => 'order-confirmation.history|show-notes',
            'Return To Merchant' => 'expired-order.history|return-order.update|show-notes',
			'Returned Order' => 'returned-order.index|show-notes',
			 
        ],
        'E-commerce Flag Order Details'=>
        [
            'E-commerce Flag Order List' => 'flag-order-list.index|flag-order-list.data|flag-order-list-pie-chart-data',
            'E-commerce Flag Order Detail' => 'flag-order.details',
            'Approved E-commerce Flag List' => 'approved-flag-list.index|approved-flag-list.data|flag-order.details',
            'Un-Approved E-commerce Flag List' => 'un-approved-flag-list.index|un-approved-flag-list.data',
            'Multiple Approved Flag' => 'multiple.approved.flag',
            'Mark Approved' => 'joey-performance-status.update',
            'Blocked Joey List' => 'block-joey-flag-list.index|block-joey-flag-list.data',
            'Unblock Joey' => 'unblock-joey-flag.update',

        ],
		'Grocery Flag Order Details'=>
        [
            'Grocery Flag Order List' => 'grocery-flag-order-list.index|grocery-flag-order-list.data|grocery-flag-order-list-pie-chart-data',
            'Grocery Flag Order Detail' => 'grocery-flag-order.details',
            'Approved Grocery Flag List' => 'grocery-approved-flag-list.index|grocery-approved-flag-list.data|grocery-flag-order.details',
            'Un-Approved Grocery Flag List' => 'grocery-un-approved-flag-list.index|grocery-un-approved-flag-list.data',
            'Multiple Approved Flag' => 'multiple.approved.flag.grocery',
            'Mark Approved' => 'grocery-joey-performance-status.update',
            'Blocked Joey List' => 'grocery-block-joey-flag-list.index|grocery-block-joey-flag-list.data',
            'Unblock Joey' => 'grocery-unblock-joey-flag.update',

        ],
      'Reason'=>
        [
            'Reason' => 'reason.index|reason.data',
            'Create' => 'reason.add|reason.create',
            'Edit' => 'reason.edit|reason.update',
            'Delete' => 'reason.delete',
        ],
    /* 'Vendor Reporting'=>
         [
             'Vendor Reporting' => 'reporting.index|reporting.data',
             'Vendor Reporting Excel' => 'export_reporting.excel',
         ],*/

   /* 'CTC Summary '=>
        [
            'CTC Summary' => 'ctc_reporting.index|ctc_reporting_data.data',
			 'CTC Reporting Excel' => 'export_ctc_reporting.excel',
        ],*/

/*    'WareHouse Performance Report '=>
        [
            'Warehouse Performance' => 'warehouse-performance.index|warehouse-performance.data',
        ],
  'WareHouse Settings'=>
        [
            'Warehouse Settings List' => 'warehousesorter.index|warehousesorter.data',
            'Create' => 'warehousesorter.add|warehousesorter.create',
            'Edit' => 'warehousesorter.edit|warehousesorter.update'
        ],*/
		'Rights'=>
    [
        'Right List' => 'right.index',
        'Create' => 'right.create|right.store',
        'Edit' => 'right.edit|right.update',
        'View' => 'right.show',
        'Duplicate' => 'right.duplicate',
    ],
	
	'Chat Thread'=>
    [
        'Right List' => 'thread.index',
    ],
	
	'Complain'=>
        [
            'Complain List' => 'complain.index',

        ],
];
