<?php
    $list_name_cate = $PostModel->select_name_cate_post();
    $error = '';
    $success = '';
    //Thêm chuyên mục bài viêt
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_category"])) {
        $name = trim($_POST["name"]);

        if(!empty($name)) {
            if(strlen($name) > 255) {
                $error = 'Tên chuyên mục tối đa 255 ký tự';
            }
    
            foreach ($list_name_cate as $value) {
                if ($value['name'] == $name) {
                    $error = 'Tên chuyên mục đã tồn tại.<br>';
                    break; 
                }
            }

            if(empty($error)) {
                $PostModel->insert_category_post($name);
                $success = 'Thêm chuyên mục thành công';
            }

        }else {
            $error = 'Không được để trống';
        }
    }

    // Xóa
    $err_remove = '';
    if(isset($_GET['xoa']) && isset($_GET['qty_post'])) {
        $cate_post_id = $_GET['xoa'];
        $count_post = $_GET['qty_post'];

        if($count_post <=0) {
            $PostModel->delete_category_posts($cate_post_id);
            $success = 'Xóa chuyên mục thành công';
        }else {
            $err_remove = 'Không được xóa chuyên mục chứa bài viết';
        }

    }

    $list_categoryposts = $PostModel->select_category_posts();

    $html_alert = $BaseModel->alert_error_success($err_remove, $success);
?>

<!-- Form Start -->
<div class="container-fluid pt-4" style="margin-bottom: 110px;">
    <div class="row g-4">
        <div class="col-sm-8 col-xl-5">
            <form action="" method="post">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">
                        <a href="index.php?quanli=danh-sach-bai-viet" class="link-hover">Bài viết</a> /
                        <a href="index.php?quanli=danh-muc-bai-viet" class="link-not-hover">Chuyên mục</a>
                        / Thêm chuyên mục
                    </h6>
                    <?=$html_alert?>
                    <label for="floatingInput">Tên chuyên mục</label>
                    <div class="form-floating mb-4">
                        <input name="name" type="text" class="form-control" id="floatingInput">
                        <span class="text-danger" ><?=$error?></span>
                    </div>
                    <input type="submit" name="add_category" value="Thêm chuyên mục" class="btn btn-custom">           
                </div>
            </form>
        </div>

        <div class="col-sm-8 col-xl-7">
            <div class="bg-light rounded h-100 p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Danh sách chuyên mục</h6>
                </div>
                <div class="table-responsive">
                    <table class="table text-start align-middle table-bordered table-hover mb-0">
                        <thead>
                            <tr class="text-dark">

                                <th scope="col">#</th>
                                <th scope="col">Tên</th>
                                <th scope="col">Bài viết</th>
                                <th scope="col">Chỉnh sửa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i= 0;
                            foreach ($list_categoryposts as $value) {
                                $i++
                            ?>
                            <tr>
                                <td><?=$i?></td>
                                <td><?=$value['name']?></td>
                                <td><?=$value['post_count']?></td>
                                <td>
                                    <a href="cap-nhat-danh-muc-bai-viet&id=<?=$value['id']?>" class="btn-sm btn-secondary">Sửa</a>
                                    <a href="index.php?quanli=danh-muc-bai-viet&xoa=<?=$value['id']?>&qty_post=<?=$value['post_count']?>" class="btn-sm btn-danger">Xóa</a>
                                </td>
                            </tr>
                            <?php 
                            }
                            ?>

                        </tbody>
                    </table>

                </div>

            </div>
        </div>
    </div>

</div>
<!-- Form End -->