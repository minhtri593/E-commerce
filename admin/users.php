<?php 
    include_once('./includes/restriction.php');
    if(!(isset($_SESSION['logged-in']))){
      header("Location:login.php?unauthorizedAccess");
    }
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f9f9f9;
        display: flex; /* Ensure body displays as flex */
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
        margin-left: 250px;
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
<hr>
<?php
  include "includes/config.php";
     
        /* define how much data to show in a page from database*/
        $limit = 4;
        if(isset($_GET['page'])){
          $page = $_GET['page'];
          switch($page){
            case 1: $sn = 0; break;
            case 2: $sn = 4;break;
            case 3: $sn = 8; break;
            case 4: $sn = 12; break;
            case 5: $sn = 16; break;
            case 6: $sn = 20; break;
          }
        }else{
          $page = 1;
          switch($page){
            case 1: $sn = 0; break;
            case 2: $sn = 4;break;
            case 3: $sn = 8; break;
            case 4: $sn = 12; break;
            case 5: $sn = 16; break;
            case 6: $sn = 20; break;
          }
        }
        //define from which row to start extracting data from database
        $offset = ($page - 1) * $limit;

$sql = "SELECT * FROM customer LIMIT {$offset},{$limit}";
$result = $conn->query($sql);
if ($result->num_rows > 0) { ?>
    
    <div class="table-cont">
    <table class="table">
  <thead>
    <tr>
      <th scope="col">S.No</th>
      <th scope="col">Name</th>
      <th scope="col">Phone</th>
      <th scope="col">Address</th>
      <th scope="col">Role</th>
      <th scope="col">Edit</th>      
      <th scope="col">Delete</th>      
    </tr>
  </thead>
  <tbody class="table-group-divider">
<?php 
// output data of each row
while($row = $result->fetch_assoc()) {
    $sn = $sn+1;
?>
    <tr>
      <th scope="row"><?php echo $sn ?></th>
      <td><?php echo $row["customer_fname"] ?></td>
      <td><?php echo $row["customer_phone"] ?></td>    
      <td scope="row"><?php echo $row["customer_address"] ?></td>
      <td><?php echo $row["customer_role"] ?></td>
      <td>
        <a class="fn_link" href="update-user.php?id=<?php echo $row["customer_id"] ?>">
        <i class='fa fa-edit'></i>
        </a>
      </td>          
      <td scope="row">
        <a class="fn_link" href="remove-user.php?id=<?php echo $row["customer_id"] ?>">
          <i class='fa fa-trash'></i>
        </a>
      </td>         
    </tr>

<?php }}else { echo "0 results"; }
             $conn->close(); 
             ?>

</table>
</div>

<!--Pagination-->
<?php
    include "includes/config.php"; 
    // Pagination btn using php with active effects 
    $sql1 = "SELECT * FROM customer";
    $result1 = mysqli_query($conn, $sql1) or die("Query Failed.");

    if(mysqli_num_rows($result1) > 0){
        $total_products = mysqli_num_rows($result1);
        $total_page = ceil($total_products / $limit);
?>
    <nav aria-label="..." style="margin-left: 10px;">
      <ul class="pagination pagination-sm">

<?php 
        for($i=1; $i<=$total_page; $i++){
            // important: this is for active effects that denote in which page you are in the current position
            if($page == $i) {
                $active = "active";
            } else {
                $active = "";
            }
        ?>
        <li class="page-item">
            <a class="page-link <?php echo $active; ?>" href="users.php?page=<?php echo $i; ?>">
            <?php echo $i; ?>
            </a>
        </li>
        <?php }} ?>

      </ul>
    </nav>
