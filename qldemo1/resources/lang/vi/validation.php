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
    /*
    |--------------------------------------------------------------------------
    | Dòng thông báo xác thực
    |--------------------------------------------------------------------------
    */
    'accepted'             => ':attribute phải được chấp nhận.',
    'active_url'           => ':attribute không phải là URL hợp lệ.',
    'after'                => ':attribute phải là một ngày sau :date.',
    'alpha'                => ':attribute chỉ được chứa ký tự chữ cái.',
    'alpha_dash'           => ':attribute chỉ được chứa ký tự chữ cái, số, gạch ngang và gạch dưới.',
    'alpha_num'            => ':attribute chỉ được chứa ký tự chữ cái và số.',
    'array'                => ':attribute phải là một mảng.',
    'before'               => ':attribute phải là một ngày trước :date.',
    'between'              => [
        'numeric' => ':attribute phải nằm trong khoảng :min và :max.',
        'file'    => ':attribute phải có dung lượng từ :min đến :max kilobytes.',
        'string'  => ':attribute phải có độ dài từ :min đến :max ký tự.',
        'array'   => ':attribute phải có từ :min đến :max phần tử.',
    ],
    'boolean'              => ':attribute phải là true hoặc false.',
    'confirmed'            => 'Xác nhận :attribute không khớp.',
    'date'                 => ':attribute không phải là ngày hợp lệ.',
    'date_format'          => ':attribute không khớp với định dạng :format.',
    'different'            => ':attribute và :other phải khác nhau.',
    'digits'               => ':attribute phải gồm :digits chữ số.',
    'digits_between'       => ':attribute phải nằm trong khoảng :min đến :max chữ số.',
    'email'                => ':attribute phải là địa chỉ email hợp lệ.',
    'exists'               => ':attribute không tồn tại.',
    'filled'               => ':attribute là bắt buộc.',
    'image'                => ':attribute phải là một hình ảnh.',
    'in'                   => ':attribute không hợp lệ.',
    'integer'              => ':attribute phải là một số nguyên.',
    'ip'                   => ':attribute phải là địa chỉ IP hợp lệ.',
    'max'                  => [
        'numeric' => ':attribute không được lớn hơn :max.',
        'file'    => ':attribute không được lớn hơn :max kilobytes.',
        'string'  => ':attribute không được vượt quá :max ký tự.',
        'array'   => ':attribute không được có quá :max phần tử.',
    ],
    'min'                  => [
        'numeric' => ':attribute phải tối thiểu là :min.',
        'file'    => ':attribute phải tối thiểu :min kilobytes.',
        'string'  => ':attribute phải có ít nhất :min ký tự.',
        'array'   => ':attribute phải có ít nhất :min phần tử.',
    ],
    'not_in'               => ':attribute không hợp lệ.',
    'numeric'              => ':attribute phải là số.',
    'present'              => ':attribute phải tồn tại.',
    'regex'                => 'Định dạng :attribute không hợp lệ.',
    'required'             => ':attribute là bắt buộc.',
    'required_if'          => ':attribute là bắt buộc khi :other là :value.',
    'same'                 => ':attribute và :other phải khớp nhau.',
    'size'                 => [
        'numeric' => ':attribute phải bằng :size.',
        'file'    => ':attribute phải có dung lượng :size kilobytes.',
        'string'  => ':attribute phải có :size ký tự.',
        'array'   => ':attribute phải chứa :size phần tử.',
    ],
    'unique'               => ':attribute đã được sử dụng.',
    'uploaded'             => 'Tải lên :attribute thất bại.',
    'url'                  => 'Định dạng :attribute không hợp lệ.',

    /*
    |--------------------------------------------------------------------------
    | Tùy chỉnh thông báo thuộc tính
    |--------------------------------------------------------------------------
    */
    'attributes' => [
        'MaSV' => 'Mã sinh viên',
        'HoTen' => 'Họ và tên',
        'NgaySinh' => 'Ngày sinh',
        'Khoa' => 'Khoa',
        'Lop' => 'Lớp',
        'MaTK' => 'Mã tài khoản',
        'file' => 'Tệp Excel',
        'TenDangNhap' => 'Tên đăng nhập',
        'MatKhau'     => 'Mật khẩu',
        'Email'       => 'Email công tác',
    ],
];
