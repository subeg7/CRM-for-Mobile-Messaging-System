<div id="uploadAddress">

<form id="uploadAddressform"  action="#">
	<h2>Upload File</h2>
	<table>
    	<tr>
            <td><input  name="uptype" type="radio" value="numbers" /> : Numbers Only<span class="allhelp"><span><p><span class="closeHelp">x</span>a browney fox quickly jumps over the lazy dog. a browney fox quickly jumps over the lazy dog.a browney fox quickly jumps over the lazy dog.a browney fox quickly jumps over the lazy dog. a browney fox quickly jumps over the lazy dog.a browney fox quickly jumps over the lazy dog.a browney fox quickly jumps over the lazy dog. a browney fox quickly jumps over the lazy dog.a browney fox quickly jumps over the lazy dog.</p></span><img src="images/load/allhelp.png"></span></td>
            <td><input type="radio" value="both" name="uptype"/> : Numbers & Name<span class="allhelp"><span><p><span class="closeHelp">x</span>a browney fox quickly jumps over the lazy dog. a browney fox quickly jumps over the lazy dog.a browney fox quickly jumps over the lazy dog.</p></span><img src="images/load/allhelp.png"></span></td>

        </tr>
    </table>
   	<p><input name="file_up" id="uploadContact" type="file" /></p>
    <p id="message_up"></p>

</form>
 <p><input class="button" form="uploadAddressform" type="reset" value="Reset"/><input id="up_sub" class="button" type="submit" name="submit" value="Submit"/></p>
</div>
<script type="text/javascript" language="javascript">
(function(){
var fileContent = [];
$('#uploadContact').change(function(e) {
	fileContent = [];
    var type = $('input[name="uptype"]:checked').val();
	if(type== undefined){
		obj.message_show('**Warning : Select upload Type','error');
		$(this).val('');
	}
	else{
		var ext = $(this).val().split(".").pop().toLowerCase();
		if($.inArray(ext, ['csv','txt']) == -1) {
			obj.message_show('**Error : Invalid Upload file , Upload CSV Format','error');
			return false;
		}
		var file = e.target.files[0];
		if(file != undefined) {
			var reader = new FileReader();
			reader.readAsText(file);
			reader.onload = function(e) {
				var fileLine=e.target.result.split("\n");
				for( i = 0; i<fileLine.length; i++){
					var lineText = (type == 'numbers')?fileLine[i].replace(/\D/g,''):fileLine[i].split(',');
					console.log(JSON.stringify(lineText));
					if(lineText!='' && lineText!=' '){
						if(type == 'both'){
							if(fileLine[i].replace(/ /g,'')!==''){
								if(lineText.length != 2 ){
									obj.message_show('**Error : Invalid Upload Data Format','error');
									$('#uploadAddress form')[0].reset();
									return;
								}
							}
						}
						else if(/^\d+$/.test(lineText.replace(/\r/g,'')) == false){
							obj.message_show('**Error : Invalid Character found in file','error');
							$('#uploadAddress form')[0].reset();
							return;
						}
						fileContent.push(fileLine[i].replace(/\r/g,''));
					}
				}
			}
		}
	}

});
$('#up_sub').click(function(e) {

	if(fileContent.length != 0) fileContent = fileContent.join('_');
	else{
		obj.message_show('** Warning : No data found for upload','error');
		return;
	}
	var res = obj.dhx_ajax('push_c/uploadAddress/'+obj.tree['addressbookTree'].getSelectedItemId(),'data='+fileContent+'&type='+$('input[name="uptype"]:checked').val());
	if(res==='sucess'){
		obj.grid['addressGrid_t'].clearAndLoad( "push_c/renderContact/"+obj.tree['addressbookTree'].getSelectedItemId() );
		obj.wind.window('upload').close();

	}
	else{
		obj.message_show('** Warning : error found in numbers but correct numbers has been updloaded','error');
		obj.grid['addressGrid_t'].clearAndLoad( "push_c/renderContact/"+obj.tree['addressbookTree'].getSelectedItemId() );
		res = res.split('_');
		console.log(res);
		$('#uploadAddress').empty();
		for(i =0; i <res.length; i++){
			var arr = res[i].split(':');
			if(arr[0] == 'invalid'){
				$('#uploadAddress').append('</br><p style="color:red;"> Invalid Number fount : </p></br><p>'+arr[1]+'</p><p></p>');
			}
			else if(arr[0] == 'exist'){
				$('#uploadAddress').append('</br><p style="color:red;">Following number Already Exist in Addressbook : </p></br><p>'+arr[1]+'</p><p></p>');
			}
			else if(arr[0] == 'similar'){
				$('#uploadAddress').append('</br><p style="color:red;"> Similar Number found in file : </p></br><p>'+arr[1]+'</p><p></p>');
			}
			else if(arr[0] == 'error'){
				obj.message_show(arr[1],'error');
			}
		}



	}

});
}());
</script>
<style>
#uploadAddress { width:100%; height:100%; padding:10px; -webkit-box-sizing: border-box;
-moz-box-sizing: border-box;
box-sizing: border-box; font-size:12px; overflow:auto;}
#uploadAddress form { border: 1px solid #a4bed4;position:relative; padding:10px;
-webkit-box-sizing: border-box;
-moz-box-sizing: border-box;
box-sizing: border-box;  height:140px; font-size:12px;  }
#uploadAddress> form  h2 { font-size:12px; position:absolute; top:-8px; left:15px; padding:2px 10px; background-color:white;
color:blue;
}
#uploadAddress> form table tr td{ padding:3px 5px;}
#uploadAddress> form p { margin-top:10px; }
#uploadAddress>  p:last-child { text-align:right;}
#uploadAddress> p:last-child input{ margin:10px 0 0 10px;}
#uploadAddress form table tr td span.allhelp > span{ padding:0px !important;}
#message_up{ font-size:10px; color: red;}
</style>
