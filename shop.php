<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <a href="#" class="logo"><img src="assets/logo.png" style="height: 18px;" alt=""></a>
        <nav>
            <a href="home.html">Home</a>
            <a href="/shop.html" style="font-weight: 700;"">Shop</a>
            <a href="#">Contact</a>
        </nav>
        <a href="#" class="login">log in <span class="dot"></span></a>
    </header>

        <div class="Product-section">
        <div class="product-container">
            <div class="product-navigation">
                <div class="category">Apparel</div>
                <div class="navigation">Show All</div>
            </div>
            <div class="card-container">
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<div class="card">';
                        echo '<div class="card-title">'; 
                        echo '<h3 class="carde-title">' . ($row["name"] ?? '') . '</h3>';
                        echo '<p>Rp ' . number_format($row["price"] ?? 0, 0, ',', '.') . '</p>';
                        // echo '<p>Stok: ' . ($row["stock"] ?? '') . '</p>';
                        echo '</div>';
                        echo '</div>';
                    }
                    
                } else {
                    echo '<p>Tidak ada produk ditemukan.</p>';
                }
                ?>
            </div>
    </div>

    <footer>       
        <a href="#" class="logo"><img src="assets/logo.png" style="height: 18px;" alt=""></a>
        <div>
            <a href="">ig</a>
            <a href="" >X</a>
        </div>
    </footer>
</body>
</html>