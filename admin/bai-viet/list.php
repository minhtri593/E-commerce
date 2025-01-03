<?php
    $list_posts = $PostModel->select_all_posts();

    $success = '';
    if(isset($_GET['xoa']) && $_GET['xoa'] >0) {
        $post_id = $_GET['xoa'];
        $PostModel->delete_post($post_id);
        setcookie('success_delete', 'Đã xóa thành công 1 bài viết', time() + 5, '/');
        header("Location: index.php?quanli=danh-sach-bai-viet");
    }

    if(isset($_COOKIE['success_delete']) && !empty($_COOKIE['success_delete'])) {
        $success = $_COOKIE['success_delete'];
    }
    $html_alert = $BaseModel->alert_error_success('', $success);
?>

<!-- LIST PRODUCTS -->
<div class="container-fluid pt-4 px-4">
    <div class="bg-light text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Danh sách bài viết</h6>
            <a href="them-bai-viet" class="btn btn-custom"><i class="fa fa-plus"></i> Thêm bài viết</a>
        </div>

        <div class="table-responsive">
            <?=$html_alert?>
            <table class="table text-start align-middle table-bordered table-hover mb-0" id="post-list">
                <thead>
                    <tr class="text-dark">

                        <th scope="col">#</th>
                        <th scope="col">Tiêu đề</th> 
                        <th scope="col">Tác giả</th> 
                        <th scope="col">Chuyên mục</th>
                        <th scope="col">Ngày đăng</th>           
                        <th scope="col">Chỉnh sửa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i=0;
                    foreach ($list_posts as $value) {
                        extract($value);
                        $i++;
                    ?>
                    <tr>
                        <td><?=$i?></td>
                        <td style="min-width: 180px;"><?=$title?></td>
                        <td style="min-width: 120px;"><?=$author?></td>
                        <td style="min-width: 180px;">
                            <?=$category_name?>
                        </td>
                        <td style="min-width: 180px;"> <?=$created_at ?> </td>
                        <td style="min-width: 180px;">
                            <a href="cap-nhat-bai-viet&id=<?=$post_id?>" class="btn-sm btn-success">Xem</a>
                            <a href="cap-nhat-bai-viet&id=<?=$post_id?>" class="btn-sm btn-secondary">Sửa</a>
                            <a onclick="return confirm('Bạn có chắc muốn xóa ?\nSau khi xóa sẽ không thể khôi phục');" href="danh-sach-bai-viet&xoa=<?=$post_id?>" class="btn-sm btn-danger">Xóa</a>
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
<!-- LIST PRODUCTS END -->
<style>
    td {
        height: 50px;
    }
</style>