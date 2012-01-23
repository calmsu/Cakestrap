<?php
/**
* Cakestrap Form Helper
*
* This helper extends the built-in CakePHP FormHelper and as such is designed
* to replace it.
*
* PHP 5/CakePHP 2.0
*
* Cakestrap: https://github.com/calmsu/cakestrap
* Copyright 2012, Michigan State University Board of Trustees
*
* Licensed under The MIT License
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright 2012, Michigan State University Board of Trustees
* @link          http://github.com/calmsu/cakestrap
* @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
*/

App::uses('FormHelper', 'View/Helper');
class CakestrapFormHelper extends FormHelper {
	
	/**
	 * input method
	 * 
	 * Drop in replacement for the built-in FormHelper input function.   For the
	 * most part this means reformatting divs and classes to work with Bootstrap.
	 * We also are replacing boolean fields with Yes/No dropdowns as opposed to
	 * checkboxes.  This can be overridden by setting 'type' => 'checkbox' in
	 * the options array.
	 * 
	 * @param string $fieldName
	 * @param array $options optional array()
	 */
	public function input($fieldName, $options = array()) {
		//Check to see if what we have here is a checkbox:
		$this->setEntity($fieldName);
		
		$modelKey = $this->model();
		$fieldKey = $this->field();
		$chk_options = array();
		if ($fieldDef = $this->_introspectModel($modelKey, 'fields', $fieldKey)) {
			$type = $fieldDef['type'];
			if ($type == 'boolean') {
				//If it's a checkbox and type=checkbox isn't explicitly set
				//change the input type to a yes/no dropdown because checkboxes
				//are ambiguously evil.
				if (empty($options['type'])){
					$chk_options = array('options' => array('0' => 'No', '1' => 'Yes'), 'class' => 'span2');
				} else if ($options['type'] == 'checkbox') {
					$chk_options = array('format' => array('before', 'label', 'between','input','error', 'after', ));
				}
			}
		}
		if (array_key_exists('help_text', $options)) {
			$options['after'] = '<span class="help-block">'.$options['help_text'].'</span></div>';
			unset($options['help_text']);
		}
		$options = array_merge(
			array('div' => array('class' => 'clearfix'), 'before' => null, 'between' => '<div class="input">', 'after' => '</div>', 'format' => null),
			$chk_options,
			$options
		);
		return parent::input($fieldName, $options);
	}
	
	/**
	 * error method
	 * 
	 * Drop-in replacement for the built-in FormHelper error method.  For the
	 * most part this means reformatting some divs and classes to work with 
	 * Bootstrap.
	 * 
	 * @param string $field
	 * @param string $text optional null
	 * @param array $options optional array()
	 */
	public function error($field, $text = null, $options = array()) {
		$options = array_merge(
			array('wrap' => 'span', 'class' => 'help-inline', 'escape' => true),
			$options
		);
		
		return parent::error($field, $text, $options);
	}
	
	/**
	* postButton method
	*
	* Wraps the FormHelper's postLink method to create a bootstrap styled link.
	* 
	* Additional options:
	* 'size'  - large, small, <empty>
	* 'type'  - primary, info, success, danger, <empty>
	*
	* @param string $title
	* @param mixed $url optional NULL
	* @param array $options optional array()
	* @param string $confirmMessage optional false
	*/
	public function postButton($title, $url = null, $options = array(), $confirmMessage = false) {
		$options['class'] = (!empty($options['class']))?$options['class'].' ':'' . 'btn';
		if (array_key_exists('size', $options)) {
			$options['class'] = $options['class'] .' ' . $options['size'];
			unset($options['size']);
		}
		if (array_key_exists('type', $options)) {
			$options['class'] = $options['class'] .' '. $options['type'];
			unset($options['type']);
		}
	
		return $this->postLink($title, $url, $options, $confirmMessage);
	}
}