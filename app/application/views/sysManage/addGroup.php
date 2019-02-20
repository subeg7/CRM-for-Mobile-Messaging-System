<form id="addGroup" action="#">
	<ul>
    	<li>Group Name : <input type="text" name="name" autocomplete="off" /></li>
        <li>Description &nbsp;&nbsp;: <input type="text" name="description" autocomplete="off" /></li>
        <li><input class='admin' type="checkbox" name="admin" value="admin" autocomplete="off" /> Under Admin Group</li>
        <li>Assign Privilages</li>
        <li>
        	<div>
            	<div>
                	<h3>PRIVILEGES</h3>
                    <ul>
                    	<?php 
							foreach($privileges as $key=>$val){
								echo '<li><input type="checkbox" name="'.$key.'" value="'.$key.'"/><span>'.$key.'</span><span> :'.$val.'</span></li>';
							}
						?>
                        <li>
                            
                        </li>
                    </ul>
                </div>
                
                
            </div>
        </li>
    </ul>
	
    <p><input class="button" type="reset" value="Reset"/><input class="button" type="submit" name="submit" value="Submit"/></p>
</form>
<script type="text/javascript" language="javascript">
$('#addGroup ul li input[name="USER_MANAGE"]').on('click',function(e){
	if($(this).is(':checked')){
		var res = obj.dhx_ajax('vas/sms/sysManage_c/getGroup');
		var ele = '<div id="subgroup"><h3>Sub-Group</h3><ul>';
		if(res !=='fail'){
			res = JSON.parse(res);
			for( var i=0; i<res.length; i++){
				if(res[i].id !== '1')
				ele = ele + '<li><input class="subgroup" type="checkbox" name="'+res[i].name+'" value="'+res[i].id+'"/>'+res[i].name+'</li>';
			}
		}
		ele = ele+ '</ul></div>';
		$('#addGroup > ul > li >div div:first-child > ul li:last-child').empty().append(ele);
	}
	else{
		$('#addGroup > ul > li >div div:first-child > ul li:last-child').empty();
	}
});
$('#addGroup').submit(function(e) {
	e.preventDefault();
	var priv = Array();
	var subg = Array();
	var admin = Array();
	$('#addGroup ul li input[type="checkbox"]:checked').each(function(index) {
		if($(this).hasClass('subgroup')) subg.push($(this).val());
		else if($(this).hasClass('admin')) admin.push($(this).val());
		else priv.push($(this).val());
    });
	var pram = 'name='+$('#addGroup ul li input[name="name"]').val()+'&description='+$('#addGroup ul li input[name="description"]').val()+'&privileges='+priv.toString();
	if(subg.length >0) pram = pram+ '&subgroup='+subg.toString();
	if(admin.length >0) pram = pram+ '&admin='+admin.toString();
   
	var res = obj.dhx_ajax('vas/sms/sysManage_c/group/new',pram);
	if(res==='sucess'){
		obj.message_show('New Group has been added sucessfully');
		obj.grid['dhxDynFeild_t'].clearAndLoad('vas/sms/sysManage_c/renderGroup');
		$(this)[0].reset();
	}
	else{
		obj.message_show(res,'error');
	}
	
});

</script>
<style>
#addGroup { font-size:12px; padding:10px;}
#addGroup ul li { padding:3px 5px;}
#addGroup > ul >li:nth-child(3){ margin-bottom:8px;}
#addGroup ul li input{ padding:4px 6px; font-size:12px;}
#addGroup ul li input[name="description"] { width:458px;}
#addGroup > p{ text-align:right; padding-right:24px; margin-top:10px;}
#addGroup > p input{ margin-left:5px;}
#addGroup> ul> li > div{ border:1px solid #a4bed4; height:305px; width:550px;
padding:10px;
-webkit-box-sizing: border-box; /* Safari/Chrome, other WebKit */
-moz-box-sizing: border-box;    /* Firefox, other Gecko */
box-sizing: border-box; overflow:auto;
}
#addGroup ul li > div div{ border:1px solid #a4bed4; position:relative; margin-top:15px; padding:5px 0;}
#addGroup ul li > div div:first-child{ margin-top:10px; }
#addGroup ul li > div div h3{ backface-visibility:visible; position:absolute; top:-7px; left:10px; background-color:white; padding:0 5px;
color:#343477;}

#addGroup ul li > div div > ul li span:nth-child(2){ color:#d42121;}
#addGroup ul li > div div > ul li span:nth-child(3){ color:#595959;}
#addGroup ul li > div div > ul li input{ vertical-align:middle;}

</style>