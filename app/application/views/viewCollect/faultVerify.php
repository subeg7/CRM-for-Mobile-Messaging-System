<div id="faultVerify">
	<div id="faultnumbers"></div>
</div>

<script type="text/javascript">
(function(){
	
	if(obj.verifiedNumber != null){
		var arr_tab = [];
		var keys = Object.keys(obj.verifiedNumber);
		for(i=0; i < Object.keys(keys).length; i++){
			arr_tab.push(keys[i].toUpperCase());
		}
		var res = obj.create_dhx_tabar({ id:'faultnumbers',tab_text:arr_tab});
		for(i=0;i<res.length; i++){
			$('#'+res[i]).css('font-size','12px').css('padding','10px');
			$('#'+res[i]).append(obj.verifiedNumber[keys[i]].join(', '));
		}
		
	}
	
}());
</script>
<style>
#faultVerify{ padding:5px 10px; height:420px; overflow:auto;}
#faultnumbers{ height:420px; overflow:auto;}

/*#faultnumbers { height:100%; width:100%; overflow:auto;}*/
</style>