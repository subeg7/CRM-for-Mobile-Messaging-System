<div id="assignGateway">
<p>Gateway Lists : <select name="gateway">
						<option value="none">--Select--</option>
                    <?php
						foreach($gateway as $row){
							echo '<option value="'.$row->fld_int_id.'">'.$row->fld_char_gw_name.'</option>';
						}
					?>
					</select>
                    <button type="button" id="submit"  class="button" value="Submit">Submit</button></p>
<div id="gatewayFeild">
<?php 	if($userGateway !=='none'){
			foreach($userGateway as $row){
				echo '<div> <p data-val="'.$row->fld_int_id.'"><span>'.$row->fld_char_gw_name.'</span></p><p class="close">X</p></div>';
			}
		}

?>


</div>

</div>
<script type="text/javascript">

$('#gatewayFeild').on('click','div p:last-child',function(e) {
	console.log('sdfd');
   	var selId = obj.getSelected('dhxDynFeild');
	if(selId == null) return;

	var arr =[];
	var id = $(this).siblings('p').data('val');

	var res = obj.dhx_ajax('push_c/assignGateway/remove/'+id+'/'+selId );
	if(res==='sucess'){
		obj.message_show('Gateway Removed  Sucessfully');
		$(this).parent('div').remove();
	}
	else{
		obj.message_show(res,'error');
	}
});
$('#submit').click(function(e) {
	var selId = obj.getSelected('dhxDynFeild');
	if(selId == null) return;

    if($('select[name="gateway"]').val()=='none' ){
		obj.message_show('** Select Shortcode ','error');
		return;
	}
	var gwname = $('select[name="gateway"]').children('option:selected').text();
	var gwid = $('select[name="gateway"]').val();
	var res = obj.dhx_ajax('push_c/assignGateway/assign/'+gwid+'/'+selId );
	if(res==='sucess'){
		obj.message_show('Gateway Assigned  Sucessfully');
		$('#gatewayFeild').append('<div> <p data-val="'+gwid+'"><span>'+gwname+'</span></p><p class="close">X</p></div>');

	}
	else{
		obj.message_show(res,'error');
	}
});
</script>

<style>
#assignGateway{ font-size:12px; padding:10px;  }
#assignGateway select{ padding:3px 10px;}
#assignGateway > p select,#assignGateway > p input{ font-size:12px;}
#submit { margin-left:80px;}
#gatewayFeild{
	-webkit-box-sizing: border-box;
-moz-box-sizing: border-box;
box-sizing: border-box;
	width:100%; height:349px; border:1px solid #a4bed4; margin-top:15px; padding:10px;
}
#gatewayFeild > div{ border:1px solid blue; padding:10px; width:230px; position:relative; float:left;
-webkit-box-sizing: border-box;
-moz-box-sizing: border-box; margin:10px 10px 0 0;
box-sizing: border-box;
}
#gatewayFeild > div p{ color:red;}
#gatewayFeild > div p.close{ position:absolute; right:5px; top:8px; background-color:#d1d1d1; padding:2px 5px; font-size:11px; color:white; cursor:pointer;}
#gatewayFeild > div p.close:hover{ background-color:red;}

#gatewayFeild > div p span{ border:1px solid #a4bed4; padding:3px 10px; border-radius:3px;}
</style>
