<footer id="mainFooter">
	<div>
    	<small>© 2018 <?php
			// $domain = explode(".", $_SERVER['HTTP_HOST'], 2); 
		// echo strtoupper($domain[1]); ?> All rights reserved.</small>
    </div>
    <style>
		#mainFooter{
			position: absolute;
			left: 0;
			bottom: 0;
			width: 100%;
			height: 55px;
			font-family:Gotham, "Helvetica Neue", Helvetica, Arial, sans-serif;
		}
		#mainFooter div{ width:1000px; margin:0 auto; text-align:center; padding-top:10px;}
		#mainFooter div small{ font-size:12px; color: rgb(80, 77, 77);  }
	</style>
</footer>
</body>
<script language="javascript" type="text/javascript">
<?php if($isLogin ===TRUE){ require ('/var/www/easysms/app/assets/js/main.js'); } ?>
</script>
</html>
