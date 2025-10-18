<?php

return [
    'required' => ':attribute là bắt buộc.',
    'email'    => ':attribute phải là địa chỉ email hợp lệ.',
    'min'      => [
        'string' => ':attribute phải có ít nhất :min ký tự.',
    ],
    'max'      => [
        'string' => ':attribute không được vượt quá :max ký tự.',
    ],

    // Tuỳ chỉnh tên hiển thị
    'attributes' => [
        'TenDangNhap' => 'Tên đăng nhập',
        'MatKhau'     => 'Mật khẩu',
        'Email'       => 'Email công tác',
    ],
];
