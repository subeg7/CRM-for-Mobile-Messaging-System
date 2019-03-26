
var response = false;
var state = 'continue';
onmessage = function(e) {
	if(response==false){
		var p_data = 'data='+JSON.stringify(e.data.m)+'&keyId='+e.data.k;
		send_requst('../../pull_c/uploadDb',p_data);
	}
	else {
		postMessage(e);
		postMessage('error');
		return;
	}
	/*var d = e.data.m;
	var j=0;
	var data_u = new Array();
	for(var i=0; i < d.length; i++){
		data_u[j] = d[i];
		j++;
		if( ((i+1) % 80) ===0){
			j=0;
			var p_data = 'data='+JSON.stringify(data_u)+'&keyId='+e.data.k;
			if(response==false)send_requst('../../pull_c/uploadDb',p_data);
			else {
				postMessage('error');
				return;
			}
			data_u = new Array();
		}
		else if(i == (d.length-1)){
			var p_data = 'data='+JSON.stringify(data_u)+'&keyId='+e.data.k;
			send_requst('../../pull_c/uploadDb',p_data);
		}
		
		delete d[i];
	}
	postMessage('complete');*/
	
}
function send_requst(url,parameter){
	var xhr = new XMLHttpRequest();
	xhr.open('POST',url,false);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.onreadystatechange =function(){
			if(xhr.readyState===4){
				if((xhr.status >=200 && xhr.status<300) || xhr.status==304){
					if(xhr.responseText == 'error'){
						response = true;
						postMessage('error');
					}
					else postMessage(xhr.responseText);
				}
				else{
					postMessage('error');
				}
			} 
	}
	xhr.send(parameter);
}
