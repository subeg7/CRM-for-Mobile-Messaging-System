<div id="verifyNumber">
    <p>Check the checkbox to EXCLUDE NUMBERS from send list and close window.</p>
    </br><p><span style="color:blue;">***</span> [ <span style="color:blue;">To search specific name or number press Ctrl + f and type name or number</span> ]</p>
    <div id="verifyNumberList">
    	<?php 
			foreach($numbers as $val){
				echo '<div><p data-val="'.$val[0].'"><span>'.$val[1].'</span><span>'.$val[2].'</span><span class="close"><input type="checkbox"/></span></p></div>';
			}
		?>
    </div>
</div>
<script type="text/javascript">
(function(){
	$('#verifyNumberList > div').each(function(index, element) {
		var pChild = $(this).children('p');
		if( $.inArray( pChild.data('val'),obj.excludeNumber) !=-1){
			pChild.children('span.close').children('input').attr('checked',true);
		}
		
	});
	$('#verifyNumberList').on('click','div p span:last-child input',function(e) {
		var val = $(this).parent('span').parent('p').data('val');
		if($(this).is(':checked')){
			if($.inArray(val,obj.excludeNumber) == -1){
				obj.excludeNumber.push(val);
			}
		}else{
			var indx = $.inArray(val,obj.excludeNumber);
			if(indx != -1){
				obj.excludeNumber.splice(indx, 1);
			}
		}
		
    });
	
}() );
</script>
<style>
#verifyNumber{ width:100%; height:100%; padding:10px 20px;
-webkit-box-sizing: border-box;
-moz-box-sizing: border-box; 
box-sizing: border-box; 
font-size:12px;
}
#verifyNumberList { width:100%; padding:10px; height:370px; margin-top:10px;
-webkit-box-sizing: border-box;
-moz-box-sizing: border-box; 
box-sizing: border-box; 
border:1px solid #a4bed4; overflow:auto; position:relative;
}
#verifyNumber > p{ text-align:left; font-size:11px; color:red; margin-top:0px;}
#verifyNumberList > div{ width:229px; border:1px solid #d1d1d1; position:relative; padding:6px 20px 6px 7px;
-webkit-box-sizing: border-box;
-moz-box-sizing: border-box; 
box-sizing: border-box; margin-bottom:5px;
font-size:11px; margin-left:10px; float:left;
}
#verifyNumberList > div span:nth-child(2){ margin-left:8px;}
#verifyNumberList > div span:last-child{ position:absolute; top:2px; right:3px;
}

</style>

