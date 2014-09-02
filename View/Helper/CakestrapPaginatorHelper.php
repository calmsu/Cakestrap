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

/**
 * prev method
 *
 * Overload parent with bootstrap formatting.
 *
 * @param string $title Title for the link. Defaults to '<< Previous'.
 * @param array $options Options for pagination link. See #options for list of keys.
 * @param string $disabledTitle Title when the link is disabled.
 * @param array $disabledOptions Options for the disabled pagination link. See #options for list of keys.
 * @return string A "previous" link or $disabledTitle text if the link is disabled.
 *
 * (non-PHPdoc)
 * @see PaginatorHelper::prev()
 */
	public function prev($title = '<i class="fa fa-chevron-left"></i> Previous', $options = array(), $disabledTitle = null, $disabledOptions = array()) {
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
		$disabledTitle = '<a href="#">' . (empty($disabledTitle)?$title:$disabledTitle) . '</a>';
		return parent::prev($title, $options, $disabledTitle, $disabledOptions);
	}

/**
 * next method
 *
 * Overload parent with bootstrap formatting.
 *
 * @param string $title Title for the link. Defaults to 'Next >>'.
 * @param array $options Options for pagination link. See above for list of keys.
 * @param string $disabledTitle Title when the link is disabled.
 * @param array $disabledOptions Options for the disabled pagination link. See above for list of keys.
 * @return string A "next" link or $disabledTitle text if the link is disabled.
 *
 * (non-PHPdoc)
 * @see PaginatorHelper::next()
 */
	public function next($title = 'Next <i class="fa fa-chevron-right"></i>', $options = array(), $disabledTitle = null, $disabledOptions = array()) {
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
		$disabledTitle = '<a href="#">' . (empty($disabledTitle)?$title:$disabledTitle) . '</a>';
		return parent::next($title, $options, $disabledTitle, $disabledOptions);
	}

/**
 * numbers method
 *
 * Overload parent with bootstrap formatting
 *
 * @param array $options Options for the numbers, (before, after, model, modulus, separator)
 * @return string numbers string.
 *
 * (non-PHPdoc)
 * @see PaginatorHelper::numbers()
 */
	public function numbers($options = array()) {
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

			$out .= $this->Html->tag($tag, '<a href="#">' . $params['page'] . '</a>', array('class' => $currentClass));
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

/**
 * sort method
 *
 * Overload parent with bootstrap formatting.
 *
 * @param string $key The name of the key that the recordset should be sorted.
 * @param string $title Title for the link. If $title is null $key will be used
 *		for the title and will be generated by inflection.
 * @param array $options Options for sorting link. See above for list of keys.
 * @return string A link sorting default by 'asc'. If the resultset is sorted 'asc' by the specified
 *  key the returned link will sort by 'desc'.
 *
 * (non-PHPdoc)
 * @see PaginatorHelper::sort()
 */
	public function sort($key, $title = null, $options = array()) {
		if (empty($title)) {
			$title = Inflector::humanize($key);
		}
		$title = $title . ' <span></span>';

		$options = array_merge(
				array('escape' => false),
				$options
		);
		return parent::sort($key, $title, $options);
	}

}