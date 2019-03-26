<div id="ptp">
	<div>
    	<p>Add Subkey </p>
        <p><input style="width:123px;" type="text" id="subkey"/></p>
        <p>Select AddressBook </p>
        <p><select id="addresbook">
        	<option value="none">--Select--</option>
            <?php 
				if($addressbook!=='none'){
					foreach($addressbook as $row){
						echo '<option value="'.$row->fld_int_id.'">'.$row->fld_chr_name.'</option>';
					}
				}
			?>
        </select></p>
        <p>Select Template </p>
        <p><select id="template">
        	<option value="none">--Select--</option>
            <?php 
				if($template!=='none'){
					foreach($template as $row){
						echo '<option value="'.$row->fld_int_id.'">'.$row->fld_chr_title.'</option>';
					}
				}
			?>
        </select></p>
        <p>Sucess Message </p>
        <p><textarea style="width:133px; font-size:12px;" id="sucess"></textarea></p>
        <p><input type="button" value="Add" id="addSubkey" class="button"/>
    </div>
    <div id="subkeyFeild" style="background-color: #f5f5f5;">
    	
    </div>
</div>
<script type="text/javascript">
(function(){

	$('#ptp #addSubkey').click(function(e) {
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
		$('#ptp #subkeyFeild div').each(function(index, element) {
			if($(this).children('p.subKey').text() == subkey){ 
				obj.message_show('Subkey Already Exist','error');
				exist = true;
			}
        });
		if(exist== true) return;
		
		if($('#ptp #addresbook').val()=='none'){
			obj.message_show('Please Select Addressbook','error');
			return;
		}
		var addbook = $('#ptp #addresbook').children('option:selected').text();
		var addbookid = $('#ptp #addresbook').val();
		if($('#ptp #template').val()=='none'){
			obj.message_show('Please Select Template','error');
			return;
		}
		var template = $('#ptp #template').children('option:selected').text();
		var templateid = $('#ptp #template').val();
		if($('#ptp #sucess').val().replace(/ /g,'')==''){
			obj.message_show('Please Enter Subkey Sucess Message','error');
			return;
		}
		var subMessage = $('#ptp #sucess').val();
		if( subkey!==''){
			$('#subkeyFeild').append('<div><p class="subKey">'+subkey+'</p><p class="addbok" data-val="'+addbookid+'">'+addbook+'</p><p class="temp" data-val="'+templateid+'">'+template+'</p><p class="message">'+subMessage+'</p><p class="close">x</p></div>');
			$('#subkey').val('');
			$('#ptp #template').val('none');
			$('#ptp #addresbook').val('none');
			$('#ptp #sucess').val('');
		}
		
	});
	$('#ptp #subkeyFeild').on('click','p.close',function(e) {
		$(this).parent('div').remove();
	});
}());


</script>
<style>
#ptp{
	width:100%; height:100%; 
}
#ptp input, #ptp select, #ptp textarea{ font-size:11px;}
#ptp >  div { float:left; }
#ptp >  div:first-child{ width:150px; font-size:11px;}
#ptp >  div:first-child p{ margin-top:7px;}
#ptp >  div:first-child p:first-child{ margin-top:5px;}
#ptp >  div:first-child select{ width:136px;}
#ptp >  div:first-child p:nth-child(9){ text-align:right; padding:0 14px 0 0;}
#ptp >  div:last-child{ width:330px;
-webkit-box-sizing: border-box;
-moz-box-sizing: border-box; 
box-sizing: border-box; padding:10px; border: 1px solid #a4bed4; height:250px; overflow:auto;
}
#ptp #addSubkey{ font-size:11px;}
#subkeyFeild p.close{
position:absolute;right: 3px;
top: 4px;
background-color: #d1d1d1;
color: white;
padding:  0px 5px 2px 5px; cursor:pointer;
background-color: #9b2424;
	
	 }
#subkeyFeild p.close:hover{ background-color:red; color:white;}
#subkeyFeild > div{ position:relative;padding: 4px 25px 4px 5px !important;border: 1px solid #d1d1d1; 
-webkit-box-sizing: border-box; background-color:white;
-moz-box-sizing: border-box; 
box-sizing: border-box;
 float: left;
 width:100%;
 transition-property:all;
    transition-duration: .500s;
    transition-timing-function: ease;
}
#subkeyFeild > div:hover{
	box-shadow: 3px 3px 5px #888888;
}
#subkeyFeild > div >p{ float:left; padding:2px 5px !important; }
#subkeyFeild > div:first-child{ margin-top:0;}
#subkeyFeild > div:nth-child(n+1){margin-top:5px; }
#subkeyFeild > div p{ padding:1px 5px; border:1px solid #d1d1d1; margin-right:5px; font-size:11px;}
.subKey{ color:#961a1a;}
.addbok{ color:blue;}
.temp{ color:green;}
</style>




