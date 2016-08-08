$.qubitAjax = function(url,options){
    debug = {};
	debug.windowSource = 'about:blank';
	debug.workId = 'qubit_ajax_work'+ new Date().getTime();
	debug.windowID = 'qubit_ajax_debug_' + debug.workId;
	debug.windowStyle = 
			'width=800,' +
			'height=600,' +
			'scrollbars=yes,' +
			'resizable=yes,' +
			'status=yes';

	debug.debugTemplate = 
			'<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">' +
			'<html><head>' +
			'<title>Qubit ajax debug output</title>' +
			'<style type="text/css">' +
			'/* <![CDATA[ */' +
			'.debugEntry { margin: 3px; padding: 3px; border-top: 1px solid #999999; } ' +
			'.debugDate { font-weight: bold; margin: 2px; } ' +
			'.debugText { margin: 2px; } ' +
			'.warningText { margin: 2px; font-weight: bold; } ' +
			'.errorText {margin: 2px; font-weight: bold; color: #ff7777; }' +
			'/* ]]> */' +
			'</style>' +
			'</head><body>' +
			'<h2>Qubit ajax debug output</h2>' +
			'<div id="debugTag"></div>' +
			'</body></html>';

    var defaults = {
    	type 		: 'POST', 
    	data 		: {},
    	dataType	: 'json',
    	timeout 	: 100000,
    	contentType	: 'application/x-www-form-urlencoded; charset=utf-8',
    	onSucess 	: function() {},
    	onError 	: function() {},
    	onComplete	: function() {},
    	debug		: false,
    },settings = $.extend({},defaults, options);
    
    $("#load-qubit-ajax").remove();
    $("<div class='loader load-container hide navbar-fixed-top' id='load-qubit-ajax'><div class='load-message'><img src='images/loader.gif'> Cargando...</div></div>").appendTo('body');
    $("#load-qubit-ajax").fadeIn();

	$.ajax(  {                  
		type 		: settings.type,  
		dataType 	: settings.dataType, 
		contentType : settings.contentType,
		timeout 	: settings.timeout,
		url 		: url, 
        data 		: settings.data,
		success 	: _onSucess,  
		error 		: _onError,  
		complete 	: _onComplete
	});
	
	function _onComplete(requestData, exito){

		$("#load-qubit-ajax").remove();

		// onComplete add callBack function
	    if (typeof settings.onComplete === "function") {
	        settings.onComplete();
	    }  	
	}
	function _onError(request, quepaso, otroobj){

		$("#load-qubit-ajax").remove();

		if (settings.debug){
			debug_writeMessage(request.responseText);
		}

		// onError add callBack function
	    if (typeof settings.onError === "function") {
	        settings.onError();
	    } 	
	}

	function _onSucess(datos){

		$("#load-qubit-ajax").remove();

		a = datos.data; //.reverse();
		for (x=0;x<a.length;x++){

			cmd = datos.data[x].cmd;
			name = datos.data[x].name;
			
			if (cmd == 'log'){
				if (settings.debug){
					value = datos.data[x].value;
					debug_writeMessage(value);
				}
			}
			else if (cmd == 'js'){
				if (settings.debug){
					var jsonString=JSON.stringify(datos.data[x])
					debug_writeMessage(jsonString);
				}


				eval(name);
			}
			else if (cmd == 'as'){

				if (settings.debug){
					var jsonString=JSON.stringify(datos.data[x])
					debug_writeMessage(jsonString);
				}

				pro = datos.data[x].pro;
				value = datos.data[x].value;

				if (pro.substring(0,4) == 'css.'){
					pros = pro.substring(4);
					$('#'+name+',[name='+name+']').css(pros,value);
				}
				else if (pro == 'innerHTML'){
					$('#'+name+',[name='+name+']').html(value);
				}
				else if (pro == 'value'){
					$('#'+name+',[name='+name+']').val(value);
				}
				else{
					$('#'+name+',[name='+name+']').attr(pro,value);
				}
			}
		}

		// onSucess add callBack function
	    if (typeof settings.onSucess === "function") {
	        settings.onSucess();
	    }
	}

	function debug_writeMessage(text){
		
		var xd = debug;

		if ('undefined' == typeof text){
			text = 'Mensaje de error desconicido';
		}

		if ('undefined' == typeof xd.window || true == xd.window.closed) {
			xd.window = window.open(xd.windowSource, xd.windowID, xd.windowStyle);

			if ("about:blank" == xd.windowSource){
				xd.window.document.write(xd.debugTemplate);
			}
		}

		var xdw = xd.window;
		var xdwd = xdw.document;
		
		if ('undefined' == typeof cls)
			cls = 'debugText';

		var debugTag = xdwd.getElementById('debugTag');
		var debugEntry = xdwd.createElement('div');
		var debugDate = xdwd.createElement('span');
		var debugText = xdwd.createElement('pre');

		debugDate.innerHTML = new Date().toString();
		debugText.innerHTML = text;

		debugEntry.appendChild(debugDate);
		debugEntry.appendChild(debugText);
		debugTag.insertBefore(debugEntry, debugTag.firstChild);

		try {
			debugEntry.className = 'debugEntry';
			debugDate.className = 'debugDate';
			debugText.className = cls;
		} catch (e) {
		}
	}

	return this;
}
