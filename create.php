<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$nik = $name = $age = $city = $agama = "";
$nik_err = $name_err = $age_err = $city_err = $agama_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate NIK
    $input_nik = trim($_POST["nikCode"]);
    if(empty($input_nik)){
        $nik_err = "Please enter a NIK.";
    } else{
        $nik = $input_nik;
    }

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
    $agama_err = "Please enter a city.";
    } else{
    $agama = $input_agama;
    }

    // Check input errors before inserting in database
    if(empty($nik_err) && empty($name_err) && empty($age_err) && empty($city_err) && empty($agama_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO profil (nikCode, fullName, age, city, agama) VALUES (?, ?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_nik, $param_name, $param_age, $param_city, $param_agama);

            // Set parameters
            $param_nik = $nik;
            $param_name = $name;
            $param_age = $age;
            $param_city = $city;
            $param_agama = $agama;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
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
                        <h2>Tambah Record</h2>
                    </div>
                    <p>Silahkan isi form di bawah ini kemudian submit untuk menambahkan data diri ke dalam database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($nik_err)) ? 'has-error' : ''; ?>">
                            <label>NIK</label>
                            <input type="text" name="nikCode" class="form-control" value="<?php echo $nik; ?>">
                            <span class="help-block"><?php echo $nik_err;?></span>
                        </div>
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
                            <input type="text" name="agama" class="form-control" value="<?php echo $age; ?>">
                            <span class="help-block"><?php echo $agama_errs;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>