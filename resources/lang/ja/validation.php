<?php

$validation = [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'         => ':attribute が承認されていません。',
    'active_url'       => ':attribute が有効なURLではありません。',
    'after'            => ':attribute には:date以降の日付を指定してください。',
    'alpha'            => ':attribute にはアルファベッドを指定してください。',
    'alpha_dash'       => ':attribute には英数字とダッシュ(-)及び下線(_)を指定してください。',
    'alpha_num'        => ':attribute には英数字を指定してください。',
    'array'            => ':attribute には配列を指定してください。',
    'before'           => ':attribute には :date 以前の日付を指定してください。',
    'between'          => [
        'numeric' => ':attribute には :min から :max まで数字を指定してください。',
        'file'    => ':attribute のファイルサイズは :min kB から :max kB までとなります。',
        'string'  => ':attribute は :min 文字から :max 文字までとなります。',
        'array'   => ':attribute は :min 個から :max 個までとなります。',
    ],
    'boolean'          => ':attribute は真偽値で指定してください。',
    'confirmed'        => ':attribute は確認用フィールドと一致していません。',
    'date'             => ':attribute は正しい日付ではありません。',
    'date_format'      => ':attribute は日付フォーマット（ :format ）と一致していません。',
    'different'        => ':attribute には :other と異なる内容を指定してください。',
    'digits'           => ':attribute は :digits 桁で指定してください。',
    'digits_between'   => ':attribute は :min 桁から :max 桁で指定してください。',
    'email'            => ':attribute にはメールアドレスを指定してください。',
    'filled'               => ':attribute は必須となります。',
    'exists'           => ':attribute が正しくありません。',
    'image'            => ':attribute には画像を指定してください。',
    'in'               => '選択された :attribute が正しくありません。',
    'integer'          => ':attribute には整数を指定してください。',
    'ip'               => ':attribute には有効なIPアドレスを指定してください。',
    'max'              => [
        'numeric' => ':attribute には :max 以下の数字を指定してください。',
        'file'    => ':attribute のサイズが :max kB 以下のファイルを指定してください。',
        'string'  => ':attribute は :max 文字以下となります。',
        'array'   => ':attribute は :max 個以下となります。',
    ],
    'mimes'            => ':attribute には :values タイプのファイルを指定してください。',
    'min'              => [
        'numeric' => ':attribute には :min 以上の数字を指定してください。',
        'file'    => ':attribute のファイルサイズは :min kB 以上までとなります。',
        'string'  => ':attribute は :min 文字以上となります。',
        'array'   => ':attribute は :max 個以上となります。',
    ],
    'not_in'           => '選択された:attributeが正しくありません。',
    'numeric'          => ':attribute には数字を指定してください。',
    'regex'            => ':attribute は指定のフォーマットと一致していません。',
    'required'         => ':attribute は必須となります。',
    'required_if'      => ':attribute は :other が :value の場合には必須となります。',
    'required_with'    => ':attribute は :values が存在している場合に必須となります。',
    'required_with_all' => ':attribute は :values が存在している場合に必須となります。',
    'required_without' => ':attribute は :values が存在していない場合に必須となります。',
    'required_without_all' => ':attribute は :values が存在していない場合に必須となります。',
    'same'             => ':attribute は :other と一致していません。',
    'size'             => [
        'numeric' => ':attribute には :size を指定してください。',
        'file'    => ':attribute のファイルサイズは :size kB と一致しません。',
        'string'  => ':attribute は :size 文字で指定してください。',
        'array'   => ':attribute は :size 個で指定してください。',
    ],
    'string'           => ':attribute は文字列を指定してください。',
    'timezone'         => ':attribute はタイムゾーンを指定してください。',
    'unique'           => ':attribute が既に存在しています。',
    'url'              => ':attribute にはURLを指定してください。',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention 'attribute.rule' to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
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

    'attributes' => [
        'article' => [
            'title' => '記事のタイトル',
            'body' => '記事の本文',
        ],
        'tags' => 'タグ',
        'body' => 'コメントの本文',
        'name' => 'ユーザー名',
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'password_confirmation' => 'パスワード確認',
    ],

];

return $validation;
