<div id="addScheme">
	<form id="addSchForm">
	<div>
    	<h3>Generate Scheme </h3>
        <p>Upload File <span style="color:red;">( CSV [ MS-Dos ] or TXT )</span> : &nbsp;&nbsp; <input type="file" id="schmUpload"/></p>
        <p>Add Custom Data : <input type="text" id="customData"/><input id="addCustom" style="margin-left:15px;"type="button"value="Add" class="button"/></p>
        <div id="headerField" ondrop="drop(event)" ondragover="allowDrop(event)" >            
        </div>
        <div id="ButtonFeild">
        	<input type="button" id="schesingle" value="Single" class="button"/>
        	<input type="button" id="schePair" value="Pair" class="button"/>
            <input type="button" id="schepartial"value="Partial" class="button"/>
            <input type="button" id="schRemove" value="Remove" class="button"/>
            <input type="button" id="schGen" value="Generate" style="position:absolute; bottom:0px; left:0;" class="schbutton"/>
        </div>
        <div id="schemeFeild">
        	<table>
            	<tr><td>Identity Field :</td><td><div ondrop="drop(event)" ondragover="allowDrop(event)" id="identityFeild">
                </div></td></tr>
            </table>
            <div id="schemeFormat" ondrop="drop(event)" ondragover="allowDrop(event)">
            	
            </div>
        </div>
        <div class="clearBoth" style="height:0;"></div>
        <p style="margin-top:10px;">Scheme Name : <input style="width:164px;" type="text" id="schName" required/></p>
        
    </div>
    <p><input id="schReset" class="button" type="reset" value="Reset"/><input class="button" type="submit" value="Submit" /></p>
	</form>
</div>
<script type="text/javascript">
var genData = {};
var data = [];
var genScheme = [];
var testMessage = '';
function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    ev.dataTransfer.setData("id", ev.target.id);
}

function drop(ev) {
    ev.preventDefault();
    var data = ev.dataTransfer.getData("id");
	var ids = ev.target.id.split('_');
	var child_id = data.split('_');
	
	if((ids[0]=='ele' && child_id[0]=='ele') || (ids[0]=='div' && child_id[0]=='div') ||  (ids[0]=='mouse' && child_id[0]=='div') || (ids[0]=='ele' && child_id[0]=='div') ){
		var beforeInsetEle = '';
		if(ids[0] == 'mouse'){
			beforeInsetEle = "#"+$("#"+ids.join('_')).parent('div').attr('id');
		}
		else if(ids[0] == 'ele' && child_id[0] =='div'){
			var parent = ($("#"+ids.join('_')).parent('div').attr('id')).split('_');
			if(parent[0]=='div') beforeInsetEle = "#"+$("#"+ids.join('_')).parent('div').attr('id');
		}
		else{
			var parent = ($("#"+ids.join('_')).parent('div').attr('id')).split('_');
			var parentChild = ($("#"+child_id.join('_')).parent('div').attr('id')).split('_');
			if(parent.join('_') !== parentChild.join('_')) return;
			if( (parent[0]!='div' && parentChild[0]!='div') || (parent[0]=='div' && parentChild[0]=='div'))beforeInsetEle ="#"+ids.join('_');
			
			else{return;}
		}
		$( document.getElementById(data) ).insertBefore( beforeInsetEle );
		return;
	}
	else if( (child_id[0]!='ele' && ids[0]!='mouse') && (child_id[0]=='div' && ids[0]!='ele')) {
		 ev.target.appendChild(document.getElementById(data));
	}
	else if(child_id[0]=='ele' && ids[0]=='identityFeild') {
		ev.target.appendChild(document.getElementById(data));
	}
   
	
	$('#headerField >div').each(function(index, element) {
        if($(this).children('p').length== 0) $(this).remove();
    });
}
function conformCalback(res){
	console.log(res);
}
$('#schmUpload').change(function(e) {
    
	var fileContent = [];
	
	var ext = $(this).val().split(".").pop().toLowerCase();	
	if($.inArray(ext, ['csv','text']) == -1) {
		obj.message_show('**Error : Invalid Upload file , Upload CSV Format','error');
		return false;
	}		
	var file = e.target.files[0];
	if(file != undefined) {	
		var reader = new FileReader();			
		reader.readAsText(file);
		reader.onload = function(e) {
			var fileLine= (e.target.result.split("\n"));
			data = fileLine[1].replace(/\r/g,'').split(',');
			var dataFeildIndex =[];
			for(i=1; i< fileLine.length; i++){
				temp = fileLine[i].replace(/\r/g,'').split(',');
				for(j=0;j < temp.length; j++){
					if(temp[j].replace(/ /g,'')!=''){ 
						if($.inArray(j,dataFeildIndex)==-1){ dataFeildIndex.push(j);}
					}
				}
			}
			fileLine = fileLine[0].replace(/\r/g,'');
			fileLine = fileLine.split(',');
			var ele = '';
			for(i=0; i<fileLine.length; i++){
				if($.inArray(i,dataFeildIndex)!=-1){
					ele = ele+'<p draggable="true" ondragstart="drag(event)" id="ele_'+i+'">'+fileLine[i]+'<input style="vertical-align:middle;" type="checkbox" class="schChk"/></p>';
					genData[fileLine[i]] = i;
				}
			}
			$('#headerField').empty().append(ele);
		}
	}
});
$('#schRemove').click(function(e) {
    $('#headerField p').each(function(index, element) {
        if($(this).children('input').is(':checked')){
			$(this).remove();
		}
    });
});

$('#schePair,#schepartial,#addCustom,#schesingle').click(function(e) {
	var className = ($(this).attr('id')=='schePair')?'moveSelect':($(this).attr('id')=='schepartial')?'moveSelectpartial':($(this).attr('id')=='addCustom')?'customSch':'singleSch'; 
	var divLen = ($('#headerField div').length+1) +($('#schemeFormat div').length+1) ;
	var dataVal = ($(this).attr('id')=='schePair')?'pair':($(this).attr('id')=='schepartial')?'partial':($(this).attr('id')=='addCustom')?'custom':'single'; 
	var divEle = '<div data-type = "'+dataVal+'" id="div_'+divLen+'"><div id="mouse_'+divLen+'" class="'+className+'"></div><span class="scheClose">x</span>';
	var childEle = '';
	var check = 0;
	var removeEle = [];
	if($(this).attr('id') != 'addCustom'){
			
		$('#headerField p').each(function(index, element) {
			if($(this).children('input').is(':checked')){
				
				check++;
				childEle = childEle+'<p draggable="true" ondragstart="drag(event)" id="'+$(this).attr('id')+'">'+$(this).text()+'<input style="vertical-align:middle;" type="checkbox" class="schChk"/></p>';
				removeEle.push($(this).attr('id'));
			}
		});
		divEle = divEle+childEle +'</div>';
		if( (check >1 && $(this).attr('id')=='schePair') || (check ==1 && $(this).attr('id')=='schepartial')|| (check ==1 && $(this).attr('id')=='schesingle') ){
			for(i=0; i< removeEle.length; i++){
				$('#'+removeEle[i]).remove();
			}
			$('#headerField').prepend(divEle);
		}
	}else{
		divEle = divEle+'<p draggable="true" ondragstart="drag(event)" id="ele_'+( parseInt(divLen)+1)+'">'+$('#customData').val()+'<input style="vertical-align:middle;" type="checkbox" class="schChk"/></p></div>';
		$('#headerField').prepend(divEle);
	}
	
		
});
$('#headerField,#schemeFormat').on('mouseenter','div.moveSelect,div.moveSelectpartial,div.customSch,div.singleSch',function(e){
	$(this).parent('div').attr('draggable','true').attr('ondragstart',"drag(event)");;
});
$('#headerField,#schemeFormat').on('mouseout','div.moveSelect,div.moveSelectpartial,div.customSch,div.singleSchs',function(e){
	$(this).parent('div').removeAttr('draggable').removeAttr('ondragstart',"drag(event)");
});
$('#headerField').on('click','div span.scheClose',function(e){
	$(this).parent('div').children('p').each(function(index, element) {
        $('#headerField').prepend($(this));
    });
	$(this).parent('div').remove();
});
$('#schGen').click(function(e) {
	
	if($('#schemeFormat div').length==0){
		obj.message_show('** There is nothing to generate','error');
		return;
	}
	else{
		genScheme = [];
		$('#schemeFormat div').each(function(index, element) {
            if($(this).data('type')=='partial'){
				genScheme.push('partial#'+$(this).children('p').text());
			}
			else if($(this).data('type')=='pair'){
				var pairCol = '';
				$(this).children('p').each(function(index, element) {
                    pairCol = (pairCol=='')?pairCol + $(this).text():pairCol +' '+ $(this).text();
                });
				genScheme.push('pair#'+pairCol);
			}
			else if($(this).data('type')=='custom'){
				genScheme.push('custom#'+$(this).children('p').text());
			}
			else if($(this).data('type')=='single'){
				genScheme.push('single#'+$(this).children('p').text());
			}
			
        });
		if($('#identityFeild p').length ==1) genScheme.push('identity#'+$('#identityFeild p').text());
		else{
			obj.message_show('** Alert : Insert Identity Field First','error');
			return;
		}
		testMessage = '';
		for(i=0;i<genScheme.length; i++){
			var dataS = genScheme[i].split('#');
			if(dataS[0] == 'pair'){
				var dataP = dataS[1].split(' ');
				var msgPar = '';
				for(j=0; j <dataP.length; j++){
					msgPar = (msgPar=='')?data[genData[dataP[j]]]: msgPar+' '+data[genData[dataP[j]]];
				}
				testMessage = (testMessage=='')?msgPar: testMessage+msgPar;
			}
			if(dataS[0] == 'partial'){
				var dataP = data[genData[dataS[1]]].split(' ');
				
				testMessage = (testMessage=='')?dataP[0]: testMessage+dataP[0];
			}
			if(dataS[0] == 'custom'){
				testMessage = (testMessage=='')?dataS[1]: testMessage+dataS[1];
			}
			if(dataS[0] == 'single'){
				testMessage = (testMessage=='')?data[genData[dataS[1]]]: testMessage+data[genData[dataS[1]]];
			}
			if(i!=(genScheme.length-1))testMessage = testMessage + "\r\n"
			
			
			
		}
		obj.message_show('<p>Generated Message:</p><textarea style="width:250px; height:200px;" readonly="readonly">'+testMessage+'</textarea>','confirm',conformCalback)
		
		
	}
});
$('#addSchForm').submit(function(e) {
	e.preventDefault();
    if(genScheme.length==0){
		obj.message_show('** Alert : Generate Message First and Submit','error');
		return;
	}
	var arr =[];
	arr.push('name='+$('#schName').val() );
	arr.push('scheme='+genScheme.join('_') );
	arr.push('details='+testMessage);
	var res = obj.dhx_ajax('vas/sms/pull_c/scheme',arr.join('&') );
	if(res==='sucess'){
		obj.message_show('New Scheme has been added sucessfully');
		obj.grid['scheme_t'].clearAndLoad('vas/sms/pull_c/renderScheme?object=grid');
		$('#headerField').empty();
		$('#schemeFormat').empty();
		$('#identityFeild').empty();
		$('#SchemeDetail > div').empty();
		$(this)[0].reset();
	}
	else{
		obj.message_show(res,'error');
	}
	genScheme =[];
	testMessage = ''
});
$('#schReset').click(function(e) {
    $('#headerField,#identityFeild,#schemeFormat').empty();
	//$('#headerField').empty();
});
</script>
<style>
.schbutton{ padding:5px 17px; border:none; background-color:#bc1c10; color:white; cursor:pointer; font-size:11px;}
.schbutton:hover{ background-color:#0078d7;}
#addScheme{ height:100%; width:100%; padding:10px;
-webkit-box-sizing: border-box;
-moz-box-sizing: border-box; 
box-sizing: border-box;
}
#addScheme ,#addScheme input{ font-size:11px;}
#addScheme input { padding:4px 10px;}
#addScheme form > div{ border:1px solid #a4bed4; padding:10px;
width:100%; height:100%;
-webkit-box-sizing: border-box;
-moz-box-sizing: border-box; 
box-sizing: border-box;
position:relative;
}
#addScheme form > p{ text-align:right; margin-top:10px;}
#addScheme form > p input:last-child{ margin-left:10px;}
#addScheme form > div >p:nth-child(3){ margin-top:10px;}
#addScheme form > div h3{ position:absolute; top:-8px; left:15px; padding:2px 10px; background-color:white; color:blue;}
#headerField,#ButtonFeild,#schemeFeild{ float:left;height:260px;  
-webkit-box-sizing: border-box;
-moz-box-sizing: border-box; 
box-sizing: border-box;
position:relative;
 margin-top:10px;
}
#ButtonFeild{
	width:90px;; 
}
#schemeFormat{-webkit-box-sizing: border-box;
-moz-box-sizing: border-box; 
box-sizing: border-box; position:relative; padding: 0 10px 10px 10px;}
#schemeFeild,#ButtonFeild{ margin-left:10px;}
#headerField,#schemeFeild{
	width:266px; 
	border:1px solid #a4bed4; 
}
#headerField{ overflow:auto; padding:0px 10px 10px 10px;}
#schemeFeild{ border:none; padding:0px;}
#identityFeild{ width:182px; height:20px; border:1px solid #a4bed4; margin-left:10px;}
#schemeFormat{ width:100%; height:230px; margin-top:7px; border:1px solid #a4bed4; overflow:auto;}
#ButtonFeild > input{ width:100%; margin-bottom:5px;}
#headerField p, #headerField div, #schemeFormat  div, #schemeFormat p{ float:left; margin:5px 5px 0px 0px; }
#schemeFormat  div{ float:none; overflow:auto;}
#headerField >div,#schemeFormat > div{ padding:0 25px 0 22px; border:1px solid #d1d1d1; position:relative; }
#headerField >div div,#schemeFormat > div div{ width:20px; height:100%; margin:0px; padding:0; background-color:blue; position:absolute; left:0; cursor:move;}
#schemeFormat > div span{ display:none;}
#headerField div p input,#schemeFormat div p input{ display:none;  }
#headerField p, #schemeFormat p{ padding:1px 8px; border:1px solid #d1d1d1; cursor:move;}
#identityFeild p input{ display:none;}
#identityFeild p{ border:1px solid #d1d1d1;  padding: 2px 10px;
margin: 2px 10px; cursor:move;}
#headerField > div > p,#schemeFormat > div>p{ padding:3px 6px; margin:3px 0 3px 5px;}
.scheClose{ /*border:1px solid #d1d1d1;*/ background-color:#d1d1d1; color:white;position: absolute;
top: 3px;
right: 3px;
padding: 0px 3px 2px;}
.scheClose:hover{ background-color:red; color:white; cursor:pointer;}
.moveSelectpartial{ background-color:red !important;}
.customSch{ background-color:green !important;}
.singleSch{ background-color:orange !important;}
</style>