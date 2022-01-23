<?php
// Include config file
require_once "config.php";

 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $cake_name= $_POST['cake_name'];
    $raw_material_cost= $_POST['raw_material_cost'];
    $transportation_cost= $_POST['transportation_cost'];
    $utility_cost= round((($_POST['raw_material_cost'] + $_POST['transportation_cost'])/100) * $_POST['utility_cost'],2);
    $space_cost= $_POST['space_cost'];
    $staff_cost= $_POST['staff_cost'];
    $selling_price= $_POST['selling_price'];

    $sql = "INSERT INTO items (cake_name, raw_material_cost, transportation_cost, utility_cost, space_cost, staff_cost, selling_price) VALUES (?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sdddddd", $cake_name, $raw_material_cost, $transportation_cost, $utility_cost, $space_cost, $staff_cost, $selling_price);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
}
// Close connection
$mysqli->close();
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
        <h2>Add New Item</h2>
        <p>Please fill this form to add item</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Cake Name</label>
                <input type="text" name="cake_name" required class="form-control <?php echo (!empty($cake_name_err)) ? 'is-invalid' : ''; ?>" value="">
                <span class="invalid-feedback"><?php echo $cake_name_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Raw material cost (Per Pound)</label>
                <input type="number" name="raw_material_cost" required class="form-control <?php echo (!empty($raw_material_cost_err)) ? 'is-invalid' : ''; ?>" value="">
                <span class="invalid-feedback"><?php echo $raw_material_cost_err; ?></span>
            </div>
            <div class="form-group">
                <label>Transportation Cost (Per Cake)</label>
                <input type="number" name="transportation_cost" required class="form-control <?php echo (!empty($transportation_cost_err)) ? 'is-invalid' : ''; ?>" value="">
                <span class="invalid-feedback"><?php echo $transportation_cost_err; ?></span>
            </div>
            <div class="form-group">
                <label>Utility Cost (Percent on marerial cost and transportation cost)</label>
                <input type="number" name="utility_cost" required class="form-control <?php echo (!empty($utility_cost_err)) ? 'is-invalid' : ''; ?>" value="">
                <span class="invalid-feedback"><?php echo $utility_cost_err; ?></span>
            </div>
            <div class="form-group">
                <label>Space Cost (Per cake)</label>
                <input type="number" name="space_cost" required class="form-control <?php echo (!empty($space_cost_err)) ? 'is-invalid' : ''; ?>" value="">
                <span class="invalid-feedback"><?php echo $space_cost_err; ?></span>
            </div>
            <div class="form-group">
                <label>Staff Cost (Per pound)</label>
                <input type="number" name="staff_cost" required class="form-control <?php echo (!empty($staff_cost_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $staff_cost; ?>">
                <span class="invalid-feedback"><?php echo $staff_cost_err; ?></span>
            </div>
            <div class="form-group">
                <label>Selling Price (Per pound)</label>
                <input type="number" name="selling_price" required class="form-control <?php echo (!empty($selling_price_err)) ? 'is-invalid' : ''; ?>" value="">
                <span class="invalid-feedback"><?php echo $selling_price_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
        </form>
    </div>    
</body>
</html>