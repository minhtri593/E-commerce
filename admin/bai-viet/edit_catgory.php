<?php
    $list_name_cate = $PostModel->select_name_cate_post();
    $error = '';
    $success = '';
    if(isset($_GET['id']) && $_GET['id'] >0) {
        $cate_post_id = $_GET['id'];
        $catepost = $PostModel->select_cate_post_by_id($cate_post_id);
    }else {
        header("Location: index.php?quanli=danh-muc-bai-viet");
    }

    //Câp nhật chuyên mục bài viêt
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_category"])) {
        $name = trim($_POST["name"]);

        if(!empty($name)) {
            if(strlen($name) > 255) {
                $error = 'Tên chuyên mục tối đa 255 ký tự';
            }

            if(empty($error)) {
                $PostModel->update_cate($name, $cate_post_id);
                header("Location: index.php?quanli=danh-muc-bai-viet");
            }

        }else {
            $error = 'Không được để trống';
        }
    }

    $list_categoryposts = $PostModel->select_category_posts();

    $html_alert = $BaseModel->alert_error_success('', $success);
?>

<!-- Form Start -->
<div class="container-fluid pt-4" style="margin-bottom: 110px;">
    <div class="row g-4">
        <div class="col-sm-10 col-xl-6">
            <form action="" method="post">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">
                        <a href="index.php?quanli=danh-muc-bai-viet" class="link-not-hover">Chuyên mục</a>
                        / Cập nhật chuyên mục
                    </h6>
                    <?=$html_alert?>
                    <label for="floatingInput">Tên chuyên mục</label>
                    <div class="form-floating mb-4">
                        <input name="name" value="<?=$catepost['name']?>" type="text" class="form-control" id="floatingInput">
                        <span class="text-danger" ><?=$error?></span>
                    </div>
                    <input type="submit" name="update_category" value="Cập nhật" class="btn btn-custom">           
                </div>
            </form>
        </div>

        
        
    </div>

</div>
<!-- Form End -->