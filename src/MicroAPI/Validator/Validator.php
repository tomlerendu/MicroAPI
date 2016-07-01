<?php

namespace MicroAPI\Validator;

class Validator
{
    use Rules;

    private $data;
    private $rules;

    /**
     * Validator constructor.
     *
     * @param array $data The data to be validated
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;

        if (method_exists($this, 'rules')) {
            $this->rules();
        }
    }

    /**
     * Set the data to be validated.
     *
     * @param array $data The data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * Adds rules or a single rule to the validator.
     *
     * @param string $key The data the rule is for
     * @param mixed $rules The rule(s)
     */
    public function addRules(string $key, $rules)
    {
        //If a single rule was passed
        if (is_string($rules)) {
            $rules = [$rules];
        }

        //If there are already rules for the key
        if (isset($this->rules[$key])) {
            $this->rules[$key] = array_merge($this->rules[$key], $rules);
        } else {
            $this->rules[$key] = $rules;
        }
    }

    /**
     * Removes all rules for a piece of data.
     *
     * @param string $key The key to remove the rules for
     */
    public function resetRules(string $key)
    {
        unset($this->rules[$key]);
    }

    /**
     * Get rules for a piece of data.
     *
     * @param string $key The key to get the rules for
     * @return array
     */
    public function getRules(string $key): array
    {
        return $this->rules[$key] ?? [];
    }

    /**
     * Determines if the rules successfully validate the data.
     *
     * @return bool
     */
    public function isValid(): bool
    {
        foreach ($this->rules as $ruleFor => $ruleSet) {
            foreach ($ruleSet as $rule) {
                $rule = $this->parseRule($rule);

                if (method_exists($this, $rule['method'])) {
                    $params = array_merge([$this->data[$ruleFor]], $rule['params']);
                    if (!$this->{$rule['method']}(...$params)) {
                        return false;
                    }
                } else {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Parses a rule into the format ['method' => method-name, 'params' => [each, param, here]].
     *
     * @param string $rule The rule to parse
     * @return array
     */
    private function parseRule(string $rule): array
    {
        if (strpos($rule, ':') !== false) {
            //Parameters were passed
            $rule = explode(':', $rule);
            $params = explode(',', $rule[1]);
            $method = $rule[0];
        } else {
            //No parameters
            $params = [];
            $method = $rule;
        }

        $method = 'validate' . ucfirst($method);

        return [
            'method' => $method,
            'params' => $params
        ];
    }
}