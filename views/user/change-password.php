<!-- Breadcrumb Begin -->
<?php
    $success = '';
    $error = array(
        'password_old' => '',
        'new_password' => '',
        'confirm_new_password' => '',
    );
    $temp = array(
        'password_old' => '',
        'new_password' => '',
        'confirm_password' => '',
    );
  
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["change_password"])) {
        // Table orders
        $user_id = $_SESSION['user']['id'];
        $password_old = trim($_POST["password_old"]);
        $new_password = trim($_POST["new_password"]);
        $confirm_new_password = trim($_POST["confirm_new_password"]);

        if(empty($password_old)) {
            $error['password_old'] = 'Mật khẩu cũ không được để trống';
        }else {
            // Kiểm tra mật khẩu cũ đúng không
            $password_current = $_SESSION['user']['password'];
            $check_password = password_verify($password_old, $password_current);
            if (!$check_password) {
                $error['password_old'] = 'Mật khẩu cũ không chính xác';

                $temp['new_password'] = $new_password;
                $temp['confirm_password'] = $confirm_new_password;
            }else{
                $temp['password_old'] = $password_old;
            }
        }

        if(strlen($password_old) > 255) {
            $error['password_old'] = 'Mật khẩu cũ tối đa 255 ký tự';
        }

        if(empty($new_password)) {
            $error['new_password'] = 'Mật khẩu mới không được để trống';
        }

        if(strlen($new_password) > 255) {
            $error['new_password'] = 'Mật khẩu mới tối đa 255 ký tự';
        }

        if(!empty($new_password) && strlen($new_password) <8) {
            $error['new_password'] = 'Mật khẩu tối thiểu 8 ký tự';
        }

        if(empty($confirm_new_password)) {
            $error['confirm_new_password'] = 'Không được để trống';
        }

        if(strlen($confirm_new_password) > 255) {
            $error['confirm_new_password'] = 'Tối đa 255 ký tự';
        }

        if($new_password !== $confirm_new_password) {
            $error['confirm_new_password'] = 'Nhập lại mật khẩu không trùng khớp với mật khẩu mới';
            // Lưu vào biến tạm
            $temp['new_password'] = $new_password;
        }
          
        if(empty(array_filter($error))) {
            try {
                //MÃ hóa password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $result = $CustomerModel->update_password($hashed_password, $user_id);

                // Cập nhật lại SESSION
                $_SESSION['user']['password'] = $hashed_password;
                
                setcookie('success_update', 'Thay đổi mật khẩu thành công', time() + 5, '/');
                header("Location: index.php?url=doi-mat-khau");
    
            } catch (Exception $e) {
                $error_message = $e->getMessage();
                echo 'Thay đổi mật khẩu thất bại: ' . $error_message;
                setcookie('success_update', 'Thay đổi mật khẩu thất bại', time() + 5, '/');
            }
        }
    }

    if(isset($_COOKIE['success_update']) && !empty($_COOKIE['success_update'])) {
        $success = $_COOKIE['success_update'];
    }
    
    $html_alert = $BaseModel->alert_error_success('', $success);

?>
<?php 
    if(isset($_SESSION['user'])) { 
        $user_id = $_SESSION['user']['id'];
        
    ?>
<div class="breadcrumb-option">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb__links">
                    <a href="index.php"><i class="fa fa-home"></i> Trang chủ</a>
                    <a href="ho-so">Tai khoản</a>
                    <span>Đổi mật khẩu</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Checkout Section Begin -->
<section class="checkout spad">
    <div class="container">

        <form action="" method="post" class="checkout__form" enctype="multipart/form-data">

            <div class="row">
                <!-- Slider bar profile -->
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <img src="upload/<?=$_SESSION['user']['image']?>" alt="avatar"
                                    class="rounded-circle img-fluid" style="width: 80px;">
                                <div class="ml-2">
                                    <h6 class="my-2 font-weight-bold"></h6>
                                    <a href="ho-so" style="opacity: 0.6;" class="text-dark font-weight-bold">Sửa hồ
                                        sơ</a>
                                </div>
                            </div>

                            <div class="row mt-2">

                                <div class="list-group col-12 p-0" style="border: none;">
                                    <a href="index.php?url=thong-tin-tai-khoan"
                                        class="list-group-item list-group-item-action">

                                        Hồ sơ
                                    </a>
                                    <a href="index.php?url=don-hang" class="list-group-item list-group-item-action">Đơn
                                        mua</a>
                                    <a href="index.php?url=doi-mat-khau"
                                        class="list-group-item list-group-item-action">Đổi mật khẩu</a>
                                    <a href="index.php?url=dang-xuat"
                                        class="list-group-item list-group-item-action">Đăng xuất</a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Sliderbar profile end -->
                <div class="col-lg-8">
                    <h5>Đổi mật khẩu</h5>
                    <?=$html_alert?>
                    <div class="row">

                        <div class="col-lg-12">

                            <div class="checkout__form__input">
                                <p>Tên đăng nhập</p>
                                <input disabled type="text" name="address" value="<?= $_SESSION['user']['username'] ?>"
                                    class="text-dark">
                            </div>

                        </div>
                        <div class="col-lg-12">
                            <div class="checkout__form__input">
                                <p>Mật khẩu cũ <span>*</span></p>
                                <input class="mb-0" type="password" value="<?=$temp['password_old']?>"
                                    name="password_old" placeholder="Nhập mật khẩu cũ">
                                <span class="text-danger error"><?=$error['password_old']?></span>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="checkout__form__input">
                                <p>Mật khẩu mới<span>*</span></p>
                                <input class="mb-0" type="password" value="<?=$temp['new_password']?>"
                                    name="new_password" placeholder="Nhập mật khẩu mới">
                                <span class="text-danger error"><?=$error['new_password']?></span>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="checkout__form__input">
                                <p>Nhập lại mật khẩu mới<span>*</span></p>
                                <input class="mb-0" type="password" value="<?=$temp['confirm_password']?>"
                                    name="confirm_new_password" placeholder="Nhập lại mật khẩu mới">
                                <span class="text-danger error"><?=$error['confirm_new_password']?></span>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <span class="text-primary" style="font-size: 20px;">*</span>
                            <span class="text-dark">Các trường không được để trống</span>
                        </div>
                        <div class="col-lg-12">
                            <div class="cart__btn">
                                <input type="submit" name="change_password" value="Thay đổi">
                                <a class="ml-2" href="index.php?url=thanh-toan">Đến trang thanh toán</a>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </form>
    </div>
</section>
<!-- Checkout Section End -->
<?php } else { ?>
<div class="row" style="margin-bottom: 400px;">
    <div class="col-lg-12 col-md-12">
        <div class="container-fluid mt-5">
            <div class="row rounded justify-content-center mx-0 pt-5">
                <div class="col-md-6 text-center">
                    <h4 class="mb-4">Vui lòng đăng nhập để có thể sử dụng chức năng</h4>
                    <a class="btn btn-primary rounded-pill py-3 px-5" href="index.php?url=dang-nhap">Đăng nhập</a>
                    <a class="btn btn-secondary rounded-pill py-3 px-5" href="index.php">Trang chủ</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>


<style>
.cart__btn input[type="submit"] {
    font-size: 14px;
    color: #111111;
    font-weight: 600;
    text-transform: uppercase;
    display: inline-block;
    padding: 14px 30px 12px;
    background: #f5f5f5;
}

.cart__btn input:hover {
    background-color: #0A68FF;
    color: #fff;
    transition: 0.2s;
}

.cart__btn a:hover {
    background-color: #0A68FF;
    color: #fff;
    transition: 0.2s;
}

.error {
    display: inline-block;
    height: 20px;
}
</style>