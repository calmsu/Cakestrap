<?php
/**
 * Cakestrap Html Helper
 *
 * This helper extends the built-in CakePHP HtmlHelper and as such is designed
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

App::uses('HtmlHelper', 'View/Helper');
class CakestrapHtmlHelper extends HtmlHelper {
	
	/*
	 * closing tags 
	 * 
	 * Stores the closing tags that are waiting to be output.
	 */
	private $closing_tags = array();
	
	/**
	 * label method
	 * 
	 * Creates an inline label.  Class can be one of success, warning, important,
	 * or notice. (Or anything really, it's just added to the class field.
	 * 
	 * @param string $text
	 * @param string $class
	 */	
	public function label($text, $class=null, $options = array()) {
		$class = 'label' . 
				 (!empty($class)?(' '.$class):'');
		if (array_key_exists('class', $options)) {
			$class .= ' ' .$options['class'];
			unset($options['class']);
		} 
		
		$options = array_merge(
			array('class' => $class),
			$options
		);
		return $this->tag('span', $text, $options);
	}
	
	/**
	 * button method
	 * 
	 * Creates an anchor link styled as a button.  This is a wrapper for the 
	 * HtmlHelper's link method so all of the options available there are available
	 * for this method.  Options array can include 'size' and 'type' options.
	 * 
	 * Size: large, small, <empty>
	 * Type: primary, info, success, danger, <empty>
	 * 
	 * Creates a twitter bootstrap
	 * @param string $text
	 * @param mixed $url optional NULL
	 * @param array $options optional array()
	 * @param string $confirmMessage optional false
	 */
	public function button($title, $url = null, $options = array(), $confirmMessage = null) {
		$options['class'] = (!empty($options['class'])?$options['class'].' ':'') . 'btn';
		if (array_key_exists('size', $options)) {
			$options['class'] = $options['class'] .' ' . $options['size'];
			unset($options['size']);
		}
		if (array_key_exists('type', $options)) {
			$options['class'] = $options['class'] .' '. $options['type'];
			unset($options['type']);
		}
		return $this->link($title, $url, $options, $confirmMessage);		
		
	}
	
	/**
	 * copyLink method
	 * 
	 * Creates a 'copy to clipboard' javascript link. Set 'button' key in options
	 * array to true to use a button instead of a link.
	 * 
	 * @param string $title
	 * @param string $text
	 * @param array $options optional array()
	 */
	public function copyLink($title, $text, $options = array()) {
		$onclick = 'window.prompt("Copy to Clipboard. Crtl-C", "'.$text . '"); return false;';
		$options = array_merge($options, array('onclick' => $onclick));
		if (!empty($options['button'])) {
			return $this->button($title, '#', $options);
		} else {
			return $this->link($title, '#', $options);
		}
	}
	
	/**
	 * mediaGridStart method
	 * 
	 * Outputs a media grid starting ul tag.  Options array is passed to the ul tag element
	 * 
	 * @param array $options optional array()
	 */
	public function mediaGridStart($options = array()) {
		$options['class'] = (!empty($options['class'])?$options['class'] . ' ':'') . 'media-grid';
		array_push($this->closing_tags, '</ul>');
		return $this->tag('ul', null, $options);
		
	}
	
	/**
	 * mediaGridImage method
	 * 
	 * Outputs a media grid image.
	 * 
	 * @param string $path
	 * @param mixed $url
	 * @param array $options optional array()
	 */
	public function mediaGridImage($path, $url, $options = array()) {
		$options['class'] = (!empty($options['class'])?$options['class'] . ' ':'') . 'thumbnail';
		$options['url'] = $url;
		return $this->image($path, $options);
	}
	
	/**
	 * rowStart method
	 * 
	 * Outputs a starting div tag for a row in the grid layout system.  Any options are passed
	 * to the tag method.
	 * 
	 * @param array $options optional array()
	 */
	public function rowStart($options = array()) {
		$options['class'] = (!empty($options['class'])?$options['class'] . ' ':'') . 'row';
		array_push($this->closing_tags, '</div>');
		return $this->tag('div', null, $options);
	}
	
	/**
	 * columnStart method
	 * 
	 * Outputs a starting div tag for a column in the grid layout system. Any options are 
	 * passed to the tag method. Width parameter defines the span class that is added to 
	 * the div.
	 * 
	 * @param int $width
	 * @param array $options optional array()
	 */
	public function columnStart($width, $options = array()) {
		$span = 'span'.$width;
		$options['class'] = (!empty($options['class'])?$options['class'] . ' ':'') . $span;
		array_push($this->closing_tags, '</div>');
		return $this->tag('div', null, $options);
	}
	
	/**
	 * end method
	 * 
	 * Outputs closing tags in nesting order.  Optional all parameter will output all closing tags
	 * that are pending.
	 * 
	 * @param bool $all optional false 
	 */
	public function end($all = false) {
		if (!$all) {
			return array_pop($this->closing_tags);
		} else if (!empty($this->closing_tags)) {
			$out = implode($this->closing_tags);
			$this->closing_tags = array();
			return $out;
		}
	}
	
	/**
	 * alertMessage method
	 *  
	 * Outputs a one line alert message.  Use the 'class' key in the options array to control styling
	 * (info, warning, error, success).
	 * 
	 * @param string $text
	 * @param array $options optional array()
	 */
	public function alertMessage($text, $options = array()) {
		$out = '';
		$default_options = array(
			'close' => true,
			'class' => '',
			'escape' => true,
		);
		$options = array_merge($default_options, $options);
		//Save the close and escape options and unset them so that they don't show up as attrs on the element
		
		$close = $options['close'];
		unset ( $options['close'] );
		$escape = $options['escape'];
		unset ( $options['escape']);
		
		$options['class'] .= ' alert-message';
		$out .= $this->tag('div', null, $options);
		$out .= $close?$this->tag('a', '&times;', array('href' => '#', 'class' => 'close')):'';
		$out .= $this->tag('p', $text, array('escape' => $escape));
		$out .= '</div>';
		return $out;
	}
	
	
	public function less($path, $options = array()) {
		$options += array('inline' => true);
		if (is_array($path)) {
			$out = '';
			foreach($path as $i) {
				if (substr($i, -5) !== '.less') {
					$i .= ".less?";
				} else {
					$i .= "?";;
				}
				$out .= $this->css($i, 'stylesheet/less', $options);
			}
			if ($options['inline']) {
				return $out;
			}
			return;
		}	
		
		if (substr($path, -5) !== '.less') {
			$path .= '.less?';
		} else {
			$path .= '?';
		}
		if ($options['inline']) {
			return $this->css($path, 'stylesheet/less', $options);
		} else {
			$this->css($path, 'stylesheet/less', $options);
			return;
		}
	}
	
	/**
	 * tabs method
	 * 
	 * Create bootstrap styled tabs.  Set the 'type' key to 'pills' in 
	 * $ul_options to create pills instead of tabs.
	 * 
	 * @param array $tabs
	 * @param array $ul_options optional array()
	 * @param array $li_options optional array()
	 * @param array $a_options optional array()
	 */
	public function tabs($tabs, $ul_options = array(), $li_options = array(), $a_options = array()) {
		$ul_options['class'] = !empty($ul_options['type'])?$ul_options['type']:'tabs' . (!empty($ul_options['class'])?' ' .$ul_options['class']:'');
		$out = $this->tag('ul', null, $ul_options);
		foreach ($tabs as $key => $tab) {
				$options = isset($tab['options'])?$tab['options']:array();
				$href = isset($tab['url'])?$tab['url']:'#';
				$out .= $this->tag('li', null, array_merge($li_options, $options));
				$out .= $this->link($key, $href, $a_options);
				$out .= '</li>';
			
		}
		$out .= '</ul>';
		return $out;
	}

	public function breadcrumbs($links, $options = array()) {
		$out = '';
		$options = array_merge(array(
			'divider' => '/',
			'class' => 'breadcrumb',
		), $options);
		if (!empty($options['class'])) {
			$options['class'] = 'breadcrumb '.$options['class'];
		}
		$out .= $this->tag('ul', null, $options);
		$i = 1;
		foreach($links as $text => $link) {
			if ($i == count($links)) {
				$out .= $this->tag('li', $text, array('class' => 'active'));
		
				
			} else {
				$li = $this->link($text, $link). ' '. $this->tag('span', $options['divider'], array('class' => 'divider'));
				$out .= $this->tag('li', $li, array('escape' => false));
				
			}
			$i++;
		}
		$out .= '</ul>';
		return $out;
	}
}