<?php

/*
 * This file is part of the wisonlau/validation.
 *
 * (c) wisonlau <122022066@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Wisonlau\Validation;

class Factory
{
    public $success = true;
    public $errors  = [];

    private $message = [];
    private $data;
    private $rules;
    private $validator;

    private $dateRules   = array('after', 'afterOrEqual', 'before', 'beforeOrEqual');
    private $formatRules = array('dateFormat');
    private $otherRules  = array('different', 'inArray', 'same');
    private $digitsRules = array('digits');
    private $maxRules    = array('max');
    private $minRules    = array('min');
    private $valuesRules = array('mimes', 'mimetypes', 'requiredWith', 'requiredWithAll', 'requiredWithout', 'requiredWithoutAll');
    private $sizeRules   = array('size');

    private $minMaxRules       = array('between', 'digitsBetween');
    private $otherValueRules   = array('requiredIf', 'requiredUnless');


    public function __construct($data, $rules, $attributes = null, $mode = 'make', $messages = null, $lang = 'zh_cn')
    {
        $this->data    = $data;
        $this->rules   = $rules;

        is_file(__DIR__ . '/lang/' . $lang . '.php') ? $this->message = require_once __DIR__ . '/lang/' . $lang . '.php' : $this->message = require_once __DIR__ . '/lang/zh_cn.php';
        $attributes ? ($this->message['attributes'] = array_merge($this->message['attributes'], $attributes)) : null;

        $this->validator = new Validator();
        $this->$mode();
    }

    public function make()
    {
        foreach ($this->rules as $attribute => $rule)
        {
            foreach (explode('|', $rule) as $item)
            {
                $explodeRules = explode(':', $item);

                if (count( $explodeRules ) > 1 && strpos($explodeRules[1], ','))
                {
                    $pop_arr       = array_pop($explodeRules);
                    $explodeRules  = array_merge($explodeRules, explode(',', $pop_arr));
                }

                $valid = false;
                if (in_array($attribute, array_keys($this->data)))
                {
                    $compare = $explodeRules[0] . 'Validate';
                    switch (count( $explodeRules ))
                    {
                        case '1':
                            $valid = $this->validator->$compare($this->data[$attribute]);
                            break;
                        default:
                            // 后面的转数组
                            $valid = $this->validator->$compare($this->data[$attribute], array_slice($explodeRules, 1));
                            break;
                    }
                }

                $valid !== true ? $this->makeError($attribute, $explodeRules) : null;

            }
        }

        count($this->errors) ? $this->success = false : true;
    }

    /**
     * Merging error messages
     * @param $attribute
     * @param $validateInfo
     * @return array
     */
    private function makeError($attribute, $validateInfo)
    {

        $attribute = empty($this->message['attributes'][$attribute]) ? $attribute : $this->message['attributes'][$attribute];
        $error = str_replace(':attribute', $attribute, $this->message['messages'][$validateInfo[0]]);
        switch (count($validateInfo))
        {
            case '2':
                $field_arr = array('dateRules' => ':date', 'formatRules' => ':format', 'otherRules' => ':other', 'digitsRules' => ':digits', 'maxRules' => ':max', 'minRules' => ':min', 'valuesRules' => ':values', 'sizeRules' => ':size');
                foreach ($field_arr as $key => $value)
                {
                    in_array($validateInfo[0], $this->$key) ? ($error = str_replace($value, $validateInfo[1], $error)) : null;
                }
                break;
            case '3':
                $field_arr = array('minMaxRules' => array(':min', ':max'), 'otherValueRules' => array(':other', ':value'));
                foreach ($field_arr as $key => $value)
                {
                    in_array($validateInfo[0], $this->$key) ? ( $error = str_replace($value[1], $validateInfo[2], str_replace($value[0], $validateInfo[1], $error)) ) : null;
                }
                break;
            default:
                $field_arr = array('valuesRules' => ':values');
                foreach ($field_arr as $key => $value)
                {
                    in_array($validateInfo[0], $this->$key) ? ($error = str_replace($value, implode(',', $validateInfo), $error)) : null;
                }
        }

        $this->errors[] = $error;
    }


    public function validate()
    {
        foreach ($this->rules as $attribute => $rule)
        {
            if (isset($rule[1]))
            {
                $this->message['attributes'] = array_merge($this->message['attributes'], array($attribute => $rule[1]));
            }

            foreach (explode('|', $rule[0]) as $item)
            {
                $explodeRules = explode(':', $item);

                if (count( $explodeRules ) > 1 && strpos($explodeRules[1], ','))
                {
                    $pop_arr       = array_pop($explodeRules);
                    $explodeRules  = array_merge($explodeRules, explode(',', $pop_arr));
                }

                $valid = false;
                if (in_array($attribute, array_keys($this->data)))
                {
                    $compare = $explodeRules[0] . 'Validate';
                    switch (count( $explodeRules ))
                    {
                        case '1':
                            $valid = $this->validator->$compare($this->data[$attribute]);
                            break;
                        default:
                            // 后面的转数组
                            $valid = $this->validator->$compare($this->data[$attribute], array_slice($explodeRules, 1));
                            break;
                    }
                }

                $valid !== true ? $this->makeError($attribute, $explodeRules) : null;

            }
        }

        count($this->errors) ? $this->success = false : true;
    }

    /**
     * Custom rules
     */
    public function extend()
    {
        // do something
    }

}
