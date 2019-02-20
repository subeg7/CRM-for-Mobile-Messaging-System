<form id="addUsers" action="#">
	<div>
    	<h3>Organization & Contact information </h3>
        <table>
            <tr>
                <td>Organization Name</td>
                <td>: <input type="text" name="org_name"/></td>
                <td>Organization Phone Number</td>
                <td>: <input type="text" name="org_phone"/></td>
            </tr>
            <tr>
                <td>Contact Person Full Name</td>
                <td>: <input type="text" name="person_name"/></td>
                <td>Contact Person Number</td>
                <td>: <input type="text" name="person_phone"/></td>
            </tr>
            <tr>
                <td>Full Address</td>
                <td>: <input type="text" name="address"/></td>
                <td>E-mail</td>
                <td>: <input type="text" name="email"/></td>
            </tr>
            <tr>
                <td>User Name</td>
                <td>: <input type="text" name="username"/></td>
                <td>Password</td>
                <td>: <input type="password" name="password"/></td>
            </tr>
            <tr>
                <td>Country</td>
                <td>: <select name='country'>
                	<?php 
						if($isadmin == 'admin'){
							echo '<option value="default">Default</option>';
						}
						if($country !='none'){
							foreach($country as $coun){
								echo '<option value="'.$coun->fld_int_id.'">'.$coun->fld_chr_name.'</option>';
							}
						}
					?>
                </select></td>
                <td>Balance Type</td>
                <td>: <select name='balance'>
                	<?php 
						echo '<option value="Seperate">Seperate Balance</option>';
						/*if($balanceType =='postpaid' || $balanceType =='single'  )
							echo '<option value="single">Single Balance</option>';*/
						if($isadmin =='admin')
							echo '<option value="postpaid">Post Paid Balance</option>';
					?>
                </select></td>
            </tr>
             <tr>
                <td>User Type</td>
                <td>: <select name="userType">
    					<option value="none">--Select--</option>
    				<?php 
						foreach ($userType as $row){
							echo '<option value="'.$row->id.'">'.$row->name.'</option>';
						}
					?>
        			
        			</select></td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>
   
</form>
<p><input class="button" type="reset" value="Reset" form="addUsers"/><input class="button" type="submit" name="submit" value="Submit"/></p>
<script type="text/javascript">
(function(e){
	var res = null;
	
	/*$('#addUsers > p select[name="userType"]').change(function(e) {
        if($(this).val()!=='none'){ 
			$('#addUsers').children('div:last-child').remove();
			$('#addUsers > h2').remove();
			$(this).siblings('span').show()
		}
		else{
			$(this).siblings('span').hide();
		}
		$('#addUsers p input[type="radio"]').attr('checked',false);
    });*/
	$('#addUsers p input[type="radio"]').change(function(e) {/*
		if($('#addUsers > p select[name="userType"]').val() =='none') obj.message_show('Select UserType');
		$('div#load').show();
		$('#addUsers').children('div:last-child').remove();
		$('#addUsers > h2').remove();
        if($(this).val()!='default'){
			res = obj.dhx_ajax('vas/sms/userManage_c/getGroupPrivileges/'+$('#addUsers > p select[name="userType"]').val() );
			if(res=='none') {
				obj.message_show('** Error: Unknown Group Privileges ','error');
				return;
			}
			else{
				res = JSON.parse(res);
				console.log(res);
				var ele = '<h2>Group Privileges </h2><div>';
				var objKeys = Object.keys(res);
				
				for(i=0; i<objKeys.length;i++){
					var subObj = null;
					ele = ele + '<div><h4>Privileges</h4><ul>' ;
					if(objKeys[i] == 'userPrivileges'){}
					else if(objKeys[i] == 'pushPrivileges'){	ele = ele + '<div><h4>PUSH Privileges</h4><ul>' ;}
					else if(objKeys[i] == 'pullPrivileges'){	ele = ele + '<div><h4>PULL Privileges</h4><ul>' ;}
					subObj = res[ objKeys[i] ] ;
					var keyNames = Object.keys(subObj);
					for(var j=0; j< keyNames.length; j++){
						ele = ele+'<li><input type="checkbox" value="'+keyNames[j]+'" name="test"/><span>'+keyNames[j]+' : </span><span>'+subObj[keyNames[j]]+'</span></li>';
					}
					
					ele = ele+'</ul></div>';
					
				}
				ele = ele+'</div>';
				$('#addUsers').append(ele);
			}
		}
		setTimeout(function(){  $('div#load').hide(); }, 900);
		
    */});
	/*$('body').on('click','#addUsers > div:last-child div ul li input',function(e){
		if($(this).val()== 'REMOTE_ROUTE'){
			if($(this).is(':checked')){
				$(this).parents('li').children('p').remove();
				$(this).parent('li').append('<p>Routing Url : <input type="url" name="url"/><p>[&nbsp;&nbsp; Shortcode Route : <input type="radio" name="routeType" value="shortcode" /> &nbsp;&nbsp;&nbsp; Keys Route : <input type="radio" name="routeType" value="key"/> &nbsp;&nbsp; ]</p> ');
			}
			else{
				$(this).parents('li').children('p').remove();
			}
		}
	});*/
	/*** form submit event ***/
	$('#addUsers + p input[type="submit"').click(function(e) {
		var submitArr = {};
		if($('select[name="userType"]').val()=='none') {
			obj.message_show('** Warning : Please Select User Type','error');	
			return;
		}
		else submitArr['group'] = $('select[name="userType"]').val();
		
		$('#addUsers >div:first-child table tr td input, #addUsers >div:first-child table tr td select').each(function(index) {
			submitArr[$(this).attr('name')] = $(this).val();
		});
		//submitArr['privilegesType'] = 'default';
       	/*if($('input[type="radio"][name="privilegesType"]:checked').val()==='default'){
			submitArr['privilegesType'] = 'default';
		   	if(res=='exist'){
				submitArr['routeType'] = $('#addUsers > div:last-child input[type="radio"][name="routeType"]:checked').val(); 
				submitArr['url'] = $('#addUsers > div:last-child input[type="url"][name="url"]').val(); 
				submitArr['route'] = 'exist';
			} 
	   	}
		else if($('input[type="radio"][name="privilegesType"]:checked').val()==='custom'){
			submitArr['privilegesType'] = 'custom';
			var priv =[];
			$('#addUsers >div:last-child div ul li input[type="checkbox"]:checked').each(function(index) {
				if($(this).val() == 'REMOTE_ROUTE'){
					submitArr['routeType'] = $('#addUsers > div:last-child input[type="radio"][name="routeType"]:checked').val(); 
					submitArr['url'] = $('#addUsers > div:last-child input[type="url"][name="url"]').val(); 
				}
				priv.push($(this).val());
			});
			submitArr['privileges'] = priv.toString();
		}
		else{
			obj.message_show('** Warning : Please select privileges','error');
			return;
		}*/
		//var objKeys = Object.keys(submitArr);
		/*if($.inArray('routeType',objKeys) !== -1 && submitArr['routeType']== undefined){
			obj.message_show('** Warning : Please select REMOTE_ROUTE privileges Type','error');
			return;
		}*/
		/*if($.inArray('routeType',objKeys) !== -1 && submitArr['url']== '' ){
			obj.message_show('** Warning : Please Enter Routing Url','error');
			return;
		}*/
		var objKeys = Object.keys(submitArr);
		var arrSub = Array();
		for(i=0; i<objKeys.length; i++){
			arrSub.push(objKeys[i]+'='+submitArr[objKeys[i]])
		}
		arrSub = arrSub.join('&')
		
		var usr = obj.dhx_ajax('vas/sms/userManage_c/addUser',arrSub );
		if(usr=='sucess'){
			obj.message_show('User Added Sucessfully');
			obj.winTooble = true;
			obj.wind.window('newuser').close();
		}
		else{
			obj.message_show(usr );
		}
		
    });
})();
</script>
<style>
#addUsers input { font-size:12px;}
#addUsers select { font-size:12px; padding: 3px 25px;}
#addUsers { font-size:12px; padding:10px;}
#addUsers > h2{ margin-bottom:10px; color:blue;
}
#addUsers> div,#addUsers> div:last-child > div{ position:relative; border:1px solid #a4bed4; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; padding:10px;  
box-sizing: border-box; margin-bottom:15px;}
#addUsers> div h3, #addUsers> div:last-child > div h4{ position:absolute; top:-8px; left:15px; background-color:white; padding:0 10px; color:blue; }
#addUsers> div table tr td:first-child,#addUsers> div table tr td:nth-child(3){ width:116px;}
#addUsers> div table tr td{ padding:4px 5px;}
#addUsers> div table tr td input{ padding:3px 5px; width:160px; margin:3px; font-size:12px;}
#addUsers> p{ margin-bottom:15px; }
#addUsers> p select{ padding:3px 25px;}
#addUsers> p span { padding-left:15px; display:none;}

#addUsers + p{ text-align: right; }
#addUsers + p input{ margin-right:10px; font-size:12px;}

</style>





