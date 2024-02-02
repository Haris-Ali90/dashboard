<?php 
 $statuscode = array("136" => "Client requested to cancel the order",
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
"110" => "Returned to hub for re-delivery",
"111" => "Delivery to hub for return to merchant",
"121" => "Pickup from Hub",
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
"61" => "Scheduled order",
"60"  => "Task failure",
     '153' => 'Miss sorted to be reattempt',
     '154' => 'Joey unable to complete the route','155' => 'To be re-attempted tommorow');
?>
 <table class="table table-striped table-bordered" border="1">
                <thead>
                    <tr><th>Total Orders</th><th>Total Lates</th></tr>
                </thead>
                <tody>
                <tr><td id='total_order_value'><?php echo $total;?></td><td id="total_late_value"><?php echo $total-$otd;?></td></tr>
                </tody>
            </table>
<div class="table-responsive fixTableHead">
<table id="export-csv" class="table table-striped table-bordered" border="1">
                <thead>
                    <tr>
                        <th style="text-align: center">Route Number</th>
                        <th style="text-align: center">Joey</th>
                        <th style="text-align: center">Merchant Order #</th>
                        <th style="text-align: center"> Store Name </th>
                        <th style="text-align: center">Customer Address</th>
                        <th style="text-align: center">Planned Arrival</th>
                        <th style="text-align: center">Arrival time @ PU</th>
                        <th style="text-align: center">Actual Departure </th>
                        <th style="text-align: center">Time Open</th>
                        <th style="text-align: center">Time Close</th>
                        <th style="text-align: center">Estimated Delivery ETA</th>
                        <th style="text-align: center">Actual Arrival @ CX</th>
						<th style="text-align: center">Wait Time</th>
                        <th style="text-align: center">Drive Time</th>
                        <th style="text-align: center">Image</th>
                        <th style="text-align: center">Signature</th>
                        <th style="text-align: center">Distance</th>
                        <th style="text-align: center">Status</th>          
                        <th style="text-align: center">Notes</th>
                        
                        
                    </tr>
                </thead>
                <tbody>

                        <?php $allrec=0;
                    if(!empty($fullrecord)){
                    foreach($fullrecord as $record) { 
                      
                        $status = $record->status_id;

                        // if($record->sprint_status==32 && $record->task_status==28){
                        //     $status = 28;
                        // }

                        $allrec++;  ?>
                        <tr>
                            <td <?php
                                if ($record->good_food_delivery_time){
                                    if($record->GoodFoodTaskMerchants){
                                        if( $record->GoodFoodTaskMerchants->end_time!=NULL && date('Y-m-d',strtotime($record->dropoff_eta)).' '.date('H:i:s',strtotime($record->GoodFoodTaskMerchants->end_time)+300) < date('Y-m-d H:i:s',strtotime($record->good_food_delivery_time->delivery_time)))
                                        { echo "style='background:red;color: white'";}
                                    }
                                }

                                ?>><?php echo $record->sprint_id; ?></td>
                            <td><?php  echo $record->sprint->goodFoodJoey?$record->sprint->goodFoodJoey->joey_name:''; ?></td>
                            <td><?php echo $record->GoodFoodTaskMerchants?$record->GoodFoodTaskMerchants->merchant_order_num:''; ?></td>
                            <td><?php echo $record->sprint->GoodFoodContactTasks?$record->sprint->GoodFoodContactTasks->goodFoodContacts->name:''; ?></td>
                            <td><?php echo $record->task_Location->address; ?></td>
                            <td><?php
                                if ($record->sprint->GoodFoodTasks)
                                {
                                    $time = strtotime($record->sprint->GoodFoodTasks->arrival_eta);
                                    $time = $time - (15 * 60);
                                    echo  date("Y-m-d H:i:s", $time);
                                }
                                else
                                {
                                    echo '';
                                }
                              ?></td>
                            <td><?php echo $record->sprint->good_food_arrival_time?$record->sprint->good_food_arrival_time->arrival_time:''; ?></td>
                            <td><?php echo $record->sprint->good_food_departure_time?$record->sprint->good_food_departure_time->departure_time:''; ?></td>
                            <td><?php echo $record->GoodFoodTaskMerchants? date('H:i',strtotime($record->GoodFoodTaskMerchants->start_time)):''?></td>
                            <td><?php echo $record->GoodFoodTaskMerchants? date('H:i',strtotime($record->GoodFoodTaskMerchants->end_time)):''; ?></td>
                            <td><?php echo $record->dropoff_eta; ?></td>
                            <td><?php echo $record->good_food_delivery_time?$record->good_food_delivery_time->delivery_time:''; ?></td>
                            <td><?php

                                if($record->sprint->good_food_departure_time!=NULL && $record->sprint->good_food_arrival_time!=NULL)
                                {
                                    $date1=date_create($record->sprint->good_food_arrival_time->arrival_time);
                                    $date2=date_create($record->sprint->good_food_departure_time->departure_time);
                                    $diff=date_diff($date1,$date2);
                                    $wait=$diff->format("%H:%i:%S");
                                }
                                else
                                {
                                    $wait="00:00:00";
                                }
                                echo $wait; ?></td>
                            <td><?php
                                if($record->good_food_delivery_time!=NULL && $record->sprint->good_food_departure_time!=NULL)
                                {
                                    $date1=date_create($record->sprint->good_food_departure_time->departure_time);
                                    $date2=date_create($record->good_food_delivery_time->delivery_time);
                                    $diff=date_diff($date1,$date2);
                                    $drive=$diff->format("%H:%i:%S");
                                }
                                else
                                {
                                    $drive="00:00:00";
                                }
                                echo $drive ?></td>
                            <td><?php if(!empty($record->GoodFoodSprintConfirmationsImage))
                                {
                                    echo "<img id='".$record->order_id."' src='".$record->GoodFoodSprintConfirmationsImage->attachment_path."' alt='".$record->order_id."' width='150' onclick='modalimage(this.id)' >";
                                } ?></td>
                            <td><?php if(!empty($record->GoodFoodSprintConfirmationsSignature))
                                {
                                    echo "<img id='".$record->order_id."-sig' src='".$record->GoodFoodSprintConfirmationsSignature->attachment_path."' alt='".$record->order_id."' width='150' onclick='modalimage(this.id)' >";
                                } ?></td>
                            <td><?php echo round($record->distance/1000,3).'km'; ?></td>
                            <td><?php echo $statuscode[$status]; ?></td>

                            <td><?php
                                if ($record->sprint->SprintNotes){
                                    foreach ($record->sprint->SprintNotes as $note) {
                                        echo $note->note.". ";
                                    }
                                }
                                ?></td>
                        </tr>
                    <?php }} ?>
                                    </tbody>
            </table>
</div>

            <?php 
            $start=1;$end=$total_page;
                             if($total_page > 1) { ?>
          <ul class="pagination pagination-sm justify-content-center">
            <!-- Link of the first page -->
            <li class='page-item <?php ($page <= 1 ? print 'disabled' : '')?>'>
              <button data-targeted-div="loblaws-orders-main-wrap" class='paginationbt btn btn-primary'   data-id='1'><<</button>
            </li>
            <!-- Link of the previous page -->
            <li class='page-item <?php ($page <= 1 ? print 'disabled' : '')?>'>
              <button data-targeted-div="loblaws-orders-main-wrap" class='paginationbt btn btn-primary'   data-id='<?php ($page>1 ? print($page-1) : print 1)?>'><</button>
            </li>
            <!-- Links of the pages with page number -->
            <?php $active=""; for($i=$start; $i<=$end; $i++) { ?>
                
            <li class='page-item <?php
                if($i == $page){
                    $active='green';
                }else{$active="";}?>'>
              <button data-targeted-div="loblaws-orders-main-wrap" class='<?php echo 'paginationbt  btn btn-primary'.$active ?>'    data-id='<?php echo $i;?>'><?php echo $i;?></button>
            </li>
            <?php } ?>
            <!-- Link of the next page -->
            <li class='page-item <?php ($page >= $total_page ? print 'disabled' : '')?>'>
              <button data-targeted-div="loblaws-orders-main-wrap" class='paginationbt btn btn-primary'   data-id='<?php ($page < $total_page ? print($page+1) : print $total_page)?>'>></button>
            </li>
            <!-- Link of the last page -->
            <li class='page-item <?php ($page >= $total_page ? print 'disabled' : '')?>'>
              <button data-targeted-div="loblaws-orders-main-wrap" class='paginationbt btn btn-primary'  data-id='<?php echo $total_page;?>'>>>                      
              </button>
            </li>
          </ul>
       <?php } ?>
       
    </div>
 </div>
</div>
