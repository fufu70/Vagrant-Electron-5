Electron = (function () {

	var _defined_constants = {
		READ: "read",
		WRITE: "write",
		HIGH: 1,
		LOW: 0
	};

	var _constants = Object.freeze(_defined_constants);

	/**
	 * Goes to read the given pin, and pass the response to the callback 
	 * function given.
	 * 
	 * @param  {Object} params Contains the pin and callback
	 */
	function _readPin(params) {

	    params = params || {};
	    params.pin = params.hasOwnProperty('pin') ? params.pin : 'D6';
	    params.callback = params.hasOwnProperty('callback') ? params.callback : (function() {});

	    $.ajax({
			url: '/api/index.php',
			type: 'GET',
			dataType: 'json',
			data: {
				action: _constants.READ,
				pin: params.pin,
				value: '',
			},
			success: function(data) {
				params.callback(data);
			},
			error: function(e) {
				var str = e.responseText;
				str = str.substring(0, str.length - 4);
				params.callback(JSON.parse(str));
			}
		});
	};

	/**
	 * Goes to write to the pin given and pass the response to the callback 
	 * function given.
	 * 
	 * @param  {Object} params Contains the pin, value, and callback
	 */
	function _writePin(params) {

	    params = params || {};
	    params.pin = params.hasOwnProperty('pin') ? params.pin : 'D6';
	    params.value = params.hasOwnProperty('value') ? params.value : _constants.LOW;
	    params.callback = params.hasOwnProperty('callback') ? params.callback : (function() {});

	    $.ajax({
			url: '/api/index.php',
			type: 'GET',
			dataType: 'json',
			data: {
				action: _constants.WRITE,
				pin: params.pin,
				value: params.value,
			},
			success: function(data) {
				params.callback(data);
			},
			error: function(e) {
				var str = e.responseText;
				str = str.substring(0, str.length - 4);
				params.callback(JSON.parse(str));
			}
		});

	};

	return {
		readPin: _readPin,
		writePin: _writePin,
		constants: _constants
	};

})();