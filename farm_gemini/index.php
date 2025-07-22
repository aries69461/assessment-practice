<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreshFarm Market</title>
    <style>
        body {
            font-family: 'Inter', sans-serif; 
            background-color: #f7f7f7;
            color: #333;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        .header {
            background-color: #22c55e; 
            color: white;
            padding: 20px;
            text-align: center;
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
        }

        .header h1 {
            font-size: 2.5rem; 
            font-weight: 700; 
            margin: 0;
        }
        .header button {
            background-color: #facc15; 
            color: #333;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-left: auto; 
        }
        .header button:hover {
            background-color: #eab308; 
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 15px;
        }

        .product-grid {
            display: grid;
            gap: 20px;
            margin-top: 32px; 
            /* Default: 1 column for mobile */
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        @media (min-width: 768px) {
            /* Medium screens (tablets): 2 columns */
            .product-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (min-width: 1024px) {
            /* Large screens (desktops): 3 columns */
            .product-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        .product-card {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            text-align: center;
        }

        .product-card img {
            width: 100%;
            max-width: 200px; 
            height: auto;
            border-radius: 8px; 
            margin-bottom: 15px;
            cursor: pointer;
        }

        .product-card h3 {
            font-size: 1.5rem; 
            font-weight: 600; 
            margin-bottom: 10px;
            color: #22c55e; 
        }

        .product-card p {
            font-size: 1.125rem; 
            color: #555;
            margin-bottom: 15px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin: 5px;
            border: none;
            background-color: #22c55e;
            color: white;
        }
        .btn:hover {
            background-color: #16a34a;
        }

        .btn-secondary {
            background-color: #facc15; 
            color: #333;
        }

        .btn-secondary:hover {
            background-color: #eab308; 
        }

        /* Modal specific styles (for both order and image) */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1000; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0,0,0,0.6); /* Black w/ opacity */
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            width: 90%;
            max-width: 600px;
            position: relative;
        }

        .close-button {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            top: 15px;
            right: 25px;
            cursor: pointer;
        }

        .close-button:hover,
        .close-button:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .cart-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .cart-item img {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            margin-right: 15px;
        }

        .cart-item-details {
            flex-grow: 1;
        }

        .cart-item-details input {
            width: 80px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 6px;
            margin-left: 10px;
            text-align: center;
        }

        .customer-info label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .customer-info input {
            width: calc(100% - 20px); 
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            margin-bottom: 10px;
            box-sizing: border-box; 
        }

        .total-price {
            font-size: 1.3rem;
            font-weight: 600;
            text-align: right;
            margin-top: 20px;
            color: #22c55e;
        }

        .modal-title {
            font-size: 2rem; 
            font-weight: 700; 
            margin-bottom: 16px; 
            color: #16a34a;
        }

        .confirm-button {
            width: 100%; 
            margin-top: 24px; 
        }

        /* Message box styles */
        .message-box {
            position: fixed;
            bottom: 16px; 
            right: 16px; 
            background-color: #333; 
            color: white;
            padding: 16px; 
            border-radius: 8px; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); 
            z-index: 9999; 
        }

        /* Image Popup Specific Styles */
        #imagePopup .modal-content {
            max-width: 800px; /* Larger max-width for images */
            text-align: center;
            padding: 20px; /* Adjust padding for image popup */
        }

        #imagePopup img {
            max-width: 100%;
            height: auto;
            display: block; /* Remove extra space below image */
            margin: 0 auto; /* Center image */
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>FreshFarm Online Market</h1>
        <button class="btn btn-secondary" style="margin-top: 8px;" onclick="openOrderModal()">View Order</button>
    </header>

    <main class="container">
        <section id="product-list" class="product-grid">
            <!-- Product cards will be loaded here by JavaScript -->
        </section>
    </main>

    <!-- Order Modal -->
    <div id="orderModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeOrderModal()">&times;</span>
            <h2 class="modal-title">Your Order</h2>
            <div id="cart-items-display">
                <!-- Cart items will be displayed here -->
            </div>
            <div class="total-price">
                Total: <span id="modal-total-price">₱0.00</span>
            </div>
            <div class="customer-info">
                <label for="customerName">Customer Name:</label>
                <input type="text" id="customerName" placeholder="Enter your name (letters and spaces only)">
                <label for="contactNumber">Contact Number:</label>
                <input type="text" id="contactNumber" maxlength="10" placeholder="Enter 10-digit contact number">
            </div>
            <button class="btn confirm-button" onclick="confirmOrder()">Confirm Order</button>
        </div>
    </div>

    <!-- Image Popup Modal -->
    <div id="imagePopup" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeImagePopup()">&times;</span>
            <img id="largeProductImage" src="" alt="Large Product Image">
        </div>
    </div>

    <script>
        // Sample Product Data (simulating database)
        const products = [
            { id: 1, name: 'Fresh Apples', price: 120, unit: 'Kilo', imageUrl: 'https://tinyurl.com/upsmnaka' },
            { id: 2, name: 'Organic Bananas', price: 80, unit: 'Kilo', imageUrl: 'https://tinyurl.com/5atu66am' },
            { id: 3, name: 'Farm Eggs', price: 15, unit: 'Pack', imageUrl: 'https://tinyurl.com/5etrawt4' },
            { id: 4, name: 'Sweet Potatoes', price: 65, unit: 'Kilo', imageUrl: 'https://tinyurl.com/5n7aeem7' },
            { id: 5, name: 'Garden Tomatoes', price: 90, unit: 'Kilo', imageUrl: 'https://tinyurl.com/39es284p' },
            { id: 6, name: 'Fresh Lettuce', price: 50, unit: 'Pack', imageUrl: 'https://tinyurl.com/27e3pxkm' }
        ];

        // Cart to store selected items
        let cart = [];

        // Function to render product cards
        function renderProducts() {
            const productListDiv = document.getElementById('product-list');
            productListDiv.innerHTML = ''; // Clear existing products
            products.forEach(product => {
                const productCard = document.createElement('div');
                productCard.className = 'product-card';
                productCard.innerHTML = `
                    <img src="${product.imageUrl}" alt="${product.name}">
                    <h3>${product.name}</h3>
                    <p>₱${product.price.toFixed(2)} per ${product.unit}</p>
                    <button class="btn" onclick="addToCart(${product.id})">Add to Cart</button>
                    
                `;
                productListDiv.appendChild(productCard);
            });
        }

        // Function to add item to cart
        function addToCart(productId) {
            const product = products.find(p => p.id === productId);
            if (product) {
                // Check if item already in cart
                const existingItem = cart.find(item => item.id === productId);
                if (existingItem) {
                    existingItem.quantity += (product.unit === 'Kilo' ? 0.5 : 1); // Increment by 0.5 Kilo or 1 Pack
                } else {
                    cart.push({ ...product, quantity: (product.unit === 'Kilo' ? 0.5 : 1) });
                }
                // alert(`Added ${product.name} to cart!`);
                showMessage('Item added to cart!');
                updateCartDisplay(); // Update display for current cart status
            }
        }

        // Function to open the order modal
        function openOrderModal() {
            const orderModal = document.getElementById('orderModal');
            orderModal.style.display = 'flex'; // Use flex to center content
            updateCartDisplay();
        }

        // Function to close the order modal
        function closeOrderModal() {
            const orderModal = document.getElementById('orderModal');
            orderModal.style.display = 'none';
        }

        // Function to open the image popup
        function openImagePopup(imageUrl) {
            const imagePopup = document.getElementById('imagePopup');
            const largeProductImage = document.getElementById('largeProductImage');
            largeProductImage.src = imageUrl;
            imagePopup.style.display = 'flex'; // Use flex to center content
        }

        // Function to close the image popup
        function closeImagePopup() {
            const imagePopup = document.getElementById('imagePopup');
            imagePopup.style.display = 'none';
        }

        // Function to update cart display in the modal
        function updateCartDisplay() {
            const cartItemsDisplay = document.getElementById('cart-items-display');
            cartItemsDisplay.innerHTML = ''; // Clear previous items
            let totalOrderPrice = 0;

            if (cart.length === 0) {
                cartItemsDisplay.innerHTML = '<p style="text-align: center; color: #888;">Your cart is empty.</p>';
            } else {
                cart.forEach(item => {
                    const itemTotal = item.price * item.quantity;
                    totalOrderPrice += itemTotal;

                    const cartItemDiv = document.createElement('div');
                    cartItemDiv.className = 'cart-item';
                    cartItemDiv.innerHTML = `
                        <img src="${item.imageUrl}" alt="${item.name}">
                        <div class="cart-item-details">
                            <h4 style="font-weight: 600; margin-bottom: 5px;">${item.name}</h4>
                            <p style="margin-bottom: 5px;">₱${item.price.toFixed(2)} per ${item.unit}</p>
                            <label for="qty-${item.id}">Quantity:</label>
                            <input type="${item.unit === 'Kilo' ? 'number' : 'number'}"
                                   step="${item.unit === 'Kilo' ? '0.1' : '1'}"
                                   min="0.1"
                                   id="qty-${item.id}"
                                   value="${item.quantity}"
                                   onchange="updateQuantity(${item.id}, this.value)">
                            <p style="text-align: right; font-weight: 500;">Subtotal: ₱${itemTotal.toFixed(2)}</p>
                        </div>
                    `;
                    cartItemsDisplay.appendChild(cartItemDiv);
                });
            }
            document.getElementById('modal-total-price').textContent = `₱${totalOrderPrice.toFixed(2)}`;
        }

        // Function to update quantity from modal input
        function updateQuantity(productId, newQuantity) {
            const item = cart.find(i => i.id === productId);
            if (item) {
                const parsedQuantity = parseFloat(newQuantity);
                if (!isNaN(parsedQuantity) && parsedQuantity > 0) {
                    // For 'Pack' units, ensure quantity is an integer
                    if (item.unit === 'Pack') {
                        item.quantity = Math.floor(parsedQuantity);
                        // Update input field to reflect integer value if it was decimal
                        document.getElementById(`qty-${productId}`).value = item.quantity;
                    } else { // For 'Kilo' units, allow decimals
                        item.quantity = parsedQuantity;
                    }
                } else {
                    // If invalid quantity, revert to previous or default to 1
                    item.quantity = (item.unit === 'Kilo' ? 0.5 : 1);
                    document.getElementById(`qty-${productId}`).value = item.quantity;
                    showMessage('Invalid quantity. Must be a positive number.');
                    // alert('Invalid quantity. Must be a positive number.');
                    return;
                }
                updateCartDisplay(); // Recalculate and update total
            }
        }

        // Function to confirm order (simplified)
        function confirmOrder() {
            const customerNameInput = document.getElementById('customerName');
            const contactNumberInput = document.getElementById('contactNumber');

            const customerName = customerNameInput.value.trim();
            const contactNumber = contactNumberInput.value.trim();

            // Input Validation
            if (!customerName.match(/^[a-zA-Z\s]+$/)) {
                showMessage('Please enter a valid customer name (letters and spaces only).');
                // alert('Please enter a valid customer name (letters and spaces only).');
                return;
            }
            if (!contactNumber.match(/^\d{10}$/)) {
                showMessage('Please enter a valid 10-digit contact number.');
                // alert('Please enter a valid 10-digit contact number.');
                return;
            }
            if (cart.length === 0) {
                showMessage('Your cart is empty. Please add items before confirming.');
                // alert('Your cart is empty. Please add items before confirming.');
                return;
            }

            // Simulate saving order to database (in a real app, this would be an API call)
            console.log('Order Confirmed:', {
                customerName: customerName,
                contactNumber: contactNumber,
                items: cart,
                total: document.getElementById('modal-total-price').textContent
            });

            // alert('Order Confirmed! Redirecting to main page...');
            showMessage('Order Confirmed! Redirecting to main page...');
            // Simulate redirecting back to the main page
            setTimeout(() => {
                cart = []; // Clear cart after successful order
                customerNameInput.value = ''; // Clear form
                contactNumberInput.value = '';
                closeOrderModal();
                updateCartDisplay(); // Ensure cart display is cleared
                // In a real app, you might refresh the page or navigate away
            }, 2000);
        }

        // Simple message box (instead of alert)
        function showMessage(message) {
            const messageBox = document.createElement('div');
            messageBox.className = 'message-box';
            messageBox.textContent = message;
            document.body.appendChild(messageBox);
            setTimeout(() => {
                messageBox.remove();
            }, 3000); // Message disappears after 3 seconds
        }

        // Initialize on page load
        window.onload = renderProducts;
    </script>
</body>
</html>
