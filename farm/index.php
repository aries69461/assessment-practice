<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreshFarm Online Market</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>FreshFarm Online Market</h1>
            <button id="viewOrderBtn">View Order</button>
        </header>
    </div>
    <section class="product-container">
        <div class="product-list"></div>
    </section>

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

    <button class="add-products">Add Products</button>

    <div class="modal" id="modal">
        <div class="modal-content">
            <button class="close-modal">×</button>

            <br/>
            <br/>

            <form id="addproductform" method="post" enctype="multipart/form-data" action="../php/addProducts.php">
                <input type="text" name="product-name" id="product-name" placeholder="Enter Product Name" required>
                <input type="number" name="price" id="price" placeholder="Enter Price" step="0.01" required>
                
                <select name="unit" id="unit">
                    <option value="kg">Kg</option>
                    <option value="pack">Pack</option>
                </select>

                <input type="file" name="image" id="image" accept="image/*" required>

                <img id="preview" style="max-width: 100px; display: none; margin-top: 10px; border: 1px solid green;">
                <br/>
                <br/>
                <button type="submit" id="add-product-btn">Add Product</button>
            </form>
        </div>
    </div>
    <div class="product-grid" id="product-list"></div>
    
    <script src="products.js"></script>
    <script src="script.js"></script>
</body>
</html>