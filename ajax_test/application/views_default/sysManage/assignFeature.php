<div id="assignFeature">
<table style="float:left;" >
	<tr>
    	<td>Feature Lists</td>
        <td>: <select id="selectFeature" name="feature">
						<option value="none">--Select--</option>
                    <?php
						foreach($feature as $row){
							echo '<option value="'.$row->fld_int_id.'">'.$row->fld_chr_feature.'</option>';
						}
					?>
					</select></td>
    </tr>
    <tr class="displayOff features" id="domain_url">
    	<td>Sub-Domain Url</td>
        <td>: <input type="text" name="infos" style="padding:2px 5px; width:200px; font-size:12px;"/></td>
    </tr>
    <tr class="displayOff features" id="title">
    	<td>Title</td>
        <td>: <input type="text" name="title" style="padding:2px 5px; width:200px; font-size:12px;"/></td>
    </tr>
    <tr class="displayOff features" id="route">
    	<td>Route Url</td>
        <td>: <input type="text" name="url" style="padding:2px 5px; width:200px; font-size:12px;"/></td>
    </tr>

</table>
<button style="float:right;" type="button"  id="submit"  class="button" value="Submit">Submit</button>
<div class="clearBoth"></div>
<div id="featureFeild">
<?php 	if($userFeature !=='none'){
			foreach($userFeature as $row){
				if($row->fld_chr_feature=='subbranding'){
					echo '<div> <p data-val="'.$row->fld_int_id.'"><span>'.$row->fld_chr_feature.'</span><span>'.$row->fld_chr_title.'</span><span>'.$row->extra_info.'</span></p><p class="close">X</p></div>';
				}
				elseif($row->fld_chr_feature=='pullroute' || $row->fld_chr_feature=='api' || $row->fld_chr_feature=='avoid'){
					echo '<div> <p data-val="'.$row->fld_int_id.'"><span>'.$row->fld_chr_feature.'</span></p><p class="close">X</p></div>';
				}
			}
		}

?>


</div>

</div>
<script type="text/javascript">
(function(){
var userpriv ="<?php echo $usermanagePriv;?>";
$('#selectFeature').on('change',function(e){
	$('.features').hide();
	if($.trim($(this).children('option:selected').text())=='subbranding'){
		$('#domain_url,#title').show();
	}
	else if($.trim($(this).children('option:selected').text())=='pullroute' && userpriv=='no'){
		$('#route').show();
	}
});
$('#featureFeild').on('click','div p:last-child',function(e) {
   	var selId = obj.getSelected('dhxDynFeild');
	if(selId == null) return;

	var arr =[];
	var id = $(this).siblings('p').data('val');

	var res = obj.dhx_ajax('userManage_c/assignFeature/remove/'+id+'/'+selId );
	if(res==='sucess'){
		obj.winTooble = true;
		obj.message_show('Feature Removed  Sucessfully');
		$(this).parent('div').remove();
	}
	else{
		obj.message_show(res,'error');
	}
});
$('#submit').click(function(e) {
	var param=[];
	param.push('userManagePriv='+userpriv);
	var selId = obj.getSelected('dhxDynFeild');
	var featureType = $('#selectFeature').children('option:selected').text();
	var featureId= $('#selectFeature').val();
	if(selId == null) return;

	if( featureId=='none'){
		obj.message_show('Select Feautre','error');
			return;
	}
	else if( featureType=='subbranding'){
		if($('input[name="infos"]').val().replace(/\s/g, '')=='' || $('input[name="title"]').val().replace(/\s/g, '')==''){
			obj.message_show('Sub-Domain Url or Title Feild is empty','error');
			return;
		}
		param.push('domain_url='+$('input[name="infos"]').val());
		param.push('title='+$('input[name="title"]').val());
	}
	else if(featureType=='pullroute'){
		if($('input[name="url"]').val().replace(/\s/g, '')=='' && userpriv=='no'){
			obj.message_show('Route Url Feild is required','error');
			return;
		}
		param.push('route_url='+$('input[name="url"]').val());
	}
	param.push('featureType='+featureType);
	var feaature = $('#selectFeature').val();
	/*
	var gwname = $('select[name="feature"]').children('option:selected').text();
	var gwid = $('select[name="feature"]').val();
	var info = $('input[name="infos"]').val();
	var title = $('input[name="title"]').val();*/
	var res = obj.dhx_ajax('userManage_c/assignFeature/assign/'+feaature+'/'+selId,param.join('&'));
	if(res==='sucess'){
		obj.winTooble = true;
		obj.message_show('Feature Assigned  Sucessfully');
		if(featureType=='subbranding'){
			$('#featureFeild').append('<div> <p data-val="'+featureId+'"><span>'+featureType+'</span><span>'+$('input[name="title"]').val()+'</span><span>'+$('input[name="infos"]').val()+'</span></p><p class="close">X</p></div>');
		}
		else if(featureType=='pullroute'){
			$('#featureFeild').append('<div> <p data-val="'+featureId+'"><span>'+featureType+'</span></p><p class="close">X</p></div>');
		}
		else if(featureType=='api' || featureType=='avoid'){
			$('#featureFeild').append('<div> <p data-val="'+featureId+'"><span>'+featureType+'</span></p><p class="close">X</p></div>');
		}
	}
	else{
		obj.message_show(res,'error');
	}
});
}());
</script>

<style>
#assignFeature{ font-size:12px; padding:10px;  }
#assignFeature select{ padding:3px 10px;}
#assignFeature > table tr td select,#assignFeature > table tr td input{ font-size:12px;}
#assignFeature > table tr td { padding:2px 10px;}
#submit { margin-left:15px;}
#featureFeild{
	-webkit-box-sizing: border-box;
-moz-box-sizing: border-box;
box-sizing: border-box;
	width:100%; height:249px; border:1px solid #a4bed4; margin-top:15px; padding:10px;
	background-color:#f5f5f5;
}
#featureFeild > div{ border:1px solid rgba(151,151,151,1.00); padding:10px; width:450px; position:relative; float:left;
-webkit-box-sizing: border-box;
-moz-box-sizing: border-box; margin:10px 10px 0 0;
box-sizing: border-box;
background-color:white;
transition-property:all;
    transition-duration: .300s;
    transition-timing-function: ease;
}
#featureFeild > div:hover{
	box-shadow: 3px 3px 5px #888888;
}

#featureFeild > div p{ color:red;}
#featureFeild > div p.close{ position:absolute; right:5px; top:8px; background-color:#d1d1d1; padding:2px 5px; font-size:11px; color:white; cursor:pointer;}
#featureFeild > div p.close:hover{ background-color:red;}

#featureFeild > div p span{ border:1px solid #a4bed4; padding:3px 10px; border-radius:3px;}
</style>
