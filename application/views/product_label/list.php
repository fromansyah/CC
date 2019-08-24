
        <span width="100" style="background-color: #e0e0e0; display: block;">
            &nbsp;<font size="5" style="font-weight: bold; color: #737373">Product Label Management - <?=$product_name?></font>
        </span>
        <br/>
        <button class="btn btn-warning" onclick="back_to_product()"><i class="glyphicon glyphicon-backward"></i> Back</button>
        <button class="btn btn-success" onclick="add_product_label()"><i class="glyphicon glyphicon-plus"></i> Add Label</button>
        <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
        <br />
        <br />
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="1100">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Country</th>
                    <th>Label</th>
                    <th style="width:30px;">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>

            <tfoot>
                <tr>
                    <th>ID</th>
                    <th>Country</th>
                    <th>Label</th>
                    <th>Action</th>
                </tr>
            </tfoot>
        </table>

<script type="text/javascript">

var _base_url = '<?= base_url() ?>';
var _product_id = <?=$product_id?>;
var _country = '<?=$country?>';

var save_method; //for save method string
var table;

var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
    csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
             
var dataJson = { [csrfName]: csrfHash };

$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('Product_label/ajax_list/')?>"+ _product_id,
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



function add_product_label()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add Product Label'); // Set Title to Bootstrap modal title
     $('[name="mode"]').val(0);
}

function edit_product_label(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('Product_label/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="id"]').val(data.id);
            $('[name="productId"]').val(data.product_id);
            $('[name="country"]').val(data.country);
            $('[name="mode"]').val(1);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Product Label'); // Set title to Bootstrap modal title
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
        url = "<?php echo site_url('Product_label/ajax_add')?>";
    } else {
        url = "<?php echo site_url('Product_label/ajax_update')?>";
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

function delete_product_label(id, product_label)
{
    if(confirm('Are you sure delete this data: ' + product_label + ' ?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('Product_label/ajax_delete')?>/"+id,
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

function back_to_product()
{
    window.location = _base_url + 'Product/index/' + _country;
}

function product_label($id){
        //alert($lang);
        var win = window.open(_base_url+'Product_label/view_product_label/'+ $id, '_blank');
        win.focus();
    }
</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" product_label="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Product Label Form</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="<?=base_url()?>Product_label/save_product_label" method="post" id="form" class="form-horizontal" enctype="multipart/form-data">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
            <input name="mode" class="form-control" type="hidden">
            <input value="<?=$product_id?>" name="productId" class="form-control" type="hidden">
                <div class="modal-body form">
                <!--<form action="#" id="form" class="form-horizontal">-->
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">ID</label>
                            <div class="col-md-9">
                                <input name="id" placeholder="ID" class="form-control" type="text" readonly="true">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Country</label>
                            <div class="col-md-9">
                                <?php echo form_dropdown('country', $country_list, '', 'name="country" placeholder="Country" class="form-control" type="text"'); ?>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">File</label>
                            <div class="col-md-9">
                                <input type="file" name="berkas" id="berkas" size="20" class="input_text" accept="application/pdf"/>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                <!--</form>-->
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
           </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
</body>
</html>