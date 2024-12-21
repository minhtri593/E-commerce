
<?php
session_start();

// Kiểm tra nếu có yêu cầu từ AJAX để cập nhật giỏ hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $productId = $_POST['id'];

    // Kiểm tra nếu giỏ hàng đã tồn tại
    if (isset($_SESSION['mycart'][$productId])) {
        $product = $_SESSION['mycart'][$productId];

        if ($action === 'increase') {
            $product['product_qty']++;
        } elseif ($action === 'decrease' && $product['product_qty'] > 1) {
            $product['product_qty']--;
        }

        // Cập nhật lại sản phẩm trong giỏ hàng
        $_SESSION['mycart'][$productId] = $product;
    }

    // Tính toán lại tổng giá trị giỏ hàng
    $totalPrice = 0;
    foreach ($_SESSION['mycart'] as $item) {
        $totalPrice += $item['price'] * $item['product_qty'];
    }

    // Trả về kết quả dưới dạng JSON
    echo json_encode(['success' => true, 'total' => $totalPrice]);
    exit;
}


?>
<?php include_once('./includes/headerNav.php'); ?>

<div class="overlay" data-overlay></div>
<!--
    - HEADER
  -->

<header>
  <!-- top head action, search etc in php -->
  <!-- inc/topheadactions.php -->
  <?php require_once './includes/topheadactions.php'; ?>
  <!-- desktop navigation -->
  <!-- inc/desktopnav.php -->
  <?php require_once './includes/desktopnav.php' ?>
  <!-- mobile nav in php -->
  <!-- inc/mobilenav.php -->
  <?php require_once './includes/mobilenav.php'; ?>

<!-- style -->
    <style>
      :root{
         --main-maroon: #CE5959;
        --deep-maroon: #89375F;
      }
      td,th{
        text-align:center;
      }
      td img{
        margin-left:auto;
        margin-right:auto;
      }
      .delete-icon{
        color:var(--bittersweet);     
        cursor: pointer; 
      }
  .child-register-btn {
    margin-top:20px;
    width:85%;
    margin-left:auto;
    margin-right:auto;
}
.child-register-btn p {
  width: 350px;
  height: 60px;
  background-color: var( --main-maroon);
  box-shadow: 0px 0px 4px #615f5f;
  line-height: 60px;
  color: #FFFFFF;
  margin-left: auto;
  border-radius: 8px;
  text-align: center;
  cursor: pointer;
  font-size: 19px;
  font-weight: 600;
}
@media screen and (max-width: 794px) {
 
  .child-register-btn {
    margin-top:30px;
  
}
 .child-register-btn p {
   width: 100%;
 }
}

    </style>
</header>





<!--
    - MAIN
  -->

<!-- Giỏ hàng -->
<main>
  <div class="product-container">
    <div class="container">
      <table>
        <tr>
          <th>Image</th>
          <th>Name</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Action</th>
        </tr>
        <?php
        if (isset($_SESSION['mycart'])) {
            foreach ($_SESSION['mycart'] as $key => $value) {
        ?>
        <tr>
          <td>
            <img class="cart-product-image" src="./admin/upload/<?php echo $value['product_img']; ?>" alt="">
          </td>
          <td><?php echo $value['name']; ?></td>
          <td><?php echo "$" . $value['price']; ?></td>
          <td>
            <button class="update-qty" data-action="decrease" data-id="<?php echo $key; ?>">-</button>
            <?php echo $value['product_qty']; ?>
            <button class="update-qty" data-action="increase" data-id="<?php echo $key; ?>">+</button>
          </td>
          <td>
            <a href="remove_from_cart.php?id=<?php echo $key; ?>" class="delete-icon">Remove</a>
          </td>
        </tr>
        <?php
            }
        } else {
        ?>
        <tr>
          <td colspan="5">No item available in cart</td>
        </tr>
        <?php
        }
        ?>
      </table>

      <!-- Hiển thị tổng giá trị giỏ hàng -->
      <?php
      if (isset($_SESSION['mycart'])) {
          $totalPrice = 0;
          foreach ($_SESSION['mycart'] as $item) {
              $totalPrice += $item['price'] * $item['product_qty'];
          }
      ?>
      <div class="total-price">
        <p>Total Price: $<?php echo number_format($totalPrice, 2); ?></p>
      </div>
      <?php
      }
      ?>

      <!-- Nút Checkout -->
      <?php
      if (isset($_SESSION['mycart'])) {
      ?>
      <div class="child-register-btn">
        <p><a href="checkout.php" style="color:#FFFFFF">Proceed To CheckOut</a></p>
      </div>
      <?php
      }
      ?>

    </div>
  </div>
</main>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const updateQtyButtons = document.querySelectorAll('.update-qty');

  updateQtyButtons.forEach(button => {
    button.addEventListener('click', function() {
      const action = this.dataset.action;  // "increase" or "decrease"
      const productId = this.dataset.id;   // The index of the product in the session

      // Tạo đối tượng FormData để gửi dữ liệu
      const formData = new FormData();
      formData.append('action', action);
      formData.append('id', productId);

      // Gửi yêu cầu AJAX để cập nhật giỏ hàng
      fetch('cart.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        // Cập nhật lại tổng giá trị giỏ hàng
        if (data.success) {
          document.querySelector('.total-price p').textContent = 'Total Price: $' + data.total.toFixed(2);
          location.reload();  // Tải lại trang để cập nhật giỏ hàng
        } else {
          alert('Error updating cart');
        }
      })
      .catch(error => console.error('Error:', error));
    });
  });
});
</script>



<?php require_once './includes/footer.php'; ?>