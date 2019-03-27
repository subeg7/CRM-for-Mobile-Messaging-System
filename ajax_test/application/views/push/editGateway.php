<form id="editGateway" action="#">
	<table>
    	<tr>
        	<td>Gateway Name :</td>
            <td><input autocomplete="off" disabled name="gname" type="text" class="editDisabled" data-val="<?php echo ucwords($gateway[0]->fld_char_gw_name); ?>" value="<?php echo ucwords($gateway[0]->fld_char_gwsdfsdf_name); ?>" /><span class="edit">Edit..</span></td>
        </tr>
    	<tr>
        	<td>Host Name :</td>
            <td><input autocomplete="off" disabled name="host" type="url" class="editDisabled" data-val="<?php echo ucwords($gateway[0]->fld_char_hostname); ?>" value="<?php echo ucwords($gateway[0]->fld_char_hostname); ?>"  /><span class="edit">Edit..</span></td>
        </tr>
        <tr>
        	<td>Port :</td>
            <td><input autocomplete="off" disabled name="port" type="text" class="editDisabled" data-val="<?php echo ucwords($gateway[0]->fld_int_port); ?>" value="<?php echo ucwords($gateway[0]->fld_int_port); ?>"  /><span class="edit">Edit..</span></td>
        </tr>
        <tr>
        	<td>SMSCID :</td>
            <td><input autocomplete="off" disabled name="smsc" type="text" class="editDisabled" data-val="<?php echo ucwords($gateway[0]->fld_chr_smscid); ?>" value="<?php echo ucwords($gateway[0]->fld_chr_smscid); ?>"  /><span class="edit">Edit..</span></td>
        </tr>
        
        <tr>
        	<td>Username :</td>
            <td><input autocomplete="off" disabled name="username" type="text" class="editDisabled" data-val="<?php echo ucwords($gateway[0]->fld_char_username); ?>" value="<?php echo ucwords($gateway[0]->fld_char_username); ?>"  /><span class="edit">Edit..</span></td>
        </tr>
        <tr>
        	<td>Password :</td>
            <td><input autocomplete="off" disabled name="password" type="text" class="editDisabled" data-val="<?php echo ucwords($gateway[0]->fld_char_password); ?>" value="<?php echo ucwords($gateway[0]->fld_char_password); ?>"  /><span class="edit">Edit..</span></td>
        </tr>
        <tr>
        	<td>Priority :</td>
            <td><select disabled name="priority" class="editDisabled" data-val="<?php echo ($gateway[0]->fld_int_priority); ?>"  >											
            	<option value="2" <?php  if($gateway[0]->fld_int_priority==2) echo 'selected="selected"'; ?> >Normal</option>
            	<option value="1" <?php  if($gateway[0]->fld_int_priority==1) echo 'selected="selected"'; ?>>Default</option>
            </select><span class="edit">Edit..</span></td>
        </tr>
        
        <tr>
        	<td>Operator :</td>
            <td><select disabled id="operator" name="operator"class="editDisabled" data-val="<?php echo ($gateway[0]->fld_chr_operator); ?>"   >
            <option value="none">--Select--</option>
            <?php 
				foreach($operator as $row){
					echo '<option value="'.$row->fld_int_id.'">'.strtoupper($row->acronym).'</option>';
				}
			?>
            </select><span class="edit">Edit..</span></td>
        </tr>
    </table>
    <p><input class="button" type="reset" value="Reset"/><input class="button" type="submit" name="submit" value="Submit"/></p>
</form>
<script type="text/javascript" language="javascript">
(function(){
$('.edit').on('click',function(e){
	if($(this).siblings().is('input'))
		$(this).siblings('input').removeAttr('disabled').focus();
	else if($(this).siblings().is('select'))
		$(this).siblings('select').removeAttr('disabled').focus();
});
$('.editDisabled').on('focusout',function(e){
	
	if( $(this).val().toString().toLowerCase()==$(this).data('val').toString().toLowerCase() ){
		$(this).attr('disabled','disabled');
	} 
});
$('#editGateway').submit(function(e) {
    e.preventDefault();
	var id = obj.getSelected('dhxDynFeild');
	if(id==null) return;
	var res = obj.dhx_ajax('push_c/gateway/edit/'+id,$(this).serialize() );
	if(res==='sucess'){
		obj.message_show('New Gateway has been updated sucessfully');
		obj.winTooble = true;
		obj.wind.window('gateway').close();
	}
	else{
		obj.message_show(res,'error');
	}
	
});
}());
</script>
<style>
.edit{ margin-left:10px;}
#editGateway,#editGateway table tr td select { font-size:12px;}
#editGateway table{ margin:0 auto; padding-top:15px;}
#editGateway table tr td { padding:3px 5px;}
#editGateway table tr td input,#editGateway table tr td select{ padding:4px 6px; font-size:12px; width:148px;}
#editGateway p{ text-align:right; padding-right:24px; margin-top:10px;}
#editGateway p input{ margin-left:5px;}
</style>
