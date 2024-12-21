<?php 
    include_once('./includes/restriction.php');
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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

    <div class="content">
        <h1>PRODUCT CATEGORIES</h1>
        <hr>

        <div class="table-cont">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">S.No</th>
                        <th scope="col">Category</th>
                        <th scope="col">No. of Posts</th>
                    </tr>
                </thead>

                <tbody class="table-group-divider">
                    <?php
                    include "includes/config.php";

                    // todo: work with those categories
                    $catagory_list = ['men', 'women', 'kids', 'electronics', 'home', 'sports', 'beauty', 'furniture', 'books', 'stationary', 'grocery', 'other'];

                    for($i = 0; $i < sizeof($catagory_list); $i++) {
                        $sn = $i + 1;
                        $catagory = $catagory_list[$i];
                        $sql = "SELECT * FROM products WHERE product_catag= '{$catagory}' ";
                        $result = $conn->query($sql);
                        $total_post = $result->num_rows;

                        // output data of each row
                        if ($row = $result->fetch_assoc()) {
                    ?>
                    <tr>
                        <th scope="row"><?php echo $sn ?></th>
                        <td><?php echo $row["product_catag"] ?></td>
                        <td><?php echo $total_post?></td>    
                    </tr>
                    <?php
                        } // End of if condition
                    } // End of the for loop
                    ?>
                </tbody>
            </table>
        </div>
        <br>
    </div>
</body>
