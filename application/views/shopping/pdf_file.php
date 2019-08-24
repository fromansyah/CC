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
            <td width="130"><img height="25" src="<?= base_url() ?>images/index.png"></td>
            <td valign="middle" align="center"><b><font face="arial" size="3">(Chemical Application and Safety Chart)</font></b></td>
            <td width="175"></td>
            <td width="30"></td>
        </tr>
        <tr>
            <td height="10" colspan="5"></td>
        </tr>
        <tr>
            <td width="30"></td>
            <td colspan="3">
                <table id="customers" width="730">
                    <thead bgcolor="#67a8fe">
                        <tr>
                            <th><font face="arial" color="" size="3">No.</font></th>
                            <th><font face="arial" color="" size="3">Image</font></th>
                            <th><font face="arial" color="" size="3">Product</font></th>
                            <th><font face="arial" color="" size="3">Desc.</font></th>
                        </tr>
                    </thead>
                    <tbody>
                        <? 
                            $i =1;
                            foreach($cart as $row):
                        ?>
                        <tr>
                            <td align="center" width="40"><font face="arial" size="2"><?=$i?></font></td>
                            <td align="center" width="70"><img height="60" width="60" class="img-responsive" src="<?php echo base_url().'assets/images/'.$row['image']; ?>"/></td>
                            <td width="100"><font face="arial" size="2"><?=$row['prod_no'].' '.$row['name']?></font></td>
                            <td><font size="2">&nbsp;กรณีสัมผัสทางผิวหนัง : 한글 十课 第十一课 第十 平仮名, ひらがな, ét-xì; xờ nặng, بشدة إلى الأصالة हिंदी फोंट डाउनलोड करे, 献给母亲的爱</font></td>
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
    </table>
    
    
</body>
</html>
