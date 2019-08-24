
<!DOCTYPE html>
<html>
    <head> 
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!--<meta http-equiv="X-UA-Compatible" content="IE=edge">-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/gif" href="<?= base_url() ?>images/icon_ecolab.jpg" />
    <style>
    #customers {
      font-family: "Trebuchet MS", Arial, Helvetica, sans-serif, dejavusanscondensed;
      border-collapse: collapse;
      width: 100%;
    }

    #customers td, #customers th {
      border: 1px solid #ddd;
      padding: 8px;
    }

    #customers tr:nth-child(even){background-color: #f2f2f2;}

    #customers tr:hover {background-color: #ddd;}

    #customers th {
      padding-top: 12px;
      padding-bottom: 12px;
      text-align: left;
      background-color: #67a8fe;
      color: white;
    }
    
/*    *{
        font-family:"DeJaVu Sans Mono",monospace;
    }*/
    </style>
    </head> 
<body>

                <table id="customers" width="1000">
                    <thead bgcolor="#67a8fe">
                        <tr>
                            <th align="center"><font face="arial" color="" size="2">No.</font></th>
                            <th align="center"><font face="arial" color="" size="2">Image</font></th>
                            <th align="center"><font face="arial" color="" size="2">Name</font></th>
                            <th align="center"><font face="arial" color="" size="2">Pack Size</font></th>
                            <th align="center"><font face="arial" color="" size="2">Application</font></th>
                            <th align="center"><font face="arial" color="" size="2">Type</font></th>
                            <!--<th align="center"><font face="arial" color="" size="2">Sub Type</font></th>-->
                            <th align="center"><font face="arial" color="" size="2">Dilution</font></th>
<!--                            <th align="center"><font face="arial" color="" size="2">Description</font></th>
                            <th align="center"><font face="arial" color="" size="2">Bvg.</font></th>
                            <th align="center"><font face="arial" color="" size="2">Brw.</font></th>
                            <th align="center"><font face="arial" color="" size="2">Dry.</font></th>
                            <th align="center"><font face="arial" color="" size="2">Fod</font></th>
                            <th align="center"><font face="arial" color="" size="2">Sfd.</font></th>
                            <th align="center"><font face="arial" color="" size="2">Pty.</font></th>
                            <th align="center"><font face="arial" color="" size="2">Phr.</font></th>-->
                            <th align="center"><font face="arial" color="" size="2">GHS</font></th>
                            <th align="center"><font face="arial" color="" size="2">PPE Req.</font></th>
                            <th align="center"><font face="arial" color="" size="2">First Aid</font></th>
                        </tr>
                    </thead>
                    <tbody>
                        <? 
                            $i =1;
                            foreach($product_result as $row):
                        ?>
                        <tr>
                            <td valign="top" align="center" width="40"><font face="arial" style="font-size:70%;"><?=$i?></font></td>
                            <td valign="top" align="center"><img height="60" width="60" class="img-responsive" src="<?php echo base_url().'assets/images/'.$row->image_name; ?>"/></td>
                            <td valign="top"><font face="arial" style="font-size:70%;"><? echo $row->product_name;?><br/><br/><? echo $row->prod_name_lang;?></font></td>
                            <td valign="top" width="80"><font face="arial" style="font-size:70%;"><? echo $row->kg_package;?></font></td>
                            <td valign="top"><font face="arial" style="font-size:70%;"><? echo $row->category_name;?></font></td>
                            <td valign="top" width="150"><font face="arial" style="font-size:70%;"><? echo $row->sub_category_name;?></font></td>
                            <!--<td valign="top" width="150"><font face="arial" style="font-size:70%;"><? echo $row->sub_type;?></font></td>-->
<!--                        <td valign="top"><font face="arial" style="font-size:70%;"><? echo $row->prod_desc;?></font></td>
                            <td valign="top" align="center"><font face="arial" style="font-size:70%;"><? if($row->beverage==1) echo 'x';?></font></td>
                            <td valign="top" align="center"><font face="arial" style="font-size:70%;"><? if($row->beverage==1) echo 'x';?></font></td>
                            <td valign="top" align="center"><font face="arial" style="font-size:70%;"><? if($row->brewery==1) echo 'x';?></font></td>
                            <td valign="top" align="center"><font face="arial" style="font-size:70%;"><? if($row->dairy==1) echo 'x';?></font></td>
                            <td valign="top" align="center"><font face="arial" style="font-size:70%;"><? if($row->food==1) echo 'x';?></font></td>
                            <td valign="top" align="center"><font face="arial" style="font-size:70%;"><? if($row->seafood==1) echo 'x';?></font></td>
                            <td valign="top" align="center"><font face="arial" style="font-size:70%;"><? if($row->pharma==1) echo 'x';?></font></td>-->
                            
                            <td valign="top">
                                <? //if($dilution_mode == 'show'):?>
                                <font face="arial" style="font-size:70%;"><? echo $dilution[$row->product_id];?></font>
                                <? //endif;?>
                            </td>
                            
                            <td valign="top" align="center" width="70">
                                <?
                                    if($row->ghs_icon != null):
                                        $k=1;
                                    $ghs_icon_ex = explode(',', $row->ghs_icon);
                                    foreach($ghs_icon_ex as $row_icon):
                                ?>
                                <img height="20" src="<?= base_url() ?>assets/uploads/images/<?=$row_icon?>"><br/>
                                <? if($k%2 == 0):?>
                                <!--<br/>-->
                                <? endif; $k++; endforeach; endif;?>
                                <? // echo $row->safety_icon;?>
                            </td>
                            <td valign="top" align="center" width="70">
                                <?
                                    if($row->safety_icon != null):
                                        $j=1;
                                    $icon_ex = explode(',', $row->safety_icon);
                                    foreach($icon_ex as $icon):
                                ?>
                                
                                <img height="20" src="<?= base_url() ?>assets/uploads/images/<?=$icon?>"><br/>
                                <? if($j%2 == 0):?>
                                <!--<br/>-->
                                <?  endif;
                                    $j++; 
                                    endforeach; 
                                    endif;?>
                                <? // echo $row->safety_icon;?>
                            </td>
                            <td valign="top" width="150"><font style="font-size:70%;">Refer to Safety Data Sheet Section 4</font></td>
                        </tr>
                        <? 
                            $i++;
                            endforeach; 
                        ?>
                    </tbody>
                </table>
            <br/>
            <table>
                <? for($i=0;$i<count($contact_name);$i++):?>
                <tr>
                    <td width="10"></td>
                    <td><font face="arial"  style="font-size:70%;"><b>Contact Name: </b></font></td>
                    <td width="150"><font face="arial" style="font-size:70%;"><?=$contact_name[$i]?></font></td>
                    <td><font face="arial"  style="font-size:70%;"><b>Contact Number: </b></font></td>
                    <td><font face="arial"  style="font-size:70%;"><?=$contact_number[$i]?></font></td>
                </tr>
                <? endfor;?>
<!--                <tr>
                    <td width="10"></td>
                    <td><font face="arial"  style="font-size:70%;"><b>Contact Name: </b></font></td>
                    <td width="100"><font face="arial" style="font-size:70%;"><?=$sales_name_1?></font></td>
                    <td><font face="arial"  style="font-size:70%;"><b>Contact Number: </b></font></td>
                    <td><font face="arial"  style="font-size:70%;"><?=$sales_tel_1?></font></td>
                </tr>
                <tr>
                    <td width="10"></td>
                    <td><font face="arial"  style="font-size:70%;"><b>Contact Name: </b></font></td>
                    <td width="100"><font face="arial"  style="font-size:70%;"><?=$sales_name_2?></font></td>
                    <td><font face="arial"  style="font-size:70%;"><b>Contact Number: </b></font></td>
                    <td><font face="arial"  style="font-size:70%;"><?=$sales_tel_2?></font></td>
                </tr>-->
            </table>
            <? //print_r($contact_name);?>
</body>
</html>
