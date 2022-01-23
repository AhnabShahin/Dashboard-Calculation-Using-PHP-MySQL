<?php
// Initialize the session
session_start();
require_once "config.php";
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$sql = "SELECT * FROM items";
if($stmt = $mysqli->prepare($sql)){
    if($stmt->execute()){
        // Store result
        $stmt->store_result();
        $stmt->bind_result($id, $cake_name, $raw_material_cost, $transportation_cost, $utility_cost, $space_cost, $staff_cost, $selling_price);

    }
}
$sql = "SELECT * FROM sells";
if($stmt1 = $mysqli->prepare($sql)){
    if($stmt1->execute()){
        // Store result
        $stmt1->store_result();
        $stmt1->bind_result($id, $quantity, $total_cost, $total_price, $discounted_price, $item_id);

    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
    <h1 class="my-2">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
    <p>
        <a href="reset-password.php" class="btn btn-success">Reset Your Password</a>
        <a href="logout.php" class="btn btn-primary m-3">Sign Out of Your Account</a>
        <a href="add-item.php" class="btn btn-secondary m-3">Add Item</a>
        <a href="add-order.php" class="btn btn-secondary m-3">Add Oder</a>
    </p>
    <table class="table table-striped table-dark mb-5">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Cake Name</th>
      <th scope="col">Material cost</th>
      <th scope="col">Transportation</th>
      <th scope="col">Utility Cost</th>
      <th scope="col">Space Cost</th>
      <th scope="col">Staff Cost</th>
      <th scope="col">Selling Price</th>
    </tr>
  </thead>
  <tbody>
      <?php
      while ($stmt->fetch()) {
          ?>
    <tr>
      <th scope="row"><?php echo $id ?></th>
      <td><?php echo $cake_name ?></td>
      <td><?php echo $raw_material_cost ?></td>
      <td><?php echo $transportation_cost ?></td>
      <td><?php echo $utility_cost ?></td>
      <td><?php echo $space_cost ?></td>
      <td><?php echo $staff_cost ?></td>
      <td><?php echo $selling_price ?></td>
    </tr>

    <?php } ?>
  </tbody>
</table>
<h4>Sells Details Table </h4>
<table class="table table-striped table-dark mb-5">
  <thead>
    <tr>
      <th scope="col">Item ID</th>
      <th scope="col">Quantity</th>
      <th scope="col">Actual Price</th>
      <th scope="col">discounted price</th>
      <th scope="col">Inventory cost</th>
      <th scope="col">Profit on sell</th>
    </tr>
  </thead>
  <tbody>
      <?php
      $total_sells_after_discount=0;
      $total_inventory_cost=0;
      while ($stmt1->fetch()) {
          ?>
    <tr>
      <th scope="row"><?php echo $item_id ?></th>
      <td><?php echo $quantity ?></td>
      <td><?php echo $total_price ?></td>
      <td><?php echo $discounted_price  ?></td>
      <td><?php echo $total_cost ?></td>
      <td><?php echo $discounted_price - $total_cost ?></td>
    </tr>

    <?php 
     $total_sells_after_discount=$discounted_price+$total_sells_after_discount;
     $total_inventory_cost=$total_cost+$total_inventory_cost;
    } ?>
  </tbody>
</table>
<p>Total Sells After Discount Till Now : <b class="text-success"><?php echo $total_sells_after_discount ?></b></p>
<p>Total Inventory Cost Till Now : <b class="text-success"><?php echo $total_inventory_cost ?></b></p>
<p>Total Profit Till Now : <b class="text-primary"><?php echo $total_sells_after_discount - $total_inventory_cost ?></b></p>
</div>
</body>
</html>