<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Details</title>
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
        <h1 class="registration-title">Hostel Details</h1>
    </div>
</div>

<div class="container">
    <?php
function displayHostelData($conn, $filters = [])
{
    try {
        $hostelViewQuery = "SELECT hostels.hostel_id, hostels.Name AS hostelName, 
                            YEAR(hostels.ESTD) AS ESTD, hostels.capacity, faculty.name AS wardenName
                            FROM hostels
                            LEFT JOIN faculty ON hostels.warden_id = faculty.faculty_id
                            WHERE 1";

        // Apply filters
        if (!empty($filters['estd'])) {
            $hostelViewQuery .= " AND YEAR(hostels.ESTD) = :estd";
        }

        if (!empty($filters['capacity'])) {
            $hostelViewQuery .= " AND hostels.capacity >= :capacity";
        }

        $result = $conn->prepare($hostelViewQuery);

        // Bind parameters
        foreach ($filters as $key => $value) {
            $result->bindValue(":$key", $value);
        }

        $result->execute();
        echo "<div class='success-message'>Hostel filtered successfully</div>";
        echo "<h2 class='mt-4'>Hostel List</h2>";
            echo "<table class='table'>";
            echo "<thead><tr><th>ID</th><th>Name</th><th>ESTD</th><th>Capacity</th><th>Warden Name</th></tr></thead><tbody>";
    
            // Display data in a table
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>{$row['hostel_id']}</td>";
                echo "<td>{$row['hostelName']}</td>";
                echo "<td>{$row['ESTD']}</td>";
                echo "<td>{$row['capacity']}</td>";
                echo "<td>{$row['wardenName']}</td>";
                echo "</tr>";
            }
    
            echo "</tbody></table>";
    } catch (PDOException $ex) {
        echo "<div class='alert alert-danger'>Error: " . $ex->getMessage() . "</div>";
    }
}

session_start();

if (isset($_POST['submit'])) {
    // Retrieve form data
    $hostelName = $_POST["hostelName"];
    $estd = $_POST["estd"];
    $capacity = $_POST["capacity"];
    $wardenId = $_POST["wardenId"];

    // SQL statement to insert data into the hostels table
    $sql = "INSERT INTO hostels (Name, ESTD, capacity, warden_id) 
            VALUES (:hostelName, :estd, :capacity, :wardenId)";

    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':hostelName', $hostelName);
    $stmt->bindParam(':estd', $estd);
    $stmt->bindParam(':capacity', $capacity);
    $stmt->bindParam(':wardenId', $wardenId);

    // Execute the statement
    $stmt->execute();
    $lastInsertId = $conn->lastInsertId();

    // Set success message in session
    $_SESSION['hostel_success_message'] = "Hostel registered successfully with Hostel ID: $lastInsertId";

    // Redirect to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!-- Rest of your HTML code -->

<?php
// Display success message if available
if (isset($_SESSION['hostel_success_message'])) {
    echo "<div class='success-message'>{$_SESSION['hostel_success_message']}</div>";
    unset($_SESSION['hostel_success_message']); // Clear the session variable
}
    ?>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <form method="post">
                <div class="form-group">
                    <label for="hostelName">Hostel Name:</label>
                    <input type="text" class="form-control" id="hostelName" name="hostelName" required>
                </div>

                <div class="form-group">
                    <label for="estd">Establishment Date (ESTD):</label>
                    <input type="date" class="form-control" id="estd" name="estd" required>
                </div>

                <div class="form-group">
                    <label for="capacity">Capacity:</label>
                    <input type="number" class="form-control" id="capacity" name="capacity" required>
                </div>

                <div class="form-group">
                    <label for="wardenId">Warden:</label>
                    <select class="form-control" id="wardenId" name="wardenId" required>
                        <?php
                        $facultyQuery = "SELECT faculty_id, name FROM faculty WHERE faculty_id NOT IN (SELECT warden_id FROM hostels)";
                        $result = $conn->query($facultyQuery);

                        // Populate the options dynamically
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            $facId = $row["faculty_id"];
                            $facName = $row["name"];
                            echo "<option value='$facId'>$facName</option>";
                        }
                        ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            </form>
        </div>
    </div>
</div>
<!-- Your existing HTML code -->

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form method="post">
                <!-- Your existing form for adding a new hostel -->

                <h2 class="mb-3">Filter Hostels</h2>
                <div class="form-group">
                    <label for="estd">ESTD (Year):</label>
                    <select class="form-control" id="estd" name="estd">
                        <option value=''>ALL</option>
                        <?php
                        // Get the current year
                        $currentYear = date("Y");

                        // Generate options for years from 1994 to the current year
                        for ($year = 1994; $year <= $currentYear; $year++) {
                            echo "<option value='$year'>$year</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="capacity">Minimum Capacity:</label>
                    <input type="number" class="form-control" id="capacity" name="capacity">
                </div>

                <button type="submit" class="btn btn-primary" name="filter">Filter</button>
            </form>
        </div>
    </div>
    <?php
    if (isset($_POST['filter'])) {
        $filters = [
            'estd' => $_POST['estd'],
            'capacity' => $_POST['capacity']
        ];

        // Remove empty values from filters
        $filters = array_filter($filters);

        // Display filtered hostel data
        displayHostelData($conn, $filters);
    } else {
        // Display all hostel data
        displayHostelData($conn);
    }
    ?>
    <!-- Your existing script tags -->
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>