<?php

/**
 * Helps to set active state in menu
 *
 * @param string $path Path in menu
 * @param string $active Class name which should be returned
 * @return string
 */
function set_active($path, $active = 'active') {
	return Request::path() == $path ? $active : '';
}

/**
 * Helps to set selected option in select
 *
 * @param $optionName
 * @param $optionValue
 * @param string $default
 * @param string $attribute
 * @return string
 */
function old_select($optionName, $optionValue, $default = '', $attribute = 'selected') {
	if ($optionValue == old($optionName)) {
		return $attribute;
	}

	return old($optionName,'') == '' ? $default : '';
}

/**
 * Returns value if key exists, null otherwise
 *
 * @param array $array Array of values
 * @param mixed $key Key in array
 */
function valueOrNull($array, $key) {
	return isset($array[$key]) ? $array[$key] : null;
}