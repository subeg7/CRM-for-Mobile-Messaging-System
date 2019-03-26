<div id="uploadDb">
<p>Select Scheme : <select id="upDbScheme" ><option value="none">--Select--</option>
<?php
		if($schemes!=='none'){
			foreach($schemes as $row){
				echo '<option data-val="'.$row->scheme.'&'.$row->detail.'" value="'.$row->fld_int_id.'">'.$row->scheme_name.'</option>';
			}
		}
	?>
</select></p>
<p>Upload File( CSV [ MS-Dos ] ) <input type="file" id="fileUpDb"/></p>
<div id="upDbSchMsg"><h3>Scheme Sample Message</h3>
    <div>

    </div>
</div>
<div id="upDbGenMsg"><h3>Generated Sample Message</h3>
	<div>

    </div>
</div>
<div class="clearBoth" style="border:none; height:0; width:100%; padding:0; margin:0 0 10px 0;"></div>
<p><input class="button" type="button" id="upDbReset" value="Reset"/><input class="button" type="button" id="upDbSubmit" value="Submit"/></p>
</div>
<script type="text/javascript">
(function(){
	var worker=null;
	var procecss = true;;
	var tempUPdata = [];
	$('#upDbScheme').change(function(e) {
        var sche = $(this).children('option:selected').data('val');
		sche = sche.split('&');
		var msg = sche[1].split("\n");
		var ele = '';
		for(i =0 ; i <msg.length; i++){
			ele = ele + '<p>'+ msg[i] +'</p>';
		}
		$('#upDbSchMsg div').empty().append(ele);
    });// end of event

	$('#fileUpDb').change(function(e) {
		var fileContent = [];
		var firstId = null;
		$('#load').show();
		var ext = $(this).val().split(".").pop().toLowerCase();
		if($.inArray(ext, ['csv']) == -1) {
			obj.message_show('**Error : Invalid Upload file , Upload CSV Format','error');
			return false;
		}
		var file = e.target.files[0];
		if(file != undefined) {
			var reader = new FileReader();
			reader.readAsText(file);
			reader.onload = function(e) {
				var sche = $('#upDbScheme').children('option:selected').data('val');
				sche = ((sche.split('&'))[0]).split('_');
				var normSche = [];
				var fileLine= (e.target.result.split("\n"));
				var data = fileLine[0].replace(/\r/g,'').split(',');
				for(i=0; i < sche.length;i++){
					var tempData = sche[i].split('#');
					if( tempData[0].toLowerCase() !='custom'){
						tempData[1] = tempData[1].split(' ');
						for(j=0; j <tempData[1].length;j++){
							var indx = $.inArray(tempData[1][j].replace(/ /g,''),data);
							if(indx !=-1) tempData[1][j] = indx;
							else console.log('error found');// wrong data base file
						}
						tempData[1] = tempData[1].join(' ');
					}
					normSche.push(tempData.join('#'));
				}

				for(i=1; i< fileLine.length; i++){
					if(fileLine[i] !== undefined && fileLine[i].replace(/\r/g,'').replace(/ /g,'')!==''){
						fileLine[i] = fileLine[i].replace(/\r/g,'').split(',');
						var mesg = '';
						var identical=null;;
						for(j=0; j < normSche.length; j++){
							var temp = normSche[j].split('#');
							if(temp[0].toLowerCase() == 'partial'){
								var part = fileLine[i][parseInt(temp[1])].split(' ');
								mesg = (j == (normSche.length-2) ) ?mesg + part[0]: mesg + part[0] + "\r\n";
							}
							else if(temp[0].toLowerCase() == 'pair'){
								var pair = temp[1].split(' ');
								var tempMs = [];
								for(k=0; k < pair.length; k++){
									tempMs.push(fileLine[i][pair[k]]);
								}
								mesg = (j == (normSche.length-2) )?mesg + tempMs.join(' ').trim() : mesg + tempMs.join(' ').trim() + "\r\n";
							}
							else if(temp[0].toLowerCase() == 'custom'){
								mesg = (j == (normSche.length-2) )?mesg + temp[1].trim():mesg + temp[1].trim() + "\r\n";
							}
							else if(temp[0].toLowerCase() == 'single'){
								mesg =(j == (normSche.length-2) )?mesg +fileLine[i][temp[1]].trim():mesg +fileLine[i][temp[1]].trim() +"\r\n";
							}
							else if(temp[0].toLowerCase() == 'identity'){
								if(firstId==null){ firstId = fileLine[i][temp[1]].trim();
									console.log(firstId);
								}
								identical = fileLine[i][temp[1]].trim();
							}
						}
						if(identical !=null && identical!='' && identical!=' ') tempUPdata.push({ m:mesg.trim(),i:identical});
						else console.log('error');// some of the identity field is empty  i+1
						//console.log(tempUPdata); return;
						delete fileLine[i];
					}
				}
				var genMsgse = tempUPdata[0].m.split("\r\n");

				for(i=0; i < genMsgse.length; i++){
					genMsgse[i] = '<p>'+genMsgse[i]+'</p>'
				}

				$('#upDbGenMsg div').empty().append(genMsgse.join(''));
				setTimeout(function(){ $('#load').hide();}, 900);

			}// end of onload
		}// end of undefined


    });//end of event

	$('#upDbSubmit').click(function(e) {
		if(tempUPdata.length ==0){
			obj.message_show("Enter valid file for upload",'error');

			return;
		}

		var ele = "<div id='uploadInfo'><p>Uploading Data</p><p></p><p>0</p><i class='coloseUpDb'>x</></div>";
		$('#mainLoadidng').removeClass('displayOff').addClass('displayOn');

		$( "#mainLoadidng" ).animate({height: "100%"}, 300, function() {

			$(this).append(ele);
			$('#uploadInfo').animate({margin: "200px auto"}, 400,function(){
				if(typeof(Worker) !== "undefined") {

					var totalLength =tempUPdata.length;
					var j=0;
					var data_u = new Array();
					worker = new Worker("pull_c/pullView/uploadDb");
					for(i=0; i< totalLength; i++){
						if(procecss==true){
							data_u[j] = tempUPdata[i];
							j++;
							if( ((i+1) % 80) ===0){
								j=0;
								//var p_data = 'data='+JSON.stringify(data_u)+'&keyId='+obj.tree['uploadkeyTree'].getSelectedItemId();
								worker.postMessage({m:data_u,k:obj.tree['uploadkeyTree'].getSelectedItemId()});
								data_u = new Array();
							}
							else if(i == (totalLength-1)){
								/*var p_data = 'data='+JSON.stringify(data_u)+'&keyId='+obj.tree['uploadkeyTree'].getSelectedItemId();
								worker.postMessage(p_data );*/
								worker.postMessage({m:data_u,k:obj.tree['uploadkeyTree'].getSelectedItemId()});
								tempUPdata = [];
							}
						}
						else{
							return;
						}
					}



					worker.onmessage = function(event) {
						if(event.data === 'error'){
							worker.terminate();
							worker = null;
							$('#uploadInfo p:nth-child(3)').empty().append('!!! Sorry connection problem');
							return;
						}
						else if(event.data === 'complete'){
							$('#uploadInfo p:nth-child(2)').css('background','none').empty().append('Completed');
						}
						else{
							var numb = parseInt($('#uploadInfo p:nth-child(3)').text());
							$('#uploadInfo p:nth-child(3)').empty().append(numb+parseInt(event.data)+' out of '+ totalLength);
						}
						tempUPdata = [];
					};
				} else {
					obj.message_show("Sorry! Your brower doesn't support this feature.",'error');

				}
			});
		});


    });
	$('#mainLoadidng').on('click','div#uploadInfo i',function(e){
		if(worker!=null){
			procecss = false;
			worker.terminate();
			worker = null;
			tempUPdata = [];
			obj.wind.window('upload').close();
		}
		$('div#uploadInfo').animate({margin: "-1000px auto"}, 400,function(){
			$("#mainLoadidng").animate({height: "0%"}, 300, function() {
				$( "#mainLoadidng" ).empty();

			});
		});
	});
}());
</script>
<style>
#uploadDb{ font-size:12px; padding:10px;}
#uploadDb input,#uploadDb select{ padding:2px 10px; font-size:12px;}
#uploadDb > p{ color:#9f4040;}
#uploadDb > p:nth-child(2){ margin-top:10px;}
#uploadDb > p:last-child{ text-align:right;}
#uploadDb > p:last-child input:last-child{ margin-left:10px; }
#uploadDb > div{ border:1px solid #a4bed4; float:left; margin-top:15px; padding:10px; position:relative;

width:232px; height:220px;
}
#uploadDb > div div, #uploadDb > div{
	-webkit-box-sizing: border-box;
-moz-box-sizing: border-box;
box-sizing: border-box;
}
#uploadDb > div div{ overflow:auto;}
#uploadDb > div:nth-child(3){ margin-right:10px;}
#uploadDb > div h3{ position:absolute; top:-8px; left:15px; background-color:white; padding:3px 10px; color:blue;}
#upDbSchMsg p , #upDbGenMsg p{ margin-bottom:5px;}
#upDbSchMsg p:first-child, #upDbGenMsg p:first-child { margin-top:7px;}
#uploadInfo{ width:400px; height:200px;margin:-1000px auto; position:relative; background-color:white;}
#uploadInfo > i{ position:absolute; right:0; top:0;    width: 30px;
    height: 20px;
    text-align: center;
    font-family: Tahoma,Helvetica;
    background-color: rgba(128, 128, 128, 0.13);
    cursor: pointer;
}
#uploadInfo > i:hover{ color:white; background-color:red;
}
#uploadInfo p:first-child{ text-align:center; margin-top:50px; padding-top:50px; font-size:18px;}
#uploadInfo p:nth-child(2){ height:40px; background: transparent url("images/load/loading.gif") no-repeat scroll 80px 0px / 20px 20px;background-position: center;
text-align:center; font-size:16px; color:green;
margin-top: 10px;}
#uploadInfo p:nth-child(3){ text-align:center;}
#mainLoadidng{ z-index:1007;}
</style>
