<?php
if (isset($_POST['search'])) {
    $keyword = $_POST['keyword'];
    $cate_id = $_POST['search_cate'];
} else {
    $keyword = '';
    $cate_id = 0;
}

if(isset($_GET['xoa'])) {
    $id = $_GET['xoa'];

    try {
        $WarehousemModel->delete_warehouse($id);
    } catch (\Throwable $th) {
        throw $th;
    }
}



$list_warehouse = $WarehousemModel->select_all_warehouse();

?>

<!-- LIST PRODUCTS -->
<div class="container-fluid pt-4 px-4">
    <div class="bg-light text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Quản lý kho hàng</h6>
            <a href="them-hoa-don" class="btn btn-custom"><i class="fa fa-plus"></i> Thêm hóa đơn</a>


        </div>

        <div class="row align-items-center">
            <div class="col-lg-7 d-flex mb-3">

                


            </div>
            
        </div>


        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0" id="khohang-list">
                <thead>
                    <tr class="text-dark">

                        <th scope="col">#</th>
                        <th scope="col">Tên sản phẩm</th>
                        <th scope="col">Ngày nhập</th>
                        <th scope="col">Tồn kho</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col">Giá mua vào</th>
                        <th scope="col">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $index = 0;
                    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $productsPerPage = 5;  // Set your items per page
                    foreach ($list_warehouse as $value) {
                        $index++;
                       $orderNumber = ($currentPage - 1) * $productsPerPage + $index;

                       $date_formated = $BaseModel->date_format($value['created_at'], '');
                    ?>
                    <tr>

                        <td class="text-dark"><?=$orderNumber?></td>
                        <td class="text-dark" style="min-width: 200px;"><?=$value['name']?></td>
                        
                        <td class="text-dark"><?=$date_formated?></td>
                        <td class="text-dark"><?=$value['quantity']?></td>
                        <td class="text-dark">
                            <?php
                                if ($value['quantity'] == 0) {
                            ?>
                            <span class="badge bg-danger">Hết hàng</span>
                            <?php
                                }elseif($value['quantity'] >= 10) {
                            ?>
                            <span class="badge bg-success">Còn hàng</span>
                            <?php
                                }elseif($value['quantity'] < 10) {
                            ?>

                            <span class="badge bg-warning">Số lượng còn ít</span>
                            <?php
                                }
                            ?>

                        </td>
                        
                        <td class="text-dark" style="font-weight: 600;">
                            <?=number_format($value['price'])."₫"?>
                        </td>
                        

                        <td>
                            <a href="index.php?quanli=kho-hang&xoa=<?=$value['id']?>" class="btn btn-danger">Xóa</a>
                            
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