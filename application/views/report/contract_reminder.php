<?php 
    $filename = 'contract_reminder_'.date("Y_m_d").'.xls';
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
            
            th{  
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
    <p></p>
    <body>
        <table>
            <tr>
                <th colspan="18">Contract Reminder</th>
            </tr>
<!--            <tr>
                <th colspan="11"><?=$day?> Before Expire</th>
            </tr>-->
            <tr>
                <th colspan="18">Period : <? echo date('d F Y');?></th>
            </tr>
            <tr>
                <th colspan="18">&nbsp;</th>
            </tr>
            <tr>
                <th style="background-color: #9fcdff;">No.</th>
                <th style="background-color: #9fcdff;">Contract No.</th>
                <th style="background-color: #9fcdff;">Division</th>
                <th style="background-color: #9fcdff;">First Party</th>
                <th style="background-color: #9fcdff;">Customer/Vendor</th>
                <th style="background-color: #9fcdff;">Description</th>
                <th style="background-color: #9fcdff;">Agreement Date</th>
                <th style="background-color: #9fcdff;">Effective Date</th>
                <th style="background-color: #9fcdff;">Expired Date</th>
                <th style="background-color: #9fcdff;">Type of Goods</th>
                <th style="background-color: #9fcdff;">Estimated Value</th>
                <th style="background-color: #9fcdff;">Checked by</th>
                <th style="background-color: #9fcdff;">Acknowledged by</th>
                <th style="background-color: #9fcdff;">Requestor</th>
                <th style="background-color: #9fcdff;">Approved by</th>
                <th style="background-color: #9fcdff;">Note</th>
                <th style="background-color: #9fcdff;">Filed at Legal</th>
                <th style="background-color: #9fcdff;">Status</th>
            </tr>
            <? $i = 1; foreach($contract as $row):?>
            <? if($i == 1):?>
            <tr>
                <td colspan="17" align="left" style="background-color: azure;"><b><?=$row->reminder;?></b></td>
            </tr>
            <? elseif($row->reminder != $contract[$i-2]):?>
            <tr>
                <td colspan="17" align="left" style="background-color: azure;"><b><?=$row->reminder;?></b></td>
            </tr>
            <? endif;?>
            <tr>
                <td align="left"><?=$i;?></td>
                <td><?=$row->contract_no;?></td>
                <td><?=$row->division_name;?></td>
                <td><?=$row->company_name;?></td>
                <td><?=$row->other_party;?></td>
                <td><?=$row->description;?></td>
                <td><?
                        $agr_date = DateTime::createFromFormat('Y-m-d', $row->agr_date)->format('d F Y');
                        echo $agr_date;
                    ?></td>
                <td><?
                        $eff_date = DateTime::createFromFormat('Y-m-d', $row->eff_date)->format('d F Y');
                        echo $eff_date;
                    ?></td>
                <td><?
                        $exp_date = DateTime::createFromFormat('Y-m-d', $row->exp_date)->format('d F Y');
                        echo $exp_date;
                    ?></td>
                <td><?=$row->tog;?></td>
                <td align="right"><? echo number_format($row->est_value, 2);?></td>
                <td><?=$row->check_by;?></td>
                <td><?=$row->ack_by;?></td>
                <td><?
                        $req_by = $row->req_by;
                        if($row->req_by_2 != null && $row->req_by_2 != ''){
                            $req_by .= ', '.$row->req_by_2;
                        }
                        if($row->req_by_3 != null && $row->req_by_3 != ''){
                            $req_by .= ', '.$row->req_by_3;
                        }
                        if($row->req_by_4 != null && $row->req_by_4 != ''){
                            $req_by .= ', '.$row->req_by_4;
                        }
                        if($row->req_by_5 != null && $row->req_by_5 != ''){
                            $req_by .= ', '.$row->req_by_5;
                        }
                                                                
                        echo $req_by;
                    ?></td>
                <td><?=$row->app_by;?></td>
                <td><?=$row->note;?></td>
                <td align="center"><?
                        if($row->filed_at_legal == 1){
                            echo 'Yes';
                        }else{
                            echo 'No';
                        }
                    ?></td>
                <td align="center"><?=$row->status_name;?></td>
            </tr>
            <? $i++; endforeach;?>
        </table>
    </body>
</html>
