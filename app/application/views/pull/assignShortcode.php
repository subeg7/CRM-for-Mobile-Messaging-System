<div id="assignShortcode">
<p>Shortcode Lists : <select name="shortcode">
						<option value="none">--Select--</option>
                    <?php 
						foreach($shortcode as $row){
							if(!in_array($row->fld_int_id,$dedicated))
							echo '<option value="'.$row->fld_int_id.'">'.$row->fld_chr_name.'</option>';
						}
					?>
					</select> 
                    
                    <input type="radio" name='type' value="dedicated"/> Dedicated | <input name='type' checked type="radio" value="normal"/> Normal<button type="button" id="submit"  class="button" value="Submit">Submit</button></p>
<div id="shortcodeFeild">
<?php 	if($userShortcode !=='none'){
			foreach($userShortcode as $row){
				echo '<div> <p data-val="'.$row->fld_int_id.'"><span>'.$row->fld_chr_name.'</span><span>Type : '.$row->assign_type.'</span></p><p class="close">X</p></div>';
			}
		}

?>
	
    
</div>

</div>
<script type="text/javascript">

$('#shortcodeFeild').on('click','div p:last-child',function(e) {
   	var selId = obj.getSelected('dhxDynFeild');
	if(selId == null) return;
	
	var arr =[];
	var shortcode = $(this).siblings('p').data('val');
	var assignType = $.trim(($.trim($(this).siblings('p').children('span:last-child').text())).split(':')[1]);
	
	arr.push('shortcode='+shortcode);
	arr.push('assignType='+assignType);
	arr.push('assignTo='+selId);

	var res = obj.dhx_ajax('vas/sms/pull_c/removeShortcode',arr.join('&') );
	if(res==='sucess'){
		obj.message_show('ShortCode Removed  Sucessfully');
		$(this).parent('div').remove();
	}
	else{
		obj.message_show(res,'error');
	}
});
$('#submit').click(function(e) {
	var selId = obj.getSelected('dhxDynFeild');
	if(selId == null) return;
	var arr =[];
    if($('select[name="shortcode"]').val()=='none' ){
		obj.message_show('** Select Shortcode ','error');
		return;
	}
	arr.push('shortcode='+$('select[name="shortcode"]').val());
	var codeType = ($('input[name="type"]:checked').val()==undefined)?'normal':$('input[name="type"]:checked').val();
	arr.push('assignType='+codeType);
	arr.push('assignTo='+selId);
	var shortcodeName = $('select[name="shortcode"]').children('option:selected').text();
	var res = obj.dhx_ajax('vas/sms/pull_c/assignShortcode',arr.join('&') );
	if(res==='sucess'){
		obj.message_show('ShortCode Assigned  Sucessfully');
		$('#shortcodeFeild').append('<div> <p data-val="'+$('select[name="shortcode"]').val()+'"><span>'+shortcodeName+'</span><span>Type : '+codeType+'</span></p><p class="close">X</p></div>');
		
	}
	else{
		obj.message_show(res,'error');
	}
});
</script>

<style>
#assignShortcode{ font-size:12px; padding:10px;  }
#assignShortcode select{ padding:3px 10px;}
#assignShortcode > p select,#assignShortcode > p input{ font-size:12px;}
#submit { margin-left:80px;}
#shortcodeFeild{
	-webkit-box-sizing: border-box; 
-moz-box-sizing: border-box;   
box-sizing: border-box;
	width:100%; height:349px; border:1px solid #a4bed4; margin-top:15px; padding:10px;
}
#shortcodeFeild > div{ border:1px solid blue; padding:10px; width:230px; position:relative; float:left;
-webkit-box-sizing: border-box; 
-moz-box-sizing: border-box; margin:10px 10px 0 0; 
box-sizing: border-box; 
}
#shortcodeFeild > div p{ color:red;}
#shortcodeFeild > div p.close{ position:absolute; right:5px; top:8px; background-color:#d1d1d1; padding:2px 5px; font-size:11px; color:white; cursor:pointer;}
#shortcodeFeild > div p.close:hover{ background-color:red;}
#shortcodeFeild > div p span:last-child{ margin:15px; color:black;}
#shortcodeFeild > div p span{ border:1px solid #a4bed4; padding:3px 10px; border-radius:3px;}
</style>