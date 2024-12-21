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
if (isset($_POST['id']) && isset($_SESSION['mycart'])) {
  $productId = $_POST['id'];

  // Check if the product exists in the cart and remove it
  if (isset($_SESSION['mycart'][$productId])) {
      unset($_SESSION['mycart'][$productId]);
      echo 'Product removed'; // Optionally send a message back
  } else {
      echo 'Product not found'; // Handle error if product doesn't exist
  }
  exit(); // Stop further script execution after handling the request
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
            <div class="qty-buttons">
              <button class="update-qty" data-action="decrease" data-id="<?php echo $key; ?>">-</button>
              <span class="qty"><?php echo $value['product_qty']; ?></span>
              <button class="update-qty" data-action="increase" data-id="<?php echo $key; ?>">+</button> 
            </div>
          </td>
          <td>
            <button class="remove-btn" data-id="<?php echo $key; ?>"><i class="fas fa-trash"></i></button> 
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
      </div>
      <!-- Hiển thị tổng giá trị giỏ hàng -->
       
      <div class="total-price">
        <?php
        if (isset($_SESSION['mycart'])) {
            $totalPrice = 0;
            foreach ($_SESSION['mycart'] as $item) {
                $totalPrice += $item['price'] * $item['product_qty'];
            }
        ?>
        <p>Total Price: $<?php echo number_format($totalPrice, 2); ?></p>
        <?php
        }
        ?>
      </div>

      <!-- Nút Checkout -->
      <div class="child-register-btn">
        <?php
        if (isset($_SESSION['mycart'])) {
        ?>
        <p><a href="checkout.php" style="color:#FFFFFF">Thanh Toán</a></p>
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
  document.querySelector('.container').addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('remove-btn')) {
        var productId = e.target.getAttribute('data-id'); // Get the product ID from the button's data-id

        // Send AJAX request to remove the product from session
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '', true); // Send the request to the same file (cart.php)
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status == 200) {
                // If removal was successful, remove the row from the table
                var productRow = document.getElementById('product-' + productId);
                productRow.parentNode.removeChild(productRow); // Remove the product row from the table
            } else {
                alert('Error removing item');
            }
        };
        xhr.send('id=' + productId); // Send product ID to the server for removal
    }
});
});
</script>

<style>

:root {
  --main-maroon: #CE5959;
  --deep-maroon: #89375F;
}
.cart-summary {
  margin-top: 30px;
  text-align: center;
  font-weight: bold;
}
td, th {
  text-align: center;
}

td img {
  margin-left: auto;
  margin-right: auto;
}
.delete-icon {
  color: var(--main-maroon); 
  cursor: pointer; 
}
.remove-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: var(--main-maroon);
  color: white;
  border: none;
  padding: 5px 10px;
  margin: 0 5px;
  cursor: pointer;
  font-size: 18px;
  border-radius: 5px;
}

.remove-btn i {
  font-size: 20px; /* Adjust size of the trash icon */
  display: flex;
  align-items: center;
  justify-content: center;
}
.qty-buttons {
  display: flex;
  align-items: center;
  justify-content: center;
}

.qty-buttons button {
  background-color: var(--main-maroon);
  color: white;
  border: none;
  padding: 5px 10px;
  margin: 0 5px;
  cursor: pointer;
  font-size: 18px;
  border-radius: 5px;
}

.qty-buttons .qty {
  margin: 0 10px;
  font-size: 18px;
}

.child-register-btn {
  margin-top: 30px;
  text-align: center;
}

.total-price {
  margin-bottom: 20px;
  font-size: 20px;
  font-weight: bold;
}
table {
  width: 100%; /* Adjust as per your requirement */
  border-collapse: collapse;
  text-align: center;
}
.child-register-btn p {
  background-color: var(--main-maroon);
  box-shadow: 0px 0px 4px #615f5f;
  line-height: 60px;
  color: white;
  width: 350px;
  height: 60px;
  margin: 0 auto;
  border-radius: 8px;
  font-size: 19px;
  font-weight: 600;
  cursor: pointer;
}


@media screen and (max-width: 794px) {
  table {
    width: 100%;
  }

  .child-register-btn {
    width: 100%;
  }
}

.total-price {
  margin-top: 20px;
  text-align: center;
  font-size: 20px;
  font-weight: bold;
}
</style>


<?php require_once './includes/footer.php'; ?>