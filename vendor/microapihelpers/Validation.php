<?php
/*

	NOT FINISHED

	Will eventually work by passing in an array like so

	[

		[
			'data that will be validated',
			['The number was not numeric', 'numeric', 3, 4],
			['The number was not numeric', 'numeric', 3, 4],
			['The number was not numeric', 'numeric', 3, 4]
		],

		[
			'data that will be validated',
			['The number was not numeric', 'numeric', 3, 4],
			['The number was not numeric', 'numeric', 3, 4],
			['The number was not numeric', 'numeric', 3, 4]
		]
	]
*/

namespace MicroAPIHelpers;

class Validation
{
	public static function check($validation, $options = [])
	{
		//An array to store errors
		$errors = [];

		//For each piece of data
		foreach($validation as $item)
		{
			//Set the data and remove it from the array
			$data = $item[0];
			unset($item[0]);

			//For each rule applied to the data
			foreach($item as $rule)
			{
				$ruleType = $rule[1];
				$ruleParams = array_slice($rule, 2);

				//Perform the validation
				if(!self::doRule($data, $ruleType, $ruleParams))
					//If the validation failed add the message to the errors array
					$errors[] = $rule[0];
			}
		}

		return (count($errors) === 0) ? true : $errors;
	}

	private static function doRule($data, $rule, $params)
	{
		$passedRule;

		switch($rule)
		{
			case 'equal':
				$passedRule = self::equalRule($data, $params[0]);
				break;
			case 'alphanumeric':
				$passedRule = self::alphanumericRule($data);
				break;
			case 'alpha':
				$passedRule = self::alphaRule($data);
				break;
			case 'numeric':
				$passedRule = call_user_func_array('self::numericRule', array_merge([$data], $params));
				break;
			case 'integer':
				$passedRule = call_user_func_array('self::integerRule', array_merge([$data], $params));
				break;
			case 'charset':
				$passedRule = self::charsetRule($data, $item['rules']['charset']);
				break;
			case 'length':
				$passedRule = self::lengthRule($data, $params[0], $params[1]);
				break;
			case 'regex':
				$passedRule = self::regexRule($data, $params[0]);
				break;
			case 'unique':
				break;
		}

		return $passedRule;
	}

	private static function equalRule($data, $comparisonData , $typeSafe = false)
	{
		if($typeSafe === false)
			return ($data == $comparisonData);

		return ($data === $comparisonData);
	}

	private static function alphanumericRule($data)
	{
		return ctype_alnum($data);
	}

	private static function alphaRule($data)
	{
		return ctype_alpha($data);
	}

	private static function numericRule($data, $min = NULL, $max = NULL)
	{
		if(!is_numeric($data))
			return false;

		if($data < $min || $data > $max)
			return false;

		return true;
	}

	private static function integerRule($data, $min = NULL, $max = NULL)
	{
		if(!ctype_digit($data))
			return false;

		$data = (int) $data;

		if($data < $min || $data > $max)
			return false;

		return true;
	}

	private static function charsetRule($data, $charset, $caseSensitive = true)
	{
		//If case doesn't matter then make everything lowercase
		if($caseSensitive === false)
		{
			$data = strtolower($data);
			$charset = strtolower($charset);
		}

		//Avoid an O(n^2) situation
		$charsetMap = [];
		for($i=0; $i<strlen($charset); $i++)
			$charsetMap[$charset[$i]] = true;

		//Check each character
		for($i=0; $i<strlen($data); $i++)
		{
			if($charsetMap[$data[$i]] !== true)
				return false;
		}

		return true;
	}

	private static function lengthRule($data, $min = NULL, $max = NULL)
	{
		if($min != NULL && !isset($data[$min-1]))
			return false;

		if($max != NULL && isset($data[$max-1]))
			return false;

		return true;
	}

	private static function regexRule($data, $regex)
	{
		return (bool) preg_match($regex, $data);
	}

	private static function uniqueRule($data, $tableName, $columnName)
	{

	}
}