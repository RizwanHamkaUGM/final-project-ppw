<?php
include 'koneksi.php';

$product = $conn->query("SELECT * FROM products LIMIT 4");
$products = $product->fetch_all(MYSQLI_ASSOC);

// parameter page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 4;
$offset = ($page - 1) * $limit;

// GET data produk sesuai halaman
$sql = "SELECT * FROM products LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Hitung total halaman
$totalResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM products");
$totalRow = mysqli_fetch_assoc($totalResult);
$totalData = $totalRow['total'];
$totalPages = ceil($totalData / $limit);

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    ob_start();
    if (!empty($products)) {
        foreach ($products as $row) {
            echo '<div class="card">';
            echo '  <div class="card-image">';
            echo '      <img src="' . ($row["image_url"] ?? '') . '" alt="">';
            echo '  </div>';
            echo '  <div class="card-title">'; 
            echo '      <h3 class="carde-title">' . ($row["name"] ?? '') . '</h3>';
            echo '      <p>Rp ' . number_format($row["price"] ?? 0, 0, ',', '.') . '</p>';
            echo '  </div>';
            echo '</div>';
        }
    } else {
        echo '<p>Tidak ada produk ditemukan.</p>';
    }
    $content = ob_get_clean();
    
    echo json_encode([
        'content' => $content,
        'currentPage' => $page,
        'totalPages' => $totalPages
    ]);
    exit;
}
?>
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
            <a href="#" style="font-weight: 700;">Home</a>
            <a href="shop.php">Shop</a>
            <a href="#">Contact</a>
        </nav>
        <a href="#" class="login">log in <span class="dot"></span></a>
    </header>

    <div class="Banner">
        <div class="text-area">
            <div class="banner-title">
                <h1>NOT JUST MERCH. <br> IT'S IDENTITY</h1>
                <a href="">explore now</a>
            </div>
        </div>
        <div class="image-area">
            <img src="assets/shirt.png" alt="">
        </div>
    </div>
    
    <div class="Product-section">
        <div class="product-container">
            <div class="product-navigation">
                <div class="category">Apparel</div>
                <div class="pagination-container">
                    <a href="shop.php">Show All</a>
                    <div class="pagination">
                        <a href="#" class="arrow prev-page" data-page="<?= max(1, $page - 1) ?>" <?= ($page <= 1) ? 'disabled' : '' ?>>←</a>
                        
                        <a href="#" class="arrow next-page" data-page="<?= min($totalPages, $page + 1) ?>" <?= ($page >= $totalPages) ? 'disabled' : '' ?>>→</a>
                    </div>
                </div>
            </div>

            <div class="card-container" id="product-cards">
                <?php
                if (!empty($products)) {
                    foreach ($products as $row) {
                        echo '<div class="card">';
                        echo '  <div class="card-image">';
                        echo '      <img src="' . ($row["image_url"] ?? '') . '" alt="">';
                        echo '  </div>';
                        echo '  <div class="card-title">'; 
                        echo '      <h3 class="carde-title">' . ($row["name"] ?? '') . '</h3>';
                        echo '      <p>Rp ' . number_format($row["price"] ?? 0, 0, ',', '.') . '</p>';
                        echo '  </div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>Tidak ada produk ditemukan.</p>';
                }
                ?>
            </div>
        </div>
    </div>


    <div class="Banner" style="background-color: #84a1ff;">
        <img src="assets/3RD.png" alt="">
    </div>

    <div class="Product-section">
        <div class="product-container">
            <div class="product-navigation">
                <div class="category">Apparel</div>
                <a href="">Show All</a>
            </div>
            <div class="card-container">
                <div class="card">
                    <div class="card-title"></div>
                </div>
                <div class="card">
                    <div class="card-title"></div>
                </div>
                <div class="card">
                    <div class="card-title"></div>
                </div>
                    <div class="card">
                        <div class="card-title"></div>
                    </div>
            </div>
        </div>
    </div>

    <div class="Banner">
        <h1>NOT <br> JUST <br>MERCH.</h1>
    </div>

    <footer>       
        <a href="#" class="logo"><img src="assets/logo.png" style="height: 18px;" alt=""></a>
        <div>
            <a href="">ig</a>
            <a href="" >X</a>
        </div>
    </footer>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Load page from URL if exists
        const url = new URL(window.location.href);
        if (url.searchParams.get("page")) {
            window.scrollTo({
                top: document.querySelector(".Product-section").offsetTop,
                behavior: "smooth"
            });
        }
        
        // Function to fetch products using AJAX
        function fetchProducts(page) {
            // Create a new XMLHttpRequest
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `?page=${page}`, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            
            xhr.onload = function() {
                if (this.status === 200) {
                    try {
                        const response = JSON.parse(this.responseText);
                        
                        // Update the product cards
                        document.getElementById('product-cards').innerHTML = response.content;
                        
                        // Update pagination buttons
                        const prevPageBtn = document.querySelector('.prev-page');
                        const nextPageBtn = document.querySelector('.next-page');
                        
                        prevPageBtn.dataset.page = Math.max(1, response.currentPage - 1);
                        if (response.currentPage <= 1) {
                            prevPageBtn.setAttribute('disabled', '');
                        } else {
                            prevPageBtn.removeAttribute('disabled');
                        }
                        
                        nextPageBtn.dataset.page = Math.min(response.totalPages, response.currentPage + 1);
                        if (response.currentPage >= response.totalPages) {
                            nextPageBtn.setAttribute('disabled', '');
                        } else {
                            nextPageBtn.removeAttribute('disabled');
                        }
                        
                        // Update URL without refreshing
                        const newUrl = new URL(window.location.href);
                        newUrl.searchParams.set('page', response.currentPage);
                        window.history.pushState({page: response.currentPage}, '', newUrl);
                        
                    } catch (e) {
                        console.error('Error parsing JSON:', e);
                    }
                }
            };
            
            xhr.send();
        }
        
        // Add event listeners to pagination buttons
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('prev-page') || e.target.classList.contains('next-page')) {
                e.preventDefault();
                const page = parseInt(e.target.dataset.page);
                if (!e.target.hasAttribute('disabled')) {
                    fetchProducts(page);
                    
                    // Scroll to product section
                    window.scrollTo({
                        top: document.querySelector(".Product-section").offsetTop,
                        behavior: "smooth"
                    });
                }
            }
        });
    });
    </script>

</body>
</html>