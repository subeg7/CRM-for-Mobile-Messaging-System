<div id="pvp">
	<div>
    	<p>Add Subkey </p>
        <p><input type="text" style="width:123px;" id="subkey"/></p>
        <p>Sucess Message</p>
        <p><textarea style="width:133px; font-size:12px;" id="sucess"></textarea></p>
        <p><input type="button" value="Add" id="addSubkey" class="button"/>
    </div>
    <div id="subkeyFeild" style="background-color:#f5f5f5;">
    	
    </div>
</div>
<script type="text/javascript">
(function(){
	var exist = false;
	$('#pvp #addSubkey').click(function(e) {
		var subkey =  $.trim($('#subkey').val());
		var exist = false;
		if(subkey.replace(/ /g,'')==''){
				obj.message_show('Please Enter Subkey','error'); return;
		}
		if(subkey.indexOf(' ') !== -1){
			obj.message_show('Subkey Contains Space Character','error');
			return;
		}
		subkey = subkey.replace(/ /g,'');
		$('#pvp #subkeyFeild div').each(function(index, element) {
			if($(this).children('p.subkey').text() == subkey){ 
				obj.message_show('Subkey Already Exist','error');
				exist = true;
			}
        });
		if($('#pvp #sucess').val().replace(/ /g,'')==''){
			obj.message_show('Please Enter Subkey Sucess Message','error');
			return;
		}
		if(exist== true) return;
		if( subkey!==''){
			var message = $('#sucess').val();
			$('#subkeyFeild').append('<div><p class="subkey">'+subkey+'</p><p class="message">'+message+'</p><p class="close">x</p></div>');
			$('#subkey').val('');
			$('#pvp #sucess').val('');
		}
		
	});
	$('#pvp #subkeyFeild').on('click','p.close',function(e) {
		$(this).parent('div').remove();
	});
}());


</script>
<style>
#pvp{
	width:100%; height:100%; 
}
#pvp >  div { float:left; background-color:white; }
#pvp >  div:first-child{ width:150px;}
#pvp >  div:first-child p{ margin-top:7px;}
#pvp >  div:first-child p:first-child{ margin-top:5px;}
#pvp >  div:first-child p:nth-child(5){ text-align:right; padding:0 14px 0 0;}
#pvp >  div:last-child{ width:330px;
-webkit-box-sizing: border-box;
-moz-box-sizing: border-box; 
box-sizing: border-box; padding:10px; border: 1px solid #a4bed4; height:230px; overflow:auto;
}
#pvp #addSubkey{ font-size:11px; -webkit-box-sizing: border-box;
-moz-box-sizing: border-box; 
box-sizing: border-box; }
#subkeyFeild p.close{
position:absolute;right: 3px;
top: 5px;
background-color: #9b2424;
color: white;
padding: 2px 6px 4px 7px; cursor:pointer;
	
	 }
#subkeyFeild p.close:hover{ background-color:red; color:white;}
#subkeyFeild > div{ position:relative;padding: 5px 25px 5px 5px !important;border: 1px solid #d1d1d1; float:left; width:100%;  
-webkit-box-sizing: border-box;
-moz-box-sizing: border-box; 
box-sizing: border-box;
margin-top:6px;
background-color:white;
transition-property:all;
    transition-duration: .500s;
    transition-timing-function: ease;
}
#subkeyFeild > div:hover{
	box-shadow: 3px 3px 5px #888888;
}
#subkeyFeild > div > p { float:left;}
/*#subkeyFeild > p:nth-child(even){ margin-left:10px;}*/
#subkeyFeild > div > p:nth-child(1){ margin-right:5px;}
#subkeyFeild > div > p:nth-child(1),#subkeyFeild > div p:nth-child(2){
	border: 1px solid #d1d1d1; padding:2px 5px;
}
.subkey{ color:#961a1a;}
</style>