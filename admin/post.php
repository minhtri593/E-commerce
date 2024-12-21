<?php 
    include_once('./includes/restriction.php');
    if (!(isset($_SESSION['logged-in']))) {
        header("Location:login.php?unauthorizedAccess");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
              font-family: 'Arial', sans-serif;
              background-color: #f9f9f9;
              display: flex; /* Đảm bảo body hiển thị theo dạng flex */
              margin: 0;
              padding: 0;
          }
        .sidebar {
          height: 100vh;
          width: 200px;
          background: #FFF9F3;
          border-right: 1px solid #f0f0f0;
          position: fixed;
          display: flex;
          flex-direction: column;
          padding: 10px 15px;
          box-sizing: border-box;
        }
        .sidebar .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
        }
        .sidebar .brand img {
            width: 165px;
            height: 53px;
            object-fit: cover;
        }
        .sidebar .nav-link {
            color: #8d8d8d;
            padding: 10px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            background: #ffc107;
            color: #ffffff;
        }
        .sidebar .logout {
            margin-top: auto;
            text-align: center;
        }
        .sidebar .logout a {
            color: #f2994a;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }
        .sidebar .logout a:hover {
            color: #e67e22;
        }
        .content {
          margin-left: 250px; /* Căn chính xác với chiều rộng của sidebar */
          padding: 20px;
          flex-grow: 1;
          box-sizing: border-box;
        }
        .table-cont {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">
        <a href="../index.php" class="text-decoration-none">
        <img src="../admin/upload/logo.png" alt="Logo">
    </a>
        </div>
        <nav class="nav flex-column">
        <a href="post.php" class="nav-link"><i class="fas fa-file-alt"></i> Home</a>
        <a href="post.php" class="nav-link"><i class="fas fa-file-alt"></i> Post</a>
        <a href="catagory.php" class="nav-link"><i class="fas fa-th-large"></i> Category</a>
        <a href="users.php" class="nav-link"><i class="fas fa-shopping-cart"></i> Users</a>

        </nav>
        <div class="logout">
            <a href="logout.php?"><i class="fas fa-sign-out-alt"></i> Log out</a>
        </div>
    </div>

    <!-- Content -->
    <div class="content">
        <?php
          if (isset($_GET['delete'])) {
              if ($_GET['delete'] == 'success') {
                  echo '<div class="alert alert-success">Post deleted successfully!</div>';
              } elseif ($_GET['delete'] == 'error') {
                  echo '<div class="alert alert-danger">Error deleting post. Please try again.</div>';
              }
          }
        ?>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>PRODUCTS</h1>
            <a href="add-post.php" class="btn btn-primary btn-lg">ADD Products</a>
        </div>
        <hr>
        <?php
            include "includes/config.php"; 
            $limit = 10;
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $sn = ($page - 1) * $limit;
            $offset = $sn;

            if ($_SESSION["customer_role"] == 'admin') {
                $sql = "SELECT * FROM products ORDER BY products.product_id DESC LIMIT {$offset},{$limit}";
            } elseif ($_SESSION["user_role"] == 'normal') {
                $sql = "SELECT * FROM products WHERE product_author='{$_SESSION['customer_name']}' ORDER BY products.product_id DESC LIMIT {$offset},{$limit}";
            }

            $result = $conn->query($sql) or die("Query Failed.");
            if ($result->num_rows > 0) {
        ?>
        <div class="table-cont">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">S.No</th>
                        <th scope="col">Title</th>
                        <th scope="col">Category</th>
                        <th scope="col">Date</th>
                        <th scope="col">Author</th>
                        <th scope="col">Edit</th>
                        <th scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { $sn++; ?>
                    <tr>
                        <th scope="row"><?php echo $sn; ?></th>
                        <td><?php echo $row["product_title"]; ?></td>
                        <td><?php echo $row["product_catag"]; ?></td>
                        <td><?php echo $row["product_date"]; ?></td>
                        <td><?php echo $row["product_author"]; ?></td>
                        <td>
                            <a href="update-post.php?id=<?php echo $row["product_id"]; ?>" class="text-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                        <td>
                            <a href="remove-post.php?id=<?php echo $row["product_id"]; ?>" class="text-danger">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php } else { echo "<p>No results found.</p>"; } $conn->close(); ?>
        <!-- Pagination -->
        <?php
            include "includes/config.php"; 
            $sql1 = "SELECT * FROM products";
            $result1 = mysqli_query($conn, $sql1) or die("Query Failed.");
            if (mysqli_num_rows($result1) > 0) {
                $total_products = mysqli_num_rows($result1);
                $total_page = ceil($total_products / $limit);
        ?>
        <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm">
                <?php for ($i = 1; $i <= $total_page; $i++) {
                    $active = $page == $i ? "active" : "";
                ?>
                <li class="page-item">
                    <a class="page-link <?php echo $active; ?>" href="post.php?page=<?php echo $i; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
                <?php } ?>
            </ul>
        </nav>
        <?php } ?>
    </div>
</body>
</html>
