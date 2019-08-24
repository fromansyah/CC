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
    <!--<font face="DeJaVu Sans Mono" size="4">กรณีสัมผัสทางผิวหนัง</font>-->
    <table width="100%" border="0">
        <tr>
            <td height="40" width="30"></td>
            <td width="70"><img height="30" src="<?= base_url() ?>images/index.png"></td>
            <td valign="middle" align="center"><b><font face="arial" size="3">(Chemical Application and Safety Chart)</font></b></td>
            <td  width="130" align="right">
                <?//$image_name?>
                <? if($image == 1):?>
                <img height="75" src="<?=$image_name?>"></td>
                <? endif;?>
            <td width="30"></td>
        </tr>
        <tr>
            <td height="10" colspan="5"></td>
        </tr>
        <tr>
            <td width="30"></td>
            <td colspan="3">
                <table id="customers" width="1000">
                    <thead bgcolor="#67a8fe">
                        <tr>
                            <th><font face="arial" color="" size="2">No.</font></th>
                            <th><font face="arial" color="" size="2">Image</font></th>
                            <th><font face="arial" color="" size="2">Name</font></th>
                            <th><font face="arial" color="" size="2">Type</font></th>
                            <th><font face="arial" color="" size="2">Application</font></th>
                            <th><font face="arial" color="" size="2">Properties</font></th>
                            <th><font face="arial" color="" size="2">Dilution</font></th>
                            <th><font face="arial" color="" size="2">How To Use</font></th>
                            <th><font face="arial" color="" size="2">Safety</font></th>
                            <th><font face="arial" color="" size="2">First Aid</font></th>
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
                            <td valign="top"><font face="arial" style="font-size:70%;"><? echo $row->product_name;?></font></td>
                            <td valign="top"><font face="arial" style="font-size:70%;"><? echo $row->product_type;?></font></td>
                            <td valign="top"><font face="arial" style="font-size:70%;"><? echo $row->application;?></font></td>
                            <td valign="top"><font face="arial" style="font-size:70%;"><? echo $row->property;?></font></td>
                            <td valign="top">
                                <? if($dilution_mode == 'show'):?>
                                <font face="arial" style="font-size:70%;"><? echo $row->dilution;?></font>
                                <? endif;?>
                            </td>
                            <td valign="top"><font face="arial" style="font-size:70%;"><? echo $row->how_to_use;?></font></td>
                            <td valign="top" align="center">
                                <?
                                    $icon_ex = explode(',', $row->safety_icon);
                                    foreach($icon_ex as $icon):
                                ?>
                                <img height="20" src="<?= base_url() ?>assets/uploads/images/<?=$icon?>">
                                <br/>
                                <? endforeach;?>
                                <? // echo $row->safety_icon;?>
                            </td>
                            <td valign="top"><font style="font-size:70%;"><? echo $row->first_aid;?></font></td>
                            <!--<td><font size="2">&nbsp;กรณีสัมผัสทางผิวหนัง : 한글 十课 第十一课 第十 平仮名, ひらがな, ét-xì; xờ nặng, بشدة إلى الأصالة हिंदी फोंट डाउनलोड करे, 献给母亲的爱</font></td>-->
                        </tr>
                        <? 
                            $i++;
                            endforeach; 
                        ?>
                    </tbody>
                </table>
            </td>
            <td width="30"></td>
        </tr>
        <tr>
            <td width="30"></td>
            <td colspan="3">
                <table>
                    <tr>
                        <? foreach($safety_list as $safety):?>
                        <? if($safety['safety_id'] == 3):?>
                        <td valign="middle" width="220">
                        <? else: ?>
                        <td valign="middle" width="120">
                        <? endif; ?>
                            <img height="20" src="<?= base_url() ?>assets/uploads/images/<?=$safety['image_name']?>">
                            &nbsp;<font face="arial"  style="font-size:70%;"><?=$safety['desc']?></font>
                        </td>
                        <? endforeach;?>
                        <!--<td><font face="arial"  size="1">Contact : </font></td>-->
                        <td align="right">
                            <table>
                                <tr>
                                    <td width="100"></td>
                                    <td><font face="arial"  style="font-size:70%;">Contact : </font></td>
                                    <td><font face="arial"  style="font-size:70%;"><?=$sales_name_1?> (<?=$sales_tel_1?>)</font></td>
                                </tr>
                                <tr>
                                    <td width="100"></td>
                                    <td><font face="arial"  style="font-size:70%;"></font></td>
                                    <td><font face="arial"  style="font-size:70%;"><?=$sales_name_2?> (<?=$sales_tel_2?>)</font></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="30"></td>
        </tr>
    </table>
    
    <? 
//        print_r($dilution_mode);
//        print_r($category);
//        print_r($sub_category);
//        print_r($sales_name_1);
//        print_r($sales_tel_1);
//        print_r($sales_name_2);
//        print_r($sales_tel_2);
//        print_r($language);
//        print_r($safety_list);
    ?>
</body>
</html>