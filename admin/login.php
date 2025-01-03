<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Fahasa Admin</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="public_admin/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="public_admin/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="public_admin/css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="public_admin/css/style.css" rel="stylesheet">
</head>

<?php
    ob_start();
    session_start();
    require_once "models_admin/pdo_library.php";
    require_once "models_admin/BaseModel.php";
    require_once "models_admin/CustomerModel.php";

    $error ='';
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);

        if (!empty($username) && !empty($password)) {
            $user = $CustomerModel->get_user_admin($username);

            if ($user && isset($user[0]['password'])) { 

                if($user[0]['active'] != 1) {
                    $error = 'Tài khoản đã bị khóa';
                }else {
                    if (password_verify($password, $user[0]['password'])) {
                        //Lưu thông tin đăng nhập vào Sessison
                        $_SESSION['user_admin']['id'] = $user[0]['user_id'];
                        $_SESSION['user_admin']['username'] = $user[0]['username'];
                        $_SESSION['user_admin']['full_name'] = $user[0]['full_name'];
                        $_SESSION['user_admin']['image'] = $user[0]['image'];
                        $_SESSION['user_admin']['email'] = $user[0]['email'];
                        $_SESSION['user_admin']['phone'] = $user[0]['phone'];
                        $_SESSION['user_admin']['address'] = $user[0]['address'];
                        

                        header("Location: index.php");
                    } else {
                        $error = 'Sai tên tài khoản hoặc mật khẩu';
                    }
                }
            }        
        }
    }

    $html_alert = $BaseModel->alert_error_success($error, '');
    
?>

<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Sign In Start -->
        <div class="container-fluid">
            <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                    

                    <div class="bg-light rounded p-4 p-sm-5 my-4 mx-3">      
                                          
                        <form action="" method="post">
                            <h3 class="text-center mb-4">Đăng nhập Admin</h3>
                            <p class="text-danger">Vui lòng đăng nhập để vào trang quản trị</p>
                            <?=$html_alert?>
                            <div class="form-floating mb-3">
                                <input name="username" type="text" class="form-control" id="floatingInput" placeholder="Tên đăng nhập" required>
                                <label for="floatingInput">Tên đăng nhập</label>
                            </div>
                            <div class="form-floating mb-4">
                                <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Mật khẩu" required>
                                <label for="floatingPassword">Mật khẩu</label></label>
                            </div>
                            
                            <button type="submit" name="login" class="btn btn-primary py-3 w-100 mb-4">Đăng nhập</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sign In End -->
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="public_admin/lib/chart/chart.min.js"></script>
    <script src="public_admin/lib/easing/easing.min.js"></script>
    <script src="public_admin/lib/waypoints/waypoints.min.js"></script>
    <script src="public_admin/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="public_admin/lib/tempusdominus/js/moment.min.js"></script>
    <script src="public_admin/lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="public_admin/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="public_admin/js/main.js"></script>
</body>

</html>

<?php
    ob_end_flush();
?>