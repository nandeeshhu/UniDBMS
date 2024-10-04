<?php
// Database connection details (removed for security reasons)
// ...

// Initialize session
session_start();

// Check if the user is already logged in, redirect to another page if true
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: page2.php");
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form values
    $inputUsername = isset($_POST["username"]) ? $_POST["username"] : "";
    $inputPassword = isset($_POST["password"]) ? $_POST["password"] : "";
    $inputCaptcha = isset($_POST["captcha"]) ? $_POST["captcha"] : "";

    // Validate CAPTCHA (You can implement a more robust CAPTCHA system)
    if (!isset($_POST["notRobot"])) {
        $loginError = "Please confirm that you're not a robot.";
    } else {
        try {
            // Database connection
            $conn = new PDO("mysql:host=localhost;port=3306;dbname=university", "nandeesh.u", "abc123");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Hardcoded admin credentials (for testing purposes)
            $adminUsername = "admin";
            $adminPassword = "abc123";

            // Check username and password
            if ($inputUsername === $adminUsername && $inputPassword === $adminPassword) {
                // Login successful, set session variables
                $_SESSION["loggedin"] = true;
                $_SESSION["username"] = $inputUsername;

                // Redirect to welcome page
                header("location: page2.php");
                exit;
            } else {
                // Login failed
                $loginError = "Username or password is incorrect.";
            }
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }

        // Close the database connection
        $conn = null;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f0f0; /* Light gray background */
        }

        .header {
    background-color: #333; /* Dark background for the header */
    padding: 15px; /* Adjust the padding as needed */
    text-align: middle;
    color: #fff; /* Text color */
    font-size: 35px; /* Adjust the font size as needed */
    margin-left: auto; /* Add this line */
    margin-right: auto; /* Add this line */
    max-width: 100% /* Set a maximum width for centering on larger screens */
}


        .logo {
            max-height: 140px; /* Adjust the logo's size as needed */
            vertical-align: middle;
        }

        .login-container {
            margin-top: 20px; /* Adjust the top margin */
        }

        .login-form {
            max-width: 400px; /* Adjust the form width */
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .center-text {
            text-align: center;
        }

    </style>
</head>
<body>

<div class="header d-flex align-items-center">
    <img src="iitlogo.png" alt="Institute Logo" class="logo mr-auto"> <!-- Logo aligned to the left -->
    <h1 class="mx-auto">Indian Institute of Technology</h1> <!-- Institute name centered -->
    <!-- If you have more content for the right side, add here -->
</div>

<div class="container login-container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="login-form">
                <form method="post">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="notRobot" required> I'm not a robot
                        </label>
                    </div>

                    <?php if (isset($loginError)) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $loginError; ?>
                        </div>
                    <?php endif; ?>

                    <button type="submit" class="btn btn-primary" name="submit">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
