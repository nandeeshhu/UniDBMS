<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Registration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f0f0; /* Light gray background */
            font-family: Times New Roman;
        }

        .header {
            background-color: #333; /* Dark background for the header */
            padding: 15px; /* Adjust the padding as needed */
            text-align: center;
            color: #fff; /* Text color */
        }

        .logo {
            max-height: 120px; /* Adjust the logo's size as needed */
            vertical-align: middle;
        }

        .registration-title {
            display: inline-block;
            margin: 0 auto;
        }
        
        .container {
            margin-top: 20px; /* Adjust the overall top margin */
        }

        .success-message {
            text-align: center;
            color: green;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<?php
$servername = "localhost";
$port_no = 3306;
$username = "nandeesh.u"; 
$password = "abc123"; 
$myDB = "university"; 

try {
    $conn = new PDO("mysql:host=$servername;port=$port_no;dbname=$myDB", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
<div class="header">
    <div class="d-flex justify-content-between align-items-center">
        <img src="logo.jpeg" alt="Institute Logo" class="logo">
        <h1 class="registration-title">Department Registration</h1>
    </div>
</div>

<div class="container">
    <?php
    session_start(); // Start the session

    if (isset($_POST['submit'])) {
        try {
            // Retrieve user inputs
            $hod = null;
            $dept_name = $_POST["departmentName"];
    
            if (isset($_POST["hod"])) {
                $hod = $_POST["hod"];
            }
    
            $checkDeptStmt = $conn->prepare("SELECT * FROM department WHERE name = :deptName");
            $checkDeptStmt->bindParam(':deptName', $dept_name);
            $checkDeptStmt->execute();
    
            $checkDeptStmt1 = $conn->prepare("SELECT * FROM department WHERE hod = :hod");
            $checkDeptStmt1->bindParam(':hod', $hod);
            $checkDeptStmt1->execute();
    
            if ($checkDeptStmt->rowCount() > 0) {
                echo "<div class='alert alert-danger'>Department with the name '$dept_name' already exists. Please choose a different name.</div>";
            } else if ($checkDeptStmt1->rowCount() > 0) {
                $checkDeptStmt2 = $conn->prepare("SELECT name FROM faculty WHERE faculty_id = :hod");
                $checkDeptStmt2->bindParam(':hod', $hod);
                $checkDeptStmt2->execute();
                while ($row = $checkDeptStmt2->fetch(PDO::FETCH_ASSOC)) {
                    $facultyName = $row["name"];
                }
                echo "<div class='alert alert-danger'>Department with the HOD '$facultyName' already exists. Please choose a different faculty.</div>";
            } else {
                $stmt = $conn->prepare("INSERT INTO department (name, hod) VALUES (:name, :hod)");
                $stmt->bindParam(':name', $dept_name);
                $stmt->bindParam(':hod', $hod);
                $stmt->execute();
                $lastInsertId = $conn->lastInsertId();
    
                $stmt = $conn->prepare("UPDATE Faculty SET dept_id = :dept_id WHERE faculty_id = :hod;");
                $stmt->bindParam(':dept_id', $lastInsertId);
                $stmt->bindParam(':hod', $hod);
                $stmt->execute();
    
                // Set success message in session
                $_SESSION['dept_success_message'] = "Department created successfully with Department ID: $lastInsertId";
    
                // Redirect to prevent form resubmission
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
        } catch (PDOException $ex) {
            echo "<div class='alert alert-danger'>Error: " . $ex->getMessage() . "</div>";
        }
    }
    
    // Display success message if available
    if (isset($_SESSION['dept_success_message'])) {
        echo "<div class='success-message'>{$_SESSION['dept_success_message']}</div>";
        unset($_SESSION['dept_success_message']); // Clear the session variable
    }
    ?>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <form method="post">
                <div class="form-group">
                    <label for="departmentName">Department Name:</label>
                    <input type="text" class="form-control" id="departmentName" name="departmentName" required>
                </div>

                <div class="form-group">
                    <label for="hod">Head of Department:</label>
                    <select class="form-control" id="hod" name="hod" required>
                        <?php
                        $facultyQuery = "SELECT faculty_id, name FROM faculty WHERE faculty.experience >= 10 AND faculty.dept_id=1";
                        $result = $conn->query($facultyQuery);

                        // Populate the options dynamically
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            $facultyId = $row["faculty_id"];
                            $facultyName = $row["name"];
                            echo "<option value='$facultyId'>$facultyId: $facultyName</option>";
                        }
                        ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" name="submit">Submit</button>

            </form>
        </div>
    </div>
</div>
<div class="container">
    <?php
    function displayDepartmentData($conn) {
        try {
            $deptViewQuery = "SELECT department.dept_id, department.name, faculty.name AS hod_name 
                             FROM department
                             LEFT JOIN faculty ON department.hod = faculty.faculty_id";
            $result = $conn->query($deptViewQuery);

            echo "<h2 class='mt-4'>Present Department List</h2>";
            echo "<table class='table'>";
            echo "<thead><tr><th>ID</th><th>Name</th><th>Head of Department</th></tr></thead><tbody>";

            // Display data in a table
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>{$row['dept_id']}</td>";
                echo "<td>{$row['name']}</td>";
                echo "<td>{$row['hod_name']}</td>";
                echo "</tr>";
            }

            echo "</tbody></table>";
        } catch (PDOException $ex) {
            echo "<div class='alert alert-danger'>Error: " . $ex->getMessage() . "</div>";
        }
    }

    // Display department data
    displayDepartmentData($conn);

    // Your existing PHP code for form submission
    ?>
    
    <div class="row justify-content-center">
        <div class="col-md-6">
            <!-- Your existing form -->
            <form method="post">
                <!-- Your form fields -->
                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
