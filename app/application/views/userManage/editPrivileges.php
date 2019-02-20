<div id="editpriv">
	<h2><?php echo ucwords($userName[0]->company)?> , Privileges</h2>
    <form>
    <div>
    	<?php 
			if(isset($groupPriv['privileges'])){
				echo '<div><h3>User Privileges</h3><ul>';
				foreach($groupPriv['privileges'] as $key=>$val){
					if(in_array($key,$userPriv)){
						echo '<li><input type="checkbox" checked="checked" value="'.$key.'"/><span>'.$key.'</span><span> : &nbsp;&nbsp;'.$val.'</span></li>';
					}
					else{
						echo '<li><input type="checkbox" value="'.$key.'"/><span>'.$key.'</span><span> : &nbsp;&nbsp;'.$val.'</span></li>';
					}
				}
				echo '</ul></div>';
			}
		?>
    </div>
    <p><input class="button" type="reset" value="Reset"/><input id="submitPriv" class="button" type="submit" name="submit" value="Submit"/></p>
    </form>
</div>

<style>
#editpriv{ font-size:12px; padding:10px;}
#editpriv h2{ color:#b90c0c; font-size:13px;}
#editpriv  div{
	border: 1px solid #a4bed4; padding:10px; position:relative;margin-top:10px;
}
#editpriv form > div{
	 margin-top:15px; height:300px; overflow:auto;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;    box-sizing: border-box;
}
#editpriv form > p{ text-align:right; margin-top:10px;}
#editpriv form > p input:last-child{ margin-left:10px; }
#editpriv div h3{ position:absolute; top:-10px; left:15px; padding:3px 10px; background-color:white; color:blue;}
#editpriv div ul li span:nth-child(2){  color:#018287;}
#editpriv div ul li span:last-child{  color:#353535;}
</style>


<script type="text/javascript">
$('#submitPriv').click(function(e) {
	e.preventDefault();
	var priv = []
    $('#editpriv form div ul li').each(function(index, element) {
		var checkbox = $(this).children('input[type="checkbox"]');
        if(checkbox.is(':checked'))priv.push(checkbox.val());
    });
	if(priv.length > 0){
		console.log(priv.toString());
		
		$('div#load').show();
		var selId = obj.getSelected('dhxDynFeild');
		if(selId == null) return;
		var res = obj.dhx_ajax('vas/sms/userManage_c/manageUserPrivileges/'+selId,'priv='+priv.toString() );
		if(res ==='sucess') obj.message_show( 'User Prvileges updated sucessfully' );
		else obj.message_show( res,'error' );
		setTimeout(function(){  $('div#load').hide(); }, 300);
	}
	else{
		obj.message_show( '**Warning : Please select privileges','error' );
	}
	
});

</script>







