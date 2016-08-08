/* Custom JavaScript */
$(document).ready(function($) {
	
	/*---------- For Placeholder on IE9 and below -------------*/
	$('input, textarea').placeholder();
	
	/*----------- For icon rotation on input box foxus -------------------*/ 	
	$('.input-field').focus(function() {
  		$('.page-icon img').addClass('rotate-icon');
	});
	
	/*----------- For icon rotation on input box blur -------------------*/ 	
	$('.input-field').blur(function() {
  		$('.page-icon img').removeClass('rotate-icon');
	});
	
	
	/* for demo only */
	function getparameter(){
		var themename = window.location.href.split('?');  
		var tid = themename[1];
		if(tid === undefined){
			 tid = 'login-theme-1';
		}
		 
		$('link#fordemo').replaceWith('<link rel="stylesheet"  id="fordemo" href="css/' 
                + tid+'.css" type="text/css" />');  
       $('a').each(function() { 
       		 var link = $(this).attr('href') + '?' +tid ;
       		 $(this).attr('href',link);
       });
       
       var k = tid.split('login-theme-');
       var thnumber = Number(k[1]); 
       $('.th'+thnumber).addClass('active');
       if(thnumber>8 && thnumber<15 || thnumber==7){      	 
        	$('.login-logo a img').attr('src','img/login-logo2.png');
       }  
	} 
	$('a.demo-btn').click(function(e) {
		 e.preventDefault(); 
		 var themeid =$(this).attr('data-demoid');
		 var themename = window.location.href.split('?'); 
		 window.location.href =   themename[0]+'?'+themeid ;
		 
	});
        
        $("#btnlogin").click(function(e) {	
            e.preventDefault();  
            
            crear_ajax('frmPrincipal','../pro_session');
        });
        
	getparameter();
	
});



function crear_ajax(form_id,script_pro){
	
	jQuery.ajax({
		type: "POST",
		url: script_pro,
		dataType: "json", 
		error: function(objeto, quepaso, otroobj) {                             
                    jQuery("#mensaje-box").removeClass('hide');
                    jQuery("#mensaje-text").html(objeto + ', ' + quepaso + ', ' +  otroobj);
		},
		data: jQuery('#'+form_id).serialize(),
		success: function(datos){
			
                        
                    if (datos.isok == 1 && datos.redirect.length > 0){
                        window.location.href = "../../"+datos.redirect; 	
                    }
                    else{
                        jQuery("#mensaje-box").removeClass('hide');
                        jQuery("#mensaje-text").html(datos.respuesta);
                    }
		},
		 timeout: 10000
	});
	// fin jquery.ajax
}

