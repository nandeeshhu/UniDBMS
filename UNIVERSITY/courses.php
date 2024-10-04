<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Registration</title>
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
        <h1 class="registration-title">Course Registration</h1>
    </div>
</div>

<div class="container">
    <?php
    function displayCourseData($conn, $filters = [])
    {
        try {
            $courseViewQuery = "SELECT * FROM courses WHERE 1";

            // Apply filters
            if (!empty($filters['facultyId'])) {
                $courseViewQuery .= " AND faculty_id = :facultyId";
            }

            if (!empty($filters['deptId'])) {
                $courseViewQuery .= " AND dept_id = :deptId";
            }

            if (!empty($filters['credits'])) {
                $courseViewQuery .= " AND credits = :credits";
            }

            $result = $conn->prepare($courseViewQuery);

            // Bind parameters
            foreach ($filters as $key => $value) {
                $result->bindValue(":$key", $value);
            }

            $result->execute();

            echo "<h2 class='mt-4'>Present Course List</h2>";
            echo "<table class='table'>";
            echo "<thead><tr><th>ID</th><th>Name</th><th>Faculty ID</th><th>Department ID</th><th>Credits</th><th>Max Limit</th></tr></thead><tbody>";

            // Display data in a table
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>{$row['Course_id']}</td>";
                echo "<td>{$row['Name']}</td>";
                echo "<td>{$row['faculty_id']}</td>";
                echo "<td>{$row['dept_id']}</td>";
                echo "<td>{$row['credits']}</td>";

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
        $courseName = $_POST["courseName"];
        $facultyId = $_POST["facultyId"];
        $deptId = $_POST["deptId"];
        $credits = $_POST["credits"];
        $maxLimit = $_POST["maxLimit"];

        // Insert data into the courses table
        $stmt = $conn->prepare("INSERT INTO courses (name, faculty_id, dept_id, credits)
                               VALUES (:courseName, :facultyId, :deptId, :credits)");
        $stmt->bindParam(':courseName', $courseName);
        $stmt->bindParam(':facultyId', $facultyId);
        $stmt->bindParam(':deptId', $deptId);
        $stmt->bindParam(':credits', $credits);
        $stmt->execute();

        $lastInsertId = $conn->lastInsertId();

        // Set success message in session
        $_SESSION['course_success_message'] = "Course registered successfully with Course ID: $lastInsertId";

        // Redirect to prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } catch (PDOException $ex) {
        echo "<div class='alert alert-danger'>Error: " . $ex->getMessage() . "</div>";
    }
}

// Display success message if available
if (isset($_SESSION['course_success_message'])) {
    echo "<div class='success-message'>{$_SESSION['course_success_message']}</div>";
    unset($_SESSION['course_success_message']); // Clear the session variable
}
    ?>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form method="post">
                <div class="form-group">
                    <label for="courseName">Course Name:</label>
                    <input type="text" class="form-control" id="courseName" name="courseName" required>
                </div>

                <div class="form-group">
                    <label for="facultyId">Faculty ID:</label>
                    <select class="form-control" id="facultyId" name="facultyId" required>
                        <?php
                        // Fetch and display faculty names from the database
                        $facultyQuery = "SELECT faculty_id, name FROM faculty";
                        $facultyResult = $conn->query($facultyQuery);
                        while ($row = $facultyResult->fetch(PDO::FETCH_ASSOC)) {
                            $facultyId = $row["faculty_id"];
                            $facultyName = $row["name"];
                            echo "<option value='$facultyId'>$facultyName</option>";
                        }
                        ?>
                    </select>
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
                    <label for="credits">Credits:</label>
                    <input type="number" class="form-control" id="credits" name="credits" required>
                </div>


                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            </form>
        </div>
    </div>

    <!-- Add this section before the course list -->
    <div class="row justify-content-center mt-4">
        <div class="col-md-6">
            <form method="post">
                <h2 class="mb-3">Filter Courses</h2>

                <div class="form-group">
                    <label for="facultyIdFilter">Faculty ID:</label>
                    <input type="text" class="form-control" id="facultyIdFilter" name="facultyId">
                </div>

                <div class="form-group">
                    <label for="deptIdFilter">Department ID:</label>
                    <input type="text" class="form-control" id="deptIdFilter" name="deptId">
                </div>

                <div class="form-group">
                    <label for="creditsFilter">Credits:</label>
                    <input type="number" class="form-control" id="creditsFilter" name="credits">
                </div>

                <button type="submit" class="btn btn-primary" name="filter">Filter</button>
            </form>
        </div>
    </div>

    <?php
    if (isset($_POST['filter'])) {
        $filters = [
            'facultyId' => $_POST['facultyId'],
            'deptId' => $_POST['deptId'],
            'credits' => $_POST['credits'],
        ];

        // Remove empty values from filters
        $filters = array_filter($filters);

        // Display filtered course data
        displayCourseData($conn, $filters);
    } else {
        // Display all course data
        displayCourseData($conn);
    }
    ?>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
