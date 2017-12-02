<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages.
    |
    */
    'messages'               =>[
        'accepted'             => ':attribute 必须接受。',
        'activeUrl'            => ':attribute 不是一个有效的网址。',
        'after'                => ':attribute 必须要晚于 :date。',
        'afterOrEqual'         => ':attribute 必须要等于 :date 或更晚。',
        'alpha'                => ':attribute 只能由字母组成。',
        'alphaDash'            => ':attribute 只能由字母、数字和斜杠组成。',
        'alphaNum'             => ':attribute 只能由字母和数字组成。',
        'array'                => ':attribute 必须是一个数组。',
        'before'               => ':attribute 必须要早于 :date。',
        'beforeOrEqual'        => ':attribute 必须要等于 :date 或更早。',
        'between'              => ':attribute 必须介于 :min - :max 之间。',
        'boolean'              => ':attribute 必须为布尔值。',
        'confirmed'            => ':attribute 两次输入不一致。',
        'date'                 => ':attribute 不是一个有效的日期。',
        'dateFormat'           => ':attribute 的格式必须为 :format。',
        'different'            => ':attribute 和 :other 必须不同。',
        'digits'               => ':attribute 必须是 :digits 位的数字。',
        'digitsBetween'        => ':attribute 必须是介于 :min 和 :max 位的数字。',
        'dimensions'           => ':attribute 图片尺寸不正确。',
        'distinct'             => ':attribute 已经存在。',
        'email'                => ':attribute 不是一个合法的邮箱。',
        'exists'               => ':attribute 不存在。',
        'file'                 => ':attribute 必须是文件。',
        'filled'               => ':attribute 不能为空。',
        'image'                => ':attribute 必须是图片。',
        'in'                   => '已选的属性 :attribute 非法。',
        'inArray'              => ':attribute 没有在 :other 中。',
        'integer'              => ':attribute 必须是整数。',
        'ip'                   => ':attribute 必须是有效的 IP 地址。',
        'ipv4'                 => ':attribute 必须是有效的 IPv4 地址。',
        'ipv6'                 => ':attribute 必须是有效的 IPv6 地址。',
        'json'                 => ':attribute 必须是正确的 JSON 格式。',
        'max'                  => ':attribute 不能大于 :max。',
        'mimes'                => ':attribute 必须是一个 :values 类型的文件。',
        'mimetypes'            => ':attribute 必须是一个 :values 类型的文件。',
        'min'                  => ':attribute 必须大于等于 :min。',
        'notIn'                => '已选的属性 :attribute 非法。',
        'numeric'              => ':attribute 必须是一个数字。',
        'present'              => ':attribute 必须存在。',
        'regex'                => ':attribute 格式不正确。',
        'required'             => ':attribute 不能为空。',
        'requiredIf'           => '当 :other 为 :value 时 :attribute 不能为空。',
        'requiredUnless'       => '当 :other 不为 :value 时 :attribute 不能为空。',
        'requiredWith'         => '当 :values 存在时 :attribute 不能为空。',
        'requiredWithAll'      => '当 :values 存在时 :attribute 不能为空。',
        'requiredWithout'      => '当 :values 不存在时 :attribute 不能为空。',
        'requiredWithoutAll'   => '当 :values 都不存在时 :attribute 不能为空。',
        'same'                 => ':attribute 和 :other 必须相同。',
        'size'                 => ':attribute 大小必须为 :size。',
        'string'               => ':attribute 必须是一个字符串。',
        'timezone'             => ':attribute 必须是一个合法的时区值。',
        'unique'               => ':attribute 已经存在。',
        'uploaded'             => ':attribute 上传失败。',
        'url'                  => ':attribute 格式不正确。',
        'table'                => ':attribute 表不存在。',
        'database'             => ':attribute 数据库不存在。',
        'chinese'              => ':attribute 必须为中文。',
        'idCard'               => ':attribute 必须是一个合法的身份证。',
    ],
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of 'email'. This simply helps us make messages a little cleaner.
    |
    */
    'attributes'           => [
        'name'                  => '名称',
        'username'              => '用户名',
        'email'                 => '邮箱',
        'first_name'            => '名',
        'last_name'             => '姓',
        'password'              => '密码',
        'password_confirmation' => '确认密码',
        'city'                  => '城市',
        'country'               => '国家',
        'address'               => '地址',
        'phone'                 => '电话',
        'mobile'                => '手机',
        'age'                   => '年龄',
        'sex'                   => '性别',
        'gender'                => '性别',
        'day'                   => '天',
        'month'                 => '月',
        'year'                  => '年',
        'hour'                  => '时',
        'minute'                => '分',
        'second'                => '秒',
        'title'                 => '标题',
        'content'               => '内容',
        'description'           => '描述',
        'excerpt'               => '摘要',
        'date'                  => '日期',
        'time'                  => '时间',
        'available'             => '可用的',
        'size'                  => '大小',
    ],
];