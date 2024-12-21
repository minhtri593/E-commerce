<?php
    include "includes/config.php";
$sql = "DELETE FROM products where product_id={$_GET['id']}"; //sql query for deleting
if ($conn->query($sql)) {
    // Redirect to the post page with a success message in the URL
    header("Location: http://localhost/E-Commerce/admin/post.php?delete=success");
} else {
    // Redirect to the post page with an error message in the URL
    header("Location: http://localhost/E-Commerce/admin/post.php?delete=error");
}
?>
