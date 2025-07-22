<?php

require '../conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productName = $_POST['product-name'];
    $price = $_POST['price']; 
    $unit = $_POST['unit'];

   
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmp = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);  
        $uploadDir = '../assets/images/';
        $targetPath = $uploadDir . $imageName;

        if (move_uploaded_file($imageTmp, $targetPath)) {
            $sql = "INSERT INTO products (Product_Name, Price, Unit_Type, Image_URL) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sdss", $productName, $price, $unit, $imageName);

            if ($stmt->execute()) {
                echo "<script>alert('Product added successfully!');</script>";
            } else {
                echo "<script>alert('Database Error: " . $stmt->error . "');</script>";
            }

            $stmt->close();
        } else {
            echo "Failed to upload image.";
        }
    } else {
        echo "Image upload error: " . $_FILES['image']['error'];
    }

    $conn->close();
} else {
    echo "Invalid request.";
}
