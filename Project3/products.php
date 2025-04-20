
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Listing</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .product {
            text-align: center;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
        }
        .product img {
            width: 100%;
            height: auto;
            aspect-ratio: 1 / 1;
            object-fit: cover;
        }
        .product select {
            margin-top: 10px;
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        .add-to-cart-button,
        .more-button{
            background: #333;
            color: white;
            border: none;
            padding: 10px 15px;
            margin-top: 10px;
            margin-left: 5px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
            transition: background 0.3s ease;
        }
        .description {
            font-size: 12px;
        }
    </style>
</head>
<body>
    <?php
        $servername = "sandrao.sgedu.site";
        $username = "udgteqoogq3uz";
        $password = "J35#e2#nB1b&";
        $dbname = "db4aggi2ahh5gw";

        $conn = new mysqli($servername, $username, $password, $dbname);

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT id, name, description, price, img_url FROM products";
        $result = $conn->query($sql);
    ?>
    <div class="header">
        <div class="title-wrapper">
        <a href="products.php" class="header-link"><h1>SYLK</h1></a>
        </div>
        <a href="cart.php" class="cart-icon-link">
            <img src="images/grocery-store.png" class="cart-icon" alt="Cart">
        </a>
    </div>
    <div class="banner">
    </div>
    <div class="nav">
        <a href="#">Shop All</a>
        <a href="#">Cleanse</a>
        <a href="#">Exfoliate</a>
        <a href="#">Hydrate</a>
    </div>
    <div class="product-grid">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='product'>";
                echo "<img src='" . $row["img_url"] . "' alt='" . $row["name"] . "'>";
                echo "<h3>" . $row["name"] . "</h3>";
                echo "<p>$" . $row["price"] . "</p>";

                $selectId = "qty_" . $row["id"];                

                echo "<select id='$selectId'>
                    <option value='1'>1</option>
                    <option value='2'>2</option>
                    <option value='3'>3</option>
                    <option value='4'>4</option>
                    <option value='5'>5</option>
                </select>";
                echo "<a 
                    class='add-to-cart-button' 
                    onclick='addToCart(this)' 
                    data-id='" . $row["id"] . "' 
                    data-name='" . $row["name"] . "' 
                    data-price='" . $row["price"] . "' 
                    data-select-id='$selectId'>
                    Add to Cart
                    </a>";
                $detailsId = "details_" . $row["id"];
                echo "<a class='more-button' onclick='toggleDetails(\"$detailsId\")'>More</a>";
                echo "<div id='$detailsId' class='product-details' style='display: none;'>
                        <p class='description'>" . $row["description"] . "</p>
                    </div>";
                echo "</div>";
                
                }
        } else {
            echo "<p>No products available</p>";
        }
        $conn->close();
        ?>
    </div>
    <div class="footer">
        <p>© SYLK. All rights reserved.</p>
    </div>
</body>
<script>
    function addToCart(button) {
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const price = parseFloat(button.getAttribute('data-price'));
        const selectId = button.getAttribute('data-select-id');
        const quantity = parseInt(document.getElementById(selectId).value);

        const item = { id, name, price, quantity };

        let cart = JSON.parse(sessionStorage.getItem('cart')) || [];

        //check if item already exists
        const existingItem = cart.find(product => product.id === id);

        if (existingItem) {
            existingItem.quantity += quantity;
        } else {
            cart.push(item);
        }

        sessionStorage.setItem('cart', JSON.stringify(cart));
        console.log("Cart updated:", cart);
        const originalText = button.textContent;
        button.textContent = "Added!";
        button.disabled = true;
        setTimeout(() => {
            button.textContent = originalText;
            button.disabled = false;
        }, 1500);
    }

    function toggleDetails(id) {
        const detailsDiv = document.getElementById(id);
        if (detailsDiv.style.display === "none") {
            detailsDiv.style.display = "inline";
        } else {
            detailsDiv.style.display = "none";
        }
    }


</script>
</html>
