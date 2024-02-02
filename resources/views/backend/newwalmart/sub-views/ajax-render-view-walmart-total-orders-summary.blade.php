<?php
namespace App\Http\Controllers\Backend;
use DB;
?>
<!--table-responsive-open-->
<div class="table-responsive">
<table class="table table-striped table-bordered" border="1">
                            <thead>
                            <tr>
                                <th style="text-align: center">Store Name</th>
                                <th style="text-align: center">Order Id</th>
                                <th style="text-align: center">Walamrt Order Number</th>
                                <th style="text-align: center">Joey Name</th>
                                <th style="text-align: center">Status</th>
                                <th style="text-align: center">Schedule Pickup</th>
                                <th style="text-align: center">Compliant Pickup</th>
                                <th style="text-align: center">Pickup ETA</th>
                                <th style="text-align: center">Joey Arrival Time</th>
                                <th style="text-align: center">Joey Departure Time</th>
                                <th style="text-align: center">Compliant Dropoff</th>
                                <th style="text-align: center">Dropoff ETA</th>
                                <th style="text-align: center">Joey Arrival At Dropoff</th>
                                <th style="text-align: center">Delivery Time</th>
                                <th style="text-align: center">Location</th>
                                <th style="text-align: center">Distance</th>
                                <!-- <th>Notes</th> -->
                                <th style="text-align: center">Marked Codes</th>
                            </tr>
                            </thead><tbody>
                            <?php $allrec=0;
                    if(!empty($fullrecord)){
                    foreach($fullrecord as $record) { 

                        if(strtotime($record->departure_time)>strtotime($record->delivery_time)){
                            $delivery_time = "20".date('y-m-d H:i:s',strtotime($record->departure_time)+120);
                        }
                        else {
                            $delivery_time = $record->delivery_time;
                        }
                        $allrec++;  ?>
                        <tr>
                            <td><?php echo $record->store_name; ?></td>
                            <td <?php if(strtotime($record->compliant_dropoff)+300 < strtotime($delivery_time) ) echo "style='background:red;color: #FFFF;'"; ?> >CR-<?php echo $record->sprint_id; ?></td>
                            <td><?php echo $record->walmart_order_num?></td>
                            <td><?php echo $record->joey_name?></td>
                            <td><?php echo $status[$record->status_id]; ?></td>
                            <td><?php echo $record->schedule_pickup; ?></td>
                            <td><?php echo $record->compliant_pickup; ?></td>
                            <td><?php echo $record->pickup_eta; ?></td>
                            <td><?php echo $record->arrival_time; ?></td>
                            <td><?php echo $record->departure_time; ?></td>
                            <td><?php echo $record->compliant_dropoff; ?></td>
                            <td><?php echo $record->dropoff_eta?></td>
                            <td><?php echo $record->atdrop_time?></td>
                            <td><?php  echo $delivery_time; ?></td>
                            <td><?php echo $record->address; ?></td>
                            <td><?php echo $record->distance."km"; ?></td>
                            <!-- <td>
                            <?php 
                            // $notes = \Laravel\Database::query("select note from notes where object_id=".$record->sprint_id."");
                            // foreach ($notes as $note) {
                            //     echo $note->note.". ";
                            // } 
                            ?>
                            </td>  -->
                            <td><?php 
                            $codes = DB::select("select code from order_code join order_assigned_code on (order_assigned_code.code_id=order_code.id) where sprint_id=".$record->sprint_id."");
                            $i=0;
                            foreach ($codes as $code) {
                                if ($i == 0){
                                    echo $code->code;
                                }
                                else{
                                    echo ",".$code->code;
                                }
                                $i = $i + 1;
                            }
                            ?></td>
                        </tr>
                    <?php }} ?>    
                        </tbody>
                            </table>
							</div>
<!--table-responsive-open-->
                            

          </div>
        </div>
    </div>
