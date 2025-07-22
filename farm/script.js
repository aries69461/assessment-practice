const productContainer = document.querySelectorAll('.product-list');

if (productContainer){
    displayProducts();
}

function displayProducts() {
    products.forEach(product => {
        const productCard = document.createElement("div");
        productCard.classList.add("product-card");
        productCard.innerHTML = `
            <div class="img-box">
                <img src="${product.Image_URL}">
            <div>
            <h3>${product.Product_Name}</h3>
            <p>${product.Price.toFixed(2)} Pesos per ${product.Unit_Type}</p>
        `;
        productContainer.appendChild(productCard);

        const imgBox = productCard.querySelector(".img-box");
        imgBox.addEventListener("click", () => {
            sessionStorage.setItem("selectedProduct", JSON.stringify(product));
            window.location.href = "product-details.html";
        });
    });
}
