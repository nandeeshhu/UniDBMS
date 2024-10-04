<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
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
        <h1 class="registration-title">Student Registration</h1>
    </div>
</div>

<div class="container">
    <?php
    function displayStudentData($conn, $filters = [])
    {
        try {
            $studentViewQuery = "SELECT studentId, name, dob, gender, mobile, email, department.name as department_name, hostels.name as hostel_name, roomNo FROM student, department, hostels WHERE student.hostelID=hostels.hostel_id AND student.departmentID=department.dept_id";

            // Apply filters
            if (!empty($filters['age'])) {
                $studentViewQuery .= " AND YEAR(CURDATE()) - YEAR(dob) = :age";
            }

            if (!empty($filters['gender'])) {
                $studentViewQuery .= " AND gender = :gender";
            }

            if (!empty($filters['department'])) {
                $studentViewQuery .= " AND department.dept_id = :departmentId";
            }

            $result = $conn->prepare($studentViewQuery);

            // Bind parameters
            foreach ($filters as $key => $value) {
                $result->bindValue(":$key", $value);
            }

            $result->execute();

            echo "<h2 class='mt-4'>Present Student List</h2>";
            echo "<table class='table'>";
            echo "<thead><tr><th>ID</th><th>Name</th><th>DOB</th><th>Gender</th><th>Mobile</th><th>Email</th><th>Department</th><th>Hostel Name</th><th>Room No</th></tr></thead><tbody>";

            // Display data in a table
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>{$row['studentId']}</td>";
                echo "<td>{$row['name']}</td>";
                echo "<td>{$row['dob']}</td>";
                echo "<td>{$row['gender']}</td>";
                echo "<td>{$row['mobile']}</td>";
                echo "<td>{$row['email']}</td>";
                echo "<td>{$row['department_name']}</td>";
                echo "<td>{$row['hostel_name']}</td>";
                echo "<td>{$row['roomNo']}</td>";
                echo "</tr>";
            }

            echo "</tbody></table>";
        } catch (PDOException $ex) {
            echo "<div class='alert alert-danger'>Error: " . $ex->getMessage() . "</div>";
        }
    }

    // ... (your existing code)

    session_start(); // Start the session

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
        try {
            // Retrieve user inputs
            $fullName = $_POST["fullName"];
            $dob = $_POST["dob"];
            $gender = $_POST["gender"];
            $mobile = $_POST["mobile"];
            $email = $_POST["email"];
            $hostelId = $_POST["hostelId"];
            $roomNo = $_POST["roomNo"];
            $departmentId = $_POST["departmentId"]; // Add this line

            // Insert data into the student table
            $stmt = $conn->prepare("INSERT INTO student (name, dob, gender, mobile, email, hostelId, roomNo, departmentID)
                                   VALUES (:name, :dob, :gender, :mobile, :email, :hostelId, :roomNo, :departmentId)");
            $stmt->bindParam(':name', $fullName);
            $stmt->bindParam(':dob', $dob);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':mobile', $mobile);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':hostelId', $hostelId);
            $stmt->bindParam(':roomNo', $roomNo);
            $stmt->bindParam(':departmentId', $departmentId); // Add this line
            $stmt->execute();

            $lastInsertId = $conn->lastInsertId();

            // Set success message in session
            $_SESSION['success_message'] = "Student registered successfully with Student ID: $lastInsertId";

            // Redirect to prevent form resubmission
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } catch (PDOException $ex) {
            echo "<div class='alert alert-danger'>Error: " . $ex->getMessage() . "</div>";
        }
    }

    // Display success message if available
    if (isset($_SESSION['success_message'])) {
        echo "<div class='success-message'>{$_SESSION['success_message']}</div>";
        unset($_SESSION['success_message']); // Clear the session variable
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
                    <div class="form-check">
                        <input type="radio" class="form-check-input" id="others" name="gender" value="Others" required>
                        <label class="form-check-label" for="others">Others</label>
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
                    <label for="departmentId">Select Department:</label>
                    <select class="form-control" id="departmentId" name="departmentId" required>
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
                    <label for="hostel_Id">Hostel:</label>
                    <select class="form-control" id="hostel_Id" name="hostelId" required onchange="updateRoomOptions()">
                    <option value="" disabled selected>Select Hostel</option>
                        <?php
                        // Query to retrieve available hostels
                        $hostelQuery = "SELECT hostel_id, name FROM hostels";
                        $hostelResult = $conn->query($hostelQuery);

                        // Populate the options dynamically
                        while ($row = $hostelResult->fetch(PDO::FETCH_ASSOC)) {
                            $hostelId = $row["hostel_id"];
                            $hostelName = $row["name"];
                            echo "<option value='$hostelId'>$hostelName</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="roomNo">Room No:</label>
                    <select class="form-control" id="roomNo" name="roomNo" required>
                        <!-- Options will be dynamically populated using JavaScript -->
                    </select>
                    <small id="roomNoHelp" class="form-text text-muted">Please choose an unassigned room within the hostel's capacity.</small>
                </div>

                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            </form>
        </div>
    </div>
</div>

<script>
function updateRoomOptions() {
    var hostelDropdown = document.getElementById('hostel_Id');
    var roomDropdown = document.getElementById('roomNo');

    // Get the selected hostel
    var selectedHostel = hostelDropdown.value;
    console.log(selectedHostel);

    // Clear existing options
    roomDropdown.innerHTML = '';

    // Send an AJAX request to get unallocated room numbers based on the selected hostel
    var xhr = new XMLHttpRequest();

    xhr.open('GET', 'getUnallocatedRooms.php?hostelId=' + selectedHostel, true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                try {
                    var roomOptions = JSON.parse(xhr.responseText);
                    console.log("Script reached 8");

                    // Add options to the room dropdown
                    for (var i = 1; i <= roomOptions.capacity; i++) {
                        if (!roomOptions.allocatedRooms.includes(i)) {
                            var option = document.createElement('option');
                            option.value = i;
                            option.text = i;
                            roomDropdown.add(option);
                        }
                    }
                } catch (e) {
                    console.error('Error parsing JSON response:', e);
                }
            } else {
                console.error('Request failed with status:', xhr.status);
            }
        }
    };

    xhr.send();
}


</script>


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
