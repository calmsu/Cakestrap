/**
 * Cakestrap Javascript File
 * 
 * Add a new confirm message format if Messenger is loaded.
 */

if (Messenger) {
window.confirm = function (message, userSuccessCallback, userCancelCallback) {
	successCallback = function(msg) {
		userSuccessCallback();
		confirmMsg.hide();
	};
	cancelCallback = function(msg) {
		userCancelCallback();
		confirmMsg.hide();
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
	}
	return false;
}