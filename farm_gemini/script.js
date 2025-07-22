const products = [
    // Example product data (this would come from your 'Products Table' database)
    { id: 1, name: 'Fresh Cabbage', price: 75.00, unit: 'Kilo', imageUrl: 'https://i.pinimg.com/474x/b1/2a/53/b12a532fa575f03b3be647bdf5ae0192.jpg' },
    { id: 2, name: 'Organic Carrots', price: 50.00, unit: 'Pack', imageUrl: 'https://i.pinimg.com/474x/85/a0/0c/85a00cb72ed70fce4b6b0ed7918e6d84.jpg' },
    { id: 3, name: 'Sweet Potatoes', price: 60.00, unit: 'Kilo', imageUrl: 'https://i.pinimg.com/736x/be/69/b9/be69b93b498568cfb42c603f5975eec5.jpg' },
    { id: 4, name: 'Red Apples', price: 120.00, unit: 'Kilo', imageUrl: 'https://i.pinimg.com/736x/67/7f/3f/677f3fab2c94f65b042fa50f1600a527.jpg' },
    { id: 5, name: 'Green Beans', price: 40.00, unit: 'Pack', imageUrl: 'https://i.pinimg.com/736x/8f/d2/31/8fd231719f5fa3975060575bb68a2bba.jpg' }
];

let cart = [];

document.addEventListener('DOMContentLoaded', () => {
    // Products are already loaded into productsData by PHP
    // We just need to ensure event listeners are set up for dynamically loaded elements (not strictly needed now)
    setupEventListeners(); // This will attach listeners to the pre-rendered HTML
    setupModalListeners();
});

function setupEventListeners() {
    // Add event listeners for "Add to Cart" buttons
    document.querySelectorAll('.addToCartBtn').forEach(button => {
        button.addEventListener('click', (event) => {
            const productId = parseInt(event.target.dataset.productId);
            addToCart(productId);
        });
    });

    // Add event listeners for product images (to open large image popup)
    document.querySelectorAll('.product-card img').forEach(img => {
        img.addEventListener('click', (event) => {
            const productId = parseInt(event.target.dataset.productId);
            const product = productsData.find(p => p.Product_ID === productId); // Correctly uses productsData
            if (product) {
                openImagePopup(product.Image_URL);
            }
        });
    });
}

function addToCart(productId) {
    const product = productsData.find(p => p.Product_ID === productId) // Correctly uses productsData
    if (product) {
        const existingItem = cart.find(item => item.Product_ID === productId);
        if (existingItem) {
            existingItem.quantity += (product.Unit_Type === 'Kilo' ? 0.5 : 1); // Increment by 0.5 for Kilo, 1 for Pack 
        } else {
            cart.push({ ...product, quantity: (product.Unit_Type === 'Kilo' ? 0.5 : 1) });
        }
        updateCartDisplay();
        alert(`${product.Product_Name} added to cart!`);
    }
}

function updateCartDisplay() {
    const selectedItemsDiv = document.getElementById('selectedItems');
    selectedItemsDiv.innerHTML = '';
    let totalOrderPrice = 0;

    if (cart.length === 0) {
        selectedItemsDiv.innerHTML = '<p>Your cart is empty.</p>';
        document.getElementById('finalTotalPrice').textContent = '0.00';
        return;
    }

    cart.forEach(item => {
        const itemTotal = item.Price * item.quantity;
        totalOrderPrice += itemTotal;

        const itemDiv = document.createElement('div');
        itemDiv.classList.add('cart-item');
        itemDiv.innerHTML = `
            [cite_start]<img src="${item.Image_URL}" alt="${item.Product_Name}"> [cite: 38]
            <span>${item.Product_Name}</span>
            [cite_start]<input type="number" value="${item.quantity}" min="${item.Unit_Type === 'Kilo' ? '0.1' : '1'}" step="${item.Unit_Type === 'Kilo' ? '0.1' : '1'}" [cite: 39]
                   data-product-id="${item.Product_ID}" onchange="updateQuantity(this)">
            <span>x ${item.Price.toFixed(2)} Pesos/${item.Unit_Type} = ${itemTotal.toFixed(2)} Pesos</span>
        `;
        selectedItemsDiv.appendChild(itemDiv);
    });
    document.getElementById('finalTotalPrice').textContent = totalOrderPrice.toFixed(2); 
}

function updateQuantity(inputElement) {
    const productId = parseInt(inputElement.dataset.productId);
    let newQuantity = parseFloat(inputElement.value);

    const cartItem = cart.find(item => item.Product_ID === productId);
    if (cartItem) {
        if (cartItem.Unit_Type === 'Pack' && !Number.isInteger(newQuantity)) {
            newQuantity = Math.round(newQuantity); [cite_start]// Ensure integers for Pack units [cite: 39]
            inputElement.value = newQuantity;
        }
        if (newQuantity <= 0) {
            cart = cart.filter(item => item.Product_ID !== productId); // Remove if quantity is zero or less
        } else {
            cartItem.quantity = newQuantity;
        }
        updateCartDisplay(); 
    }
}

function setupModalListeners() {
    const viewOrderBtn = document.getElementById('viewOrderBtn');
    const orderDetailsModal = document.getElementById('orderDetailsModal');
    const imagePopupModal = document.getElementById('imagePopupModal');
    const closeButtons = document.querySelectorAll('.close-button');
    const confirmOrderBtn = document.getElementById('confirmOrderBtn');

    viewOrderBtn.onclick = () => {
        updateCartDisplay();
        orderDetailsModal.style.display = 'flex'; // Use flex to center content
    };

    closeButtons.forEach(button => {
        button.onclick = () => {
            button.closest('.modal').style.display = 'none';
        };
    });

    // Close modal if clicked outside of modal-content
    window.onclick = (event) => {
        if (event.target === orderDetailsModal) {
            orderDetailsModal.style.display = 'none';
        }
        if (event.target === imagePopupModal) {
            imagePopupModal.style.display = 'none';
        }
    };

    confirmOrderBtn.onclick = async () => {
        const customerNameInput = document.getElementById('customerName');
        const contactNumberInput = document.getElementById('contactNumber');

        // Client-side validation [cite: 52]
        if (!customerNameInput.checkValidity()) {
            alert('Please enter a valid Customer Name (letters and spaces only).');
            return;
        }
        if (!contactNumberInput.checkValidity()) {
            alert('Please enter a valid Contact Number (exactly 10 digits).');
            return;
        }

        if (cart.length === 0) {
            alert('Your cart is empty. Please add items before confirming your order.');
            return;
        }

        const customerName = customerNameInput.value; 
        const contactNumber = contactNumberInput.value; 
        const orderDate = new Date().toISOString().slice(0, 10); // YYYY-MM-DD

        const orderDetails = {
            customerName: customerName,
            contactNumber: contactNumber,
            orderDate: orderDate,
            items: cart.map(item => ({
                productId: item.Product_ID,
                quantity: item.quantity,
                priceAtOrder: item.Price // Store price at time of order
            }))
        };

        // --- Send orderDetails to a PHP backend script for saving to database --- [cite: 46]
        // This would typically be a new PHP file, e.g., 'save_order.php'
        try {
            const response = await fetch('save_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(orderDetails),
            });

            const result = await response.json();

            if (result.success) {
                alert('Order Confirmed!'); 
                cart = []; // Clear cart
                orderDetailsModal.style.display = 'none';
                customerNameInput.value = ''; // Clear input fields
                contactNumberInput.value = '';
                // No actual "redirect" needed since it's a single page, but clearing cart and inputs feels like returning to main state
                // We don't need to reload products since they are statically rendered by PHP on page load.
            } else {
                alert('Failed to confirm order: ' + result.message);
                console.error('Server error:', result.error);
            }
        } catch (error) {
            console.error('Error confirming order:', error);
            alert('An error occurred while confirming your order. Please try again.');
        }
    };
}

function openImagePopup(imageUrl) {
    const imagePopupModal = document.getElementById('imagePopupModal');
    const popupImage = document.getElementById('popupImage');
    popupImage.src = imageUrl;
    imagePopupModal.style.display = 'flex'; // Use flex to center content
}