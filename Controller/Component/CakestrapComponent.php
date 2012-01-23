<?php
/**
* Cakestrap Component
*
* The Cakestrap Component does some magic to autoload the CakestrapFormHelper
* and CakestrapHtmlHelper.  It's also responsible for replacing the default 
* flash message element to the Cakestrap flash message.
* 
* Settings:
* - helpers: 
*
* PHP 5/CakePHP 2.0
*
* Cakestrap: https://github.com/calmsu/cakestrap
*  
*
* Licensed under The MIT License
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright 2012, Michigan State University Board of Trustees
* @link          http://github.com/calmsu/cakestrap
* @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
*/

class CakestrapComponent extends Component {
	
	
	public $helpers = true;
	public $flash = true;
	
	/**
	* initialize method
	*
	* Initialize callback checks to see if Html or Form helper have been loaded and
	* replaces them with versions from the plugin instead.  Set helpers to false in
	* the settings array to disable this functionality.
	*
	* @param Controller $controller
	*/
	function initialize(&$controller) {
		parent::initialize($controller);
		
		//If helpers is true, replace the Html and Form helpers with ours.
		if ($this->helpers) {
			
			if (($key = array_search('Form', $controller->helpers))!== false) {
				unset($controller->helpers[$key]);
				$controller->helpers['Form'] = true;
			}
			if (array_key_exists('Form', $controller->helpers)) {
				$controller->helpers['Form'] = array('className' => 'Bootstrap.BootstrapForm');
			}
			if (($key = array_search('Html', $controller->helpers))!== false) {
				unset($controller->helpers[$key]);
				$controller->helpers['Html'] = true;
			}
			if (array_key_exists('Html', $controller->helpers)) {
				$controller->helpers['Html'] = array('className' => 'Bootstrap.BootstrapHtml');
			}
			
		}
	}
	
	/**
	 * beforeRender method
	 * 
	 * Replaces default flash messages with the default bootstrap flash message.  This
	 * can be disabled by setting flash to false in the settings array.
	 * 
	 * @param Controller $controller
	 */
	public function beforeRender(&$controller) {
		if ($this->flash && is_a($controller->Session, 'SessionComponent')) {
			if ($controller->Session->check('Message.flash')) {
				$flash = $controller->Session->read('Message.flash');
				if ($flash['element'] == 'default') {
					$flash['params']['plugin'] = 'Bootstrap';
					$flash['element'] = 'bs_default';
					$controller->Session->write('Message.flash', $flash);
				} else if (strpos($flash['element'], 'bs_') === 0) {
					$flash['params']['plugin'] = 'Bootstrap';
					$controller->Session->write('Message.flash', $flash);
				}
			}
		}
	}
}