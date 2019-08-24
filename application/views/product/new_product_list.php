<html>
    <head>
<!--        <script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js')?>"></script>
        <script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>
        <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
        <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>
        <script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8/dist/sweetalert2.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@8/dist/sweetalert2.min.css" id="theme-styles">
        
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
        <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>-->
        <!--<style>
          .label {
            color: white;
            padding: 2px;
          }

          .success {background-color: #4CAF50;} /* Green */
          .info {background-color: #2196F3;} /* Blue */
          .warning {background-color: #ff9800;} /* Orange */
          .danger {background-color: #f44336;} /* Red */
          .other {background-color: #e7e7e7; color: black;} /* Gray */
        </style>-->
        <style>
            table.roundedCorners { 
                border: 1px solid #b3cce6;
                border-radius: 13px; 
                border-spacing: 0;
                }
              table.roundedCorners td, 
              table.roundedCorners th { 
                border-bottom: 1px solid #b3cce6;
                padding: 10px; 
                }
              table.roundedCorners tr:last-child > td {
                border-bottom: none;
              }

            put[type=checkbox] + label {
                display: block;
                margin: 0.2em;
                cursor: pointer;
                padding: 0.2em;
              }

              input[type=checkbox] {
                display: none;
              }

              input[type=checkbox] + label:before {
                content: "\2714";
                border: 0.1em solid #000;
                border-radius: 0.2em;
                display: inline-block;
                width: 1.3em;
                height: 1.3em;
                padding-left: 0.2em;
                padding-bottom: 0.2em;
                margin-right: 0.2em;
                vertical-align: bottom;
                color: transparent;
                transition: .2s;
              }

              input[type=checkbox] + label:active:before {
                transform: scale(0);
              }

              input[type=checkbox]:checked + label:before {
                background-color: #268afa;
                border-color: #268afa;
                color: #fff;
              }

              input[type=checkbox]:disabled + label:before {
                transform: scale(1);
                border-color: #aaa;
              }

              input[type=checkbox]:checked:disabled + label:before {
                transform: scale(1);
                background-color: #bfb;
                border-color: #bfb;
              }
            
            .checkboxes label {
                display: inline-block;
                padding-right: 10px;
                white-space: nowrap;
              }
              .checkboxes input {
                vertical-align: middle;
              }
              .checkboxes label span {
                vertical-align: middle;
              }
            
            .navigation {
                padding: 0;
                margin: 0;
                border: 0;
                line-height: 0.5;
              }

              .navigation ul,
              .navigation ul li,
              .navigation ul ul {
                list-style: none;
                margin: 0;
                padding: 0;
              }

              .navigation ul {
                position: relative;
                z-index: 500;
                float: left;
              }

              .navigation ul li {
                float: left;
                min-height: 0.05em;
                line-height: 1em;
                vertical-align: middle;
                position: relative;
              }

              .navigation ul li.hover,
              .navigation ul li:hover {
                position: relative;
                z-index: 510;
                cursor: default;
              }

              .navigation ul ul {
                visibility: hidden;
                position: absolute;
                top: 100%;
                left: 0px;
                z-index: 520;
                width: 100%;
              }

              .navigation ul ul li { float: none; }

              .navigation ul ul ul {
                top: 0;
                right: 0;
              }

              .navigation ul li:hover > ul { visibility: visible; }

              .navigation ul ul {
                top: 0;
                left: 99%;
              }

              .navigation ul li { float: none; }

              .navigation ul ul { margin-top: 0.05em; }

              .navigation {
                width: 14em;
                background: #f2f2f2;
                /*font-family: 'roboto', Tahoma, Arial, sans-serif;*/
                zoom: 1;
              }

              .navigation:before {
                content: '';
                display: block;
              }

              .navigation:after {
                content: '';
                display: table;
                clear: both;
              }

              .navigation a {
                display: block;
                padding: 1em 1.3em;
                color: #808080;
                text-decoration: none;
              }

              .navigation > ul { width: 14em; }

              .navigation ul ul { width: 14em; }

              .navigation > ul > li > a {
                border-right: 0.3em solid #f9f9f9;
                color: #808080;
              }

              .navigation > ul > li > a:hover { color: #808080; }

              .navigation > ul > li a:hover,
              .navigation > ul > li:hover a { background: #f9f9f9; }

              .navigation li { position: relative; }

              .navigation ul li.has-sub > a:after {
                content: '»';
                position: absolute;
                right: 1em;
              }

              .navigation ul ul li.first {
                -webkit-border-radius: 0 3px 0 0;
                -moz-border-radius: 0 3px 0 0;
                border-radius: 0 3px 0 0;
              }

              .navigation ul ul li.last {
                -webkit-border-radius: 0 0 3px 0;
                -moz-border-radius: 0 0 3px 0;
                border-radius: 0 0 3px 0;
                border-bottom: 0;
              }

              .navigation ul ul {
                -webkit-border-radius: 0 3px 3px 0;
                -moz-border-radius: 0 3px 3px 0;
                border-radius: 0 3px 3px 0;
              }

              .navigation ul ul { border: 1px solid #ffffff; }

              .navigation ul ul a { color: #808080; }

              .navigation ul ul a:hover { color: #808080; }

              .navigation ul ul li { border-bottom: 1px solid #ffffff; }

              .navigation ul ul li:hover > a {
                background: #ffffff;
                color: #808080;
              }

              .navigation.align-right > ul > li > a {
                border-left: 0.3em solid #f9f9f9;
                border-right: none;
              }

              .navigation.align-right { float: right; }

              .navigation.align-right li { text-align: right; }

              .navigation.align-right ul li.has-sub > a:before {
                content: '+';
                position: absolute;
                top: 50%;
                left: 15px;
                margin-top: -6px;
              }

              .navigation.align-right ul li.has-sub > a:after { content: none; }

              .navigation.align-right ul ul {
                visibility: hidden;
                position: absolute;
                top: 0;
                left: -100%;
                z-index: 598;
                width: 100%;
              }

              .navigation.align-right ul ul li.first {
                -webkit-border-radius: 3px 0 0 0;
                -moz-border-radius: 3px 0 0 0;
                border-radius: 3px 0 0 0;
              }

              .navigation.align-right ul ul li.last {
                -webkit-border-radius: 0 0 0 3px;
                -moz-border-radius: 0 0 0 3px;
                border-radius: 0 0 0 3px;
              }

              .navigation.align-right ul ul {
                -webkit-border-radius: 3px 0 0 3px;
                -moz-border-radius: 3px 0 0 3px;
                border-radius: 3px 0 0 3px;
              }
              
              .label {
                color: white;
                padding: 3px;
              }

              .success {background-color: #4CAF50;} /* Green */
              .info {background-color: #2196F3;} /* Blue */
              .warning {background-color: #ff9800;} /* Orange */
              .danger {background-color: #f44336;} /* Red */
              .other {background-color: #e7e7e7; color: #8c8c8c;} /* Gray */
        </style>
        <?
            $CI =& get_instance();
            $CI->load->library('encrypt');
        ?>
        <script type="text/javascript">
            var _base_url = '<?= base_url() ?>';
            var country = '<?=$country?>';
            var enc_country = '<?=str_replace(array('=','+','/'), array('-','_','~'), $CI->encrypt->encode($country))?>';
            var save_method; //for save method string
            var table;
            var change = '<?= str_replace(array('=','+','/'), array('-','_','~'), $CI->encrypt->encode('0'))?>';
 
            var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
                csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
             
            var dataJson = { [csrfName]: csrfHash };
            
            $(document).ready(function() {
                 $('#sort_by').change(function(){ //any select change on the dropdown with id country trigger this code
                     //search();
                     document.getElementById("buttonSearch").click();
                });
            });
            
            function category(category_id, category_name){
                $("#buttonSearch").removeAttr("disabled");
                
                $('#site_map').empty();
                $('#site_map').append('<small><b><font color="#c2c2a3"><a href="'+_base_url+'Product/new_product_lists/'+enc_country+'/'+change+'">Product Selection</a> <img src="'+_base_url+'images/cal_forward.gif"> <a href="#" onclick="category(\''+category_id+'\',\''+category_name+'\')">'+ category_name +' </a> </font></b></small>');
                $('#category').val(category_id);
                $('#sub_category').val('0');
                $('#subType').val('0');
                
                //search();
                document.getElementById("buttonSearch").click();
                
                /*var post_url = _base_url + 'Product/ajax_list_by_category/' + country + '/' + category_id + '/1';
                $.ajax({
                    type: "POST",
                    url: post_url,
                    success: function(products)
                    {
                        var result_count = 0;
                        $('#tb').empty();
                        var $i = 1;
                        var data = eval('(' + products + ')')
                        $.each(data,function(row,note)
                        {
                            result_count++;
                            $product_id = note["product_id"];
                            $product_no = note["product_no"];
                            $product_name = note["product_name"];
                            $package = note["kg_package"];
                            $image_name = note["image_name"];
                            $country = note["country"];
                            $category= note["category"];
                            $desc = note["desc"];

                            $desc_length = $desc.length;

//                            if($desc_length > 180){
//                                $desc = $desc.substring(0,180)+'...';
//                            }
                            $data_remarks = note["remarks"];
                    
                            $star = '<div align="right">&nbsp;</div>';
                            if(note["rating"] == 1){
                                $star = '<div align="right"><small><font color="#808080">Rating: </font></small><img width="12" src="'+_base_url+'/images/star.gif"/>\n\
                                        <img width="12" src="'+_base_url+'/images/star.gif"/></div>';

                                if($data_remarks != null){
                                    if($data_remarks.includes("TCO")){
                                        $star = '<div align="right">\n\
                                                    <small><font color="#808080">Rating: </font></small><span class="label success">TCO</span><font style="font-style:normal;color: #a4c1c1; font-size: 1em;"> | </font>\n\
                                                    <img width="12" src="'+_base_url+'/images/star.gif"/>\n\
                                                    <img width="12" src="'+_base_url+'/images/star.gif"/>\n\
                                                 </div>';
                                    }
                                }

                            }else if(note["rating"] == 2){
                                $star = '<div align="right"><small><font color="#808080">Rating: </font></small><img width="12" src="'+_base_url+'/images/star.gif"/>\n\
                                        <img width="12" src="'+_base_url+'/images/star.gif"/>\n\
                                        <img width="12" src="'+_base_url+'/images/star.gif"/></div>';

                                if($data_remarks != null){
                                    if($data_remarks.includes("TCO")){
                                        $star = '<div align="right">\n\
                                                    <small><font color="#808080">Rating: </font></small><span class="label success">TCO</span><font style="font-style:normal;color: #a4c1c1;"> | </font>\n\
                                                    <img width="12" src="'+_base_url+'/images/star.gif"/>\n\
                                                    <img width="12" src="'+_base_url+'/images/star.gif"/>\n\
                                                    <img width="12" src="'+_base_url+'/images/star.gif"/>\n\
                                                 </div>';
                                    }
                                }

                            }else if(note["rating"] == 3){
                                $star = '<div align="right"><small><font color="#808080">Rating: </font></small><font style="color: #a4c1c1;">Lowest Price</font></div>';

                                if($data_remarks != null){
                                    if($data_remarks.includes("TCO")){
                                        $star = '<div align="right"><small><font color="#808080">Rating: </font></small><span class="label success">TCO</span><font style="color: #a4c1c1; size:xx-small;""> | <small>Lowest Price</small></font></div>';
                                    }
                                }

                            }else if(note["rating"] == 4){
                                $star = '<div align="right"><small><font color="#808080">Rating: </font></small><small><font style="color: #a4c1c1;  size:xx-small;">Best Cost in Use</font></small></div>';

                                if($data_remarks != null){
                                    if($data_remarks.includes("TCO")){
                                        $star = '<div align="right"><small><font color="#808080">Rating: </font></small><span class="label success">TCO</span><small><font style="color: #a4c1c1; size:xx-small;"> | Best Cost in Use</font></small></div>';
                                    }
                                }

                            }else{
                                if($data_remarks != null){
                                    if($data_remarks.includes("TCO")){
                                        $star = '<div align="right">\n\
                                                    <small><font color="#808080">Rating: </font></small><span class="label success">TCO</span>&nbsp;\n\
                                                 </div>';
                                    }
                                }
                            }
                            
                            $('#tb').append('\
                                        <tr>\n\
                                            <td style="padding-top: 20px; padding-bottom: 20px;"><img style="height: 100px;" class="img-responsive" src="'+_base_url+'assets/images/'+$image_name+'"/></td>\n\
                                            <td style="padding-top: 20px; padding-bottom: 20px;">\n\
                                                <small><b><font color="#808080">'+$product_name+' ['+$package+']</font></b></small>\n\
                                                <br/><small><font color="#808080">'+$desc+'</font></small>\n\
                                                <br/><br/>\n\
                                                     <span class="label other">\n\
                                                        <font style="font-style:normal; font-size: 1em;"><img width=15 height=15 src="'+_base_url+'images/pdf.png"/> Label &nbsp;&nbsp;<a href="#" onclick="label('+$product_id+',\'ENG\')">ENG</a> | <a href="#" onclick="label('+$product_id+',\''+$country+'\')">'+$country+'</a></font>\n\
                                                        &nbsp;&nbsp;<font style="font-style:normal; font-size: 1em;"><img width=15 height=15 src="'+_base_url+'images/pdf.png"/> CAT Sheet &nbsp;&nbsp;<a href="#" onclick="cat_sheet('+$product_id+',\'ENG\')">ENG</a> | <a href="#" onclick="cat_sheet('+$product_id+',\''+$country+'\')">'+$country+'</a></font>\n\
                                                    </span>\n\
                                            </td>\n\
                                            <td align="right" style="padding-top: 20px; padding-bottom: 20px;">\n\
                                                '+$star+'\n\
                                                <br/><button type="button" onclick="add('+$product_id+')" class="btn btn-sm btn-success"><i class="glyphicon glyphicon-plus" style="font: calibry;"></i> Add to Wallchart</button>\n\
                                            </td>\n\
                                        </tr>');                    
                            $i++;
                        });

                        $('#search_result').empty();
                        $('#search_result').append('<p/><p><small><b> Found : </b>'+result_count+' product(s)</small></p>');
                    } 
                });
                $('#table_product').html(data).fadeOut('slow').fadeIn("slow");*/
                
//                var post_url_sub = _base_url + 'Category/ajax_get_category/' + category_id;
//                $.ajax({
//                    type: "POST",
//                    url: post_url_sub,
//                    dataType: "JSON",
//                    success: function(data) //we're calling the response json array 'cities'
//                    {
//                        
//                        $('#site_map').empty();
//                        $('#site_map').append('<small><b><font color="#c2c2a3"><a href="'+_base_url+'Product/new_product_lists/'+country+'">Product Selection</a> >> <a href="#" onclick="category('+category_id+')">'+ data.category_name +' </a> </font></b></small>');
//                    } 
//                });
            }
            
            function sub_category(category_id, category_name, sub_category_id, sub_category_name){
                $("#buttonSearch").removeAttr("disabled");
                
                $('#site_map').empty();
                $('#site_map').append('<small><b><font color="#c2c2a3"><a href="'+_base_url+'Product/new_product_lists/'+enc_country+'/'+change+'">Product Selection</a> <img src="'+_base_url+'images/cal_forward.gif"> <a href="#" onclick="category(\''+category_id+'\',\''+category_name+'\')">'+ category_name +' </a> <img src="'+_base_url+'images/cal_forward.gif"> <a href="#" onclick="sub_category(\''+category_id+'\',\''+category_name+'\',\''+sub_category_id+'\',\''+sub_category_name+'\')">' + sub_category_name + '</a> </font></b></small>');
                $('#category').val(category_id);
                $('#sub_category').val(sub_category_id);
                $('#subType').val('0');
                
                //search();
                document.getElementById("buttonSearch").click();
            }
            
            function sub_type(category_id, category_name, sub_category_id, sub_category_name, type, type_name){
                $("#buttonSearch").removeAttr("disabled");
                
                $('#site_map').empty();
                $('#site_map').append('<small><b><font color="#c2c2a3"><a href="'+_base_url+'Product/new_product_lists/'+enc_country+'/'+change+'">Product Selection</a> <img src="'+_base_url+'images/cal_forward.gif"> <a href="#" onclick="category(\''+category_id+'\',\''+category_name+'\')">'+ category_name +' </a> <img src="'+_base_url+'images/cal_forward.gif"> <a href="#" onclick="sub_category(\''+category_id+'\',\''+category_name+'\',\''+sub_category_id+'\',\''+sub_category_name+'\')">' + sub_category_name + '</a> <img src="'+_base_url+'images/cal_forward.gif"> <a href="#" onclick="sub_type(\''+category_id+'\',\''+category_name+'\',\''+sub_category_id+'\',\''+sub_category_name+'\',\''+type+'\',\''+type_name+'\')">' + type_name + ' </a> </font></b></small>');
                $('#category').val(category_id);
                $('#sub_category').val(sub_category_id);
                $('#subType').val(type);
                
                //search();
                document.getElementById("buttonSearch").click();
            }
            
            function search(){
                var ctgr = $('#category')[0].value;
                var sub_ctgr = $('#sub_category')[0].value;
                var keyword = $('#keyword')[0].value;

                var subType = $('#subType')[0].value;


                var beverage = 0;//$('#beverage')[0].value;
                if($('#beverage')[0].checked){
                    beverage = $('#beverage')[0].value;
                }

                var brewery = 0;//$('#brewery')[0].value;
                if($('#brewery')[0].checked){
                    brewery = $('#brewery')[0].value;
                }
                var dairy = 0;//$('#dairy')[0].value;
                if($('#dairy')[0].checked){
                    dairy = $('#dairy')[0].value;
                }
                var food = 0;//$('#food')[0].value;
                if($('#food')[0].checked){
                    food = $('#food')[0].value;
                }
                var seafood = 0;//$('#seafood')[0].value;
                if($('#seafood')[0].checked){
                    seafood = $('#seafood')[0].value;
                }
                var protein = 0;//$('#poultry')[0].value;
                if($('#protein')[0].checked){
                    protein = $('#protein')[0].value;
                }
                var pharma = 0;//$('#pharma')[0].value;
                if($('#pharma')[0].checked){
                    pharma = $('#pharma')[0].value;
                }
                
                var sort_by = $('#sort_by')[0].value;
                
                //alert(country+' - '+ctgr+' - '+sub_ctgr+' - '+subType+' - '+beverage+' - '+brewery+' - '+dairy+' - '+food+' - '+seafood+' - '+protein+' - '+pharma+' - '+keyword+' - '+sort_by);
                //var post_url = _base_url + 'Product/ajax_list_search_new/' + country + '/' + ctgr + '/' + beverage + '/' + brewery + '/' + dairy + '/' + food + '/' + seafood + '/' + protein + '/' + pharma + '/' + sort_by+ '/' + sub_ctgr + '/' + subType  + '/' + keyword ;
                var post_url = _base_url + 'Product/ajax_list_search_new/';
                $.ajax({
                    type: "POST",
                    data: $('#form').serialize(),
                    url: post_url,
                    success: function(products)
                    {
                        var result_count = 0;
                        $('#tb').empty();
                        var $i = 1;
                        var data = eval('(' + products + ')')
                        $.each(data,function(row,note)
                        {
                            result_count++;
                            $product_id = note["product_id"];
                            $product_no = note["product_no"];
                            $product_name = note["product_name"];
                            $package = note["kg_package"];
                            $image_name = note["image_name"];
                            $country = note["country"];
                            $category= note["category"];
                            $desc = note["desc"];

                            $desc_length = $desc.length;

//                            if($desc_length > 180){
//                                $desc = $desc.substring(0,180)+'...';
//                            }
                            $data_remarks = note["remarks"];
                    
                            $star = '<div align="right">&nbsp;</div>';
                            if(note["rating"] == 9){
                                $star = '<div align="right"><small><font color="#808080">Rating: </font></small><img width="12" src="'+_base_url+'/images/star.gif"/>\n\
                                        <img width="12" src="'+_base_url+'/images/star.gif"/></div>';

                                if($data_remarks != null){
                                    if($data_remarks.includes("TCO")){
                                        $star = '<div align="right">\n\
                                                    <small><font color="#808080">Rating: </font></small><span class="label success">TCO</span><font style="font-style:normal;color: #a4c1c1; font-size: 1em;"> | </font>\n\
                                                    <img width="12" src="'+_base_url+'/images/star.gif"/>\n\
                                                    <img width="12" src="'+_base_url+'/images/star.gif"/>\n\
                                                 </div>';
                                    }
                                }

                            }else if(note["rating"] == 10){
                                $star = '<div align="right"><small><font color="#808080">Rating: </font></small><img width="12" src="'+_base_url+'/images/star.gif"/>\n\
                                        <img width="12" src="'+_base_url+'/images/star.gif"/>\n\
                                        <img width="12" src="'+_base_url+'/images/star.gif"/></div>';

                                if($data_remarks != null){
                                    if($data_remarks.includes("TCO")){
                                        $star = '<div align="right">\n\
                                                    <small><font color="#808080">Rating: </font></small><span class="label success">TCO</span><font style="font-style:normal;color: #a4c1c1;"> | </font>\n\
                                                    <img width="12" src="'+_base_url+'/images/star.gif"/>\n\
                                                    <img width="12" src="'+_base_url+'/images/star.gif"/>\n\
                                                    <img width="12" src="'+_base_url+'/images/star.gif"/>\n\
                                                 </div>';
                                    }
                                }

                            }else if(note["rating"] == 8){
                                $star = '<div align="right"><small><font color="#808080">Rating: </font></small><font style="color: #a4c1c1;">Lowest Price</font></div>';

                                if($data_remarks != null){
                                    if($data_remarks.includes("TCO")){
                                        $star = '<div align="right"><small><font color="#808080">Rating: </font></small><span class="label success">TCO</span><font style="color: #a4c1c1; size:xx-small;""> | <small>Lowest Price</small></font></div>';
                                    }
                                }

                            }else if(note["rating"] == 7){
                                $star = '<div align="right"><small><font color="#808080">Rating: </font></small><small><font style="color: #a4c1c1;  size:xx-small;">Best Cost in Use</font></small></div>';

                                if($data_remarks != null){
                                    if($data_remarks.includes("TCO")){
                                        $star = '<div align="right"><small><font color="#808080">Rating: </font></small><span class="label success">TCO</span><small><font style="color: #a4c1c1; size:xx-small;"> | Best Cost in Use</font></small></div>';
                                    }
                                }

                            }else{
                                if($data_remarks != null){
                                    if($data_remarks.includes("TCO")){
                                        $star = '<div align="right">\n\
                                                    <small><font color="#808080">Rating: </font></small><span class="label success">TCO</span>&nbsp;\n\
                                                 </div>';
                                    }
                                }
                            }
                            
                            $('#tb').append('\
                                        <tr>\n\
                                            <td style="padding-top: 20px; padding-bottom: 20px;">\n\
                                                <form id="form_'+$product_id+'" method="post" action="#" method="post" accept-charset="utf-8">\n\
                                                <input type="hidden" id="csrf_prod" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />\n\
                                                <input type="hidden" name="id" value="'+$product_id+'" />\n\
                                                <input type="hidden" name="prod_no" value="'+$product_no+'" />\n\
                                                <input type="hidden" name="nama" value="'+$product_name+'" />\n\
                                                <input type="hidden" name="harga" value="1000" />\n\
                                                <input type="hidden" name="gambar" value="'+$image_name+'" />\n\
                                                <input type="hidden" name="qty" value="1" />\n\
                                                </form>\n\
                                                <img style="height: 100px;" class="img-responsive" src="'+_base_url+'assets/images/'+$image_name+'"/>\n\
                                            </td>\n\
                                            <td style="padding-top: 20px; padding-bottom: 20px;">\n\
                                                <small><b><font color="#808080">'+$product_name+'&nbsp;&nbsp;&nbsp;[ '+$package+' ]</font></b></small>\n\
                                                <br/><small><font color="#808080">'+$desc+'</font></small>\n\
                                                <br/><br/>\n\
                                                     <span class="label other">\n\
                                                        <font style="font-style:normal; font-size: 1em;"><img width=15 height=15 src="'+_base_url+'images/pdf.png"/> Label &nbsp;&nbsp;<a href="#" onclick="label('+$product_id+',\'ENG\')">ENG</a> | <a href="#" onclick="label('+$product_id+',\''+$country+'\')">'+$country+'</a></font>\n\
                                                        &nbsp;&nbsp;<font style="font-style:normal; font-size: 1em;"><img width=15 height=15 src="'+_base_url+'images/pdf.png"/> CAT Sheet &nbsp;&nbsp;<a href="#" onclick="cat_sheet('+$product_id+',\'ENG\')">ENG</a> | <a href="#" onclick="cat_sheet('+$product_id+',\''+$country+'\')">'+$country+'</a></font>\n\
                                                    </span>\n\
                                            </td>\n\
                                            <td align="right" style="padding-top: 20px; padding-bottom: 20px;">\n\
                                                '+$star+'\n\
                                                <br/><button type="button" onclick="add('+$product_id+')" class="btn btn-sm btn-success"><i class="glyphicon glyphicon-plus" style="font: calibry;"></i> Add to Wallchart</button>\n\
                                            </td>\n\
                                        </tr>');                    
                            $i++;
                        });

                        $('#search_result').empty();
                        $('#search_result').append('<p/><p><small><b> Found : </b>'+result_count+' product(s)</small></p>');
                    } 
                });
                //$('#product_list').fadeOut('slow').load('data.php').fadeIn("slow");
                $('#product_list').html(data).fadeOut('slow').fadeIn("slow");
            }
    
            function add(product_id){
                var url;
                var $form = "#form_"+product_id;

                url = "<?php echo site_url('Product/ajax_add')?>";
                $.ajax({
                url : url,
                type: "POST",
                data: $($form).serialize(),
                dataType: "JSON",
                success: function(data)
                    {
                        if(data.status) //if success close modal and reload ajax table
                        {
                            document.getElementById('cart_icon').src= _base_url + 'images/list_icon_2.png';
                            alert('The product has been add to your chart.');
                        }else{
                            serr = 'Alreadey selected.';
                            try {
                              serr = data.error+ ' ' + serr;
                            } catch(e) {}

                            alert(serr);
                        }
                    },error: function (jqXHR, textStatus, errorThrown)
                    {
                        alert('Error adding / update data');
                    }
                });
            }
            
            function clear_search(){
                location.reload();
            }
    
        </script>
    </head>
    <body>
        <div class="row-2">
            <div class="col-lg-2">
                <div style="height: 100px;">
                    <!--<small><font color="#c2c2a3"><font color="red">*</font> Select Application</font></small>-->
                                <div class="navigation">
                                    <ul>
                                        <li class="has-sub"><strong><a href="#" onclick="category(0, 'ALL')">ALL</a></strong></li>
                                        <?php foreach ($category as $row):?>
                                        <li class="has-sub"><a href="#" onclick="category('<? echo $row['category_id'] ?>', '<? echo $row['category_name'];?>')"><? echo $row['category_name'];?></a>
                                            <ul>
                                                <?php foreach($sub_category[$row['category_id']] as $sub_row): ?>
                                                <? if(count($sub_type[$sub_row['sub_category_id']]) > 0):?>
                                                <li class="has-sub"><a href="#" onclick="sub_category('<? echo $row['category_id'] ?>', '<? echo $row['category_name'];?>', '<?php echo $sub_row['sub_category_id'] ?>','<?php echo $sub_row['sub_category_name'] ?>')" class="list-group-item"><?=$sub_row['sub_category_name']?></a>
                                                <? else:?>
                                                <li><a href="#" onclick="sub_category('<? echo $row['category_id'] ?>', '<? echo $row['category_name'];?>', '<?php echo $sub_row['sub_category_id'] ?>','<?php echo $sub_row['sub_category_name'] ?>')" class="list-group-item"><?=$sub_row['sub_category_name']?></a>
                                                <? endif;?>
                                                    <ul>
                                                        <? //for($i=1; $i<count($subTypeList); $i++):?>
                                                        <!--<li><a href="#" onclick="category(0)"><?=$subTypeList[$i];?></a></li>-->
                                                        <? //endfor;?>
                                                        <? foreach($sub_type[$sub_row['sub_category_id']] as $sub_type_row):?>
                                                            <li><a href="#" onclick="sub_type('<? echo $row['category_id'] ?>', '<? echo $row['category_name'];?>', '<?php echo $sub_row['sub_category_id'] ?>','<?php echo $sub_row['sub_category_name'] ?>', '<?php echo $sub_type_row['type'];?>', '<?php echo $sub_type_row['name'];?>')"><?=$sub_type_row['name'];?></a></li>
                                                        <? endforeach;?>
                                                    </ul>
                                                </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </li>
                                        <?php endforeach;?>
                                    </ul>
                                </div>
                </div>
            </div>
            <div class="col-lg-10">
                <? //echo $this->session->userdata("country");?>
                <div id="site_map" style="padding-bottom: 10px;"><small><b><font color="#c2c2a3"><a href="<?= base_url() ?>Product/new_product_lists/<?=str_replace(array('=','+','/'), array('-','_','~'), $CI->encrypt->encode($country))?>/<?= str_replace(array('=','+','/'), array('-','_','~'), $CI->encrypt->encode('0'))?>">Product Selection</a></font></b></small></div>
                <form action="#" id="form" class="form-horizontal" enctype="multipart/form-data" method="post">
                <input type="hidden" id="csrf" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                <input type="hidden" class="form-control" name="country" id="country" value="<?=$country?>" style="width: 100px">
                <input type="hidden" class="form-control" name="category" id="category" style="width: 100px">
                <input type="hidden" class="form-control" name="sub_category" id="sub_category" style="width: 100px">
                <input type="hidden" class="form-control" name="subType" id="subType" style="width: 100px">
                
                <table class="roundedCorners" width="100%">
                    <tr>
                        <td>
                            <small><font color="#808080">Product Filter</font></small>
                            <br/><br/>
                            <div class="checkboxes" style="padding-left: 15px;">
                                <!--<small><font color="#808080">Segment </font></small>-->
                                <input type="checkbox" id="beverage" name="beverage" value="1">
                                <label for="beverage"><img height="20" src="<?= base_url() ?>images/Beverage.png" title="Beverage"></label>
                                <input type="checkbox" id="brewery" name="brewery" value="1">
                                <label for="brewery"><img height="20" src="<?= base_url() ?>images/brewery.png" title="Brewery"></label>
                                <input type="checkbox" id="dairy" name="dairy" value="1">
                                <label for="dairy"><img height="20" src="<?= base_url() ?>images/dairy.png" title="Dairy"></label>
                                <input type="checkbox" id="food" name="food" value="1">
                                <label for="food"><img height="20" src="<?= base_url() ?>images/food.png" title="Food"></label>
                                <input type="checkbox" id="seafood" name="seafood" value="1">
                                <label for="seafood"><img height="20" src="<?= base_url() ?>images/seafood.png" title="Seafood"></label>
                                <input type="checkbox" id="protein" name="protein" value="1">
                                <label for="protein"><img height="20" src="<?= base_url() ?>images/protein_2.png" title="Protein"></label>
                                <input type="checkbox" id="pharma" name="pharma" value="1">
                                <label for="pharma"><img height="20" src="<?= base_url() ?>images/pharma.png" title="Healthcare & Pharma"></label>
                                <small><font color="#ccccff">Product Segment </font></small>
                            </div>
                            <br/>
                            <div class="col-md-4 column">
                                <?php //echo form_dropdown('sub_category', $sub_category_list, '', 'id="sub_category" name="sub_category" placeholder="sub_category" class="form-control" type="text"'); ?>
                                <input type="text" class="form-control" name="keyword" id="keyword" placeholder="Product name keyword">
                            </div>
                            <div class="col-md-4 column">
                                <button type="button" id="buttonSearch" onclick="search()" class="btn btn-primary btn-search" disabled="disabled"><img src="<?= base_url() ?>images/search-icon.png" width=20 height=20>&nbsp;Search</button>&nbsp;
                                <!--<button type="button"  onclick="clear_search()" class="btn btn-primary btn-danger">&nbsp;Clear Search</button>&nbsp;-->
                            </div>
                            
                            <br/>
                            <div class="col-md-12 column">
                                <div id="search_result"><p/><p/>&nbsp;</div>
                            </div>
                        </td>
                    </tr>
                </table>
                <br/>
                <table width="100%">
                    <tr>
                        <td>
                            <?$img_url = base_url().'assets/uploads/images/'.$flag;?>
                            <!--<img id="flag" src="<?=$img_url?>" height="30" width="40">-->
                            <font style="font-size: large;">Product List</font>
                        </td>
                        <td align="right">
                            <small><font color="#808080">Sort by: </font></small>
                        </td>
                        <td align="right" width="100">
                                <select class="form-control" style="width: 150px;" id="sort_by" name="sort_by">
                                            <option value="1">Name (A to Z)</option>
                                            <option value="2">Name (Z to A)</option>
                                            <option value="3">New Arrival</option>
                                            <!--<option value="4">Popularity</option>-->
                                            <option value="4">Rating</option>
                                         </select>  
                        </td>
                    </tr>
                </table>
                </form>
                <table class="table" id="table_product">
                    <thead id="main_heading">
                        <th width="15%"></th>
                        <th width="60%"></th>
                        <th width="25%"></th>
                    </thead>
                    <tbody id="tb">
                    </tbody>
                </table>
            </div>
        </div>
<!--            <table>
                <tr>
                    <td>
                        
                    </td>
                    <td>
                        
                    </td>
                </tr>
            </table>-->
        <? // print_r($test);?>
    </body>
</html>

