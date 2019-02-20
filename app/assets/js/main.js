
(function () {
	/*** 4 top right header button list animation *******/
	$('#headerMenu > li').on('click',function(e){
		var ths = $(this);
		var ele = "<div class='headerMenu'><header><h2 style='background-image:url(vas/sms/images/load/"+ths.text()+"header.png);'>"+ths.text()+"</h2><i>x</i></header><section id='headerSection'></section></div>";
		if(ths.text()!='logout'){
			$('#mainLoadidng').removeClass('displayOff').addClass('displayOn');
			$( "#mainLoadidng" ).animate({height: "100%"}, 300, function() {
				$(this).append(ele);
				$('div.headerMenu').animate({margin: "60px auto"}, 400,function(){
					obj.load_ext_file('#headerSection','vas/sms/common_c/load_View/'+ths.text());
				});
			});
		}
		else if(ths.text()=='logout'){
			if(obj.dhx_ajax('vas/sms/auth/logout')==true){
				window.location= obj.l_loc;
			}
		}
	});
	/*** close button animation *******/
	$('body').on('click','div.headerMenu header i',function(e){
		$('div.headerMenu').animate({margin: "-1000px auto"}, 400,function(){
			$("#mainLoadidng").animate({height: "0%"}, 300, function() {
				$( "#mainLoadidng" ).empty();
			});
		});
	});
	/*** checks event target is inside ribbon element or not *******/
	$('body').on('click',function(e){
		 var target = $(e.target); 
		if (target.parents('div#ribbon').length) {
			if($('div#ribbon').height()==27){
				$('div#ribbon').animate({ height: "117px" });
			}
    	}
		else{
			if($('div#ribbon').height()==117 && $('div#ribbonState').hasClass('ribbonUp')){
				$('div#ribbon').animate({ height: "27px"});
			}
		}
	});
	/*** checks the state of the ribbon and slides according to its state*******/
	$('div#ribbonState').on('click',function(e){
		if($(this).hasClass('ribbonUp')){
			$(this).removeClass('ribbonUp').addClass('ribbonDown');
			if($('div#ribbon').height()==27){
				$('div#ribbon').animate({ height: "117px" });
			}
		}
		else if($(this).hasClass('ribbonDown')){
			$(this).removeClass('ribbonDown').addClass('ribbonUp');
			if($('div#ribbon').height()==117){
				$('div#ribbon').animate({ height: "27px" });
			}
			
		}
		
	});

	/*** home click *******/
	$('#mainHeader div >div >div img').on('click',function(e){
		obj.showItem_toolBar('toolbar');
		$('div.dhxrb_big_button').removeClass('selButton');
		obj.load_ext_file('#dhxDynFeild','vas/sms/common_c/load_View/home');		
	});
	/**************Help close button***********/
	$('body').on('click','span.closeHelp',function(e){
		$(this).parent('p').parent('span').slideUp(200);
	});
	
	$('body').on('click','span.allhelp img',function(){
		$(this).siblings('span').slideDown(200);
	});
})();








