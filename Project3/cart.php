
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
        .cart-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .cart-list {
            padding: 20px;
            border: 1px solid #ccc;
            text-align: center;
        }
        ul {
            list-style-type: none;
            padding-left: 0;
        }
        .button-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .button-wrapper a {
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
    <div class="cart-list">
    </div>
    <div class="button-wrapper">
        <a href="products.php" class="button-link">Keep Shopping</a>
    </div>
    <div class="footer">
        <p>© SYLK. All rights reserved.</p>
    </div>
</body>
<script>
    //get cart
    const cart = JSON.parse(sessionStorage.getItem('cart')) || [];
    //write stuff to page
    heading = document.createElement('h2');
    heading.textContent = 'Cart Details';
    document.querySelector('.cart-list').appendChild(heading);
    console.log(Object.keys(cart).length)
    //if no items in cart
    if (Object.keys(cart).length == 0) {
        noItems = document.createElement('h1')
        noItems.textContent = 'No items currently in cart';
        document.querySelector('.cart-list').appendChild(noItems);
    } else {
        //make list
        ul = document.createElement('ul')
        document.querySelector('.cart-list').appendChild(ul)
        total = 0
        //loop through items and add to page
        cart.forEach(item => {
            li = document.createElement('li')
            li.textContent = `${item.name} x ${item.quantity} = $${item.price * item.quantity}`;
            total += item.price * item.quantity;
            ul.appendChild(li)
        });
        totalText = document.createElement('h2');
        totalText.textContent = `Your Total is: $${total}`;
        document.querySelector('.cart-list').appendChild(totalText);
        checkoutButton =  document.createElement('a');
        checkoutButton.textContent = "Checkout";
        //checkout button to send data and load page
        checkoutButton.onclick = () => {
            const orderData = {
                cartItems: cart,
                total: cart.reduce((sum, item) => sum + (item.price * item.quantity), 0),
            };

            fetch('thanks.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ cartItems: cart, total: total })
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    window.location.href = "thanks.php";
                } else {
                    alert("Something went wrong: " + res.error);
                }
            });
        };
        document.querySelector('.button-wrapper').appendChild(checkoutButton);
    }
    
</script>
</html>
