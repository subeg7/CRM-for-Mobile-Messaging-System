<div id="assignShortcodeDetails">
	<div id="assignsDetails">
    	<p><input id="searchadd" placeholder="search addressbook" style="border:1px solid #a4bed4;width:200px; padding:3px; font-size:11px; margin-right:10px;"/><a href="#" onClick="return obj.treeSearch('assignDetailsTree');"><img src="images/load/searchadd.png" style="height:20px; vertical-align:bottom;"/></a></p>
        <div id="assignDetailsTree"></div>
    </div>
    <div id="keysDetails">
    	<p>Users Key Details</p>
        <div id="assignKeyDetails">
        	<ul>
            </ul>
        </div>
    </div>
</div>
<script type="text/javascript" >
(function(){
obj.dhx_tree({parent:'assignDetailsTree'});
obj.tree['assignDetailsTree'].parse('<?php echo $xml; ?>','xml');
obj.tree['assignDetailsTree'].attachEvent('onClick',function( id ){
			if( id== 'list' ) return;
			var selId = obj.getSelected('dhxDynFeild');
			if(selId == null) return;
			var res = obj.dhx_ajax('pull_c/getUserKeys/'+selId+'/'+id );
			$('#assignKeyDetails ul').empty();
			if(res!='none'){
				res = JSON.parse(res);
				var ele = '';
				for(i=0; i < res.length; i++){
					ele += '<li><span>'+res[i].keys_name +'</span><span>[ <span>'+ res[i].category.toUpperCase() +'</span> ]</span> </li>';
				}
				$('#assignKeyDetails ul').append(ele);
			}

		});
}());
</script>
<style>
#assignShortcodeDetails{
	font-size:12px;
	width:600px; height:490px;
	padding:10px;
	background-color:#eaeaea;
	-webkit-box-sizing: border-box; /* Safari/Chrome, other WebKit */
-moz-box-sizing: border-box;    /* Firefox, other Gecko */
box-sizing: border-box;
}
#keysDetails,#assignsDetails{ float:left;}
#assignsDetails{

	width:250px; height:435px;
	-webkit-box-sizing: border-box; /* Safari/Chrome, other WebKit */
-moz-box-sizing: border-box;    /* Firefox, other Gecko */
box-sizing: border-box;
overflow:auto;
}
#assignDetailsTree{
	border:1px solid #a4bed4;
	min-width:200px; min-height:400px;
	padding:10px;
	margin-top:6px;
	background-color:white;
	padding:10px;
	-webkit-box-sizing: border-box; /* Safari/Chrome, other WebKit */
-moz-box-sizing: border-box;    /* Firefox, other Gecko */
box-sizing: border-box;
}
#keysDetails{
position:relative;
overflow:auto;

    width: 300px;
    height: 423px;
    margin-left: 10px;
    margin-top: 5px;
}
#assignKeyDetails{
	border: 1px solid #a4bed4;
	margin-top:10px;
	min-width: 200px;
    min-height: 397px;
}
#assignKeyDetails ul li{ margin:5px; font-size:13px; width:130px; float:left; list-style:disc;}
#assignKeyDetails ul li:nth-child(odd){ margin-left:20px;}
#assignKeyDetails ul li span:last-child{ padding:0 10px;}
#assignKeyDetails ul li span:first-child{ color:#1d1da5;}
#assignKeyDetails ul li span:last-child span{ color:#b11b1b; }
</style>
