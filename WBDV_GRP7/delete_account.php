<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo "User ID not found in session.";
    exit();
}

$user_id = $_SESSION['user_id'];

// Delete related order items
if ($stmt = $conn->prepare("DELETE FROM order_items WHERE order_id IN (SELECT order_id FROM orders WHERE user_id = ?)")) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
} else {
    echo "Error preparing statement for deleting order items: " . $conn->error;
    exit();
}

// Delete related orders
if ($stmt = $conn->prepare("DELETE FROM orders WHERE user_id = ?")) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
} else {
    echo "Error preparing statement for deleting orders: " . $conn->error;
    exit();
}

// Delete the user record
if ($stmt = $conn->prepare("DELETE FROM users WHERE id = ?")) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    if ($stmt->error) {
        echo "Error: " . $stmt->error; // Display specific error
    }

    if ($stmt->affected_rows > 0) {
        session_destroy();
        header("Location: homepage.php");
        exit();
    } else {
        echo "No rows deleted. ID may not exist.";
    }

    $stmt->close();
} else {
    echo "Error preparing statement for deleting user: " . $conn->error;
}

$conn->close();
?>
