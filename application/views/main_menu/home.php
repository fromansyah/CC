<script type="text/javascript">

var _base_url = '<?= base_url() ?>';

function view_contract_reminder($day){
//    alert('Contract : '+$day);
    window.location = _base_url + 'Report/contract_reminder/';
}

function view_license_reminder($day){
//    alert('License : '+$day);
    window.location = _base_url + 'Report/license_reminder/';
}
</script>
<b><font size="3" color="grey">&nbsp;&nbsp;Notification :</font></b>
                <table width="100%" border="0" height="75%">
                    <tr valign="top">
                        <td width="2%">
                        </td>
                        <td width="40%">
                            <table class="responstable">
                                <tbody STYLE=" height: 50px; width: 100px; font-size: 12px; overflow: auto;">
                                <?  foreach ($notification as $row):
                                        if($row->notif_count > 0):?>
                                <tr height="40px">
                                  <td align="left"><b><a style="font-size: 15px;" href="#" onclick="<?=$row->link?>"><img border='0' src="<?= base_url() ?>images/warn.gif">&nbsp;&nbsp;&nbsp;<?=$row->notification?></a></b></td>
                                </tr>
                                <?      endif;
                                    endforeach;?>
                                </tbody>
                            </table>
                        </td>
                        <? //print_r($notification);?>
                        <td width="2%">
                        </td>
                        <td width="50%">
                        </td>
                        <td width="2%">
                        </td>
                    </tr>
                </table>

