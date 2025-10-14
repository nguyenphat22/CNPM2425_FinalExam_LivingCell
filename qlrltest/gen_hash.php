<?php
$plain = '123456'; // đổi sang mật khẩu bạn muốn hash
echo password_hash($plain, PASSWORD_BCRYPT);
