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

interface ValidatorInterface
{
    // 验证的字段必须为 yes、 on、 1、或 true。
    public function acceptedValidate($value);

    // 相当于使用了 PHP 函数 dns_get_record，验证的字段必须具有有效的 A 或 AAAA 记录。
    public function activeUrlValidate($value);

    // 验证的字段必须是给定日期后的值。这个日期将会通过 PHP 函数 strtotime 来验证。
    public function afterValidate($value, $afterField);

    // 验证的字段必须等于给定日期之后的值。
    public function afterOrEqualValidate($value, $afterOrEqualField);

    // 验证的字段必须完全是字母的字符。
    public function alphaValidate($value);

    // 验证的字段可能具有字母、数字、破折号（ - ）以及下划线（ _ ）。
    public function alphaDashValidate($value);

    // 验证的字段必须完全是字母、数字。
    public function alphaNumValidate($value);

    // 验证的字段必须是一个 PHP 数组。
    public function arrayValidate($value);

    // 验证的字段必须是给定日期之前的值。这个日期将会通过 PHP 函数 strtotime 来验证。
    public function beforeValidate($value, $beforeField);

    // 验证的字段必须是给定日期之前或之前的值。这个日期将会使用 PHP 函数 strtotime 来验证。
    public function beforeOrEqualValidate($value, $beforeOrEqualField);

    // 验证的字段的大小必须在给定的 min 和 max 之间。字符串、数字、数组或是文件大小的计算方式都用 size 方法进行评估。
    public function betweenValidate($value, $field);

    // 验证的字段必须能够被转换为布尔值。可接受的参数为 true、false、1、0、"1" 以及 "0"。
    public function booleanValidate($value);

    // 验证的字段必须和 foo_confirmation 的字段值一致。例如，如果要验证的字段是 password，输入中必须存在匹配的 password_confirmation 字段。
    public function confirmedValidate($value, $confirmedField);

    // 验证的字段值必须是通过 PHP 函数 strtotime 校验的有效日期。
    public function dateValidate($value);

    // 验证的字段必须等于给定的日期。该日期会被传递到 PHP 函数 strtotime。
    public function dateEqualsValidate($value, $dateEqualsField);

    // 验证的字段必须与给定的格式相匹配。你应该只使用 date 或 date_format 其中一个用于验证，而不应该同时使用两者。
    public function dateFormatValidate($value, $dateEqualsField);

    // 验证的字段值必须与字段 (field) 的值不同。
    public function differentValidate($value, $differentField);

    // 验证的字段必须是数字，并且必须具有确切的值。
    public function digitsValidate($value, $digitsField);

    // 验证的字段的长度必须在给定的 min 和 max 之间。
    public function digitsBetweenValidate($value, $field);

    // 验证数组时，指定的字段不能有任何重复值。如果是一维数组则distinctField为空。
    public function distinctValidate($value, $distinctField = null);

    // 验证的字段必须符合 e-mail 地址格式。如果你需要指定 exists 方法用来查询的数据库。你可以通过使用「点」语法将数据库的名称添加到数据表前面来实现这个目的。
    public function emailValidate($value);

    // 验证的字段在存在时不能为空。
    public function filledValidate($value);

    // 验证的字段必须包含在给定的值列表中。因为这个规则通常需要你 implode 一个数组。
    public function inValidate($value, $inField);

    // 验证的字段必须存在于另一个字段（anotherfield）的值中。
    public function inArrayValidate($value, $inArrayField);

    // 验证的字段必须是整数。
    public function integerValidate($value);

    // 验证的字段必须是 IP 地址。
    public function ipValidate($value);

    // 验证的字段必须是 IPv4 地址。
    public function ipv4Validate($value);

    // 验证的字段必须是 IPv6 地址。
    public function ipv6Validate($value);

    // 验证的字段必须是有效的 JSON 字符串。
    public function jsonValidate($value);

    // 验证中的字段必须小于或等于 value。字符串、数字、数组或是文件大小的计算方式都用 size 方法进行评估。
    public function maxValidate($value, $maxField);

    // 验证中的字段必须具有最小值。字符串、数字、数组或是文件大小的计算方式都用 size 方法进行评估。
    public function minValidate($value, $minField);

    // 验证的字段可以为 null。这在验证基本数据类型时特别有用，例如可以包含空值的字符串和整数。
    public function nullAbleValidate($value);

    // 验证的字段不能包含在给定的值列表中。foo,bar,...
    public function notInValidate($value, $notInField);

    // 验证的字段必须是数字。
    public function numericValidate($value);

    // 验证的字段必须存在于输入数据中，但可以为空。
    public function presentValidate($value, $presentField);

    // 验证的字段必须与给定的正则表达式匹配。注意： 当使用 regex 规则时，你必须使用数组，而不是使用 | 分隔符，特别是如果正则表达式包含 | 字符。
    public function regexValidate($value, $regexField);

    // 验证的字段必须存在于输入数据中，而不是空。如果满足以下条件之一，则字段被视为「空」：该值为 null。该值为空字符串。该值为空数组或空的 可数 对象。该值为没有路径的上传文件。
    public function requiredValidate($value);

    // 如果指定的其它字段（ anotherfield ）等于任何一个 value 时，被验证的字段必须存在且不为空。anotherfield,value,...
    public function requiredIfValidate($value, $requiredIfField);

    // 如果指定的其它字段（ anotherfield ）等于任何一个 value 时，被验证的字段不必存在。anotherfield,value,...
    public function requiredUnlessValidate($value, $requiredUnlessField);

    // 只要在指定的其他字段中有任意一个字段存在时，被验证的字段就必须存在并且不能为空。foo,bar,...
    public function requiredWithValidate($value, $requiredWithField);

    // 只有当所有的其他指定字段全部存在时，被验证的字段才必须存在并且不能为空。foo,bar,...
    public function requiredWithAllValidate($value, $requiredWithAllField);

    // 只要在其他指定的字段中有任意一个字段不存在，被验证的字段就必须存在且不为空。foo,bar,...
    public function requiredWithoutValidate($value, $requiredWithoutField);

    // 只有当所有的其他指定的字段都不存在时，被验证的字段才必须存在且不为空。foo,bar,...
    public function requiredWithoutAllValidate($value, $requiredWithoutAllField);

    // 给定字段必须与验证的字段匹配。
    public function sameValidate($value, $sameField);

    // 验证的字段必须具有与给定值匹配的大小。对于字符串来说，value 对应于字符数。对于数字来说，value 对应于给定的整数值。对于数组来说， size 对应的是数组的 count 值。对文件来说，size 对应的是文件大小（单位 kb ）。
    public function sizeValidate($value, $sizeField);

    // 验证的字段必须是字符串。如果要允许该字段的值为 null ，就将 nullable 规则附加到该字段中。
    public function stringValidate($value);

    // 验证的字段必须是有效的时区标识符，会根据 PHP 函数 timezone_identifiers_list 来判断。
    public function timezoneValidate($value);

    // 验证的字段在给定的数据库表中必须是唯一的。unique:table,column,column2
    public function uniqueValidate($value, $field);

    // 验证的字段必须存在于给定的数据库表中。exists:table,column,column2
    public function existsValidate($value, $field);

    // 验证表是否存在
    public function tableValidate($value);

    // 验证数据库是否存在
    public function databaseValidate($value);

    // 验证的字段必须是成功上传的文件。
    public function fileValidate($value);

    // 验证的文件必须是一个图像（ jpeg、png、bmp、gif、或 svg ）。
    public function imageValidate($value);

    // 验证的文件必须与给定 MIME 类型之一匹配。text/plain,...
    public function mimeTypesValidate($value, $mimeTypesField);

    // 验证的文件必须具有与列出的其中一个扩展名相对应的 MIME 类型。foo,bar,...
    public function mimesValidate($value, $mimesField);

    // 验证的文件必须是图片并且图片比例必须符合规则,可用的规则为： min_width、 max_width 、 min_height 、 max_height 、 width 、 height
    public function dimensionsValidate($value, $field);

    // 验证是否为中文
    public function chineseValidate($value, $encode = 'gbk');

    // 身份证
    public function idCardValidate($value);
}
