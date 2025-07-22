<?php
    include 'dbconn.php'; // Include the database connection file

    $products = [];
    $sql = "SELECT Product_ID, Product_Name, Price, Unit_Type, Image_URL FROM Products";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
    }
    mysqli_close($conn); // Close the database connection
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreshFarm Online Market</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>FreshFarm Online Market</h1> 
        <button id="viewOrderBtn">View Order</button> 
    </header>

    <main id="productContainer">
        <?php foreach ($products as $product): ?>
            <div class="product-card"> 
                <img src="<?= htmlspecialchars($product['Image_URL']) ?>" 
                    alt="<?= htmlspecialchars($product['Product_Name']) ?>" 
                    data-product-id="<?= $product['Product_ID'] ?>"> 
                <h3><?= htmlspecialchars($product['Product_Name']) ?></h3> 
                <p><?= number_format($product['Price'], 2) ?> Pesos per <?= htmlspecialchars($product['Unit_Type']) ?></p> 
                <button class="addToCartBtn" data-product-id="<?= $product['Product_ID'] ?>">Add to Cart</button> 
            </div>
        <?php endforeach; ?>
        <?php if (empty($products)): ?>
            <p>No products available at the moment.</p>
        <?php endif; ?>
    </main>

    <div id="orderDetailsModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2>Your Order</h2>
            <div id="selectedItems">
                
            </div>
            <div class="customer-info">
                <label for="customerName">Customer Name:</label>
                <input type="text" id="customerName" pattern="[A-Za-z\s]+" title="Letters and spaces only" required> 
                <label for="contactNumber">Contact Number:</label>
                <input type="text" id="contactNumber" pattern="\d{10}" title="Exactly 10 digits" required maxlength="10"> 
            </div>
            <p>Total Price: <span id="finalTotalPrice">0.00</span> Pesos</p> 
            <button id="confirmOrderBtn">Confirm Order</button> 
        </div>
    </div>

    <div id="imagePopupModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <img id="popupImage" src="" alt="Product Image"> 
        </div>
    </div>

    <script>
        // Pass PHP product data to JavaScript
        const productsData = <?= json_encode($products); ?>;
    </script>
    <script src="script.js"></script>
</body>
</html>