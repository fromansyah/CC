
<html>
    <head>
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
        
        <script type="text/javascript">
                var _base_url = '<?= base_url() ?>';
                
                function contract_due_date(){
                        //alert('contract_due_date');
                        window.location = _base_url + 'index.php/Report/cdd_page';
                }
                
                function contract_summary(){
                        //alert('contract_summary');
                        window.location = _base_url + 'index.php/Report/cs_page';
                }
                
                function license_due_date(){
                        //alert('license_due_date');
                        window.location = _base_url + 'index.php/Report/ldd_page';
                }
                
                function license_summary(){
                        //alert('license_summary');
                        window.location = _base_url + 'index.php/Report/ls_page';
                }
        </script>
	</head>		
	<body>
		<table class="roundedCorners" border="1" style="width:100%; ">
			<tr>
				<td><b><font style="color: #808080;">Report List :</font></b></td>
			</tr>
			<tr>
				<td>
					<? foreach($report_list as $row):?>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <a href='#' onclick="<?=$row->url?>"><img border='0' src="<?=$this->config->item('base_url')?>/images/Go.png"></a>
                                                &nbsp;&nbsp;&nbsp;
						<a href='#' onclick="<?=$row->url?>"><?=$row->name?></a>
						<br/><br/>
					<? endforeach;?>
				</td>
			</tr>
		</table>
		<? //print_r($report_list);?>
	</body>
</html>