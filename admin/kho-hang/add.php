<?php

    $error = array(
        'name' => '',
        'price' => '',
        'quantity' => '',
            
    );

    $temp = array(
        'name' => '',
        'price' => '',
        'quantity' => '',
             
    );
    $success = '';

    
    // Kiểm tra nếu form được submit
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_user"])) {
        
        // Lấy dữ liệu từ form
        $name = trim($_POST["name"]);
        $price = trim($_POST["price"]);
        $quantity = trim($_POST["quantity"]);
        

        if((strlen($name) > 255)) {
            $error['fullname'] = 'Họ tên không được quá 255 ký tự';
        }

        if($price < 0) {
            $error['price'] = 'Giá số nguyên dương';
        }

        if($quantity < 0) {
            $error['quantity'] = 'Số lượng nguyên dương';
        }



        if(empty(array_filter($error))) {
            //Insert dữ liệu user
            $WarehousemModel->insert_warehouse($name, $price, $quantity);
            $success = 'Thêm thành công';
            
        }else {
            $temp['name'] = $name;
            $temp['price'] = $price;
            $temp['quantity'] = $quantity;
           
        }
    }
    $html_alert = $BaseModel->alert_error_success('', $success);
    
?>

<div class="container-fluid pt-4" style="margin-bottom: 110px;">

    <form class="row g-4" action="" method="post" enctype="multipart/form-data">

        <div class="col-sm-12 col-xl-12">

            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">
                    <a href="index.php?quanli=kho-hang" class="link-not-hover">Kho hàng</a>
                    / quản lý
                </h6>
                <?=$html_alert?>
                
                <label for="">Tên sản phẩm</label>
                <div class="mb-1">
                    <input name="name" type="text" class="form-control" value="<?=$temp['name']?>" required>
                    <span class="text-danger err"><?=$error['name']?></span>
                </div>
                <label for="">Giá mua vào</label>
                <div class="mb-1">
                    <input name="price" type="number" value="<?=$temp['price']?>" class="form-control" required>
                    <span class="text-danger err"><?=$error['price']?></span>
                </div>
                <label for="">Số lượng nhập</label>
                <div class="mb-1">
                    <input name="quantity" type="number" value="<?=$temp['quantity']?>" class="form-control" required>
                    <span class="text-danger err"><?=$error['quantity']?></span>
                </div>
                
                <h6 class="mb-4">
                    <input type="submit" name="add_user" value="Thêm hóa đơn" class="btn btn-custom">

                </h6>
            </div>
            
        </div>
        

    </form>
</div>
<!-- Form End -->
<style>
.err {
    display: inline-block;
    height: 22px;
}
</style>