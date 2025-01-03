<?php
    $success = '';
    $error = array(
        'name' => '',
        'image' => '',
        'content' => '',
    );

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_post"])) {
        $title = trim($_POST["title"]);
        $content = $_POST["content"];
        $image = $_FILES["image"]['name'];
        $category_id = $_POST["category_id"];
        $author = $_SESSION['user_admin']['full_name'];

        if(strlen($title) > 255) {
            $error['name'] .= 'Tiêu đề bài viết tối đa 255 ký tự';
        }

        if(empty($title)) {
            $error['name'] .= 'Tiêu đề bài viết không được để trống';
        }

        if(empty($content)) {
            $error['content'] .= 'Nội dung bài viết không được để trống';
        }
    
        if(empty($image)) {
            $image = "default-product.jpg";
        }
    
        if(!empty($image)) {
            $img_valid = $BaseModel->is_image_valid($image);
            if (!$img_valid) {
                $error['image'] = 'File ảnh không hợp lệ chỉ được tải ảnh định dạng JPG, PNG';
            }
        }

        if(empty(array_filter($error))) {
            $target_dir = "../upload/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
    
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            }
    
            try {
                $result = $PostModel->insert_post($category_id, $title, $image, $author, $content);
                $success = 'Thêm bài viết thành công';
            } catch (Exception $e) {
                $error_message = $e->getMessage();
                echo 'Thêm bài viết thất bại: ' . $error_message;
            }
    
        }
    }

    // Danh sách chuyên mục
    $list_catgory_posts = $PostModel->select_all_cate_posts();
    // Error
    $html_alert = $BaseModel->alert_error_success('', $success);
?>

<!-- Form Start -->
<div class="container-fluid pt-4" style="margin-bottom: 110px;">

    <form class="row g-4" action="" method="post" enctype="multipart/form-data">

        <div class="col-sm-12 col-xl-9">

            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">
                    <a href="index.php?quanli=danh-sach-bai-viet" class="link-not-hover">Bài viết</a> 
                    / Thêm bài viết
                </h6>
                <?=$html_alert?>
                <label for="floatingInput">Tiêu đề bài viết</label>
                <div class="form-floating mb-4">
                    <input name="title" type="text" class="form-control" id="floatingInput">   
                    <span class="text-danger" ><?=$error['name']?></span>
                </div>
                
                <label for="text-dark">Nội dung bài viết</label>
                <div class="form-floating mb-3">
                    <span class="text-danger" ><?=$error['content']?></span>
                    <textarea name="content" class="form-control" placeholder="Nội dung" id="short_description">
                        
                    </textarea>

                </div>
                                        
            </div>
        </div>
        <div class="col-sm-12 col-xl-3">
            <div class="bg-light rounded h-100 p-4">
                <div class="mb-3">
                    <span class="text-danger" ><?=$error['image']?></span>
                    <label for="formFileSm" class="form-label">Hình ảnh (JPG, PNG)</label><br>
                    <span class="text-danger" ></span>
                    <input style="background-color: #fff" name="image" class="form-control form-control-sm"
                        id="formFileSm" type="file">
                    
                </div>
                <div class="form-floating mb-3">
                    <select name="category_id" class="form-select" id="floatingSelect">
                        <?php
                            foreach ($list_catgory_posts as $value) {
                                
                        ?>
                        <option value="<?=$value['id']?>"><?=$value['name']?></option>'
                        <?php
                            }
                                
                        ?>
                    </select>
                    <label for="floatingSelect">Chọn chuyên mục</label>
                </div>
                <h6 class="mb-4">
                    <input type="submit" name="add_post" value="Đăng" class="btn btn-custom">
                    
                </h6>

            </div>
        </div>

    </form>
</div>
<!-- Form End -->

<style>
    .ck-editor__editable[role="textbox"]:first-child {
        /* editing area */
        min-height: 300px;
    }


    .ck-content .image {
        /* block images */
        max-width: 80%;
        margin: 20px auto;
    }
</style>