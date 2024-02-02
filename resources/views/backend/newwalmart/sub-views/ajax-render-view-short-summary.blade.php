<div class="table-responsive">
<table class="table table-striped table-bordered" border="1">
    <thead style="text-align: center;font-weight: bold">
    <tr>
        <td>Range</td>
        <td>Start Date</td>
        <td>End Date</td>
        <td>Successful Deliveries</td>
        <td>OTD</td>
        <td>JOTD</td>
        <td>OTA</td>
    </tr>
    </thead>
    <tbody>
    <tr>
            <?php if(!empty($wmorders))
            {
            ?>
                        <td><?php echo date("l",strtotime($date)) ?></td>
                        <td><?php echo $date ?></td>
                        <td><?php echo $date ?></td>
                         <?php if(!empty($wmorders)) 
                        { 

                        ?>
                        <td><?php echo $totalcount ?></td>
                        <td><?php echo  round(array_sum($performance) / count($performance),2)."%"; ?></td>
                        <td><?php echo round((($totalcount-$walmartcounts[0]->wmlates)/$totalcount)*100,0)."%"; ?></td>
                        <td><?php 
                						if($overcounts[0]->ota > 0){
                							echo round(($overcounts[0]->ota/$overcounts[0]->arrivals)*100,0)."%";
                                        }
                                        else
                                        {
                                            echo "0%";
                                        }
						                ?>    
                        </td>  

                        <?php

                          }

                        ?>
                    </tr>
                    <tr>
                        <td>WTD</td>
                        <td><?php echo date('Y-m-d', strtotime('-1 week', strtotime($date))) ?></td>
                        <td><?php echo $date ?></td>
                        <td><?php echo $overcounts[0]->wdel ?></td>
                        <td>
                          <?php 
                          if ($overcounts[0]->wdel>0){
                             echo round(($overcounts[0]->wlates/$overcounts[0]->wdel)*100,0)."%"; 
                             }
                             else
                                        {
                                            echo "0%";
                                        }
                             ?>
                        </td>
                        <td><?php 
                          if($overcounts[0]->wdel > 0){  
                            echo round((($overcounts[0]->wdel-$walmartcounts[0]->wmwlates)/$overcounts[0]->wdel)*100,0)."%"; 
                            }
                            else
                                        {
                                            echo "0%";
                                        }
                            ?>  
                        </td>
                        <td><?php
              						if($overcounts[0]->wa > 0){
                                        $wota = round(($overcounts[0]->wota/$overcounts[0]->wa)*100,0);
                                        // $wota = 101;
                                        if ($wota > 100) {
                                            echo "100%";
                                        } else {
                                            echo $wota . "%";
                                        }
              							// echo round(($overcounts[0]->wota/$overcounts[0]->wdel)*100,0)."%"; 
              						}
						            ?>    
                        </td>
                    </tr>
                    <tr>
                        <td>MTD</td>
                        <td><?php echo date('Y-m-d', strtotime('-1 month', strtotime($date))) ?></td>
                        <td><?php echo $date ?></td>
                        <td><?php echo $overcounts[0]->mdel ?></td>
                        <td><?php 
              						if($overcounts[0]->mdel>0){
              							echo round(($overcounts[0]->mlates/$overcounts[0]->mdel)*100,0)."%";
                                      }
                                      else
                                        {
                                            echo "0%";
                                        }
						            ?>  
                        </td>
                        <td><?php
                						if($overcounts[0]->mdel>0){
                							echo round((($overcounts[0]->mdel-$walmartcounts[0]->wmmlates)/$overcounts[0]->mdel)*100,0)."%";
                                        }
                                        else
                                        {
                                            echo "0%";
                                        }
						            ?>  
                        </td>
                        <td><?php
              							if($overcounts[0]->ma>0){
              								echo round(($overcounts[0]->mota/$overcounts[0]->ma)*100,0)."%";
                                          }
                                          else
                                        {
                                            echo "0%";
                                        }
						            ?> 
                        </td>
                    </tr>
                    <tr>
                        <td>YTD</td>
                        <td><?php echo date('Y-m-d', strtotime('-1 year', strtotime($date))) ?></td>
                        <td><?php echo $date ?></td>
                        <td><?php echo $overcounts[0]->ydel ?></td>
                        <td>
                          <?php 
                            if($overcounts[0]->ydel>0)
                            {
                            echo round(($overcounts[0]->ylates/$overcounts[0]->ydel)*100,0)."%";
                            } 
                            else
                                        {
                                            echo "0%";
                                        }
                        ?>  
                        </td>
                        <td><?php 
                          if($overcounts[0]->ydel>0)
                            {
                            echo round((($overcounts[0]->ydel-$walmartcounts[0]->wmylates)/$overcounts[0]->ydel)*100,0)."%"; 
                            }
                            else
                                        {
                                            echo "0%";
                                        }

                        ?>
                          
                        </td>
                        <td><?php 
                          if($overcounts[0]->ya>0)
                            {
                             echo round(($overcounts[0]->yota/$overcounts[0]->ya)*100,0)."%"; 
                            }
                            else
                                        {
                                            echo "0%";
                                        }
                        ?>
                          
                        </td>
                        <?php 
                        }?>
                    </tr>
    <!-- <tr>
        <td>Wednesday</td>
        <td>2020-12-02</td>
        <td>2020-12-02</td>
        <td>264</td>
        <td>92.95%</td>
        <td>99%</td>
        <td>63%
        </td>

    </tr>
    <tr>
        <td>WTD</td>
        <td>2020-11-25</td>
        <td>2020-12-02</td>
        <td>2046</td>
        <td>
            86%                        </td>
        <td>94%
        </td>
        <td>100%
        </td>
    </tr>
    <tr>
        <td>MTD</td>
        <td>2020-11-02</td>
        <td>2020-12-02</td>
        <td>9584</td>
        <td>86%
        </td>
        <td>95%
        </td>
        <td>107%
        </td>
    </tr>
    <tr>
        <td>YTD</td>
        <td>2019-12-02</td>
        <td>2020-12-02</td>
        <td>84139</td>
        <td>
            94%
        </td>
        <td>99%
        </td>
        <td>70%
        </td>
    </tr> -->

    </tbody>
</table>
</div>