<?php 
    $filename = 'license_reminder_'.date("Y_m_d").'.xls';
    header("Content-Type: application/excel");
    header("Cache-Control: max-age=0");
    header("Accept-Ranges: none");
    header("Content-Disposition: attachment; filename=$filename");
?>
<html>
    <head>
        <style>
            p {
                font-family: "Calibri";
                color: black;
            }
            
            table, td{  
                font-family: "Calibri";
                font-size: 15px;
                border: 1px solid #606060;
                /*text-align: left;*/
                height: 28px;
            }
            
            table, th{  
                font-family: "Calibri";
                font-size: 16px;
                border: 1px solid #606060;
                text-align: center;
                height: 35px;
            }

            table {
                border-collapse: collapse;
                width: 75%;
            }
        </style>
    </head>
    <body>
        <table>
            <tr>
                <th colspan="12">License Reminder</th>
            </tr>
            <tr>
                <th colspan="12">Period : <? echo date('d F Y');?></th>
            </tr>
            <tr>
                <th colspan="12">&nbsp;</th>
            </tr>
            <tr>
                <th style="background-color: #9fcdff;">No.</th>
                <th style="background-color: #9fcdff;">License No.</th>
                <th style="background-color: #9fcdff;">License Name</th>
                <th style="background-color: #9fcdff;">Company</th>
                <th style="background-color: #9fcdff;">Branch</th>
                <th style="background-color: #9fcdff;">Description</th>
                <th style="background-color: #9fcdff;">Issued By</th>
                <th style="background-color: #9fcdff;">Issued Date</th>
                <th style="background-color: #9fcdff;">Expired Date</th>
                <th style="background-color: #9fcdff;">Remarks</th>
                <th style="background-color: #9fcdff;">Note</th>
                <th style="background-color: #9fcdff;">Status</th>
            </tr>
            <? $i = 1; foreach($license as $row):?>
            <? if($i == 1):?>
            <tr>
                <td colspan="12" align="left" style="background-color: azure;"><b><?=$row->reminder;?></b></td>
            </tr>
            <? elseif($row->reminder != $license[$i-2]):?>
            <tr>
                <td colspan="12" align="left" style="background-color: azure;"><b><?=$row->reminder;?></b></td>
            </tr>
            <? endif;?>
            <tr>
                <td align="left"><?=$i;?></td>
                <td><?=$row->license_no;?></td>
                <td><?=$row->license_name;?></td>
                <td><?=$row->company_name;?></td>
                <td><?=$row->branch_name;?></td>
                <td><?=$row->description;?></td>
                <td><?=$row->issued_by;?></td>
                <td><?
                        $agr_date = DateTime::createFromFormat('Y-m-d', $row->issued_date)->format('d F Y');
                        echo $agr_date;
                    ?>
                </td>
                <td><?
                        $exp_date = DateTime::createFromFormat('Y-m-d', $row->exp_date)->format('d F Y');
                        echo $exp_date;
                    ?>
                </td>
                <td><?=$row->remarks;?></td>
                <td><?=$row->note;?></td>
                <td align="center"><?=$row->status_name;?></td>
            </tr>
            <? $i++; endforeach;?>
        </table>
        <?
//            print_r($license);
        ?>
    </body>
</html>
