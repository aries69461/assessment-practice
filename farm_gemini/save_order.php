<?php
include 'dbconn.php'; // Include database connection

header('Content-Type: application/json'); // Set header to indicate JSON response

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if ($data === null) {
        $response['message'] = 'Invalid JSON input.';
        echo json_encode($response);
        exit();
    }

    $customerName = mysqli_real_escape_string($conn, $data['customerName']); // Sanitize input
    $contactNumber = mysqli_real_escape_string($conn, $data['contactNumber']);
    $orderDate = mysqli_real_escape_string($conn, $data['orderDate']);
    $items = $data['items'];

    // Start a transaction for atomicity
    mysqli_begin_transaction($conn);

    try {
        // 1. Insert into Orders Table [cite: 46]
        $sql_order = "INSERT INTO Orders (Customer_Name, Contact_Number, Order_Date) VALUES (?, ?, ?)";
        $stmt_order = mysqli_prepare($conn, $sql_order);
        mysqli_stmt_bind_param($stmt_order, "sss", $customerName, $contactNumber, $orderDate);
        if (!mysqli_stmt_execute($stmt_order)) {
            throw new Exception("Error inserting into Orders table: " . mysqli_error($conn));
        }
        $orderId = mysqli_insert_id($conn); // Get the auto-generated Order ID

        // 2. Insert into Order Details Table [cite: 46]
        $sql_order_details = "INSERT INTO Order_Details (Order_ID, Product_ID, Quantity_Ordered) VALUES (?, ?, ?)";
        $stmt_details = mysqli_prepare($conn, $sql_order_details);

        foreach ($items as $item) {
            $productId = mysqli_real_escape_string($conn, $item['productId']);
            $quantityOrdered = mysqli_real_escape_string($conn, $item['quantity']);

            mysqli_stmt_bind_param($stmt_details, "iid", $orderId, $productId, $quantityOrdered); // iid for int, int, double/decimal
            if (!mysqli_stmt_execute($stmt_details)) {
                throw new Exception("Error inserting into Order_Details table: " . mysqli_error($conn));
            }
        }

        mysqli_commit($conn); // Commit the transaction
        $response['success'] = true;
        $response['message'] = 'Order confirmed successfully!';

    } catch (Exception $e) {
        mysqli_rollback($conn); // Rollback on error
        $response['message'] = 'Database error: ' . $e->getMessage();
        $response['error'] = $e->getMessage(); // Include specific error for debugging
    } finally {
        // Close prepared statements
        if (isset($stmt_order)) mysqli_stmt_close($stmt_order);
        if (isset($stmt_details)) mysqli_stmt_close($stmt_details);
        mysqli_close($conn); // Close the database connection
    }

} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
?>