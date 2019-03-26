<style>
.displayinline{ display:inline;}
#settings{ width:780px; height:450px; margin:0 auto; overflow:auto; font-size:12px;
-webkit-box-sizing: border-box; /* Safari/Chrome, other WebKit */
-moz-box-sizing: border-box;    /* Firefox, other Gecko */
box-sizing: border-box; 
border-bottom: 1px solid #a4bed4
}
#settings > div{ margin-top:15px; position:relative;  width:100%; height:auto; border:1px solid red;
-webkit-box-sizing: border-box; /* Safari/Chrome, other WebKit */
-moz-box-sizing: border-box;    /* Firefox, other Gecko */
box-sizing: border-box; 
border: 1px solid #a4bed4; padding:10px;
}
#settings > div h2{ color:blue; position:absolute; top:-8px; padding:2px 10px; left:30px; background-color:white;}
#settings > div > div{
	-webkit-box-sizing: border-box; /* Safari/Chrome, other WebKit */
-moz-box-sizing: border-box;    /* Firefox, other Gecko */
box-sizing: border-box; 
padding:5px;
border: 1px solid #a4bed4; margin-bottom:10px;
}
#settings > div > div:nth-child(2){ margin-top:10px;}
#settings > div > div p{ margin-bottom:5px;}
#settings > div > div p span{ color:#9c1010;}
#settings > div > div table tr td{ padding:4px 10px;}
#settings > div > div table tr td span.edit{ color:blue; padding-right:10px; cursor:pointer;}
#settings > div > div table tr td:first-child{ text-align:right; color:#9c1010;}
#confpass,#passwrd{ padding:2px 7px; width:130px;}
#settings > div > div table tr td span:last-child{ text-transform:capitalize;}
</style>
<div id="settings">
	<?php if($admin=='admin'){ ?>
	<div>
    	<h2>System Settings</h2>
        <div>
        	<p><input data-val='enable_login' class="systemOption" type="radio" <?php if($sys_option['login']==1) echo 'checked="true"';?> name="system"/><span>Enable login system - </span>( Disable login and logout the loged in Users)</p>
        	<p><input data-val='disable_login' class="systemOption" type="radio" name="system"  <?php if($sys_option['login']==0) echo 'checked="true"';?>/><span>Temporarly Disable login system - </span>( Disable login and logout the loged in Users)</p>
        </div>
        <div>
        	<p><input id="enablQue" data-val='enable_que' class="systemOption" type="radio" name="que"<?php if($sys_option['que_job']==1) echo 'checked="true"';?>/><span>Enable Que job - </span>( Start Fetch que job and send sms)</p>
        	<p><input id="disablQue" data-val='disable_que' class="systemOption"type="radio" name="que"<?php if($sys_option['que_job']==0) echo 'checked="true"';?>/><span>Disabled Que job - </span>( Takes Request in que but stop sending sms)</p>
        </div>
        <div>
        	<p><input data-val='enable_push' class="systemOption"type="radio" name="api"<?php if($sys_option['push_api']==1) echo 'checked="true"';?>/><span>Enable All API PUSH Reqest -</span>( Direactly sends sms from Accepted Reqeusts  )</p>
        	<p><input data-val='disable_push' class="systemOption" type="radio" name="api"<?php if($sys_option['push_api']==0) echo 'checked="true"';?>/><span>Disabled All API PUSH Reqest - </span>( All accepted request is Stored in que job but doesn't send SMS Directly )</p>
        </div>
    </div>
    <script type="text/javascript">
    	(function(){
			$('.systemOption').click(function(e) {
				var res = obj.dhx_ajax('vas/sms/sysManage_c/systemSettings/'+$(this).data('val'));
				if(res =='sucess'){
					obj.message_show('System Settings Updated sucessfullys');
					if( $(this).data('val') =='disable_push' ){
						document.getElementById('disablQue').checked = true;
					}
					/*else if( $(this).data('val') =='enable_push' ){
						$('#enablQue').attr('checked',true);
					}*/
				}
				else obj.message_show(res,'error');
			});
		}());
    </script>
    <?php }?>
	<div>
    	<h2>Account/Personal Settings & Information</h2>
        <div>
            <table>
            	<tr>
                    <td>Customer ID :</td>
                    <td><span contenteditable="false"><?php echo ucwords($data[0]->fld_transaction_id); ?></span> </td>
                </tr>
                <tr>
                    <td>Username :</td>
                    <td><span contenteditable="false" data-val="username" style="text-transform:none !important;"><?php echo $data[0]->username; ?></span></td>
                </tr>
                <tr>
                    <td>Password :</td>
                    <td><span class="edit" id="password">change</span> <span class="displayOff">New Password&nbsp;&nbsp;<input autocomplete="off" id="passwrd" type="password" name="pasw"/> &nbsp;&nbsp;&nbsp;Conform Password&nbsp;&nbsp;<input autocomplete="off" id="confpass" type="password" name="conpass"/>&nbsp;&nbsp;<input id="passsub" type="button" value="Submit" style="padding:3px 7px; vertical-align:top;" class="button"/></span></td>
                </tr>
               
            </table>
        </div>
        <div>
            <table>
                <tr>
                    <td>Balance Type :</td>
                    <td><?php echo $data[0]->fld_balance_type; ?></td>
                </tr>
                <?php 
					if($operator!='none'){
					//	var_dump($operator);
						foreach($operator as $row){
							$bal = ($balance=="none")?"------":((isset($balance[$row->fld_int_id]))?$balance[$row->fld_int_id]:'------');
							echo '<tr><td>'.strtoupper($row->acronym).' :</td><td>'.$bal.'</td></tr>';
						}
					}
					if(in_array('USER_MANAGE',$priv)){
						if($balance!=="none" && isset($balance['appbal'])){
							echo '<tr><td>Application Balance :</td><td>'.$balance['appbal'].'</td></tr>';
						}
						else{
							echo '<tr><td>Application Balance :</td><td>0</td></tr>';
						}
						echo '<tr><td>Total Users :</td><td>'.$usercount.'</td></tr>';
					}
				?>
               
            </table>
        </div>
        <div>
            <table>
                <tr>
                    <td>Organization Name :</td>
                    <td><span contenteditable="false"><?php echo ucwords($data[0]->company); ?></span> </td>
                </tr>
                <tr>
                    <td>Organization Phone No. :</td>
                    <td><span class="edit" data-eval="<?php echo $data[0]->phone; ?>">[ Edit ]</span><span contenteditable="false" data-val="org_phone" class="contentEdit"><?php echo $data[0]->phone; ?></span></td>
                </tr>
                <tr>
                    <td>Contact Person :</td>
                    <td><span class="edit" data-eval="<?php echo strtolower($data[0]->contact_person); ?>">[ Edit ]</span><span contenteditable="false" data-val="contact_person" class="contentEdit"> <?php echo $data[0]->contact_person; ?></span></td>
                </tr>
                <tr>
                    <td>Contact Person Mobile No. :</td>
                    <td><span class="edit" data-eval="<?php echo strtolower($data[0]->contact_number); ?>">[ Edit ]</span><span contenteditable="false" data-val="contact_phone" class="contentEdit"><?php echo $data[0]->contact_number; ?></span></td>
                </tr>
                <tr>
                    <td>E-mail :</td>
                    <td><span class="edit" data-eval="<?php echo strtolower($data[0]->email); ?>">[ Edit ]</span><span contenteditable="false" data-val="email" style="text-transform:none;" class="contentEdit"><?php echo $data[0]->email; ?></span></td>
                </tr>
                <tr>
                    <td>Address :</td>
                    <td><span class="edit" data-eval="<?php echo strtolower($data[0]->address); ?>">[ Edit ]</span><span contenteditable="false" data-val="address"  class="contentEdit"><?php echo $data[0]->address; ?></span></td>
                </tr>
            </table>
        </div>
        
    </div>

</div>
<script type="text/javascript">
(function(){
	var enterKey = false;

var realVal= null;
$('#settings > div > div table tr td span.edit').click(function(e) {
	if($(this).text()!='change') realVal = $(this).data('eval').toString();
	if($(this).text()=='change'){
		//if($(this).siblings('span')
		
		if($(this).siblings('span').hasClass('displayOff')){
			$(this).siblings('span').removeClass('displayOff').addClass('displayinline');
		}
		else{
			$(this).siblings('span').removeClass('displayinline').addClass('displayOff');
		}
		//$(this).siblings('span').css('display','inline');
	}else{
    	$(this).siblings('span').attr('contenteditable',true).focus().css('border','1px solid #a4bed4').css('padding','0px 4px');
		
		
	}
});
$(".contentEdit").on('keydown',function(e){
	if( e.keyCode== 13 ){
		enterKey=true;
		var txt = $(this).text().toString();

		$(this).attr('contenteditable',false).css('border','none');
		
		if(realVal == null || txt.replace(/\s+/, "") ==''){
			$(this).text(realVal);
			return ;
		}
		
		if(realVal.toLowerCase().replace(/\s/g, "")   == txt.toLowerCase().replace(/\s/g, "")  ) { 
			return ;
		}
		var res = obj.dhx_ajax('vas/sms/common_c/changeSetings/'+$(this).data('val')+'/'+encodeURIComponent(txt) );
		if(res==='sucess'){
			obj.message_show($(this).parent('td').siblings('td').text()+' Sucessfully updated');
		}
		else{
			$(this).empty().text(realVal);
			obj.message_show(res,'error');
			
		}
	}
	
});
$('#settings > div > div table tr td span:last-child').on('focusout',function(e){
	if(enterKey==true){
		enterKey=false; return;
	}
	if($(this).siblings('span').text()=='change') return;
	
	var txt = $(this).text().toString();

	$(this).attr('contenteditable',false).css('border','none');
	
	if(realVal == null || txt.replace(/\s+/, "") ==''){
		$(this).text(realVal);
		return ;
	}
	
	if(realVal.toLowerCase().replace(/\s/g, "")   == txt.toLowerCase().replace(/\s/g, "")  ) { 
		return ;
	}
	var res = obj.dhx_ajax('vas/sms/common_c/changeSetings/'+$(this).data('val')+'/'+encodeURIComponent(txt) );
	if(res==='sucess'){
		obj.message_show($(this).parent('td').siblings('td').text()+' Sucessfully updated');
	}
	else{
		$(this).empty().text(realVal);
		obj.message_show(res,'error');
		
	}
});
$('#passsub').click(function(e) {
	console.log('sdf');
	var arr = [];
	if($('#passwrd').val().replace(/\s/g, "") == '') return;	
	if($('#confpass').val().replace(/\s/g, "") == '')return;

	arr.push('password='+$('#passwrd').val().replace(/\s/g, ""));
	arr.push('conpassword='+$('#confpass').val().replace(/\s/g, ""));
	
    var res = obj.dhx_ajax('vas/sms/userManage_c/resetPasswordIndi',arr.join('&') );
	if(res == 'sucess'){
		$('#passwrd').val('');
		$('#conpassword').val('');
		obj.message_show('Password Sucessfully Changed');
	}
	else{
		
		obj.message_show(res,'error');
	}
});
			
}());
</script>













