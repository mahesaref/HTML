<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$nik = $name = $age = $city = $agama = "";
$name_err = $age_err = $city_err = $agama_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $nik = $_POST["id"];
    
    // Validate name
    $input_name = trim($_POST["fullName"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } else{
        $name = $input_name;
    }

    // Validate age
    $input_age = trim($_POST["age"]);
    if(empty($input_age)){
        $age_err = "Please enter a age.";
    } elseif(!ctype_digit($input_age)){
        $age_err = "Please enter a valid age.";
    } else{
        $age = $input_age;
    }

    // Validate city
    $input_city = trim($_POST["city"]);
    if(empty($input_name)){
        $city_err = "Please enter a city.";
    } else{
        $city = $input_city;
    }

     // Validate agama
     $input_agama = trim($_POST["agama"]);
     if(empty($input_agama)){
         $city_err = "Please enter a agama.";
     } else{
         $agama = $input_agama;
     }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($age_err) && empty($city_err) && empty($agama_err)){
        // Prepare an update statement
        $sql = "UPDATE profil SET fullName=?, age=?, city=?, agama=? WHERE nikCode=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssi", $param_name, $param_age, $param_city, $param_agama, $param_nik);
            
            // Set parameters
            $param_name = $name;
            $param_age = $age;
            $param_city = $city;
            $param_agama = $agama;
            $param_nik = $nik;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $nik =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM profil WHERE nikCode = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_nik);
            
            // Set parameters
            $param_nik = $nik;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $name = $row["fullName"];
                    $age = $row["age"];
                    $city = $row["city"];
                    $agama = $row["agama"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Update Record</h2>
                    </div>
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>NAMA</label>
                            <input type="text" name="fullName" class="form-control" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($age_err)) ? 'has-error' : ''; ?>">
                            <label>Umur</label>
                            <input type="text" name="age" class="form-control" value="<?php echo $age; ?>">
                            <span class="help-block"><?php echo $age_errs;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($city_err)) ? 'has-error' : ''; ?>">
                            <label>Domisili</label>
                            <textarea name="city" class="form-control"><?php echo $city; ?></textarea>
                            <span class="help-block"><?php echo $city_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($agama_err)) ? 'has-error' : ''; ?>">
                            <label>Agama</label>
                            <input type="text" name="agama" class="form-control" value="<?php echo $agama; ?>">
                            <span class="help-block"><?php echo $agama_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $nik; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>