Validation
==========

Validation 是旨在让你更方便的在项目中便捷的完成数据验证。

# Usage

```php
<?php

use Wisonlau\Validation\Factory as ValidatorFactory;

方法一:

//验证
$rules = [
    'username' => 'required|min:5',
    'password' => 'max:10',
    ///...
];

$attributes = [
    'username' => '用户名',
    'password' => '密码',
];

//初始化工厂对象
$factory = new ValidatorFactory($input, $rules, $attributes);

//判断验证是否通过
if ($factory->success {
    //通过
} else {
    //未通过
    //输出错误消息
    print_r($factory->errors);
}

方法二:

//验证
$rules = [
            'name'  => ['required|min:3|max:9|between:5,9|unique:users,name,id=2', '用户'],
            'email' => ['required|email', '邮箱']
];

//初始化工厂对象
$factory = new ValidatorFactory($input, $rules, $attributes, 'validate');

//判断验证是否通过
if ($factory->success {
    //通过
} else {
    //未通过
    //输出错误消息
    print_r($factory->errors);
}

```

## 自定义消息语言：

> 语言列表可以放在lang目录

默认为zh_cn,目前已有语言zh_cn,zh_hk,zh_tw,en

以中文为例：

```php
$messages = [
    'accepted'             => ':attribute 必须接受。',
    'active_url'           => ':attribute 不是一个有效的网址。',
    'after'                => ':attribute 必须是一个在 :date 之后的日期。',
    'alpha'                => ':attribute 只能由字母组成。',
    'alpha_dash'           => ':attribute 只能由字母、数字和斜杠组成。',
    'alpha_num'            => ':attribute 只能由字母和数字组成。',
    // ...
];

//初始化工厂对象
$factory = new ValidatorFactory($data, $rules, $attributes, 'validate', $messages, 'zh_cn');

```

## 设置属性名称

```php
$attributes = [
    'username' => '用户名',
    'password' => '密码',
];

$rules = [
    'username' => 'required|min:5',
    'password' => 'confirmed',
    ///...
];

$messages = [...]; // 自定义消息，如果你在初始化 factory 的时候已经设置了消息，则留空即可

$factory = new ValidatorFactory($input, $rules, $attributes, 'make', $messages);
```

## 自定义扩展

```php
$factory = new ValidatorFactory($input, $rules, $attributes, 'make', $messages);
$factory->extend();
```


## 可用的验证规则

以下是所有可用的验证规则及其功能的清单：
```
Accepted
Active URL
After (Date)
After Or Equal (Date)
Alpha
Alpha Dash
Alpha Numeric
Array
Before (Date)
Before Or Equal (Date)
Between
Boolean
Confirmed
Date
Date Equals
Date Format
Different
Digits
Digits Between
Dimensions (Image Files)
Distinct
E-Mail
Exists (Database)
File
Filled
Image (File)
In
In Array
Integer
IP Address
JSON
Max
MIME Types
MIME Type By File Extension
Min
Nullable
Not In
Numeric
Present
Regular Expression
Required
Required If
Required Unless
Required With
Required With All
Required Without
Required Without All
Same
Size
String
Timezone
Unique (Database)
URL
Table (Database)
Database (Database)
Chinese
Idcard
```

```
accepted
验证的字段必须为 yes、 on、 1、或 true。这在确认「服务条款」是否同意时相当有用。

active_url
相当于使用了 PHP 函数 dns_get_record，验证的字段必须具有有效的 A 或 AAAA 记录。

after:date
验证的字段必须是给定日期后的值。这个日期将会通过 PHP 函数 strtotime 来验证。

'start_date' => 'required|date|after:tomorrow'
你也可以指定其它的字段来比较日期：

'finish_date' => 'required|date|after:start_date'

after_or_equal:date
验证的字段必须等于给定日期之后的值。更多信息请参见 after 规则。

alpha
验证的字段必须完全是字母的字符。


alpha_dash
验证的字段可能具有字母、数字、破折号（ - ）以及下划线（ _ ）。


alpha_num
验证的字段必须完全是字母、数字。


array
验证的字段必须是一个 PHP 数组。


before:date
验证的字段必须是给定日期之前的值。这个日期将会通过 PHP 函数 strtotime 来验证。

before_or_equal:date
验证的字段必须是给定日期之前或之前的值。这个日期将会使用 PHP 函数 strtotime 来验证。

between:min,max
验证的字段的大小必须在给定的 min 和 max 之间。字符串、数字、数组或是文件大小的计算方式都用 size 方法进行评估。

boolean
验证的字段必须能够被转换为布尔值。可接受的参数为 true、false、1、0、"1" 以及 "0"。

confirmed
验证的字段必须和 foo_confirmation 的字段值一致。例如，如果要验证的字段是 password，输入中必须存在匹配的 password_confirmation 字段。

date
验证的字段值必须是通过 PHP 函数 strtotime 校验的有效日期。


date_equals:date
验证的字段必须等于给定的日期。该日期会被传递到 PHP 函数 strtotime。


date_format:format
验证的字段必须与给定的格式相匹配。你应该只使用 date 或 date_format 其中一个用于验证，而不应该同时使用两者。


different:field
验证的字段值必须与字段 (field) 的值不同。


digits:value
验证的字段必须是数字，并且必须具有确切的值。


digits_between:min,max
验证的字段的长度必须在给定的 min 和 max 之间。


dimensions
验证的文件必须是图片并且图片比例必须符合规则：

'avatar' => 'dimensions:min_width=100,min_height=200'
可用的规则为： min_width、 max_width 、 min_height 、 max_height 、 width 、 height。

distinct
验证数组时，指定的字段不能有任何重复值。

email
验证的字段必须符合 e-mail 地址格式。


exists:table,column
验证的字段必须存在于给定的数据库表中。

Exists 规则的基本使用方法
'state' => 'exists:states'

指定自定义字段名称

'state' => 'exists:states,abbreviation'
如果你需要指定 exists 方法用来查询的数据库。你可以通过使用「点」语法将数据库的名称添加到数据表前面来实现这个目的：

'email' => 'exists:connection.staff,email'

file
验证的字段必须是成功上传的文件。


filled
验证的字段在存在时不能为空。


image
验证的文件必须是一个图像（ jpeg、png、bmp、gif、或 svg ）。


in:foo,bar,...
验证的字段必须包含在给定的值列表中。因为这个规则通常需要你 implode 一个数组

in_array:anotherfield
验证的字段必须存在于另一个字段（anotherfield）的值中。

integer
验证的字段必须是整数。

ip
验证的字段必须是 IP 地址。

ipv4
验证的字段必须是 IPv4 地址。

ipv6
验证的字段必须是 IPv6 地址。

json
验证的字段必须是有效的 JSON 字符串。

max:value
验证中的字段必须小于或等于 value。字符串、数字、数组或是文件大小的计算方式都用 size 方法进行评估。

mimetypes:text/plain,...
验证的文件必须与给定 MIME 类型之一匹配：

'video' => 'mimetypes:video/avi,video/mpeg,video/quicktime'
要确定上传文件的 MIME 类型，会读取文件的内容来判断 MIME 类型，这可能与客户端提供的 MIME 类型不同。

mimes:foo,bar,...
验证的文件必须具有与列出的其中一个扩展名相对应的 MIME 类型。

MIME 规则基本用法
'photo' => 'mimes:jpeg,bmp,png'
即使你可能只需要验证指定扩展名，但此规则实际上会验证文件的 MIME 类型，其通过读取文件的内容以猜测它的 MIME 类型。

这个过程看起来只需要你指定扩展名，但实际上该规则是通过读取文件的内容并判断其 MIME 的类型来验证的。

可以在以下链接中找到完整的 MIME 类型列表及其相应的扩展名：

https://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types

min:value
验证中的字段必须具有最小值。字符串、数字、数组或是文件大小的计算方式都用 size 方法进行评估。

nullable
验证的字段可以为 null。这在验证基本数据类型时特别有用，例如可以包含空值的字符串和整数。

not_in:foo,bar,...
验证的字段不能包含在给定的值列表中。

numeric
验证的字段必须是数字。

present
验证的字段必须存在于输入数据中，但可以为空。

regex:pattern
验证的字段必须与给定的正则表达式匹配。
注意： 当使用 regex 规则时，你必须使用数组，而不是使用 | 分隔符，特别是如果正则表达式包含 | 字符。

required
验证的字段必须存在于输入数据中，而不是空。如果满足以下条件之一，则字段被视为「空」：

该值为 null.
该值为空字符串。
该值为空数组或空的 可数 对象。
该值为没有路径的上传文件。

required_if:anotherfield,value,...
如果指定的其它字段（ anotherfield ）等于任何一个 value 时，被验证的字段必须存在且不为空。

required_unless:anotherfield,value,...
如果指定的其它字段（ anotherfield ）等于任何一个 value 时，被验证的字段不必存在。

required_with:foo,bar,...
只要在指定的其他字段中有任意一个字段存在时，被验证的字段就必须存在并且不能为空。

required_with_all:foo,bar,...
只有当所有的其他指定字段全部存在时，被验证的字段才必须存在并且不能为空。

required_without:foo,bar,...
只要在其他指定的字段中有任意一个字段不存在，被验证的字段就必须存在且不为空。

required_without_all:foo,bar,...
只有当所有的其他指定的字段都不存在时，被验证的字段才必须存在且不为空。

same:field
给定字段必须与验证的字段匹配。

size:value
验证的字段必须具有与给定值匹配的大小。对于字符串来说，value 对应于字符数。对于数字来说，value 对应于给定的整数值。对于数组来说， size 对应的是数组的 count 值。对文件来说，size 对应的是文件大小（单位 kb ）。

string
验证的字段必须是字符串。如果要允许该字段的值为 null ，就将 nullable 规则附加到该字段中。

timezone
验证的字段必须是有效的时区标识符，会根据 PHP 函数 timezone_identifiers_list 来判断。

unique:table,column,except,idColumn
验证的字段在给定的数据库表中必须是唯一的。如果没有指定 column，将会使用字段本身的名称。

指定自定义字段名称：

'email' => 'unique:users,email_address'
自定义数据库连接

有时，你可能需要为验证程序创建的数据库查询设置自定义连接。上面的例子中，将 unique：users 设置为验证规则，等于使用默认数据库连接来查询数据库。如果要对其进行修改，请使用「点」语法指定连接和表名：

'email' => 'unique:connection.users,email_address,id=1'

url
验证的字段必须是有效的 URL。

Table
验证的字段在给定的数据库是否存在

Database
验证的字段在给定的数据库是否是存在的数据库

Chinese
验证的字段必须是中文。

idcard
验证的字段必须是有效的 身份证。

```

# License

MIT
