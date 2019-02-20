<form id="addGateway" action="#">
	<table>
    	<tr>
        	<td>Gateway Name :</td>
            <td><input autocomplete="off" name="gname" type="text" /></td>
        </tr>
    	<tr>
        	<td>Host Name :</td>
            <td><input autocomplete="off" name="host" type="url" /></td>
        </tr>
        <tr>
        	<td>Port :</td>
            <td><input autocomplete="off" name="port" type="text" /></td>
        </tr>
        <tr>
        	<td>SMSCID :</td>
            <td><input autocomplete="off" name="smsc" type="text" /></td>
        </tr>
        
        <tr>
        	<td>Username :</td>
            <td><input autocomplete="off" name="username" type="text" /></td>
        </tr>
        <tr>
        	<td>Password :</td>
            <td><input autocomplete="off" name="password" type="text" /></td>
        </tr>
        <tr>
        	<td>Priority :</td>
            <td><select name="priority"><option value="2">Normal</option><option value="1">Default</option></select></td>
        </tr>
        <tr>
        	<td>Country :</td>
            <td><select id="country" name="country"><option value="none">--Select--</option>
            <?php 
				foreach($country as $row){
					echo '<option value="'.$row->fld_int_id.'">'.strtoupper($row->fld_chr_acro).'</option>';
				}
			?>
            </select></td>
        </tr>
        <tr>
        	<td>Operator :</td>
            <td><select id="operator" name="operator"><option value="none">--Select--</option></select></td>
        </tr>
    </table>
    <p><input class="button" type="reset" value="Reset"/><input class="button" type="submit" name="submit" value="Submit"/></p>
</form>
<script type="text/javascript" language="javascript">
$('#country').change(function(e) {
	var res = obj.dhx_ajax('vas/sms/sysManage_c/getOperatorBycountry/'+$(this).val() );
	$('#operator').empty().append('<option value="none">--Select--</option>');
	if(res=='none'){
		obj.message_show('No Operator found for this country');
		return;
	}
	res = JSON.parse(res);
	var ele ='';
	for(i=0; i < res.length; i++){
		ele = ele + '<option value="'+res[i].fld_int_id+'">'+(res[i].acronym).toUpperCase()+'</option>'
	}
    $('#operator').append(ele);
});
$('#addGateway').submit(function(e) {
    e.preventDefault();
	var res = obj.dhx_ajax('vas/sms/push_c/gateway/new',$(this).serialize() );
	if(res==='sucess'){
		obj.message_show('New Gateway has been added sucessfully');
		obj.grid['dhxDynFeild_t'].clearAndLoad('vas/sms/push_c/renderGateway');
		$(this)[0].reset();
	}
	else{
		obj.message_show(res,'error');
	}
	
});

</script>
<style>
#addGateway,#addGateway table tr td select { font-size:12px;}
#addGateway table{ margin:0 auto; padding-top:15px;}
#addGateway table tr td { padding:3px 5px;}
#addGateway table tr td input,#addGateway table tr td select{ padding:4px 6px; font-size:12px; width:148px;}
#addGateway p{ text-align:right; padding-right:24px; margin-top:10px;}
#addGateway p input{ margin-left:5px;}
</style>