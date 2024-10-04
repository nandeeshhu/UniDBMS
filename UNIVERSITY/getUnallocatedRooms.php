<?php
// File: getUnallocatedRooms.php

$host = 'localhost';
$dbname = 'university';
$user = 'nandeesh.u';
$pass = 'abc123';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the selected hostel ID from the URL parameters
    $hostelId = isset($_GET['hostelId']) ? $_GET['hostelId'] : null;
    //$hostelId = 1;
    if ($hostelId === null) {
        echo json_encode(['error' => 'Hostel ID not provided']);
        exit;
    }

    // Query to get the capacity and allocated rooms for the selected hostel
    $capacityQuery = "SELECT capacity FROM hostels WHERE hostel_id = :hostelId";
    $capacityResult = $conn->prepare($capacityQuery);
    $capacityResult->bindParam(':hostelId', $hostelId);
    $capacityResult->execute();

    $capacity = $capacityResult->fetchColumn();

    // Query to get the allocated rooms for the selected hostel
    $allocatedRoomsQuery = "SELECT RoomNo FROM student WHERE HostelID = :hostelId";
    $allocatedRoomsResult = $conn->prepare($allocatedRoomsQuery);
    $allocatedRoomsResult->bindParam(':hostelId', $hostelId);
    $allocatedRoomsResult->execute();

    $allocatedRooms = $allocatedRoomsResult->fetchAll(PDO::FETCH_COLUMN);

    // Return the response as JSON
    $response = [
        'capacity' => $capacity,
        'allocatedRooms' => $allocatedRooms,
    ];
    echo json_encode($response);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error: ' . $e->getMessage()]);
}
?>

