<!--<div class="container">-->
        <div class="row-2">
            <div class="col-lg-2">
                <div class="row clearfix">
                </div>
                <div class="list-group">
                    <a class="list-group-item"><strong>COUNTRY</strong></a>
                    <!--<a href="#" onclick="country_product('ALL')" class="list-group-item">All</a>-->
                    <?php foreach ($country as $row):?>
                    <a href="#" onclick="country_customer('<?php echo $row['country_iso_code'] ?>','<?php echo $row['image_name'] ?>')" class="list-group-item"><?php echo $row['country_name'];?></a>
                    <?php endforeach;?>
                </div>
                <br>
            </div>

            <div class="col-lg-10">
                <span width="100" style="background-color: #e0e0e0; display: block;">
                    &nbsp;<font size="5" style="font-weight: bold; color: #737373">Customer Management</font>
                    <?
                        $img_url = '';
                        if($country_flag != ''){
                            $img_url = base_url().'assets/uploads/images/'.$country_flag;
                        }
                    ?>
                    &nbsp;&nbsp;&nbsp;<img id="flag" src="<?=$img_url?>" height="30">
                </span>
                <br/>
                <button id="add_button" class="btn btn-success" onclick="add_customer()" disabled="true"><i class="glyphicon glyphicon-plus"></i> Add Customer</button>
                <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
                <br />
                <br />
                <table id="table" class="table table-striped table-bordered" cellspacing="0" width="1100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Logo</th>
                            <th style="width:30px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>

                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Logo</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    <!--</div>-->
        

<script type="text/javascript">

var _base_url = '<?= base_url() ?>';
var $pick_country = '<?=$pick_country?>';
var save_method; //for save method string
var table;

var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
    csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
             
var dataJson = { [csrfName]: csrfHash };

$(document).ready(function() {
    
    if($pick_country != 'NULL'){
        $("#add_button").attr("disabled", false);
    }
    
    //datatables
    table = $('#table').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('Customer/ajax_list/')?>"+$pick_country,
            "type": "POST",
            "data": dataJson
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ -1 ], //last column
            "orderable": false //set not orderable
        },
        ]

    });

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



function add_customer()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add Customer'); // Set Title to Bootstrap modal title
    $('[name="mode"]').val(0);
    $('[name="country"]').val($('#country_code')[0].value);
}

function edit_customer(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('Customer/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="custId"]').val(data.cust_id);
            $('[name="custName"]').val(data.cust_name);
            $('[name="country"]').val($('#country_code')[0].value);
            $('[name="mode"]').val(1);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Customer'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('Customer/ajax_add')?>";
    } else {
        url = "<?php echo site_url('Customer/ajax_update')?>";
    }

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form').modal('hide');
                reload_table();

                $('#btnSave').text('save'); //change button text
                $('#btnSave').attr('disabled',false); //set button enable 
            }else{
                serr = 'Fail to save data.';
                try {
                  serr = serr + ' ' + data.error;
                } catch(e) {}
                
                alert(serr);
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 

        }
    });
}

function delete_customer(id, customer)
{
    if(confirm('Are you sure delete this data: ' + customer + ' ?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('Customer/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            data: dataJson,
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form').modal('hide');
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}

function view_sub_customer(customer_id) {
  window.location = _base_url + 'Sub_customer/lists/' + customer_id;
}



function country_customer($country, $flag){

    table.ajax.reload(null,false); 
    var _url = "<?php echo site_url('customer/ajax_list/')?>"+$country;
    table.ajax.url(_url).load();
    
    var src = _base_url + 'assets/uploads/images/' + $flag;
    $("#flag").attr("src", src);
    
    $("#country_code").val($country);
    
    $("#add_button").attr("disabled", false);
}

</script>

<input name="country_code" id="country_code" class="form-control" type="hidden" value="<?=$pick_country?>">
<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" customer="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Customer Form</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="<?=base_url()?>Customer/save_customer" method="post" id="form" class="form-horizontal" enctype="multipart/form-data">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                <input name="mode" class="form-control" type="hidden" readonly="true">
                <input name="country" id="country" class="form-control" type="text">
            <div class="modal-body form">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">ID</label>
                            <div class="col-md-9">
                                <input name="custId" placeholder="Customer ID" class="form-control" type="text" readonly="true">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Customer Name</label>
                            <div class="col-md-9">
                                <input name="custName" placeholder="Customer Name" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Logo</label>
                            <div class="col-md-9">
                                <input type="file" name="berkas" id="berkas" size="20" class="input_text" accept="image/x-png,image/gif,image/jpeg"/>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="btnSave" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
</body>
</html>