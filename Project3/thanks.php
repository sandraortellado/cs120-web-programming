<?php
    session_start();

    header('Content-Type: text/html');
    //total and id
    $orderTotal = $_SESSION['order_total'] ?? 0.00;
    $orderId = $_SESSION['order_id'] ?? null;
    $success = false;

    //handle post
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER["CONTENT_TYPE"], "application/json") === 0) {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);

        if ($data && isset($data['cartItems']) && isset($data['total'])) {
            //read json store in variable
            $cartItems = $data['cartItems'];
            $orderTotal = $data['total'];

            try {
                //connection
                $pdo = new PDO(
                    "mysql:host=sandrao.sgedu.site;dbname=db4aggi2ahh5gw",
                    "udgteqoogq3uz", 
                    "J35#e2#nB1b&"
                );
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $pdo->beginTransaction();
                //insert data
                $stmt = $pdo->prepare("INSERT INTO orders (order_date) VALUES (NOW())");
                $stmt->execute();
                $orderId = $pdo->lastInsertId();

                //loop through result and insert
                $itemStmt = $pdo->prepare("INSERT INTO order_items (product_id, order_id, quantity) VALUES (?, ?, ?)");
                foreach ($cartItems as $item) {
                    $itemStmt->execute([$item['id'], $orderId, $item['quantity']]);
                }

                $pdo->commit();
                //update if changed
                $_SESSION['order_id'] = $orderId;
                $_SESSION['order_total'] = $orderTotal;
                echo json_encode(["success" => true]);
                exit;
            } catch (Exception $e) {
                $pdo->rollBack();
                echo json_encode(["success" => false, "error" => $e->getMessage()]);
                exit;
            }
        } else {
            echo json_encode(["success" => false, "error" => "Invalid data"]);
            exit;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You!</title>
    <link rel="stylesheet" href="style.css">
    </style>
</head>
<body>
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
    <div class="order-details">
        <h1>Thank you for your order!</h1>
        <h2>Order Total: $<?php echo number_format($orderTotal, 2); ?></h2>
        <?php
            $two_days_later = date('Y-m-d', strtotime('+2 days'));
            echo "<p>Your order is expected to ship by $two_days_later.</p>"
        ?>
    </div>
    <div class="footer">
        <p>© SYLK. All rights reserved.</p>
    </div>
</body>
<script>
    sessionStorage.removeItem('cart');
</script>
</html>
