<?php
/**
 * Cakestrap Paginator Helper
 *
 * This helper extends the built-in CakePHP PaginatorHelper and as such is designed
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

App::uses('PaginatorHelper', 'View/Helper');
class CakestrapPaginatorHelper extends PaginatorHelper {
	
	function prev($title = '<< Previous', $options = array(), $disabledTitle = null, $disabledOptions = array()) {
		$options = array_merge(
			array(
				'tag' => 'li',
			),
			$options
		);
		$disabledOptions = array_merge(
			array(
				'tag' => 'li',
				'escape' => false,
			),
			$disabledOptions
		);
		$disabledTitle = '<a href="#">'.(empty($disabledTitle)?$title:$disabledTitle).'</a>';
		return parent::prev($title, $options, $disabledTitle, $disabledOptions);
	}
	
	function next($title = '<< Previous', $options = array(), $disabledTitle = null, $disabledOptions = array()) {
		$options = array_merge(
				array(
						'tag' => 'li',
				),
				$options
		);
		
		$disabledOptions = array_merge(
				array(
						'tag' => 'li',
						'escape' => false,
				), 
				$disabledOptions
		);
		$disabledTitle = '<a href="#">'.(empty($disabledTitle)?$title:$disabledTitle).'</a>';
		return parent::next($title, $options, $disabledTitle, $disabledOptions);
	}
	
	function numbers($options = array()) {
		
		$options = array_merge(
			array(
				'tag' => 'li',
				'currentClass' => 'active',
			),	
			$options
		);
		
		if ($options === true) {
			$options = array(
					'before' => ' | ', 'after' => ' | ', 'first' => 'first', 'last' => 'last'
			);
		}
	
		$defaults = array(
				'tag' => 'span', 'before' => null, 'after' => null, 'model' => $this->defaultModel(), 'class' => null,
				'modulus' => '8', 'separator' => ' | ', 'first' => null, 'last' => null, 'ellipsis' => '...', 'currentClass' => 'current'
		);
		$options += $defaults;
	
		$params = (array)$this->params($options['model']) + array('page' => 1);
		unset($options['model']);
	
		if ($params['pageCount'] <= 1) {
			return false;
		}
	
		extract($options);
		unset($options['tag'], $options['before'], $options['after'], $options['model'],
				$options['modulus'], $options['separator'], $options['first'], $options['last'],
				$options['ellipsis'], $options['class'], $options['currentClass']
		);
	
		$out = '';
	
		if ($modulus && $params['pageCount'] > $modulus) {
			$half = intval($modulus / 2);
			$end = $params['page'] + $half;
	
			if ($end > $params['pageCount']) {
				$end = $params['pageCount'];
			}
			$start = $params['page'] - ($modulus - ($end - $params['page']));
			if ($start <= 1) {
				$start = 1;
				$end = $params['page'] + ($modulus - $params['page']) + 1;
			}
	
			if ($first && $start > 1) {
				$offset = ($start <= (int)$first) ? $start - 1 : $first;
				if ($offset < $start - 1) {
					$out .= $this->first($offset, compact('tag', 'separator', 'ellipsis', 'class'));
				} else {
					$out .= $this->first($offset, compact('tag', 'separator', 'class') + array('after' => $separator));
				}
			}
	
			$out .= $before;
	
			for ($i = $start; $i < $params['page']; $i++) {
				$out .= $this->Html->tag($tag, $this->link($i, array('page' => $i), $options), compact('class')) . $separator;
			}
	
			if ($class) {
				$currentClass .= ' ' . $class;
			}
			
			$out .= $this->Html->tag($tag, '<a href="#">'.$params['page'].'</a>', array('class' => $currentClass));
			if ($i != $params['pageCount']) {
				$out .= $separator;
			}
	
			$start = $params['page'] + 1;
			for ($i = $start; $i < $end; $i++) {
				$out .= $this->Html->tag($tag, $this->link($i, array('page' => $i), $options), compact('class')) . $separator;
			}
	
			if ($end != $params['page']) {
				$out .= $this->Html->tag($tag, $this->link($i, array('page' => $end), $options), compact('class'));
			}
	
			$out .= $after;
	
			if ($last && $end < $params['pageCount']) {
				$offset = ($params['pageCount'] < $end + (int)$last) ? $params['pageCount'] - $end : $last;
				if ($offset <= $last && $params['pageCount'] - $end > $offset) {
					$out .= $this->last($offset, compact('tag', 'separator', 'ellipsis', 'class'));
				} else {
					$out .= $this->last($offset, compact('tag', 'separator', 'class') + array('before' => $separator));
				}
			}
	
		} else {
			$out .= $before;
	
			for ($i = 1; $i <= $params['pageCount']; $i++) {
				if ($i == $params['page']) {
					if ($class) {
						$currentClass .= ' ' . $class;
					}
					$out .= $this->Html->tag($tag, $this->Html->link($i, '#'), array('class' => $currentClass));
				} else {
					$out .= $this->Html->tag($tag, $this->link($i, array('page' => $i), $options), compact('class'));
				}
				if ($i != $params['pageCount']) {
					$out .= $separator;
				}
			}
	
			$out .= $after;
		}
	
		return $out;
	}
		

}