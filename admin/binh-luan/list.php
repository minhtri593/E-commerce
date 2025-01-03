
<?php
    $list_comments = $CommentModel->select_all_comments();
?>
<div class="container-fluid pt-4 px-4">
    <div class="bg-light text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Danh sách bình luận</h6>

        </div>

        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0" id="comments-list">
                <thead>
                    <tr class="text-dark">

                        <th scope="col">#</th>
                        <th scope="col">Họ tên</th> 
                        <th scope="col">Bình luận</th>       
                        <th scope="col">Thời gian</th>  
                        <th scope="col">Chỉnh sửa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i=0;
                    foreach ($list_comments as $value) {
                        extract($value);
                        $i++;
                        $formated_date = $BaseModel->date_format($comment_date, '');
                    ?>
                    <tr>
                        <td><?=$i?></td>
                        <td class="td-name"><?=$full_name?></td>
                        <td style="max-width: 450px; min-width: 450px;">
                            <?=$content?> 
                        </td>
                        <td class="td-date">
                            <?=$formated_date?>
                        </td>
                        <td class="td-responsive-2">
                            <a href="chi-tiet-binh-luan&id=<?=$comment_id?>" class="btn-sm btn-success">Chi tiết</a>
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
