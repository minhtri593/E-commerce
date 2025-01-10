<?php
    if(isset($_GET['email']) && isset($_GET['token'])) {
        $email = $_GET['email'];
        $token = $_GET['token'];
        if($_COOKIE['token'] !== $token) {
            header("Location: index.php");
        }
    }
    
    $success = '';
    $error = array(
        'new_password' => '',
        'confirm_password' => '',
    );
    $temp = '';
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["reset_password"])) {
        $new_password = trim($_POST["new_password"]);
        $confirm_password = trim($_POST["confirm_password"]);

        if(empty($new_password)) {
            $error['new_password'] = 'Mật khẩu mới không được để trống';
        }

        if(strlen($new_password) > 255) {
            $error['new_password'] = 'Mật khẩu mới tối đa 255 ký tự';
        }

        if(!empty($new_password) && strlen($new_password) <8) {
            $error['new_password'] = 'Mật khẩu tối thiểu 8 ký tự';
        }

        if(empty($confirm_password)) {
            $error['confirm_password'] = 'Xác nhận mật khẩu không được để trống';
        }

        if(strlen($confirm_password) > 255) {
            $error['confirm_password'] = 'Tối đa 255 ký tự';
        }

        if($new_password !== $confirm_password) {
            $error['confirm_password'] = 'Nhập lại mật khẩu không trùng khớp với mật khẩu mới';
            // Lưu vào biến tạm
            $temp = $new_password;
        }

        if(empty(array_filter($error))) {
            try {
                //MÃ hóa password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $result = $CustomerModel->reset_password($hashed_password, $email);
                $success = 'Đổi mật khẩu thành công';
                //Xóa cookie sau khi reset mật khẩu
                setcookie('otp', '' , time() + 1, '/');
                setcookie('token', '' , time() + 1, '/');
    
            } catch (Exception $e) {
                $error_message = $e->getMessage();
                echo 'Thay đổi mật khẩu thất bại: ' . $error_message;

            }
        }

        
    }
    $html_alert = $BaseModel->alert_error_success('', $success);

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
                <h4 class="my-3 text-center">Quên mật khẩu</h4>
                <?=$html_alert?>

                <div class="form-row">
                    <?php
                        if(empty($success)) {
                    ?>
                    <div class="col-12">
                        <div class="input-group my-0">
                            <label class="w-100 text-dark" for="new_pw">Mật khẩu mới</label>
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-lock"></i></span>
                            </div>
                            <input name="new_password" type="text" value="<?=$temp?>" class="input form-control"
                                id="new_pw" placeholder="Mật khẩu mới" />
                            <span class="w-100 text-danger"><?=$error['new_password']?></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="input-group my-0">
                            <label class="w-100 text-dark" for="new_cfpw">Xác nhận mật khẩu</label>
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-check"></i></span>
                            </div>
                            <input name="confirm_password" type="text" value="" class="input form-control" id="new_cfpw"
                                placeholder="Xác nhận mật khẩu" />
                            <span class="w-100 text-danger"><?=$error['confirm_password']?></span>
                        </div>
                    </div>


                    <div class="col-12 mt-4">
                        <button class="btn btn-primary w-100" type="submit" name="reset_password">Đổi mật khẩu</button>
                    </div>
                    <?php
                    } else {
                    ?>
                    <div class="col-12 mt-4">
                        <a href="dang-nhap" class="btn btn-primary w-100">Đăng nhập ngay</a>
                    </div>
                    <?php
                    } 
                    ?>
                </div>
                <div class="col-12 line"></div>
                <div class="col-12 text-center">
                    <a href="index.php?url=dang-ky" class="btn btn-success w-50">Tạo tài khoản</a>
                </div>

            </form>
        </div>
    </div>

</div>