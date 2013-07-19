<?php

/*

	NOT FINISHED

	Will eventually work by passing in an array like so

	[
		'data to validate' =>
		[
			[
				'rule' => ['numeric', 23, 44],
				'message' => 'There was an error'
			],
			[
				'rule' => ['numeric', 23, 44],
				'message' => 'There was an error'
			],
		],
		
		'data to validate' =>
		[
			[
				'rule' => ['numeric', 23, 44],
				'message' => 'There was an error'
			],
			[
				'rule' => ['numeric', 23, 44],
				'message' => 'There was an error'
			],
		]
	]
*/
namespace Helpers;

class Validate
{
	public static function do($data, $options = [])
	{
		$errors = [];

		foreach($data as $item)
		{
			$passedRule;

			switch($item['rules'])
			{
				case 'alphanumeric':
					$passedRule = self::alphanumericRule($item['data']);
					break;
				case 'alpha':
					$passedRule = self::alphaRule($item['data']);
					break;
				case 'numeric':
					$passedRule = call_user_func_array(
						'self::numericRule',
						array_merge($item['data'], $item['rules']['numeric']['conditions'])
					);
					break;
				case 'integer':
					$passedRule = call_user_func_array(
						'self::integerRule',
						array_merge($item['data'], $item['rules']['numeric']['conditions'])
					);
					break;
				case 'charset':
					$passedRule = self::charsetRule($item['data'], $item['rules']['charset']);
					break;
				case 'length':
					break;
				case 'regex':
					break;
				case 'unique':
					break;
			}

			if(!$passedRule)
				$errors[] = $item[$item['rules']]['message'];
		}

		return (count($errors) == 0) ? true : $errors;
	}

	private static function numericRule($data, $min = NULL, $max = NULL)
	{
		if(!is_numeric($data))
			return false;

		$data = (int) $data;

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

	private static function alphaRule($data)
	{
		return ctype_alpha($data);
	}

	private static function alphanumericRule($data)
	{
		return ctype_alnum($data);
	}

	private static function charsetRule($data, $charset)
	{

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

	}

	private static function uniqueRule($data, $tableName, $columnName)
	{

	}
}