<div id="addScheme">
	<form id="addSchForm">
	<div>
    	<h3>Generate Scheme </h3>
        <p>Upload File <span style="color:red;">( CSV [ MS-Dos ] or TXT )</span> : &nbsp;&nbsp; <input type="file" id="schmUpload"/></p>
        <p>Add Custom Data : <input type="text" id="customData"/><input style="margin-left:15px;"type="button"value="Add" class="button"/></p>
        <div id="headerField" ondrop="drop(event)" ondragover="allowDrop(event)" >
        	<div><div class="moveSelect"></div>
            <p draggable="true" ondragstart="drag(event)" id="ele_1">dearfrend1<input style="vertical-align:middle;" type="checkbox" class="schChk"/></p>
             <p draggable="true" ondragstart="drag(event)" id="ele_2">dearfrend2<input style="vertical-align:middle;" type="checkbox" class="schChk"/></p>
              <p draggable="true" ondragstart="drag(event)" id="ele_3">dearfrend3<input style="vertical-align:middle;" type="checkbox" class="schChk"/></p>
               <p draggable="true" ondragstart="drag(event)" id="ele_4">dearfrend<input style="vertical-align:middle;" type="checkbox" class="schChk"/></p>
               <span class="scheClose">x</span>
            </div>

        </div>
        <div id="ButtonFeild">
        	<input type="button" id="schePair" value="Pair" class="button"/>
            <input type="button" value="Partial" class="button"/>
            <input type="button" id="schRemove" value="Remove" class="button"/>
            <input type="button" value="Generate" style="position:absolute; bottom:0px; left:0;" class="schbutton"/>
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
    <p><input class="button" type="reset" value="Reset"/><input class="button" type="submit" value="Submit" /></p>
	</form>
</div>
<script type="text/javascript">

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
	console.log(document.getElementById(data));


	if(ids[0]=='ele'){
		console.log("#"+ids.join('_'));
		$( document.getElementById(data) ).insertBefore( "#"+ids.join('_') );
		return;
	}
    ev.target.appendChild(document.getElementById(data));

	$('#headerField >div').each(function(index, element) {
        if($(this).children('p').length== 0) $(this).remove();
    });
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
			var fileLine= (e.target.result.split("\n"))[0].replace(/\r/g,'');
			fileLine = fileLine.split(',');
			var ele = '';
			for(i=0; i<fileLine.length; i++){
				ele = ele+'<p draggable="true" ondragstart="drag(event)" id="ele_'+i+'">'+fileLine[i]+'<input style="vertical-align:middle;" type="checkbox" class="schChk"/></p>';
			}
			$('#headerField').empty().append(ele);

			console.log(fileLine);

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
$('#schePair').click(function(e) {
	console.log('sdf');
    $('#headerField p').each(function(index, element) {
		var divLen = $('#headerField div').length+1;
		$('#headerField').append('<div id="div_'+divLen+'"><div class="moveSelect"></div></div>');
        if($(this).children('input').is(':checked')){
			$('#headerField div#div_'+divLen).append($(this));
		}
    });
});
$('#headerField').on('mouseenter','div.moveSelect',function(e){
	$(this).parent('div').attr('draggable','true').attr('ondragstart',"drag(event)");;
	//draggable="true" ondragstart="drag(event)"
});
$('#headerField').on('mouseout','div.moveSelect',function(e){
	$(this).parent('div').removeAttr('draggable').removeAttr('ondragstart',"drag(event)");;
	//draggable="true" ondragstart="drag(event)"
});
$('#headerField').on('click','div span.scheClose',function(e){
	$(this).parent('div').children('p').each(function(index, element) {
        $('#headerField').append($(this));
    });
	$(this).parent('div').remove();
	//draggable="true" ondragstart="drag(event)"
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
#headerField p, #headerField div{ float:left; margin:5px 5px 5px 0; }
#headerField >div{ padding:0 25px 0 22px; border:1px solid #d1d1d1; position:relative; }
#headerField >div div{ width:20px; height:100%; margin:0px; padding:0; background-color:blue; position:absolute; left:0; cursor:move;}

#headerField div p input{ display:none;  }
#headerField p{ padding:1px 8px; border:1px solid #d1d1d1; cursor:move;}
#identityFeild p input{ display:none;}
#identityFeild p{ border:1px solid #d1d1d1;  padding: 2px 10px;
margin: 2px 10px;}
#headerField > div > p{ padding:3px 6px; margin:3px 0 3px 5px;}
.scheClose{ border:1px solid #d1d1d1; color:#d1d1d1;position: absolute;
top: 3px;
right: 3px;
padding: 0px 3px 2px;}
.scheClose:hover{ background-color:red; color:white; cursor:pointer;}
</style>
