<form id="editGroup" action="#">
	<ul>
    	<li>Group Name : <input type="text" disabled  class="editDisabled" name="name" autocomplete="off" data-val="<?php echo strtoupper($group[0]['name']); ?>" value="<?php echo strtoupper($group[0]['name']); ?>" /><span class="edit">Edit..</span></li>
        <li>Description &nbsp;&nbsp;: <input  disabled class="editDisabled" type="text" name="description" autocomplete="off" data-val="<?php echo strtoupper($group[0]['description']); ?>" value="<?php echo $group[0]['description']; ?>" /><span class="edit">Edit..</span></li>
        <li><input type="checkbox" class="admin" <?php if($underAdmin!== FALSE) echo 'checked="checked"';?> name="admin" value="admin" autocomplete="off" /> Under Admin Group</li>
        <li>Assign Privilages</li>
        <li>
        	<div>
            	<div>
                	<h3>PRIVILEGES</h3>
                    <ul>
                    	<?php
							foreach($privileges as $key=>$val){
								if(in_array($key,$priv))
									echo '<li><input checked="checked" type="checkbox" name="'.$key.'" value="'.$key.'"/><span>'.$key.'</span><span> :'.$val.'</span></li>';
								else
									echo '<li><input type="checkbox" name="'.$key.'" value="'.$key.'"/><span>'.$key.'</span><span> :'.$val.'</span></li>';
							}
							echo '<li>';
							if($subgroup){
								echo '<div id="subgroup"><h3>Sub-Group</h3><ul>';
								if($subgroup!='none'){
									foreach($subgroup as $val){
										echo'<li><input class="subgroup" checked="checked" type="checkbox" name="'.$val->name.'" value="'.$val->id.'"/>'.$val->name.'</li>';
									}
								}
								echo '</ul></div>';
							}
							echo  '</li>';
						?>

                    </ul>
                </div>
            </div>
        </li>
    </ul>

    <p><input class="button" type="reset" value="Reset"/><input class="button" type="submit" name="submit" value="Submit"/></p>
</form>
<script type="text/javascript" language="javascript">

var id = obj.getSelected('dhxDynFeild');
if( id !== null ){

	$('.edit').on('click',function(e){
		$(this).siblings('input').removeAttr('disabled').focus();
	});
	$('.editDisabled').on('focusout',function(e){
		if( $(this).val().toLowerCase()==$(this).data('val').toLowerCase() ){
			$(this).attr('disabled','disabled');
		}
	});
	$('#editGroup ul li input[type="checkbox"]').on('click',function(e){
		if(!$(this).is(':checked')){
			$(this).removeAttr('checked');
		}
	});
	$('#editGroup ul li input[name="USER_MANAGE"]').on('click',function(e){
		if($(this).is(':checked')){
			var res = obj.dhx_ajax('sysManage_c/getGroup/'+id);
			var ele = '<div id="subgroup"><h3>Sub-Group</h3><ul>';
			if(res !=='fail'){
				res = JSON.parse(res);
				for( var i=0; i<res.length; i++){
					if(res[i].id !== '1')
					ele = ele + '<li><input class="subgroup" type="checkbox" name="'+res[i].name+'" value="'+res[i].id+'"/>'+res[i].name+'</li>';
				}
			}
			ele = ele+ '</ul></div>';
			$('#editGroup > ul > li >div div:first-child > ul li:last-child').empty().append(ele);
		}
		else{
			$('#editGroup > ul > li >div div:first-child > ul li:last-child').empty();
		}
	});
	$('#editGroup').submit(function(e) {
		e.preventDefault();
		var priv = Array();
		var parm = Array();
		var subg = Array();
		var admin = Array();
		$('#editGroup ul li input[type="checkbox"]:checked').each(function(index) {
			if($(this).hasClass('subgroup')) subg.push($(this).val());
			else if($(this).hasClass('admin')) admin.push($(this).val());
			else priv.push($(this).val());
		});
		if($('#editGroup ul li input[name="name"]').data('val').toLowerCase()!==$('#editGroup ul li input[name="name"]').val().toLowerCase()){
			parm.push('name='+$('#editGroup ul li input[name="name"]').val());
		}
		if($('#editGroup ul li input[name="description"]').data('val').toLowerCase()!==$('#editGroup ul li input[name="description"]').val().toLowerCase()){
			parm.push('description=' +$('#editGroup ul li input[name="description"]').val());
		}
		if(priv.length > 0){
			parm.push('privileges='+priv.toString());
		}
		if(subg.length >0){
			parm.push('subgroup='+subg.toString());
		}
		if(admin.length >0){
			parm.push('admin='+admin.toString());
		}
		console.log(parm);
		var res = obj.dhx_ajax('sysManage_c/group/edit/'+id,parm.join('&'));
		if(res==='sucess'){
			obj.message_show('Update Sucessfully');
			obj.winTooble = true;
			obj.wind.window('group').close();
		}
		else{
			obj.message_show(res,'error');
		}

	});
}
</script>
<style>
#editGroup { font-size:12px; padding:10px;}
#editGroup ul li { padding:3px 5px;}
#editGroup > ul >li:nth-child(3){ margin-bottom:8px;}
#editGroup ul li input{ padding:4px 6px; font-size:12px;}
#editGroup ul li input[name="description"] { width:400px;}
#editGroup ul li input[name="description"],#editGroup ul li input[name="name"]{
	margin-right:10px;
}
#editGroup > p{ text-align:right; padding-right:24px; margin-top:10px;}
#editGroup > p input{ margin-left:5px;}
#editGroup> ul> li > div{ border:1px solid #a4bed4; height:305px; width:550px;
padding:10px;
-webkit-box-sizing: border-box; /* Safari/Chrome, other WebKit */
-moz-box-sizing: border-box;    /* Firefox, other Gecko */
box-sizing: border-box; overflow:auto;
}
#editGroup ul li > div div{ border:1px solid #a4bed4; position:relative; margin-top:15px; padding:5px 0;}
#editGroup ul li > div div:first-child{ margin-top:10px; }
#editGroup ul li > div div h3{ backface-visibility:visible; position:absolute; top:-7px; left:10px; background-color:white; padding:0 5px;
color:#343477;}

#editGroup ul li > div div > ul li span:nth-child(2){ color:#d42121;}
#editGroup ul li > div div > ul li span:nth-child(3){ color:#595959;}
#editGroup ul li > div div > ul li input{ vertical-align:middle;}

</style>
