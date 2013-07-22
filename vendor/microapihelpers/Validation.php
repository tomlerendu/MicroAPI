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
namespace MicroAPIHelpers;

class Validation
{
	public static function check($validation, $options = [])
	{
		//An array to store errors
		$errors = [];

		//For each piece of data
		foreach($validation as $data => $rules)
		{
			//For each rule applied to the data
			foreach($rules as $item)
			{
				$ruleType;
				$ruleParams;

				//If the rule has conditions to be met
				if(is_array($item['rule']))
				{
					$ruleType = $item['rule'][0];
					$ruleParams = array_splice($item['rule'], 1);
				}
				//If the rule has no conditions to be met
				else
				{
					$ruleType = $item['rule'];
					$ruleParams = NULL;
				}

				//Perform the validation
				if(!self::doRule($data, $ruleType, $ruleParams))
					//If the validation failed add the message to the errors array
					$errors[] = $item['message'];
			}
		}

		return (count($errors) === 0) ? true : $errors;
	}

	private static function doRule($data, $rule, $params)
	{
		var_dump($data, $rule, $params);

		$passedRule;

		switch($rule)
		{
			case 'alphanumeric':
				$passedRule = self::alphanumericRule($data);
				break;
			case 'alpha':
				$passedRule = self::alphaRule($data);
				break;
			case 'numeric':
				$passedRule = call_user_func_array('self::numericRule', array_merge($data, $params));
				break;
			case 'integer':
				$passedRule = call_user_func_array('self::integerRule', array_merge($data, $params));
				break;
			case 'charset':
				$passedRule = self::charsetRule($data, $item['rules']['charset']);
				break;
			case 'length':
				break;
			case 'regex':
				break;
			case 'unique':
				break;
		}

		return $passedRule;
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
		return (bool) preg_match($regex, $data);
	}

	private static function uniqueRule($data, $tableName, $columnName)
	{

	}
}