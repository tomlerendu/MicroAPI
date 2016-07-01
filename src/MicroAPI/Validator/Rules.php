<?php

namespace MicroAPI\Validator;

trait Rules
{
    /**
     * Determines if a value is a given type.
     *
     * @param mixed $value The value
     * @param string $type The type
     * @return bool
     */
    private function validateType($value, string $type): bool
    {
        switch ($type) {
            case 'bool':
                return is_bool($value);
            case 'numeric':
                return is_numeric($value);
            case 'integer':
                return is_int($value);
            case 'float':
                return is_float($value);
            case 'double':
                return is_double($value);
            case 'string':
                return is_string($value);
            default:
                return false;
        }
    }

    /**
     * Determines if a value is in an array.
     *
     * @param mixed $value The value
     * @param array ...$choices The array
     * @return bool
     */
    private function validateIn($value, ...$choices): bool
    {
        return in_array($value, $choices);
    }

    /**
     * Determines if a value is not in an array.
     *
     * @param mixed $value The value
     * @param array ...$choices The array
     * @return bool
     */
    private function validateNotIn($value, ...$choices): bool
    {
        return !in_array($value, $choices);
    }

    /**
     * Determines if one value equals another.
     *
     * @param mixed $value The value
     * @param mixed $equals The second value
     * @return bool
     */
    private function validateEquals($value, $equals): bool
    {
        return $value == $equals;
    }

    /**
     * Determines if a numeric value is within a numeric range
     *
     * @param mixed $value The value
     * @param mixed $lower The lower bound
     * @param mixed $upper The upper bound
     * @return bool
     */
    private function validateRange($value, $lower, $upper): bool
    {
        return is_numeric($value) && $value >= $lower && $value <= $upper;
    }

    /**
     * Derermines if a string is within a given length
     *
     * @param mixed $value The value to check
     * @param mixed $lower The lower bound
     * @param mixed $upper The upper bound
     * @return bool
     */
    private function validateLength($value, $lower, $upper = null): bool
    {
        $length = strlen($value);

        if ($upper === null) {
            return $length == $lower;
        } else {
            return $length >= $lower && $length <= $upper;
        }
    }

    /**
     * Determines if a given value is a valid email address.
     *
     * @param mixed $value The value to check
     * @return bool
     */
    private function validateEmail($value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Determines if a given value is a valid URL.
     *
     * @param mixed $value The value to check
     * @return bool
     */
    private function validateUrl($value): bool
    {
        return filter_var($value, FILTER_VALIDATE_URL);
    }

    /**
     * Determines if a given value matches a regular expression.
     *
     * @param mixed $value The value to check
     * @param string $regex The regular expression
     * @return bool
     */
    private function validateRegex($value, string $regex): bool
    {
        return preg_match($regex, $value) == 1;
    }
}