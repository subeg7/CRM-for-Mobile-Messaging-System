<!doctype html>
<html>
<head>
<meta charset="utf-8">
<link rel="shortcut icon" href="assets/images/favicon.png"></link>

<title>MY Easy</title>

<script language="javascript" type="text/javascript">
<?php
	if($isLogin ===TRUE){

		echo"console.log('before requiring');";


		require ('assets/dthmlx/codebase/dhtmlx.js');
		require ('assets/dthmlx/codebase/ext/dhtmlxgrid_pgn.js');
		require ('assets/js/jquery.js');
		require ('assets/js/unicode.js');
		require ('assets/js/ucr_dhx.js');
		// echo"";
		// require ('assets/js/myJsTest.js');
		echo"console.log('point is here');";
	}
?>
</script>

<style type="text/css">
<?php
if($isLogin ===TRUE){
		if(require ('assets/dthmlx/skins/web/dhtmlx.css') ){
				// echo"found";
		}
		require ('assets/dthmlx/codebase/dhtmlx.css');

		require ('assets/dthmlx/codebase/ext/dhtmlxgrid_pgn_bricks.css');
		require ('assets/css/dhtmlxModify.css');
		require ('assets/css/main.css');
	}
	else{
		require ('assets/css/default.css');
	}
?>
</style>
<?php
if($isLogin ===TRUE){
		// $this->load->view('layout/toolbar_xml');
	}
?>
</head>
<body>
<?php if($isLogin ===TRUE){ ?>
<div id="mainLoadidng" class="displayOff"></div>
    <header id="mainHeader">
        <div>
            <div>
							<!-- assets/css/main.css -->
                <div><img src="assets/images/Home.png"></div>
                <h1><?php echo $domainTitle;?></h1>
            </div>
            <ul id="headerMenu">
                <li title="logout">logout</li>
                <li title="settings">settings</li>
                <li title="notification">notification</li>
                <!--<li title="help">help</li>-->
            </ul>
        </div>

    </header>
<?php } ?>
