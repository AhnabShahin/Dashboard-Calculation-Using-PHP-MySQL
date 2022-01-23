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

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $sql = "SELECT `id`, `item_id`, `discount_percent` FROM discounts WHERE item_id = ?";
       
    if($stmt1 = $mysqli->prepare($sql)){
        $stmt1->bind_param("i", $_POST['item_id']);
        if($stmt1->execute()){
            // Store result
            $stmt1->store_result();
            $stmt1->bind_result($id, $item_id, $discount_percent);
            if($stmt1->fetch()){
              
            }
        }
        $stmt1->close();
        
    }
    $sql = "SELECT `id`, `cake_name`, `raw_material_cost`, `transportation_cost`, `utility_cost`, `space_cost`, `staff_cost`, `selling_price` FROM `items` WHERE id = ?;";
    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("i", $_POST['item_id']);
        if($stmt->execute()){
            // Store result
            $stmt->store_result();
            $stmt->bind_result($id, $cake_name, $raw_material_cost, $transportation_cost, $utility_cost, $space_cost, $staff_cost, $selling_price);
            if($stmt->fetch()){
                $quantity=$_POST['quantity'];
                $total_utility_cost= ((($_POST['quantity'] * $raw_material_cost) + $transportation_cost)/100)*3;
                $total_cost= round((($_POST['quantity'] * $raw_material_cost) + $transportation_cost + $total_utility_cost + $space_cost + ($staff_cost * $_POST['quantity'])),2);
                $total_price = $selling_price * $_POST['quantity'];
                $discounted_price= round($total_price - (($total_price/100)*$discount_percent),2);
                $item_id= $_POST['item_id'];

                $sql = "INSERT INTO sells (quantity, total_cost, total_price, discounted_price, item_id) VALUES (?, ?, ?, ?, ?)";
                     
                    if($stmt = $mysqli->prepare($sql)){
                        // Bind variables to the prepared statement as parameters
                        $stmt->bind_param("idddi", $quantity, $total_cost,$total_price, $discounted_price, $item_id);
                        
                        // Attempt to execute the prepared statement
                        if($stmt->execute()){
                            // Redirect to login page
                            header("location: index.php");
                        } else{
                            echo "Oops! Something went wrong. Please try again later.";
                        }
            
                        // Close statement
                        $stmt->close();
                    }
            }
        }
    }

   
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ margin:0 auto; width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Add a new order</h2>
        <p>Please fill this form to add order</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Quantity</label>
                <input type="number" name="quantity" required class="form-control <?php echo (!empty($quantity_err)) ? 'is-invalid' : ''; ?>" value="">
                <span class="invalid-feedback"><?php echo $quantity_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Select Cake</label>
                <select name="item_id" class="form-control" id="cars">
                <?php
                    while ($stmt->fetch()) {
                ?>
                   <option value=<?php echo $id ?>><?php echo $cake_name ?></option>
                <?php }
                 $stmt->close();
                ?>
                </select>
            </div>    
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
        </form>
    </div>    
</body>
</html>