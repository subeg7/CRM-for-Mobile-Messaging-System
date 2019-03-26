<form id="addTemplate" action="#" name="unicode">
	<table>
    	<tr>
        	<td>Message Title</td>
            <td> : <input autocomplete="off" name="name" type="text" /></td>
        </tr>
        <tr>
        	<td>Message Type</td>
            <td> : <select name="msgType"  id="eomMsgtype" >
                            <option value="1">Text</option>
                            <option value="2">Unicode</option>
                            <option value="3">Text Flash</option>
                            <option value="4">Unicode Flash</option>
                    </select>
            </td>
        </tr>
    	<tr>
        	<td>Message</br>Count : <span id="messageCount">0</span> ( <span id="letterCount">0</span> )</td>
            <td> : <textarea  name="nepbox" class="unicode" id="message"  value=""></textarea></td>
        </tr>

    </table>
    <p><input class="button" type="reset" value="Reset"/><input class="button" type="submit" name="submit" value="Submit"/></p>
</form>
<script type="text/javascript" language="javascript">
(function(){
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
$('#addTemplate').submit(function(e) {
    e.preventDefault();
	var res = obj.dhx_ajax('push_c/template/new',$(this).serialize() );
	if(res==='sucess'){
		obj.message_show('New Message has been added sucessfully');
		obj.grid['dhxDynFeild_t'].clearAndLoad('push_c/renderTemplate');
		$(this)[0].reset();
	}
	else{
		obj.message_show(res,'error');
	}

});
}());
</script>
<style>
#addTemplate textarea{ width:280px; height: 200px;}
#addTemplate,#addTemplate table tr td select { font-size:12px; }
#addTemplate table{ margin:0 auto; padding-top:15px;}
#addTemplate table tr td { padding:3px 5px;}
#addTemplate table tr td input,#addTemplate table tr td select{ padding:4px 6px; font-size:12px;}
#addTemplate p{ text-align:right; padding-right:24px; margin-top:10px;}
#addTemplate p input{ margin-left:5px; }
#addTemplate table tr td select{ margin-left:5px; width:277px;}
#addTemplate table tr td input{ margin-left:5px; width:264px;}
</style>
