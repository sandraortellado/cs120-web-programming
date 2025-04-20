
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .orders-list {
            padding-left: 10vw;
        }
        hr {
            margin-left: -10vw;
            margin-right: -10vw;
            border: none;
            border-top: 1px solid #ddd;
        }
        .order hr:last-child {
            display: none;
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

        $sql = "WITH order_totals AS (
                SELECT order_id, SUM(quantity * price) AS order_total
                FROM order_items
                LEFT JOIN products ON order_items.product_id = products.id
                GROUP BY 1
                )
                SELECT orders.order_date, order_items.order_id AS order_id, products.name AS product_name, order_items.quantity, products.price, products.price * order_items.quantity AS cost, order_totals.order_total
                FROM orders
                LEFT JOIN order_items ON orders.id = order_items.order_id
                LEFT JOIN products ON order_items.product_id = products.id
                LEFT JOIN order_totals ON order_totals.order_id = order_items.order_id
                ORDER BY order_date DESC";
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
    <div class="orders-list">
        <?php
        if ($result->num_rows > 0) {
            $lastOrderId = null;

            while($row = $result->fetch_assoc()) {
                //if new order, show order details
                if ($row["order_id"] !== $lastOrderId) {
                    echo "<hr>";
                    if ($lastOrderId !== null) {
                        echo "</div>";
                    }
                    echo "<div class='order'>";
                    echo "<h3>Date: " . $row["order_date"] . "</h3>";
                    echo "<h3>Order ID: #" . $row["order_id"] . "</h3>";
                    echo "<h3>Total: $" . $row["order_total"] . "</h3>";
                    $lastOrderId = $row["order_id"];
                }
            
                echo "<h4>Product: " . $row["product_name"] . "</h4>";
                echo "<p>Quantity: " . $row["quantity"] . "</p>";
                echo "<p>Price: $" . $row["price"] . "</p>";
                echo "<p>Cost: $" . $row["cost"] . "</p>";
                echo "<hr>";
            }
            if ($lastOrderId !== null) {
                echo "</div>";
            }
        } else {
            echo "<p>No orders available</p>";
        }
        $conn->close();
        ?>
    </div>
    <div class="footer">
        <p>© SYLK. All rights reserved.</p>
    </div>
</body>
<script>
</script>
</html>
