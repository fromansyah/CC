<!DOCTYPE html>    
	<header>
		<script src="<?php echo base_url('assets/jquery/jquery-3.4.0.min.js')?>"></script>
		<script src="<?php echo base_url('assets/bootstrap/js/bootstrap-3.4.1.min.js')?>"></script>
		<script src="<?php echo base_url('assets/datatables-4/datatables.min.js')?>"></script>
		<script src="<?php echo base_url('assets/datatables-4/dataTables.bootstrap.min.js')?>"></script>
		<!--<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>-->
	    
        <script src="<?php echo base_url('assets/Bootstrap-4-4.1.1/js/bootstrap-datepicker.min.js')?>"></script>
        <link href="<?php echo base_url('assets/Bootstrap-4-4.1.1/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">
        
        <script>
            var _base_url = '<?= base_url() ?>';
            
            $(document).ready(function() {
                //datepicker
                $('.datepicker').datepicker({
                    autoclose: true,
                    format: "yyyy-mm-dd",
                    todayHighlight: true,
                    orientation: "top auto",
                    todayBtn: true,
                    todayHighlight: true
                });
            
            });
            
            function back_to_report_list(){
                window.location = _base_url + 'index.php/Report';
            }
            
            function refresh() {
              //alert('Refresh');
              //window.location = _base_url + 'index.php/Report/cs_page';
                  $.ajax({
                      'async': true,
                      'type': "GET",
                      'dataType': 'json',
                      'url': "<?php echo base_url('Main_menu/get_csrf'); ?>",
                      'success': function (data) {
                          tmp = data;
                          csrf_token = data.csrf_token;
                          $('#csrf_con_summary').val(csrf_token);
                      }
                  });
            }
            
        </script>
    
    </header>
	<body>
	    <div class="container">
			<h4 style="color: #808080;">Contract Summary Report</h4>
			<form action="<?=base_url()?>index.php/Report/ajax_run_contract_summary_new" id="form_contract_summary" class="form-horizontal" method="post" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" id="csrf_con_summary" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"  />
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Status</label>
                            <div class="col-md-6">
                                <?php echo form_dropdown('status', $status_list, '-1', 'id="status" name="status" class="form-control" type="text"'); ?>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Expired Date From</label>
                            <div class="col-md-6">
                                <input id="expDateFrom" name="expDateFrom" placeholder="yyyy-mm-dd" class="form-control datepicker" data-date-format="mm-dd-yyyy" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Expired Date To</label>
                            <div class="col-md-6">
                                <input id="expDateTo" name="expDateTo" placeholder="yyyy-mm-dd" class="form-control datepicker" data-date-format="mm-dd-yyyy" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Cut Off Date</label>
                            <div class="col-md-6">
                                <input id="cutOffDate" name="cutOffDate" placeholder="yyyy-mm-dd" class="form-control datepicker" data-date-format="mm-dd-yyyy" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
            </div>
            <font color="red">Note: Click Refresh button if you want to re-generate the report.</font>
            <div class="modal-footer">
                <button type="submit" id="btnSave_contract_due_date" onclick="windowClose();" class="btn btn-success" onclick="this.disabled=true">Generate</button>
                <button type="button" class="btn btn-primary" onclick="refresh()">Refresh</button>
                <button type="button" class="btn btn-danger" onclick="back_to_report_list()">Back</button>
            </div>
            </form>
		</div>
	</body>
</html>