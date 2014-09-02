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
 * @param string $fieldName Name of field (passed to input
 * @param array $options optional Unknown options will be passed to FormHelper
 * @return string
 */
	public function input($fieldName, $options = array()) {
		//Check to see if what we have here is a checkbox:
		$this->setEntity($fieldName);

		$modelKey = $this->model();
		$fieldKey = $this->field();
		$checkOptions = array();
		$fieldDef = $this->_introspectModel($modelKey, 'fields', $fieldKey);
		if ($fieldDef) {
			$type = $fieldDef['type'];
		} else {
			$type = "text";
		}

		if (!empty($options['type'])) {
			$type = $options['type'];
		}
		if ($type == 'boolean' || ($type == 'bool')) {
			//If it's a checkbox and type=checkbox isn't explicitly set
			//change the input type to a yes/no dropdown because checkboxes
			//are ambiguously evil.

			if (empty($options['type']) || $options['type'] == 'bool') {
				$checkOptions = array(
					'options' => array('0' => 'No', '1' => 'Yes'),
					'class' => 'form-control'
				);
				$options['type'] = 'select';
			} elseif ($options['type'] == 'checkbox') {
				$checkOptions = array(
					'format' => array(
						'before',
						'label',
						'between',
						'input',
						'error',
						'after',
					)
				);
			}
		} elseif ($type == 'dob') {
			$options['type'] = 'date';
			$options['minYear'] = date('Y') - DATE_MAX_AGE;
			$options['maxYear'] = date('Y') - DATE_MIN_AGE;
			if (!empty($options['class'])) {
				$options['class'] .= 'date';
			} else {
				$options['class'] = 'date';
			}
		}

		if (!empty($options['class'])) {
			$class = $options['class'];
			$options['class'] .= ' form-control';
		}
		if ((!empty($options['prepend'])) || (!empty($options['append']))) {
			if (empty($options['after'])) {
				$options['after'] = '';
			}
			$options['between'] = '<div class="input-group ' . ((!empty($class))?$class:'') . '">';
			if ((!empty($options['prepend']))) {
				$options['between'] .= '<span class="input-group-addon">' . $options['prepend'] . '</span>';
				unset($options['prepend']);
			}
			if (!empty($options['append'])) {
				$options['after'] .= '<span class="input-group-addon">' . $options['append'] . '</span>';
				unset($options['append']);
			}
			$options['after'] .= '</div>';
		}
		if (array_key_exists('help_text', $options)) {
			if (empty($options['after'])) {
				$options['after'] = '';
			}
			$options['after'] .= '<span class="help-block">' . $options['help_text'] . '</span>';
			unset($options['help_text']);
		}
		if ((!empty($options['label'])) && is_string($options['label'])) {
			$options['label'] = array('class' => 'control-label', 'text' => $options['label']);
		}
		$options = array_merge(
			array(
				'div' => array(
					'class' => 'form-group'
				),
				'class' => 'form-control',
				'before' => null,
				'between' => '',
				'after' => '',
				'format' => array('before', 'label', 'between', 'input', 'error', 'after'),
				'label' => array('class' => 'control-label'),
			),
			$checkOptions,
			$options
		);
		return parent::input($fieldName, $options);
	}

/**
 * error method
 *
 * Overload the error method to restructure the output for Bootstrap.
 *
 * @param string $field Field name
 * @param string $text Error text
 * @param unknown $options Options
 * @return parent::error()
 */
	public function error($field, $text = null, $options = array()) {
		$options = array_merge(
			array('wrap' => 'span', 'class' => 'help-block', 'escape' => true),
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
 * @param string $title Button title
 * @param mixed $url optional NULL
 * @param array $options optional array()
 * @param string $confirmMessage optional false
 * @return parent::postLink
 */
	public function postButton($title, $url = null, $options = array(), $confirmMessage = false) {
		$options['class'] = (!empty($options['class']))?$options['class'] . ' ':'' . 'btn';
		if (array_key_exists('size', $options)) {
			$options['class'] = $options['class'] . ' ' . $options['size'];
			unset($options['size']);
		}
		if (array_key_exists('type', $options)) {
			$options['class'] = $options['class'] . ' ' . $options['type'];
			unset($options['type']);
		} else {
			$options['class'] .= ' btn-default';
		}

		return $this->postLink($title, $url, $options, $confirmMessage);
	}

/**
 * postLink method
 *
 * Wrap the postLink method to add the icon option.
 *
 * @param string $title The content to be wrapped by <a> tags.
 * @param string|array $url Cake-relative URL or array of URL parameters, or external URL (starts with http://)
 * @param array $options Array of HTML attributes.
 * @param bool|string $confirmMessage JavaScript confirmation message.
 * @return string An `<a />` element.
 * (non-PHPdoc)
 * @see FormHelper::postLink()
 */
	public function postLink($title, $url = null, $options = array(), $confirmMessage = false) {
		if (array_key_exists('icon', $options)) {
			$title = $this->Html->icon($options['icon'], $title, true);
			unset ($options['icon']);
			$options['escape'] = false;
		}
		return parent::postLink($title, $url, $options, $confirmMessage);
	}

/**
 * bool method
 *
 * Generate a boolean field in the dropdown style.
 *
 * @param string $field Field name
 * @param array $options Options
 * @return string
 */
	public function bool($field, $options = array()) {
		$options = array_merge(
			array(
				'class' => 'span2',
				'options' => array(
					'0' => __('No'),
					'1' => __('Yes'),
				),
				'empty' => false,
			),
			$options);
		return $this->select($field, $options['options'], $options);
	}
}