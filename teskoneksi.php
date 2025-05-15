<?php
$host = "localhost";
$user = "root"; 
$pass = "Megamode#12";    
$db = "FINPRO";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$product = "SELECT * FROM products";
$result = $conn->query($product);

echo "<h2>Daftar Produk</h2>";
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>ID</th><th>Nama Produk</th><th>Harga</th><th>Gambar</th><th>Stok</th></tr>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["name"] . "</td>";
        echo "<td>Rp " . number_format($row["price"], 0, ',', '.') . "</td>";
        echo "<td><img src='" . $row["image_url"] . "' width='100'></td>";
        echo "<td>" . $row["stock"] . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>Tidak ada produk</td></tr>";
}
echo "</table>";

$conn->close();
?>
