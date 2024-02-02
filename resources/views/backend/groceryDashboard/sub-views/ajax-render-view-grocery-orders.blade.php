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
"60"  => "Task failure",
     '153' => 'Miss sorted to be reattempt',
     '154' => 'Joey unable to complete the route','155' => 'To be re-attempted tommorow');
?>
 <table class="table table-striped table-bordered" border="1">
                <thead>
                    <tr><th>Total Orders</th><th>Total Late Orders</th></tr>
                </thead>
                <tody>
                    <tr><td><?php echo $totalorder;?></td>
                    <td><?php

                    // if($otd==0){
                    //     $otd = $totalorder;
                    // }
                     echo $totalorder-$otd;?>
                     </td>
                     </tr>
                </tody>
            </table>
<div class="table-responsive fixTableHead">
<table id="export-csv" class="table table-striped table-bordered" border="1">
                <thead>
                    <tr>
                        <th style="text-align: center">Route Number</th>
                        <th style="text-align: center">Joey</th>
                        <th style="text-align: center">Merchant Order #</th>

                        <th style="text-align: center">Customer Address</th>
                        <th style="text-align: center">Planned Arrival</th>
                        <th style="text-align: center">Arrival time @ PU</th>
                        <th style="text-align: center">Actual Departure </th>
                        <th style="text-align: center">Time Open</th>
                        <th style="text-align: center">Time Close</th>
                        <th style="text-align: center">Estimated Delivery ETA</th>
                        <th style="text-align: center">Actual Arrival @ CX</th>
                        <th style="text-align: center">Image</th>
                        <th style="text-align: center">Signature</th>
                        <th style="text-align: center">Status</th>
                                     
                        <th style="text-align: center">Notes</th>
                        
                        
                    </tr>
                </thead>
                <tbody>
                        <!-- <tr>
                            <td>2016353-1</td>
                            <td>Auston Childs</td>
                            <td>531002417336016</td>
                             <td>Bayview Village Loblaws</td>
                            <td>215 Wynford Drive</td>
                            <td>2020-11-27 10:45:00</td> 
                            <td>2020-11-27 10:38:06</td>
                            <td>2020-12-01 11:05:06</td>
                            
                            <td>11:00</td>
                             
                            <td>12:00</td>
                            <td>2020-12-01 11:43:00</td>
                            <td>2020-12-01 11:25:43</td>
							<td>00:27:00</td>
                            <td>00:20:37</td>
                            <td></td>
                             <td></td>
                            <td>Delivery success</td>
                            
                            

                            <td></td>   
                        </tr> -->
                        <?php $allrec=0;
                    if(!empty($fullrecord)){

                    foreach($fullrecord as $record) {


                        $allrec++;
                        ?>
                        <tr>
                            <td <?php
                                if ($record->grocery_delivery_time){
                                    if($record->groceryTaskMerchants){
                                        if( $record->groceryTaskMerchants->end_time!=NULL && date('Y-m-d',strtotime($record->dropoff_eta)).' '.date('H:i:s',strtotime($record->groceryTaskMerchants->end_time)+300) < date('Y-m-d H:i:s',strtotime($record->grocery_delivery_time->delivery_time)))
                                        { echo "style='background:red;color: white'";}
                                    }
                                }

                                ?>>
                            <?php echo 'CR-'.$record->order_id; ?>
                            </td>
                            <td><?php echo $record->sprint->joey?$record->sprint->joey->joey_name.' ('.$record->sprint->joey->id .')':''; ?></td>
                            <td><?php echo $record->groceryTaskMerchants?$record->groceryTaskMerchants->merchant_order_num:''; ?></td>
                            <td><?php echo $record->task_Location->address; ?></td>
                            <td><?php echo $record->sprint->groceryTasks?$record->sprint->groceryTasks->arrival_eta:''; ?></td>
                            <td><?php echo $record->sprint->arrival_time?$record->sprint->arrival_time->arrival_time:''; ?></td>
                            <td><?php echo $record->sprint->departure_time?$record->sprint->departure_time->departure_time:''; ?></td>
                            <td><?php echo $record->groceryTaskMerchants? date('H:i',strtotime($record->groceryTaskMerchants->end_time)-7200):''?></td>
                            <td><?php echo $record->groceryTaskMerchants? date('H:i',strtotime($record->groceryTaskMerchants->end_time)):''; ?></td>
                            <td><?php echo $record->dropoff_eta; ?></td>
                            <td><?php echo $record->grocery_delivery_time?$record->grocery_delivery_time->delivery_time:''; ?></td>
                            <td><?php if(!empty($record->sprintConfirmationsImage->attachment_path))
                                {
                                    echo "<img id='".$record->order_id."' src='".$record->sprintConfirmationsImage->attachment_path."' alt='".$record->order_id."' width='150' onclick='modalimage(this.id)' >";
                                } ?></td>
                            <td><?php if(!empty($record->sprintConfirmationsSignature->attachment_path))
                                {
                                    echo "<img id='".$record->order_id."-sig' src='".$record->sprintConfirmationsSignature->attachment_path."' alt='".$record->order_id."' width='150' onclick='modalimage(this.id)' >";
                                } ?></td>
                            <td><?php if(isset($statuscode[$record->status_id]))
							{
								echo $statuscode[$record->status_id];
							}
							else
							{
								echo $status;
							}
							 ?></td>

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


       
    </div>
 </div>
</div>
