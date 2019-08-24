
        <?
            $CI =& get_instance();
            $CI->load->library('encrypt');
        ?>

<div class="container">
    <? $country = $this->session->userdata('country');
        if($country != 'null'):?>
    <div id="site_map" style="padding-bottom: 10px;"><small><b><font color="#c2c2a3"><a href="<?= base_url() ?>Product/new_product_lists/
<?=str_replace(array('=','+','/'), array('-','_','~'), $CI->encrypt->encode($this->session->userdata('country')))?>/<?= str_replace(array('=','+','/'), array('-','_','~'), $CI->encrypt->encode('0'))?>">Product Selection</a> <img src="<?= base_url() ?>/images/cal_forward.gif"> <a href="<?= base_url() ?>Product/wallchart">My Wall Chart </a></font></b></small></div>    
    No Selected Product.
    <? else:?>
    No Selected Product.<br/>
    <b><a href="<?= base_url() ?>">Back to Home </a></b></small>
    <? endif; ?>
</div>
