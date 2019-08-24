<?php 
    $filename = 'license_due_date_'.date("Y_m_d").'.xls';
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
                <th colspan="13">License Due Date</th>
            </tr>
            <tr>
                <th colspan="13">Cut Off Date : <? $co_date = DateTime::createFromFormat('Y-m-d', $cutoff)->format('d F Y');
                                             echo $co_date;?></th>
            </tr>
            <tr>
                <th colspan="13">&nbsp;</th>
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
                <th style="background-color: #9fcdff;">Due Date</th>
            </tr>
            <? $i = 1; foreach($license as $row):?>
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
                <? if($row->status == 0 || $row->status == 1):?>
                    <? if($row->due_date <= 0):?>
                        <td align="center" style="background-color: red;"><?=$row->due_date;?></td>
                    <? elseif($row->due_date > 0 && $row->due_date <= 30):?>
                        <td align="center" style="background-color: orange;"><?=$row->due_date;?></td>
                    <? elseif($row->due_date > 30 && $row->due_date <= 60):?>
                        <td align="center" style="background-color: yellow;"><?=$row->due_date;?></td>
                    <? elseif($row->due_date > 60 && $row->due_date <= 90):?>
                        <td align="center" style="background-color: greenyellow;"><?=$row->due_date;?></td>
                    <? else:?>
                        <td align="center"><?=$row->due_date;?></td>
                    <? endif;?>
                <? else:?>
                    <td align="center"><?=$row->due_date;?></td>
                <? endif;?>
            </tr>
            <? $i++; endforeach;?>
        </table>
        <?
//            print_r($license);
        ?>
    </body>
</html>
