<?php

    $error = array(
        'email' => '',
        'fullname' => '',
        'username' => '',
        'password' => '',
        'password_confirm' => '',
        'phone' => '',
        'address' => '',     
    );
    
    $email_tmp = "";
    $fullname_tmp = "";
    $username_tmp = "";
    $password_tmp = "";
    $phone_tmp = "";
    $address_tmp = "";
    $password_cf_tmp = "";

    $list_users = $CustomerModel->select_users();
    
    // Kiểm tra nếu form được submit
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
        
        // Lấy dữ liệu từ form
        $email = trim($_POST["email_register"]);
        $full_name = trim($_POST["full_name"]);
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);
        $password_confirm = trim($_POST["password_confirm"]);
        $phone = trim($_POST["phone"]);
        $address = trim($_POST["address"]);
        $image = "user-default.png";

        //MÃ hóa password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        foreach ($list_users as $user) {
            if ($user['email'] == $email) {
                $error['email'] = 'Email đã được đăng ký.';
                break; 
            }
        }

        if((strlen($email) > 255)) {
            $error['email'] = 'Email không được quá 255 ký tự';
        }

        if((strlen($full_name) > 255)) {
            $error['fullname'] = 'Họ tên không được quá 255 ký tự';
        }

        foreach ($list_users as $user) {
            if ($user['username'] == $username) {
                $error['username'] = 'Tên đăng nhập đã tồn tại.';
                break; 
            }
        }

        foreach ($list_users as $user) {
            if ($user['phone'] == $phone) {
                $error['phone'] = 'Số điện thoại đã được đăng ký.';
                break; 
            }
        }

        if($password != $password_confirm) {
            $error['password_confirm'] = 'Nhập lại mật khẩu không trùng khớp';
        }

        if (strlen($password) < 8) {
            $error['password'] = 'Mật khẩu phải chứa ít nhất 8 ký tự.';
        }

        if (!preg_match('/^(03|05|07|08|09)\d{8}$/', $phone)) {
            $error['phone'] = 'Số điện thoại không đúng định dạng.';
        }

        if((strlen($address) > 255)) {
            $error['address'] = 'Địa chỉ không được quá 255 ký tự';
        }

        if(empty(array_filter($error))) {
            // Insert dữ liệu user
            $CustomerModel->user_insert($username, $hashed_password, $full_name, $image, $email, $phone, $address);
            $_SESSION['user_register'] = [
                'username' => $username,
                'password' => $password
            ];

            header("Location: index.php?url=dang-nhap");
            exit();
        }else {
            $email_tmp = $email;
            $fullname_tmp = $full_name;
            $username_tmp = $username;
            $password_tmp = $password;
            $password_cf_tmp = $password;
            $phone_tmp = $phone;
            $address_tmp = $address;
            $password_cf_tmp = $password_confirm;
        }

        
    }
    
?>
<style>

    label {
        margin-top: 5px;
    }
</style>

<div class="container my-5">
    <div class="row d-flex justify-content-center align-items-center m-0">
        <div class="login_oueter">
            
            <form action="" method="post" id="login" autocomplete="off" class="p-3">
                <h4 class="my-3 text-center">ĐĂNG KÝ TÀI KHOẢN</h4>
                <div class="form-row">
                    
                    <div class="col-12">
                        
                        <div class="input-group mb-0">
                            <label class="w-100 text-dark" for="email_res">Địa chỉ Email</label>
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input name="email_register" type="email" value="<?=$email_tmp?>" class="input form-control" id="email_res" required="true"  placeholder="Email" />
                            <span class="w-100 text-danger"><?=$error['email']?></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="input-group my-0">
                            <label class="w-100 text-dark" for="full_name">Họ và tên</label>
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i></span>
                            </div>
                            <input name="full_name" type="text" value="<?=$fullname_tmp?>" class="input form-control" id="full_name" required="true"  placeholder="Họ và tên" />
                            <span class="w-100 text-danger"><?=$error['fullname']?></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="input-group my-0">
                            <label class="w-100 text-dark" for="username">Tên đăng nhập</label>
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i></span>
                            </div>
                            <input name="username" type="text" value="<?=$username_tmp?>" class="input form-control" id="username" required="true"  placeholder="Tên đăng nhập" />
                            <span class="w-100 text-danger"><?=$error['username']?></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="input-group my-0">
                            <label class="w-100 text-dark" for="password_register">Mật khẩu</label>
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-lock"></i></span>
                            </div>
                            <input name="password" type="password" value="<?=$password_tmp?>" class="input form-control" id="password_register" placeholder="Mật khẩu" required="true" aria-label="password" aria-describedby="basic-addon1" />
                            
                            <div class="input-group-append">
                                <span class="input-group-text" onclick="password_show_hide_register();">
                                    <i class="fas fa-eye" id="show_eye_register"></i>
                                    <i class="fas fa-eye-slash d-none" id="hide_eye_register"></i>
                                </span>
                            </div>
                            <span class="w-100 text-danger"><?=$error['password']?></span>
                        </div>
                        <div class="input-group my-0">
                            <label class="w-100 text-dark" for="password_confirm">Nhập lại mật khẩu</label>
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-check"></i></span>
                            </div>
                            <input name="password_confirm" type="password" value="<?=$password_cf_tmp?>" class="input form-control" id="password_confirm" placeholder="Xác nhận mật khẩu" required />
                            <span class="w-100 text-danger"><?=$error['password_confirm']?></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="input-group my-0">
                            <label class="w-100 text-dark" for="phone">Số điện thoại</label>
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-phone"></i></span>
                            </div>
                            <input name="phone" type="text" value="<?=$phone_tmp?>" class="input form-control" id="phone" placeholder="Số điện thoại" required />
                            <span class="w-100 text-danger"><?=$error['phone']?></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="input-group mt-0 mb-3">
                            <label class="w-100 text-dark" for="phone">Địa chỉ</label>
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-map-marker"></i></span>
                            </div>
                            <input name="address" type="text" value="<?=$address_tmp?>" class="input form-control" id="address" placeholder="Địa chỉ" required/>
                            <span class="w-100 text-danger"><?=$error['address']?></span>
                        </div>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary w-100" type="submit" name="register">Đăng ký</button>
                    </div>
                    <div class="col-sm-12 pt-3 text-center">
                        <a href="#">Quên mật khẩu</a>
                    </div>

                </div>
                <div class="col-12 line"></div>
                <div class="col-12 text-center">
                    <a href="index.php?url=dang-nhap" class="btn btn-success w-50">Đăng nhập</a>
                </div>
            </form>
        </div>
    </div>  
</div>

<script>
    function password_show_hide_register() {
        var password = document.getElementById("password_register");
        var password_confirm = document.getElementById("password_confirm");
        var show_eye = document.getElementById("show_eye_register");
        var hide_eye = document.getElementById("hide_eye_register");
        hide_eye.classList.remove("d-none");
        if (password.type === "password") {
            password.type = "text";
            password_confirm.type = "text";

            show_eye.style.display = "none";
            hide_eye.style.display = "block";
        } else {
            password.type = "password";
            password_confirm.type = "password";

            show_eye.style.display = "block";
            hide_eye.style.display = "none";
        }
    }
</script>