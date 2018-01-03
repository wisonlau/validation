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

class Validator implements ValidatorInterface
{
    private $pdo;
    public function __construct()
    {
        $this->pdo = PdoDb::getInstance();
    }

    // 验证的字段必须为 yes、 on、 1、或 true。
    public function acceptedValidate($value)
    {
        $acceptable = ['yes', 'on', '1', 1, true, 'true'];

        return $this->requiredValidate($value) && in_array($value, $acceptable, true);
    }

    // 相当于使用了 PHP 函数 dns_get_record，验证的字段必须具有有效的 A 或 AAAA 记录。
    public function activeUrlValidate($value)
    {
        if (! is_string($value))
        {
            return false;
        }

        if ($url = parse_url($value, PHP_URL_HOST))
        {
            try
            {
                return count(dns_get_record($url, DNS_A | DNS_AAAA)) > 0;
            } catch (\Exception $e) {
                return false;
            }
        }

        return false;
    }

    // 验证的字段必须是给定日期后的值。这个日期将会通过 PHP 函数 strtotime 来验证。
    public function afterValidate($value, $afterField)
    {
        $afterField = $afterField[0];
        if (! is_string($value) && ! is_numeric($value) && ! $value instanceof \DateTimeInterface)
        {
            return false;
        }

        if ( ! is_numeric($value))
        {
            if( ! $this->checkDateIsValid($value))
            {
                return false;
            }
            $value = strtotime($value);
        }
        if ( ! is_numeric($afterField))
        {
            if( ! $this->checkDateIsValid($afterField))
            {
                return false;
            }
            $value = strtotime($afterField);
        }

        return $value > $afterField ? true : false;
    }

    // 验证的字段必须等于给定日期之后的值。
    public function afterOrEqualValidate($value, $afterOrEqualField)
    {
        $afterOrEqualField = $afterOrEqualField[0];
        if (! is_string($value) && ! is_numeric($value) && ! $value instanceof \DateTimeInterface)
        {
            return false;
        }

        if ( ! is_numeric($value))
        {
            if( ! $this->checkDateIsValid($value))
            {
                return false;
            }
            $value = strtotime($value);
        }
        $afterOrEqualField = array_pop($afterOrEqualField);
        if ( ! is_numeric($afterOrEqualField))
        {
            if( ! $this->checkDateIsValid($afterOrEqualField))
            {
                return false;
            }
            $value = strtotime($afterOrEqualField);
        }

        return $value >= $afterOrEqualField ? true : false;
    }

    // 验证的字段必须完全是字母的字符。
    public function alphaValidate($value)
    {
        return is_string($value) && preg_match('/^[\pL\pM]+$/u', $value);
    }

    // 验证的字段可能具有字母、数字、破折号（ - ）以及下划线（ _ ）。
    public function alphaDashValidate($value)
    {
        if (! is_string($value) && ! is_numeric($value))
        {
            return false;
        }

        return preg_match('/^[\pL\pM\pN_-]+$/u', $value) > 0;
    }

    // 验证的字段必须完全是字母、数字。
    public function alphaNumValidate($value)
    {
        if (! is_string($value) && ! is_numeric($value))
        {
            return false;
        }

        return preg_match('/^[\pL\pM\pN]+$/u', $value) > 0;
    }

    // 验证的字段必须是一个 PHP 数组。
    public function arrayValidate($value)
    {
        return is_array($value);
    }

    // 验证的字段必须是给定日期之前的值。这个日期将会通过 PHP 函数 strtotime 来验证。
    public function beforeValidate($value, $beforeField)
    {
        $beforeField = $beforeField[0];
        if (! is_string($value) && ! is_numeric($value) && ! $value instanceof \DateTimeInterface)
        {
            return false;
        }

        if ( ! is_numeric($value))
        {
            if( ! $this->checkDateIsValid($value))
            {
                return false;
            }
            $value = strtotime($value);
        }
        if ( ! is_numeric($beforeField))
        {
            if( ! $this->checkDateIsValid($beforeField))
            {
                return false;
            }
            $value = strtotime($beforeField);
        }

        return $value < $beforeField ? true : false;
    }

    // 验证的字段必须是给定日期之前或之前的值。这个日期将会使用 PHP 函数 strtotime 来验证。
    public function beforeOrEqualValidate($value, $beforeOrEqualField)
    {
        $beforeOrEqualField = $beforeOrEqualField[0];
        if (! is_string($value) && ! is_numeric($value) && ! $value instanceof \DateTimeInterface)
        {
            return false;
        }

        if ( ! is_numeric($value))
        {
            if( ! $this->checkDateIsValid($value))
            {
                return false;
            }
            $value = strtotime($value);
        }
        if ( ! is_numeric($beforeOrEqualField))
        {
            if( ! $this->checkDateIsValid($beforeOrEqualField))
            {
                return false;
            }
            $value = strtotime($beforeOrEqualField);
        }

        return $value <= $beforeOrEqualField ? true : false;
    }

    // 验证的字段的大小必须在给定的 min 和 max 之间。字符串、数字、数组或是文件大小的计算方式都用 size 方法进行评估。
    public function betweenValidate($value, $field)
    {
        $type = $this->validateType($value);
        switch ($type)
        {
            case 'array':
                return (sizeof($value, 1) >= $field[0] && sizeof($value, 1) <= $field[1]) ? true : false;
                break;
            case  'numeric':
                return (strlen($value) >= $field[0] && strlen($value) <= $field[1]) ? true : false;
                break;
            case 'string':
                return (mb_strlen($value, 'utf-8') >= $field[0] && mb_strlen($value, 'utf-8') <= $field[1]) ? true : false;
                break;
            default:
                return false;
        }
    }

    // 验证的字段必须能够被转换为布尔值。可接受的参数为 true、false、1、0、"1" 以及 "0"。
    public function booleanValidate($value)
    {
        $acceptable = [true, false, 0, 1, '0', '1'];

        return in_array($value, $acceptable, true);
    }

    // 验证的字段必须和 foo_confirmation 的字段值一致。例如，如果要验证的字段是 password，输入中必须存在匹配的 password_confirmation 字段。
    public function confirmedValidate($value, $confirmedField)
    {
        return $value == $confirmedField[0] ? true : false;
    }

    // 验证的字段值必须是通过 PHP 函数 strtotime 校验的有效日期。
    public function dateValidate($value)
    {
        if ($value instanceof \DateTime) {
            return true;
        }

        if ((! is_string($value) && ! is_numeric($value)) || strtotime($value) === false)
        {
            return false;
        }

        $date = date_parse($value);

        return checkdate($date['month'], $date['day'], $date['year']);
    }

    // 验证的字段必须等于给定的日期。该日期会被传递到 PHP 函数 strtotime。
    public function dateEqualsValidate($value, $dateEqualsField)
    {
        $dateEqualsField = $dateEqualsField[0];
        if (! is_string($value) && ! is_numeric($value) && ! $value instanceof \DateTimeInterface)
        {
            return false;
        }

        if ( ! is_numeric($value))
        {
            if( ! $this->checkDateIsValid($value))
            {
                return false;
            }
            $value = strtotime($value);
        }
        if ( ! is_numeric($dateEqualsField))
        {
            if( ! $this->checkDateIsValid($dateEqualsField))
            {
                return false;
            }
            $value = strtotime($dateEqualsField);
        }

        return $value == $dateEqualsField ? true : false;
    }

    // 验证的字段必须与给定的格式相匹配。你应该只使用 date 或 date_format 其中一个用于验证，而不应该同时使用两者。
    public function dateFormatValidate($value, $dateEqualsField)
    {
        $dateEqualsField = $dateEqualsField[0];
        if (! is_string($value) && ! is_numeric($value))
        {
            return false;
        }

        $parsed = date_parse_from_format($dateEqualsField, $value);
        return $parsed['error_count'] === 0 && $parsed['warning_count'] === 0;
    }

    // 验证的字段值必须与字段 (field) 的值不同。
    public function differentValidate($value, $differentField)
    {
        $differentField = array_pop($differentField);
        if (! is_string($value) && ! is_numeric($value))
        {
            return false;
        }

        return (string) $value == (string) $differentField ? true : false;
    }

    // 验证的字段必须是数字，并且必须具有确切的值。
    public function digitsValidate($value, $digitsField)
    {
        $digitsField = array_pop($digitsField);
        return ! preg_match('/[^0-9]/', $value)
            && strlen((string) $value) == $digitsField;
    }

    // 验证的字段的长度必须在给定的 min 和 max 之间。
    public function digitsBetweenValidate($value, $field)
    {
        $length = strlen((string) $value);

        return ! preg_match('/[^0-9]/', $value)
            && $length >= $field[0] && $length <= $field[1];
    }

    // 验证数组时，指定的字段不能有任何重复值。如果是一维数组则distinctField为空。
    public function distinctValidate($value, $distinctField = null)
    {
        $distinctField = array_pop($distinctField);
        if ($distinctField == null)
        {
            if (count($value) != count($value, 1))
            {
                return false;
            }
        }
        else
        {
            $value = array_column($value, $distinctField);
        }

        if (count($value) != count(array_unique($value)))
        {
            return false;
        }

        return true;
    }

    // 验证的字段必须符合 e-mail 地址格式。如果你需要指定 exists 方法用来查询的数据库。你可以通过使用「点」语法将数据库的名称添加到数据表前面来实现这个目的。
    public function emailValidate($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) ? true : false;
    }

    // 验证的字段在存在时不能为空。
    public function filledValidate($value)
    {
        if (is_null($value))
        {
            return false;
        }

        if (isset($value))
        {
            if ($value === '')
            {
                return false;
            }
        }

        return true;
    }

    // 验证的字段必须包含在给定的值列表中。因为这个规则通常需要你 implode 一个数组。
    public function inValidate($value, $inField)
    {
        $inField = array_pop($inField);
        if(is_array($value))
        {
            if (is_array($inField))
            {
                return array_diff($inField, $value) ? false : true;
            }
            else
            {
                if (in_array($inField, $value))
                {
                    return true;
                }
            }
        }
        else
        {
            if (is_array($inField))
            {
                if (in_array($value, $inField))
                {
                    return true;
                }
            }
            else
            {
                return (string)$value == (string)$inField ? true : false;
            }
        }

        return false;
    }

    // 验证的字段必须存在于另一个字段（anotherfield）的值中。
    public function inArrayValidate($value, $inArrayField)
    {
        $inArrayField = array_pop($inArrayField);
        if( is_array($value) )
        {
            if (in_array($inArrayField, $value))
            {
                return true;
            }

            foreach ($value as $item)
            {
                if (is_array($item) && in_array($inArrayField, $item))
                {
                    return true;
                }
            }
            return false;
        }
        else
        {
            if (! is_string($value) && ! is_numeric($value))
            {
                return false;
            }

            return (string) $value == (string) $inArrayField ? true : false;
        }
    }

    // 验证的字段必须是整数。
    public function integerValidate($value)
    {
        return filter_var($value, FILTER_VALIDATE_INT) ? true : false;
    }

    // 验证的字段必须是 IP 地址。
    public function ipValidate($value)
    {
        return filter_var($value, FILTER_VALIDATE_IP) ? true : false;
    }

    // 验证的字段必须是 IPv4 地址。
    public function ipv4Validate($value)
    {
        return filter_var($value, FILTER_VALIDATE_IP) ? true : false;
    }

    // 验证的字段必须是 IPv6 地址。
    public function ipv6Validate($value)
    {
        return filter_var($value, FILTER_VALIDATE_IP) ? true : false;
    }

    // 验证的字段必须是有效的 JSON 字符串。
    public function jsonValidate($value)
    {
        if (! is_scalar($value) && ! method_exists($value, '__toString')) {
            return false;
        }

        json_decode($value);

        return json_last_error() === JSON_ERROR_NONE;
    }

    // 验证中的字段必须小于或等于 value。字符串、数字、数组或是文件大小的计算方式都用 size 方法进行评估。
    public function maxValidate($value, $maxField)
    {
        $type = $this->validateType($value);
        $maxField = $maxField[0];

        switch ($type)
        {
            case 'array':
                return (sizeof($value, 1) <= $maxField) ? true : false;
                break;
            case  'numeric':
                return (strlen($value) <= $maxField) ? true : false;
                break;
            case 'string':
                return (mb_strlen($value, 'utf-8') <= $maxField) ? true : false;
                break;
            default:
                return false;
        }
    }

    // 验证中的字段必须具有最小值。字符串、数字、数组或是文件大小的计算方式都用 size 方法进行评估。
    public function minValidate($value, $minField)
    {
        $type = $this->validateType($value);
        $minField = $minField[0];

        switch ($type)
        {
            case 'array':
                return (sizeof($value, 1) >= $minField) ? true : false;
                break;
            case  'numeric':
                return (strlen($value) >= $minField) ? true : false;
                break;
            case 'string':
                return (mb_strlen($value, 'utf-8') >= $minField) ? true : false;
                break;
            default:
                return false;
        }
    }

    // 验证的字段可以为 null。这在验证基本数据类型时特别有用，例如可以包含空值的字符串和整数。
    public function nullAbleValidate($value)
    {
        return true;
    }

    // 验证的字段不能包含在给定的值列表中。foo,bar,...
    public function notInValidate($value, $notInField)
    {
        return !$this->inValidate($value, $notInField);
    }

    // 验证的字段必须是数字。
    public function numericValidate($value)
    {
        return is_numeric($value);
    }

    // 验证的字段必须存在于输入数据中，但可以为空。
    public function presentValidate($value, $presentField)
    {
        $presentField = array_pop($presentField);
        if (is_null($value))
        {
            return true;
        }

        return $this->inValidate($value, $presentField);
    }

    // 验证的字段必须与给定的正则表达式匹配。注意： 当使用 regex 规则时，你必须使用数组，而不是使用 | 分隔符，特别是如果正则表达式包含 | 字符。
    public function regexValidate($value, $regexField)
    {
        $regexField = array_pop($regexField);
        if (! is_string($value) && ! is_numeric($value)) {
            return false;
        }

        return preg_match($regexField, $value) > 0;
    }

    // 验证的字段必须存在于输入数据中，而不是空。如果满足以下条件之一，则字段被视为「空」：该值为 null。该值为空字符串。该值为空数组或空的 可数 对象。该值为没有路径的上传文件。
    public function requiredValidate($value)
    {
        if (is_null($value))
        {
            return false;
        }
        elseif (is_string($value) && trim($value) === '')
        {
            return false;
        }
        elseif ((is_array($value) || $value instanceof \Countable) && count($value) < 1)
        {
            return false;
        }

        return true;
    }

    // 如果指定的其它字段（ anotherfield ）等于任何一个 value 时，被验证的字段必须存在且不为空。anotherfield,value,...
    public function requiredIfValidate($value, $requiredIfField)
    {
        return true; // todo
    }

    // 如果指定的其它字段（ anotherfield ）等于任何一个 value 时，被验证的字段不必存在。anotherfield,value,...
    public function requiredUnlessValidate($value, $requiredUnlessField)
    {
        return true; // todo
    }

    // 只要在指定的其他字段中有任意一个字段存在时，被验证的字段就必须存在并且不能为空。foo,bar,...
    public function requiredWithValidate($value, $requiredWithField)
    {
        return true; // todo
    }

    // 只有当所有的其他指定字段全部存在时，被验证的字段才必须存在并且不能为空。foo,bar,...
    public function requiredWithAllValidate($value, $requiredWithAllField)
    {
        return true; // todo
    }

    // 只要在其他指定的字段中有任意一个字段不存在，被验证的字段就必须存在且不为空。foo,bar,...
    public function requiredWithoutValidate($value, $requiredWithoutField)
    {
        return true; // todo
    }

    // 只有当所有的其他指定的字段都不存在时，被验证的字段才必须存在且不为空。foo,bar,...
    public function requiredWithoutAllValidate($value, $requiredWithoutAllField)
    {
        return true; // todo
    }

    // 给定字段必须与验证的字段匹配。
    public function sameValidate($value, $sameField)
    {
        $sameField = array_pop($sameField);
        if ( ! is_numeric($value) && ! is_string($value))
        {
            return false;
        }

        return (string)$value == (string)$sameField ? true : false;
    }

    // 验证的字段必须具有与给定值匹配的大小。对于字符串来说，value 对应于字符数。对于数字来说，value 对应于给定的整数值。对于数组来说， size 对应的是数组的 count 值。对文件来说，size 对应的是文件大小（单位 kb ）。
    public function sizeValidate($value, $sizeField)
    {
        $sizeField = array_pop($sizeField);
        if (is_array($value))
        {
            return count($value, 1) == $sizeField ? true : false;
        }
        elseif(is_string($value))
        {
            return mb_strlen($value, 'utf-8') == $sizeField ? true : false;
        }
        elseif (is_numeric($value))
        {
            return strlen($value) == $sizeField ? true : false;
        }

        return false;
    }

    // 验证的字段必须是字符串。如果要允许该字段的值为 null ，就将 nullable 规则附加到该字段中。
    public function stringValidate($value)
    {
        return is_string($value);
    }

    // 验证的字段必须是有效的时区标识符，会根据 PHP 函数 timezone_identifiers_list 来判断。
    public function timezoneValidate($value)
    {
        try {
            new \DateTimeZone($value);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    // 验证的字段在给定的数据库表中必须是唯一的。unique:table,column,column2
    public function uniqueValidate($value, $field)
    {
        if (count($exp_field = explode('=', $field[2])) > 1)
        {
            $and = " AND `{$exp_field[0]}` <> '{$exp_field[1]}'";
        }
        else
        {
            $and = " AND id <> '{$field[2]}'";
        }

        $result = $this->pdo->prepare("SELECT * FROM `{$field[0]}` WHERE `{$field[1]}`='{$value}' {$and} LIMIT 1");

        if ($result)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    // 验证的字段必须存在于给定的数据库表中。exists:table,column,column2
    public function existsValidate($value, $field)
    {
        $result = $this->pdo->prepare("SELECT * FROM `{$field[0]}` WHERE `{$field[1]}`='{$value}' LIMIT 1");

        if ($result)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    // 验证表是否存在
    public function tableValidate($value)
    {
        $result = $this->pdo->prepare("SHOW TABLES LIKE '{$value}'");

        if ($result)
        {
            return true;
        }
        else
        {
            return false;
        }

    }

    // 验证数据库是否存在
    public function databaseValidate($value)
    {
        $result = $this->pdo->prepare("SELECT * FROM information_schema.SCHEMATA where SCHEMA_NAME='{$value}'");

        if ($result)
        {
            return true;
        }
        else
        {
            return false;
        }

    }

    // 验证的字段必须是成功上传的文件。
    public function fileValidate($value)
    {
        foreach ($value as $file)
        {
            if (empty($file['tmp_name']))
            {
                return false;
            }
        }

        return true;
    }

    // 验证的文件必须是一个图像（ jpeg、png、bmp、gif、或 svg ）。
    public function imageValidate($value)
    {
        $mimes = ['jpeg', 'png', 'gif', 'bmp', 'svg'];

        if (empty($value))
        {
            return false;
        }

        foreach ($value as $file)
        {
            if (count($file['tmp_name']) > 1)
            {
                foreach (array_filter($file['type']) as $item)
                {
                    if ( ! in_array(str_replace('image/','', $item), $mimes))
                    {
                        return false;
                    }
                }
            }
            else
            {
                if ( ! in_array(str_replace('image/','', $file['type']), $mimes))
                {
                    return false;
                }
            }
        }

        return true;
    }

    // 验证的文件必须与给定 MIME 类型之一匹配。text/plain,...
    public function mimeTypesValidate($value, $mimeTypesField)
    {
        $mimeTypesField = array_pop($mimeTypesField);
        if (empty($value))
        {
            return false;
        }

        $mimeTypesField = is_array($mimeTypesField) ? $mimeTypesField : [$mimeTypesField];

        foreach ($value as $file)
        {
            if (count($file['tmp_name']) > 1)
            {
                foreach (array_filter($file['type']) as $item)
                {
                    if ( ! in_array($item, $mimeTypesField))
                    {
                        return false;
                    }
                }
            }
            else
            {
                if ( ! in_array($file['type'], $mimeTypesField))
                {
                    return false;
                }
            }
        }

        return true;
    }

    // 验证的文件必须具有与列出的其中一个扩展名相对应的 MIME 类型。foo,bar,...
    public function mimesValidate($value, $mimesField)
    {
        if (empty($value))
        {
            return false;
        }

        $mimesField = is_array($mimesField) ? $mimesField : [$mimesField];

        foreach ($value as $file)
        {
            if (count($file['tmp_name']) > 1)
            {
                foreach (array_filter($file['type']) as $item)
                {
                    if ( ! in_array(str_replace('image/','', $item), $mimesField))
                    {
                        return false;
                    }
                }
            }
            else
            {
                if ( ! in_array(str_replace('image/','', $file['type']), $mimesField))
                {
                    return false;
                }
            }
        }

        return true;
    }

    // 验证的文件必须是图片并且图片比例必须符合规则,可用的规则为： min_width、 max_width 、 min_height 、 max_height 、 width 、 height
    public function dimensionsValidate($value, $field)
    {
        if (empty($value))
        {
            return false;
        }

        $widthValidate  = explode('=', $field[0]);
        $heightValidate = explode('=', $field[1]);

        foreach ($value as $file)
        {
            if (count($file['tmp_name']) > 1)
            {
                foreach (array_filter($file['tmp_name']) as $item)
                {
                    $pic_info = getimagesize($item);
                    $width  = $pic_info[0];
                    $height = $pic_info[1];

                    switch ($widthValidate[0])
                    {
                        case 'width':
                            if ($widthValidate[1] != $width)
                            {
                                return false;
                            }
                            break;
                        case 'max_width':
                            if ($widthValidate[1] >= $width)
                            {
                                return false;
                            }
                            break;
                        case 'min_width':
                            if ($widthValidate[1] <= $width)
                            {
                                return false;
                            }
                            break;
                    }

                    switch ($heightValidate[0])
                    {
                        case 'height':
                            if ($heightValidate[1] != $height)
                            {
                                return false;
                            }
                            break;
                        case 'max_height':
                            if ($heightValidate[1] >= $height)
                            {
                                return false;
                            }
                            break;
                        case 'min_height':
                            if ($heightValidate[1] <= $height)
                            {
                                return false;
                            }
                            break;
                    }

                }
            }
            else
            {
                $pic_info = getimagesize($file['tmp_name']);
                $width  = $pic_info[0];
                $height = $pic_info[1];

                switch ($widthValidate[0])
                {
                    case 'width':
                        if ($widthValidate[1] != $width)
                        {
                            return false;
                        }
                        break;
                    case 'max_width':
                        if ($widthValidate[1] >= $width)
                        {
                            return false;
                        }
                        break;
                    case 'min_width':
                        if ($widthValidate[1] <= $width)
                        {
                            return false;
                        }
                        break;
                }

                switch ($heightValidate[0])
                {
                    case 'height':
                        if ($heightValidate[1] != $height)
                        {
                            return false;
                        }
                        break;
                    case 'max_height':
                        if ($heightValidate[1] >= $height)
                        {
                            return false;
                        }
                        break;
                    case 'min_height':
                        if ($heightValidate[1] <= $height)
                        {
                            return false;
                        }
                        break;
                }

            }

        }
        return true;
    }

    // 验证是否为中文
    public function chineseValidate($value, $encode = 'gbk')
    {
        $encode = array_pop($encode);
        switch ($encode)
        {
            case "utf-8":
                $regexp = "/^[\x{4e00}-\x{9fa5}]+$/u";
                break;
            default:
                $regexp = "/^[" . chr(0xa1) . "-" . chr(0xff) . "]+$/";
                break;
        }
        return preg_match($regexp, $value);
    }

    // 身份证
    public function idCardValidate($value)
    {
        $id = strtoupper($value);
        $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/";
        $arr_split = array();
        if(!preg_match($regx, $id))
        {
            return false;
        }
        if(15==strlen($id)) //检查15位
        {
            $regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/";

            @preg_match($regx, $id, $arr_split);
            //检查生日日期是否正确
            $dtm_birth = "19".$arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
            if( ! strtotime($dtm_birth))
            {
                return false;
            }
            else
            {
                return true;
            }
        }
        else           //检查18位
        {
            $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/";
            @preg_match($regx, $id, $arr_split);
            $dtm_birth = $arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
            if( ! strtotime($dtm_birth))  //检查生日日期是否正确
            {
                return false;
            }
            else
            {
                //检验18位身份证的校验码是否正确。
                //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
                $arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
                $arr_ch  = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
                $sign = 0;
                for ( $i = 0; $i < 17; $i++ )
                {
                    $b = (int) $id{$i};
                    $w = $arr_int[$i];
                    $sign += $b * $w;
                }
                $n  = $sign % 11;
                $val_num = $arr_ch[$n];
                if ($val_num != substr($id,17, 1))
                {
                    return false;
                }
                else
                {
                    return true;
                }
            }
        }
    }

    /**
     * Validate Type
     * @param $value
     * @return string
     */
    private function validateType($value)
    {
        if (is_array($value))
        {
            return 'array';
        }
        elseif (is_numeric($value))
        {
            return 'numeric';
        }
        elseif (is_string($value))
        {
            return 'string';
        }
        else
        {
            return 'null';
        }
    }

    /**
     * Check Date Is Valid
     * @param       $date
     * @param array $formats
     * @return bool
     */
    private function checkDateIsValid($date, $formats = array("Y-m-d H:i:s", "Y-m-d", "Y-m", "Y/m/d H:i:s", "Y/m/d", "Y/m"))
    {
        $unixTime = strtotime($date);
        if ( ! $unixTime)
        {
            return false;
        }

        foreach ($formats as $format)
        {
            if (date($format, $unixTime) == $date)
            {
                return true;
            }
        }

        return false;
    }

    public function __call($method, $parameters)
    {
        throw new \UnexpectedValueException("Validate rule [$method] does not exist!");
    }
}
