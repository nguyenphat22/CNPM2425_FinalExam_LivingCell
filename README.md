# Living Cells 🧬

## Giới thiệu nhóm 🚀
Chào mừng đến với kho lưu trữ của nhóm **Living Cells**! 
Đây là nơi lưu trữ các dự án, bài tập và tài liệu liên quan đến môn Nhập Môn Công nghệ Phần Mềm.

## Thành viên 👥
- 🧑‍💻 **Dương Thị Thu Diểm** - Trưởng nhóm
- 🧑‍💻 **Dương Hải Đăng** - Thành viên
- 🧑‍💻 **Hà Trung Hiếu** - Thành viên
- 🧑‍💻 **Võ Quỳnh Như** - Thành viên
- 🧑‍💻 **Nguyễn Xuân Phát** - Thành viên
- 🧑‍💻 **Nguyễn Thị Thanh Trà** - Thành viên

## Nền tảng & Công cụ 🛠️
- **Ngôn ngữ lập trình**: PHP ( Laravel )
- **Database**: MySQL
- **Công cụ**: Git, GitHub, VS Code, .....

## Dự án 📂
## Hệ thống Quản lý Rèn luyện và Khen thưởng Sinh viên 🏅

### Giới thiệu về dự án 🚀

Dự án **Hệ thống Quản lý Rèn luyện và Khen thưởng Sinh viên** nhằm mục đích quản lý điểm học tập, điểm rèn luyện, ngày tình nguyện của sinh viên và đề xuất danh hiệu khen thưởng dựa trên các tiêu chí. Hệ thống phân quyền cho các vai trò: Admin, Phòng Khảo thí, Phòng CTCT HSSV, Văn phòng Đoàn Trường, và Sinh viên.

### Các yêu cầu chính

- **Quản lý điểm học tập và điểm rèn luyện**: Phòng khảo thí và phòng CTCT HSSV có thể nhập, sửa, và xuất báo cáo.
- **Quản lý danh hiệu và khen thưởng**: Phòng Đoàn Trường quản lý các tiêu chí và danh hiệu.
- **Quản lý sinh viên**: Thông tin cá nhân, điểm học tập, rèn luyện, và ngày tình nguyện.
- **Gợi ý danh hiệu**: Sinh viên nhận gợi ý danh hiệu dựa trên các chỉ tiêu (GPA, điểm rèn luyện, ngày tình nguyện).

### 7.4. Hướng dẫn cài đặt và chạy

#### 7.4.1. Chạy trên nội bộ (localhost)

##### Bước 1: Cài đặt các phần mềm sau:
- **XAMPP**: Tải tại [đây](https://www.apachefriends.org/index.html).
- **Composer**: Mở command prompt (cmd) với quyền administrator và gõ các dòng lệnh sau:
  ```bash
  php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
  php -r "if (hash_file('sha384', 'composer-setup.php') === 'ed0feb545ba87161262f2d45a633e34f591ebb3381f2e0063c345ebea4d228dd0043083717770234ec00c5a9f9593792') { echo 'Installer verified'.PHP_EOL; } else { echo 'Installer corrupt'.PHP_EOL; unlink('composer-setup.php'); exit(1); }"
  php composer-setup.php
  php -r "unlink('composer-setup.php');"
  ```
- **Laravel**: Tải và cài đặt Laravel tại [đây](https://laravel.com/docs/12.x/installation).

##### Bước 2: Mở XAMPP lên và kích hoạt **Apache** và **MySQL** bằng nút **Start**.

##### Bước 3: Sau khi đã kích hoạt MySQL, vào trang quản lý cơ sở dữ liệu của XAMPP qua đường dẫn: 
[http://localhost/phpmyadmin/](http://localhost/phpmyadmin/).

##### Bước 4: Vào tab **SQL** trong phpMyAdmin, sao chép nội dung trong file **csdl_qldemo1.txt** và dán vào ô ghi code, sau đó chọn **Thực hiện**.

##### Bước 5: Mở console trong Visual Studio Code (hoặc IDE bạn chọn), chạy file **gen_hash.php** và thực hiện các cú pháp sau:
```bash
php artisan tinker
Hash::make('mật khẩu muốn hash')
```
Kết quả là mã đã hash của mật khẩu bạn muốn.

##### Bước 6: Trở lại phpMyAdmin, chọn bảng **bang_taikhoan** trong cơ sở dữ liệu **qldemo1**, chuyển qua tab **SQL** và nhập mã sau để khởi tạo tài khoản mặc định:
```sql
INSERT INTO BANG_TaiKhoan (MaTK, TenDangNhap, MatKhau, VaiTro, TrangThai, Email)
VALUES
(1, 'admin',     'mã đã hash', 'Admin',      'Active', 'admin@example.com'),
(2, 'sinhvien',  'mã đã hash', 'SinhVien',   'Active', 'sv@example.com'),
(3, 'ctcthssv',  'mã đã hash', 'CTCTHSSV',   'Active', 'ctct@example.com'),
(4, 'khaothi',   'mã đã hash', 'KhaoThi',    'Active', 'khaothi@example.com'),
(5, 'doantruong','mã đã hash', 'DoanTruong', 'Active', 'doan@example.com');
```

##### Bước 7: Mở console trong Visual Studio Code (hoặc IDE bạn chọn) và chạy các dòng lệnh sau:
```bash
composer update
composer install
php artisan session:table
php artisan migrate
php artisan serve
```

Kết quả bạn sẽ có một đường dẫn với IP cục bộ, là trang web của hệ thống.

---

Hãy thử các bước này và kiểm tra hệ thống hoạt động như mong đợi! Nếu có bất kỳ vấn đề nào, đừng ngần ngại tạo issue trên GitHub để được hỗ trợ.


## Liên hệ 📧
Nếu bạn có câu hỏi hoặc muốn hợp tác, hãy tạo issue trên GitHub; hoặc nếu bạn thích ai đó trong nhóm, hãy gặp họ trực tiếp, nhóm chúng tôi không giải quyết được.

