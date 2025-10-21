<?php
// Tạo mã hash bằng bcrypt
$matKhau = '123456'; // đổi thành mật khẩu bạn muốn mã hoá
$hash = password_hash($matKhau, PASSWORD_BCRYPT);
echo "Mật khẩu gốc: $matKhau<br>";
echo "Mã hash: $hash";
