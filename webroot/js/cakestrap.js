/**
 * Cakestrap Javascript File
 * 
 * Add a new confirm message format if Messenger is loaded.
 */

if (Messenger) {
window.confirm = function (message, userSuccessCallback, userCancelCallback) {
	successCallback = function(msg) {
		confirmMsg.hide();
		userSuccessCallback();
	};
	cancelCallback = function(msg) {
		confirmMsg.hide();
		userCancelCallback();
	}
	confirmMsg = Messenger().post({
		message: message,
		
		type: "info",
		actions: {
		   confirm:  {
			   label: 'Yes',
			   action: successCallback
		   },
		   cancel:  {
			   label: "Cancel",
				   action: cancelCallback
			   }
			}        
		});
	return false;
	}
}