<?php 
namespace App\Http\Controllers\Backend;

use DB;
?>
<!--table-responsive-open-->
<div class="table-responsive">
<table class="table table-striped table-bordered" style="width:99%;" border="1">
   <?php if(empty($wmorders)) { ?>
        <thead>
        <tr>
            <th>Stores</th>
        </tr>
        </thead>

        <tbody>
        <tr>
            <td> No Data Found</td>
        </tr>
       
        </tbody>
    <?php }

    else{ ?>
<thead>
                    <tr>
                        <th>Stores</th>
                        <?php 
                    
                        foreach($wmorders as $wmst) {
                        echo "<th>".$wmst->store_name."</th>";
                            }  ?>
                    </tr>
                </thead>
                <tbody>
                <?php if(!empty($wmorders)) { ?>
                        <tr>
                            <td style="background: #b7d409;color: #fff;font-weight: bold;">Total</td>
                        <?php 
                        foreach ($wmorders as $wmorder) {
                            echo "<td>".$wmorder->orders."</td>";
                        }
                        ?>
                        </tr>
                        <tr>
                            <td style="background: #dd6927;color: #fff;font-weight: bold;">Actual Late</td>
                            <?php 
                            
                        foreach ($wmorders as $lateorder) {
                            
                            echo "<td>".$lateorder->lates."</td>";
                        }
                        ?>
                        </tr>
                        <tr>
                            <td style="background: #d14625;color: #fff;font-weight: bold;">Wait Time</td>
                            <?php 
                        foreach ($waittimes as $time) {
                            echo "<td>".date("H:i:s",$time->waits)."</td>";
                        } 
                            ?>
                        </tr>
                    
                        <tr>
                            <td style="background: #42e724;color: #fff;font-weight: bold;">Customer Experience</td>
                        <?php 
                            foreach ($wmorders as $perf) {
                                $performance = 100-(($perf->lates*100)/$perf->orders); 
                                $cust[] =  100-(($perf->lates*100)/$perf->orders);
                            echo "<td>".round($performance,2)."%</td>";
                            } 
                            ?>
                        </tr>

                        <tr>
                            <td style="background: #9d5830;font-weight: bold;color: #fff;">JOEYCO Performance</td>
                        <?php 
                            foreach ($wmorders as $store_name) {

                            $codequery = "select count(distinct(case when from_unixtime(due_time+5700)<sprint__tasks_history.created_at then order_assigned_code.sprint_id else null end)) as counts from order_code 
                            join order_assigned_code on(code_id=order_code.id) 
                            join sprint__sprints on(sprint__sprints.id=order_assigned_code.sprint_id) 
                            join sprint__tasks on(sprint__tasks.sprint_id=sprint__sprints.id and type='pickup') 
                                    join sprint__tasks_history on(sprint__tasks.sprint_id=sprint__tasks_history.sprint_id and sprint__tasks_history.status_id=68)
                            where code_num=1 and CONVERT_TZ(from_unixtime(sprint__tasks.due_time),'UTC','America/Toronto') like '".$date."%' and store_num='".$store_name->store_num."' and sprint__sprints.status_id=17 group by store_num";

                            $codecounts = DB::select($codequery);
                                    
                                if(!empty($codecounts)) $lates = $codecounts[0]->counts;
                                else $lates = 0;
                                    $performance = 100-(($lates*100)/$store_name->orders);  
                                    echo "<td>".round($performance,2)."%</td>";
                            } 
                            ?>
                        </tr>

                        <?php foreach($jocodes as $code){
                        ?>
                        <tr>
                            <td><?php echo $code->code ?></td>
                            <?php 
                        
                            foreach($wmorders as $store_name) {
                                                                    
                                    $codequery = "select count(distinct(case when from_unixtime(due_time+5700)<sprint__tasks_history.created_at then order_assigned_code.sprint_id else null end)) as counts from order_code join order_assigned_code on(code_id=order_code.id) 
                                    join sprint__sprints on(sprint__sprints.id=order_assigned_code.sprint_id) 
                                    join sprint__tasks on(sprint__tasks.sprint_id=sprint__sprints.id and type='pickup') 
                                    join sprint__tasks_history on(sprint__tasks.sprint_id=sprint__tasks_history.sprint_id and sprint__tasks_history.status_id=68)
                                    where code_num=1 and CONVERT_TZ
                                    (from_unixtime(sprint__tasks.due_time),'UTC','America/Toronto') like '".$date."%' and store_num='".$store_name->store_num."' and code_id=".$code->id." and sprint__sprints.status_id=17 group by store_num";

                                    $codecounts = DB::select($codequery);
                                    
                                    if(!empty($codecounts)) echo "<td>".$codecounts[0]->counts."</td>";                        
                                    else echo "<td>0</td>"; 
                                    
                                }
                                
                            ?>
                        </tr>
                        <?php
                    } ?>
                    <tr style="background: #3e3e3e;color: #fff;">
                        <td>JoeyCo Delay</td>
                    <?php 
                            $jcolate = 0;
                            foreach($wmorders as $store_name) {
                                
                                    $codequery = "select count(distinct(case when from_unixtime(due_time+5700)<sprint__tasks_history.created_at then order_assigned_code.sprint_id else null end)) as counts from order_code 
                                    join order_assigned_code on(code_id=order_code.id) 
                                    join sprint__sprints on(sprint__sprints.id=order_assigned_code.sprint_id)
                                    join sprint__tasks on(sprint__tasks.sprint_id=sprint__sprints.id and type='pickup') 
                                    join sprint__tasks_history on(sprint__tasks.sprint_id=sprint__tasks_history.sprint_id and sprint__tasks_history.status_id=68)
                                    where code_num=1 and CONVERT_TZ(from_unixtime(sprint__tasks.due_time),'UTC','America/Toronto') like '".$date."%' and store_num='".$store_name->store_num."' and sprint__sprints.status_id=17 group by store_num";
                                    $codecounts = DB::select($codequery);
                                    
                                    if(!empty($codecounts)){
                                        $jcolate += $codecounts[0]->counts;
                                        echo "<td>".$codecounts[0]->counts."</td>";
                                    }                        
                                    else echo "<td>0</td>"; 
                                    
                                }
                                
                            ?>
                            </tr>
                    <?php foreach($wmcodes as $code){
                        ?>
                        <tr>
                            <td><?php echo $code->code ?></td>
                            <?php 
                        
                            foreach($wmorders as $store_name) {
                                                                   
                                   $codequery = "select count(distinct(case when from_unixtime(due_time+5700)<sprint__tasks_history.created_at then order_assigned_code.sprint_id else null end)) as counts from order_code join order_assigned_code on(code_id=order_code.id) 
                                   join sprint__sprints on(sprint__sprints.id=order_assigned_code.sprint_id) 
                                   join sprint__tasks on(sprint__tasks.sprint_id=sprint__sprints.id and type='pickup') 
                                   join sprint__tasks_history on(sprint__tasks.sprint_id=sprint__tasks_history.sprint_id and sprint__tasks_history.status_id=68)
                                   where code_num=0 and CONVERT_TZ
                                   (from_unixtime(sprint__tasks.due_time),'UTC','America/Toronto') like '".$date."%' and store_num='".$store_name->store_num."' and code_id=".$code->id." and sprint__sprints.status_id=17 group by store_num";

                                   $codecounts = DB::select($codequery);
                                    
                                    if(!empty($codecounts)) echo "<td>".$codecounts[0]->counts."</td>";                        
                                    else echo "<td>0</td>"; 
                                    
                                }
                                
                            ?>
                        </tr>
                        <?php
                    } ?>
                    <tr style="background: #3e3e3e;color: #fff;">
                        <td>Walmart Delay</td>

                    <?php 
                            $wmlate = 0;
                            foreach($wmorders as $store_name) {                       
                                  
                                  $codequery = "select count(distinct(case when from_unixtime(due_time+5700)<sprint__tasks_history.created_at then order_assigned_code.sprint_id else null end)) as counts from order_code 
                                  join order_assigned_code on(code_id=order_code.id) 
                                  join sprint__sprints on(sprint__sprints.id=order_assigned_code.sprint_id)
                                  join sprint__tasks on(sprint__tasks.sprint_id=sprint__sprints.id and type='pickup') 
                                  join sprint__tasks_history on(sprint__tasks.sprint_id=sprint__tasks_history.sprint_id and sprint__tasks_history.status_id=68)
                                  where code_num=0 and CONVERT_TZ(from_unixtime(sprint__tasks.due_time),'UTC','America/Toronto') like '".$date."%' and store_num='".$store_name->store_num."' and sprint__sprints.status_id=17 group by store_num";
                                  
                                  $codecounts = DB::select($codequery);
                                    
                                    if(!empty($codecounts)){
                                        $wmlate += $codecounts[0]->counts;
                                        echo "<td>".$codecounts[0]->counts."</td>";
                                    }                         
                                    else echo "<td>0</td>";  
                                }
                                
                            ?>
                        </tr>
                        <tr style="background: #b0cb0e;font-weight: bold;">
                             <td colspan="3" style="color: black">JoeyCo Delay ( <?php echo $jcolate; ?> )</td>
                            <td colspan="3" style="color: black">Walmart Delay ( <?php echo $wmlate; ?> )</td>
                            <td colspan="3" style="color: black">Total Delay ( <?php echo $jcolate+$wmlate; ?> ) </td>
                        </tr>
                        <?php } 
                        else {
                        echo "<tr><td colspan='17'>No Data Found</td></tr>";
                    } ?>
                </tbody>
				<?php }
 ?>

</table>
</div>
<!--table-responsive-open-->