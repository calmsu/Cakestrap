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
 * @param Controller $controller Instantiating controller
 * @return void
 */
	public function initialize(Controller $controller) {
		parent::initialize($controller);
		//If helpers is true, replace the Html and Form helpers with ours.
		if (is_array($this->helpers)) {
			foreach (array_keys($this->helpers) as $key) {
				if (is_int($key)) {
					$this->helpers[$this->helpers[$key]] = true;
					unset($this->helpers[$key]);
				}
			}
		}
		if ($this->helpers) {
			if ((is_array($this->helpers) && array_key_exists('Form', $this->helpers)) || ($this->helpers === true)) {
				if (($key = array_search('Form', $controller->helpers)) !== false) {
					unset($controller->helpers[$key]);
					$controller->helpers['Form'] = true;
				}
				if (array_key_exists('Form', $controller->helpers)) {
					if ((!empty($this->helpers['Form'])) && is_array($this->helper['Form'])) {
						$controller->helpers['Form'] = array_merge(['className' => 'Cakestrap.CakestrapForm'], $this->helpers['Form']);
					} else {
						$controller->helpers['Form'] = array('className' => 'Cakestrap.CakestrapForm');
					}
				}
			}
			if ((is_array($this->helpers) && array_key_exists('Html', $this->helpers)) || ($this->helpers === true)) {
				if (($key = array_search('Html', $controller->helpers)) !== false) {
					unset($controller->helpers[$key]);
					$controller->helpers['Html'] = true;
				}
				if (array_key_exists('Html', $controller->helpers)) {
					if ((!empty($this->helpers['Html'])) && is_array($this->helper['Html'])) {
						$controller->helpers['Html'] = array_merge(['className' => 'Cakestrap.CakestrapHtml'], $this->helpers['Html']);
					} else {
						$controller->helpers['Html'] = array('className' => 'Cakestrap.CakestrapHtml');
					}

				}
			}
			if ((is_array($this->helpers) && array_key_exists('Paginator', $this->helpers)) || ($this->helpers === true)) {
				if (($key = array_search('Paginator', $controller->helpers)) !== false) {
					unset($controller->helpers[$key]);
					$controller->helpers['Paginator'] = true;
				}
				if (array_key_exists('Paginator', $controller->helpers)) {
					if ((!empty($this->helpers['Paginator'])) && is_array($this->helper['Paginator'])) {
						$controller->helpers['Paginator'] = array_merge(['className' => 'Cakestrap.CakestrapPaginator'], $this->helpers['Paginator']);
					} else {
						$controller->helpers['Paginator'] = array('className' => 'Cakestrap.CakestrapPaginator');
					}

				}
			}
		}

		/** Setup our Defines **/
		if (!defined('DATE_MAX_AGE')) {
			define('DATE_MAX_AGE', 99);
		}
		if (!defined('DATE_MIN_AGE')) {
			define('DATE_MIN_AGE', 1);
		}
	}

/**
 * beforeRender method
 *
 * Replaces default flash messages with the default bootstrap flash message.  This
 * can be disabled by setting flash to false in the settings array.
 *
 * @param Controller $controller Controller
 * @return void
 */
	public function beforeRender(Controller $controller) {
		if ($this->flash && is_a($controller->Session, 'SessionComponent')) {
			if ($controller->Session->check('Message.flash')) {
				$flash = $controller->Session->read('Message.flash');
				if ($flash['element'] == 'default') {
					$flash['params']['plugin'] = 'Cakestrap';
					$flash['element'] = 'bs_default';
					$controller->Session->write('Message.flash', $flash);
				} elseif (strpos($flash['element'], 'bs_') === 0) {
					$flash['params']['plugin'] = 'Cakestrap';
					$controller->Session->write('Message.flash', $flash);
				}
			}
		}
	}
}