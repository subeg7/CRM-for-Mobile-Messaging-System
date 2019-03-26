<form id="editTemplate" action="#" name="unicode">
	<table>
    	<tr>
        	<td>Template Name</td>
            <td> : <input  class="editDisabled" disabled data-val="<?php echo $template[0]->fld_chr_title; ?>" value="<?php echo $template[0]->fld_chr_title; ?>" autocomplete="off" name="name" type="text" /><span class="edit">Edit..</span></td>
        </tr>
        <tr>
        	<td>Message Type </td>
            <td> : <select disabled name="msgType"  id="eomMsgtype" >
                            <option <?php if($template[0]->fld_int_mst==1) echo 'selected="selected"' ?> value="1">Text</option>
                            <option <?php if($template[0]->fld_int_mst==2) echo 'selected="selected"' ?> value="2">Unicode</option>
                            <option <?php if($template[0]->fld_int_mst==3) echo 'selected="selected"' ?> value="3">Text Flash</option>
                            <option <?php if($template[0]->fld_int_mst==4) echo 'selected="selected"' ?> value="4">Unicode Flash</option>
                    </select>
            </td>
        </tr>
    	<tr>
        	<td>Message</br>Count : <span id="messageCount">0</span> ( <span id="letterCount">0</span> )</td>
            <td> : <textarea  <?php if($template[0]->fld_int_mst==2 || $template[0]->fld_int_mst==4) echo "onkeyup =conversion();" ?> class="editDisabled" disabled data-val="<?php echo $template[0]->fld_chr_msg; ?>" value="<?php echo $template[0]->fld_chr_msg; ?>"  name="nepbox" class="unicode" id="message"  value=""><?php echo $template[0]->fld_chr_msg; ?></textarea><span class="edit">Edit..</span></td>
        </tr>

    </table>
    <p><input class="button" type="reset" value="Reset"/><input class="button" type="submit" name="submit" value="Submit"/></p>
</form>
<script type="text/javascript" language="javascript">
(function(){

$('.edit').on('click',function(e){
	if($(this).siblings().is('input'))
		$(this).siblings('input').removeAttr('disabled').focus();
	else if($(this).siblings().is('textarea')){
		$(this).siblings('textarea').removeAttr('disabled').focus();
		$('#eomMsgtype').removeAttr('disabled');
	}
});
$('.editDisabled').on('focusout',function(e){

	if( $(this).val().toString().toLowerCase()==$(this).data('val').toString().toLowerCase() ){
		$(this).attr('disabled','disabled');
		if($(this).is('textarea')){
			$('#eomMsgtype').attr('disabled','disabled');
		}
	}


});


var msgState=false;
$('#eomMsgtype').change(function(e) {
	$('#messageCount').empty().append('0');
	$('#letterCount').empty().append('0');
    if($(this).val()==1 || $(this).val()==3){
		$('#message').val('').removeAttr('onkeyup');
	}
	else if($(this).val()==2 || $(this).val()==4){
		$('#message').val('').attr('onkeyup',"conversion();");
	}
});
$('#message').on('keyup',function(e){
	var msg_length  = obj.count_msg($(this).val(),$('#eomMsgtype').val());
	$('#messageCount').empty().append(msg_length.messageLength);
	$('#letterCount').empty().append(msg_length.charLength);
	var charCode = e.keyCode || e.which;
	if(charCode.toString() !== '8' && charCode.toString() !== '46'){
		var msg_length  = obj.count_msg($(this).val(),$('#eomMsgtype').val());
		if( parseInt(msg_length.messageLength) > 8) {
			obj.message_show("** Sorry , We can't send more than 8 message ",'error');
			msgState = true;
		}else{ msgState= false;}
	}
	else{
		 msgState= false;
	}
});
$("#message").bind('paste', function(e) {
    if(msgState == true) e.preventDefault();
});
$('#message').on('keypress',function(e){
	if(msgState == true) e.preventDefault();
});
$('#editTemplate').submit(function(e) {
    e.preventDefault();
	var selId = obj.getSelected('dhxDynFeild');
	if(selId == null) return;
	var res = obj.dhx_ajax('push_c/template/edit/'+selId,$(this).serialize() );
	if(res==='sucess'){
		obj.message_show('Template has been Edited sucessfully');
		obj.winTooble = true;
		obj.wind.window('template').close();
	}
	else{
		obj.message_show(res,'error');
	}

});
}());
</script>
<style>
#editTemplate textarea{ width:280px; height: 200px; font-size:12px;}
#editTemplate,#editTemplate table tr td select { font-size:12px; }
#editTemplate table{ margin:0 auto; padding-top:15px;}
#editTemplate table tr td { padding:3px 5px;}
#editTemplate table tr td input,#editTemplate table tr td select{ padding:4px 6px; font-size:12px;}
#editTemplate p{ text-align:right; padding-right:24px; margin-top:10px;}
#editTemplate p input{ margin-left:5px; }
#editTemplate table tr td select{ margin-left:5px; width:277px;}
#editTemplate table tr td input{ margin-left:5px; width:264px;}
#editTemplate table tr td span.edit{ margin-left:10px;}
</style>
