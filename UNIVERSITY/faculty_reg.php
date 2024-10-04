<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Registration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f0f0; /* Light gray background */
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
        <h1 class="registration-title">Faculty Registration</h1>
    </div>
</div>

<div class="container">
    <?php
    function displayFacultyData($conn, $filters = [])
    {
        try {
            $facultyViewQuery = "SELECT * FROM faculty WHERE 1";
            
            // Apply filters
            if (!empty($filters['age'])) {
                $facultyViewQuery .= " AND YEAR(CURDATE()) - YEAR(dob) = :age";
            }
    
            if (!empty($filters['experience'])) {
                $facultyViewQuery .= " AND experience = :experience";
            }
    
            if (!empty($filters['qualification'])) {
                $facultyViewQuery .= " AND qualification = :qualification";
            }
    
            if (!empty($filters['salary'])) {
                $facultyViewQuery .= " AND salary = :salary";
            }
    
            if (!empty($filters['deptId'])) {
                $facultyViewQuery .= " AND dept_id = :deptId";
            }
    
            if (!empty($filters['gender'])) {
                $facultyViewQuery .= " AND gender = :gender";
            }
    
            $result = $conn->prepare($facultyViewQuery);
    
            // Bind parameters
            foreach ($filters as $key => $value) {
                $result->bindValue(":$key", $value);
            }
    
            $result->execute();
    
            echo "<h2 class='mt-4'>Present Faculty List</h2>";
            echo "<table class='table'>";
            echo "<thead><tr><th>ID</th><th>Name</th><th>Department</th><th>Email</th><th>Experience</th><th>Salary</th></tr></thead><tbody>";
    
            // Display data in a table
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>{$row['faculty_id']}</td>";
                echo "<td>{$row['name']}</td>";
                echo "<td>{$row['dept_id']}</td>";
                echo "<td>{$row['email']}</td>";
                echo "<td>{$row['experience']}</td>";
                echo "<td>{$row['salary']}</td>";
                echo "</tr>";
            }
    
            echo "</tbody></table>";
        } catch (PDOException $ex) {
            echo "<div class='alert alert-danger'>Error: " . $ex->getMessage() . "</div>";
        }
    }


    session_start(); // Start the session

if (isset($_POST['submit'])) {
    try {
        // Retrieve user inputs
        $fullName = $_POST["fullName"];
        $fatherName = $_POST["fatherName"];
        $dob = $_POST["dob"];
        $gender = $_POST["gender"];
        $mobile = $_POST["mobile"];
        $email = $_POST["email"];
        $qualification = $_POST["qualification"];
        $deptId = $_POST["deptId"];
        $experience = $_POST["experience"];
        $salary = $_POST["salary"];

        // Insert data into the faculty table
        $stmt = $conn->prepare("INSERT INTO faculty (name, father_name, dob, gender, mobile, email, qualification, dept_id, experience, salary)
                               VALUES (:name, :fatherName, :dob, :gender, :mobile, :email, :qualification, :deptId, :experience, :salary)");
        $stmt->bindParam(':name', $fullName);
        $stmt->bindParam(':fatherName', $fatherName);
        $stmt->bindParam(':dob', $dob);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':mobile', $mobile);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':qualification', $qualification);
        $stmt->bindParam(':deptId', $deptId);
        $stmt->bindParam(':experience', $experience);
        $stmt->bindParam(':salary', $salary);
        $stmt->execute();

        $lastInsertId = $conn->lastInsertId();
        
        // Set success message in session
        $_SESSION['faculty_success_message'] = "Faculty registered successfully with Faculty ID: $lastInsertId";

        // Redirect to prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } catch (PDOException $ex) {
        echo "<div class='alert alert-danger'>Error: " . $ex->getMessage() . "</div>";
    }
}

// Display success message if available
if (isset($_SESSION['faculty_success_message'])) {
    echo "<div class='success-message'>{$_SESSION['faculty_success_message']}</div>";
    unset($_SESSION['faculty_success_message']); // Clear the session variable
}
    
    ?>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form method="post">
                <div class="form-group">
                    <label for="fullName">Full Name:</label>
                    <input type="text" class="form-control" id="fullName" name="fullName" required>
                </div>

                <div class="form-group">
                    <label for="fatherName">Father's Name:</label>
                    <input type="text" class="form-control" id="fatherName" name="fatherName" required>
                </div>

                <div class="form-group">
                    <label for="dob">Date of Birth:</label>
                    <input type="date" class="form-control" id="dob" name="dob" required>
                </div>

                <div class="form-group">
                    <label>Gender:</label>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" id="male" name="gender" value="Male" required>
                        <label class="form-check-label" for="male">Male</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" id="female" name="gender" value="Female" required>
                        <label class="form-check-label" for="female">Female</label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="mobile">Mobile:</label>
                    <input type="tel" class="form-control" id="mobile" name="mobile" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="qualification">Qualification:</label>
                    <input type="text" class="form-control" id="qualification" name="qualification" required>
                </div>

                <div class="form-group">
                    <label for="deptId">Department ID:</label>
                    <select class="form-control" id="deptId" name="deptId" required>
                        <?php
                        $facultyQuery = "SELECT dept_id, name FROM department";
                        $result = $conn->query($facultyQuery);

                        // Populate the options dynamically
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            $deptId = $row["dept_id"];
                            $deptName = $row["name"];
                            echo "<option value='$deptId'>$deptName</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="experience">Experience:</label>
                    <input type="number" class="form-control" id="experience" name="experience" required>
                </div>

                <div class="form-group">
                    <label for="salary">Salary:</label>
                    <input type="number" class="form-control" id="salary" name="salary" required>
                </div>

                <button type="submit" class="btn btn-primary" name = "submit">Submit</button>


            </form>
    <!-- Add this section before the faculty list -->
<div class="row justify-content-center mt-4">
    <div class="col-md-6">
        <form method="post">
            <h2 class="mb-3">Filter Faculty</h2>

            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" class="form-control" id="age" name="age">
            </div>

            <div class="form-group">
                <label for="experience">Experience:</label>
                <input type="number" class="form-control" id="experience" name="experience">
            </div>

            <div class="form-group">
                <label for="qualification">Qualification:</label>
                <input type="text" class="form-control" id="qualification" name="qualification">
            </div>

            <div class="form-group">
                <label for="salary">Salary:</label>
                <input type="number" class="form-control" id="salary" name="salary">
            </div>

            <div class="form-group">
                <label for="deptId">Department ID:</label>
                <select class="form-control" id="deptId" name="deptId" required>
                        <?php
                        // Fetch and display department names from the database
                        $deptQuery = "SELECT dept_id, name FROM department";
                        $deptResult = $conn->query($deptQuery);
                        while ($row = $deptResult->fetch(PDO::FETCH_ASSOC)) {
                            $deptId = $row["dept_id"];
                            $deptName = $row["name"];
                            echo "<option value='$deptId'>$deptName</option>";
                        }
                        ?>
                    </select>
            </div>

            <div class="form-group">
                <label for="gender">Gender:</label>
                <select class="form-control" id="gender" name="gender">
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Others">Others</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" name="filter">Filter</button>
        </form>
    </div>
</div>

            <?php
            if (isset($_POST['filter'])) {
                $filters = [
                    'age' => $_POST['age'],
                    'experience' => $_POST['experience'],
                    'qualification' => $_POST['qualification'],
                    'salary' => $_POST['salary'],
                    'deptId' => $_POST['deptId'],
                    'gender' => $_POST['gender']
                ];
            
                // Remove empty values from filters
                $filters = array_filter($filters);
            
                // Display filtered faculty data
                displayFacultyData($conn, $filters);
            } else {
                // Display all faculty data
                displayFacultyData($conn);
            }
            ?>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
