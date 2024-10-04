<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fees Details</title>
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
        <h1 class="registration-title">Fees Details</h1>
    </div>
</div>

<div class="container">
    <?php
    function displayFeesDetails($conn, $filters = [])
    {
        try {
            $feesViewQuery = "SELECT * FROM fees_details WHERE 1";

            // Apply filters
            if (!empty($filters['studentId'])) {
                $feesViewQuery .= " AND student_id = :studentId";
            }

            if (!empty($filters['feesType'])) {
                $feesViewQuery .= " AND fees_type = :feesType";
            }

            if (!empty($filters['feePaid'])) {
                $feesViewQuery .= " AND fee_paid = :feePaid";
            }

            if (!empty($filters['transactionId'])) {
                $feesViewQuery .= " AND transaction_id = :transactionId";
            }

            $result = $conn->prepare($feesViewQuery);

            // Bind parameters
            foreach ($filters as $key => $value) {
                $result->bindValue(":$key", $value);
            }

            $result->execute();

            echo "<h2 class='mt-4'>Fees Details List</h2>";
            echo "<table class='table'>";
            echo "<thead><tr><th>Student ID</th><th>Fees Type</th><th>Fee Paid</th><th>Transaction ID</th></tr></thead><tbody>";

            // Display data in a table
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>{$row['Student_id']}</td>";
                echo "<td>{$row['fees_type']}</td>";
                echo "<td>{$row['fee_paid']}</td>";
                echo "<td>{$row['transaction_id']}</td>";
                echo "</tr>";
            }

            echo "</tbody></table>";
        } catch (PDOException $ex) {
            echo "<div class='alert alert-danger'>Error: " . $ex->getMessage() . "</div>";
        }
    }

    session_start();
    function getTransactionId($conn)
{
    $stmt = $conn->prepare("SELECT MAX(transaction_id) FROM fees_details");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row['MAX(transaction_id)'];
}

    

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['submit'])) {
            try {
                // Retrieve user inputs
                $studentId = $_POST["studentId"];
                $feesType = $_POST["feesType"];
                $feePaid = $_POST["feePaid"];

                // Insert data into the fees_details table
                $stmt = $conn->prepare("INSERT INTO fees_details (student_id, fees_type, fee_paid)
                                       VALUES (:studentId, :feesType, :feePaid)");
                $stmt->bindParam(':studentId', $studentId);
                $stmt->bindParam(':feesType', $feesType);
                $stmt->bindParam(':feePaid', $feePaid);
                $stmt->execute();

                $lastInsertId = $conn->lastInsertId();
                $transactionId = getTransactionId($conn, $lastInsertId);

                // Set success message in session
                $_SESSION['fees_success_message'] = "Fees details registered successfully with Transaction ID: $transactionId";

                // Redirect to prevent form resubmission
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } catch (PDOException $ex) {
                echo "<div class='alert alert-danger'>Error: " . $ex->getMessage() . "</div>";
            }
        }
    }

    // Display success message if available
    if (isset($_SESSION['fees_success_message'])) {
        echo "<div class='success-message'>{$_SESSION['fees_success_message']}</div>";
        unset($_SESSION['fees_success_message']); // Clear the session variable
    }
    ?>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <form method="post">
                <div class="form-group">
                    <label for="studentId">Student:</label>
                    <select class="form-control" id="studentId" name="studentId" required>
                        <?php
                        // Fetch and display faculty names from the database
                        $studentQuery = "SELECT studentid, name FROM student";
                        $studentResult = $conn->query($studentQuery);
                        while ($row = $studentResult->fetch(PDO::FETCH_ASSOC)) {
                            $studentId = $row["studentid"];
                            $studentName = $row["name"];
                            echo "<option value='$studentId'>$studentId $studentName</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                <label for="feesType">Fees Type:</label>
                <select class="form-control" id="feesType" name="feesType" required>
                    <option value="Tution Fees">Tution Fees</option>
                    <option value="Hostel Fees">Hostel Fees</option>
                    <option value="Mess Fees">Mess Fees</option>
                    <option value="Placement Training">Placement Training</option>
                    <option value="Others">Others</option>
                </select>
                </div>

                <div class="form-group">
                    <label for="feePaid">Fee Paid:</label>
                    <input type="number" class="form-control" id="feePaid" name="feePaid" required>
                </div>

                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            </form>
        </div>
    </div>

    <!-- Add this section before the fees details list -->
    <div class="row justify-content-center mt-4">
        <div class="col-md-6">
            <form method="post">
                <h2 class="mb-3">Filter Fees Details</h2>

                <div class="form-group">
                    <label for="studentIdFilter">Student ID:</label>
                    <select class="form-control" id="studentId" name="studentId">
                        <?php
                        // Fetch and display faculty names from the database
                        $studentQuery = "SELECT studentid, name FROM student";
                        $studentResult = $conn->query($studentQuery);
                        while ($row = $studentResult->fetch(PDO::FETCH_ASSOC)) {
                            $studentId = $row["studentid"];
                            $studentName = $row["name"];
                            echo "<option value='$studentId'>$studentId $studentName</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                <label for="feesType">Fees Type:</label>
                <select class="form-control" id="feesType" name="feesType" required>
                    <option value="Tution Fees">Tution Fees</option>
                    <option value="Hostel Fees">Hostel Fees</option>
                    <option value="Mess Fees">Mess Fees</option>
                    <option value="Placement Training">Placement Training</option>
                    <option value="Others">Others</option>
                </select>
                </div>

                <div class="form-group">
                    <label for="feePaidFilter">Fee Paid:</label>
                    <input type="number" class="form-control" id="feePaidFilter" name="feePaid">
                </div>

                <div class="form-group">
                    <label for="transactionIdFilter">Transaction ID:</label>
                    <input type="text" class="form-control" id="transactionIdFilter" name="transactionId">
                </div>

                <button type="submit" class="btn btn-primary" name="filter">Filter</button>
            </form>
        </div>
    </div>

    <?php
if (isset($_POST['filter'])) {
    $filters = [
        'studentId' => isset($_POST['studentId']) ? $_POST['studentId'] : null,
        'feesType' => isset($_POST['feesType']) ? $_POST['feesType'] : null,
        'feePaid' => isset($_POST['feePaidFilter']) ? $_POST['feePaidFilter'] : null,
        'transactionId' => isset($_POST['transactionIdFilter']) ? $_POST['transactionIdFilter'] : null,
    ];

    // Remove empty values from filters
    $filters = array_filter($filters);

    // Display filtered fees details data
    displayFeesDetails($conn, $filters);
} else {
    // Display all fees details data
    displayFeesDetails($conn);
}
?>

</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
