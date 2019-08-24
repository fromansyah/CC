<?php 
    $filename = $contract[0]->contract_no.'_contract_summary.xls';
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
    <p></p>
    <body>
        <table>
            <tr>
                <th colspan="4">Contract Standard</th>
            </tr>
            <tr>
                <td width="85">Contract No.:</td>
                <td align="left" width="250" colspan="3"><?=$contract[0]->contract_no;?></td>
            </tr>
            <tr>
                <td>Division:</td>
                <td align="left" colspan="3"><?=$contract[0]->division_name;?></td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <th>Description</th>
                <th>Bahasa</th>
            </tr>
            <tr>
                <td align="center" width="90">1</td>
                <td align="left" width="250">Date of Agreement</td>
                <td align="center" width="360"><?
                        $agr_date = DateTime::createFromFormat('Y-m-d', $contract[0]->agr_date)->format('d F Y');
                        echo $agr_date;
                    ?></td>
                <td align="left" width="260">Tanggal Perjanjian</td>
            </tr>
            <tr>
                <td align="center">2</td>
                <td align="left">Effective Date</td>
                <td align="center"><?
                        $eff_date = DateTime::createFromFormat('Y-m-d', $contract[0]->eff_date)->format('d F Y');
                        $exp_date = DateTime::createFromFormat('Y-m-d', $contract[0]->exp_date)->format('d F Y');
                        echo $eff_date.' - '.$exp_date;
                    ?></td>
                <td align="left">Tanggal Berlaku</td>
            </tr>
            <tr>
                <td align="center">3</td>
                <td align="left">Period</td>
                <td align="center"><?
                        $period = '';
                        if($contract[0]->period_year > 0){
                            $period .= number_format($contract[0]->period_year, 0).' year(s) ';
                        }
                        
                        if($contract[0]->period_month > 0){
                            $period .= $contract[0]->period_month.' month(s)';
                        }
                        echo $period;
                    ?></td>
                <td align="left">Jangka Waktu</td>
            </tr>
            <tr>
                <th colspan="2">PARTIES</th>
                <td></td>
                <th>PARA PIHAK</th>
            </tr>
            <tr>
                <td align="center">4</td>
                <td align="left"><?=$contract[0]->company_name;?></td>
                <td></td>
                <td align="left"><?=$contract[0]->company_name;?></td>
            </tr>
            <tr>
                <td align="center">5</td>
                <td align="left">Authorized Representative</td>
                <td align="center"><?=$contract[0]->rep_name;?></td>
                <td align="left">Wakil yang berwenang</td>
            </tr>
            <tr>
                <td align="center">6</td>
                <td align="left">Title</td>
                <td align="center"><?=$contract[0]->rep_title;?></td>
                <td align="left">Jabatan</td>
            </tr>
            <tr>
                <td align="center">7</td>
                <td align="left">Address</td>
                <td align="center"><?=$contract[0]->address;?></td>
                <td align="left">Alamat</td>
            </tr>
            <tr>
                <td align="center">8</td>
                <td align="left">Customer/Vendor</td>
                <td align="center"><?=$contract[0]->other_party;?></td>
                <td align="left">Nama Perusahaan Pelanggan/Pemasok</td>
            </tr>
            <tr>
                <td align="center">9</td>
                <td align="left">Authorized Representative</td>
                <td align="center"><?=$contract[0]->op_rep;?></td>
                <td align="left">Wakil yang berwenang</td>
            </tr>
            <tr>
                <td align="center">10</td>
                <td align="left">Title</td>
                <td align="center"><?=$contract[0]->op_title;?></td>
                <td align="left">Pemasok</td>
            </tr>
            <tr>
                <td align="center">11</td>
                <td align="left">Address</td>
                <td align="center"><?=$contract[0]->op_address;?></td>
                <td align="left">Alamat</td>
            </tr>
            <tr>
                <td align="center">12</td>
                <td align="left">Agreement Description</td>
                <td align="center"><?=$contract[0]->description;?></td>
                <td align="left">Kesepakatan</td>
            </tr>
            <tr>
                <td align="center">13</td>
                <td align="left">Production Location</td>
                <td align="center"><?=$contract[0]->prod_loc;?></td>
                <td align="left">Lokasi</td>
            </tr>
            <tr>
                <th colspan="2">COMMERCIAL</th>
                <td></td>
                <th>KOMERSIL</th>
            </tr>
            <tr>
                <td align="center">14</td>
                <td align="left">Type of Goods</td>
                <td align="center"><?=$contract[0]->tog;?></td>
                <td align="left">Jenis Produk</td>
            </tr>
            <tr>
                <td align="center">15</td>
                <td align="left">Quantity</td>
                <td align="center"><? echo $contract[0]->quantity;?></td>
                <td align="left">Kuantitas</td>
            </tr>
            <tr>
                <td align="center">16</td>
                <td align="left">Specifications</td>
                <td align="center"><?=$contract[0]->specs;?></td>
                <td align="left">Spesifikasi</td>
            </tr>
            <tr>
                <td align="center">17</td>
                <td align="left">Key Performane Indicator</td>
                <td align="center"><?=$contract[0]->kpi;?></td>
                <td align="left">Indikasi Kinerja Inti</td>
            </tr>
            <tr>
                <td align="center">18</td>
                <td align="left">Price</td>
                <td align="center"><?=$contract[0]->price;?></td>
                <td align="left">Harga</td>
            </tr>
            <tr>
                <td align="center">19</td>
                <td align="left">Estimated Value</td>
                <td align="center"><? echo 'Rp. '.number_format($contract[0]->est_value, 2);?></td>
                <td align="left">Perkiraan Nilai Kontrak per Tahun</td>
            </tr>
            <tr>
                <td align="center">20</td>
                <td align="left">Terms of Payment</td>
                <td align="center"><?=$contract[0]->term_of_payment;?></td>
                <td align="left">Cara Pembayaran</td>
            </tr>
            <tr>
                <td align="center">21</td>
                <td align="left">Delivery Time</td>
                <td align="center"><?=$contract[0]->delivery_time;?></td>
                <td align="left">Waktu Pengiriman</td>
            </tr>
            <tr>
                <td align="center">22</td>
                <td align="left">Termination</td>
                <td align="center"><?=$contract[0]->termination;?></td>
                <td align="left">Pengakhiran</td>
            </tr>
            <tr>
                <td align="center">23</td>
                <td align="left">Penalty</td>
                <td align="center"><?=$contract[0]->penalty;?></td>
                <td align="left">Penalti</td>
            </tr>
            <tr>
                <th colspan="2">LEGAL</th>
                <td></td>
                <th>LEGAL</th>
            </tr>
            <tr>
                <td align="center">24</td>
                <td align="left">Force Majeur</td>
                <td align="center"><?=$contract[0]->force_majeure;?></td>
                <td align="left">Keadaan Kahar</td>
            </tr>
            <tr>
                <td align="center">25</td>
                <td align="left">Dispute Resolution</td>
                <td align="center"><?=$contract[0]->dispute;?></td>
                <td align="left">Penyelesaian Sengketa</td>
            </tr>
            <tr>
                <td align="center">26</td>
                <td align="left">Governing Law</td>
                <td align="center"><?=$contract[0]->gov_law;?></td>
                <td align="left">Hukum yang berlaku</td>
            </tr>
            <tr>
                <td align="center">27</td>
                <td align="left">Confidentiality</td>
                <td align="center"><?=$contract[0]->conf;?></td>
                <td align="left">Kerahasiaan</td>
            </tr>
            <tr>
                <td align="center">28</td>
                <td align="left">Others</td>
                <td align="center"><?=$contract[0]->others;?></td>
                <td align="left">Lain-lain</td>
            </tr>
            <tr>
                <td colspan="2" align="left"><b>LEGAL</b></td>
                <td colspan="2" align="left"><b>DIVISION</td>
            </tr>
            <tr>
                <td colspan="2" align="left"><b>Checked by: <?=$contract[0]->check_by;?></b></td>
                <td colspan="2" align="left"><b>Requestor: <?
                                                                $req_by = $contract[0]->req_by;
                                                                if($contract[0]->req_by_2 != null && $contract[0]->req_by_2 != ''){
                                                                    $req_by .= ', '.$contract[0]->req_by_2;
                                                                }
                                                                if($contract[0]->req_by_3 != null && $contract[0]->req_by_3 != ''){
                                                                    $req_by .= ', '.$contract[0]->req_by_3;
                                                                }
                                                                if($contract[0]->req_by_4 != null && $contract[0]->req_by_4 != ''){
                                                                    $req_by .= ', '.$contract[0]->req_by_4;
                                                                }
                                                                if($contract[0]->req_by_5 != null && $contract[0]->req_by_5 != ''){
                                                                    $req_by .= ', '.$contract[0]->req_by_5;
                                                                }
                                                                
                                                                echo $req_by;
                                                            ?></b></td>
            </tr>
            <tr>
                <td colspan="2" align="left"><b>Acknowledged by: <?=$contract[0]->ack_by;?></b></td>
                <td colspan="2" align="left"><b>Approved by: <?=$contract[0]->app_by;?></b></td>
            </tr>
        </table>
        <?
//            print_r($contract);
        ?>
    </body>
</html>