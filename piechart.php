<?php
include('conn.php');

// Check if the year parameter is set
if (isset($_GET['year'])) {
    $selectedYear = $_GET['year'];

    $sql = "SELECT p.product, COUNT(o.productId) AS order_count
            FROM orders o
            INNER JOIN product p ON o.productId = p.id
            WHERE YEAR(o.date) = $selectedYear
            GROUP BY p.product
            ORDER BY order_count DESC
            LIMIT 5";
} else {
    $sql = "SELECT p.product, COUNT(o.productId) AS order_count
            FROM orders o
            INNER JOIN product p ON o.productId = p.id
            GROUP BY p.product
            ORDER BY order_count DESC
            LIMIT 5";
}

$result = $con->query($sql);
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = [
        'product' => $row['product'],
        'order_count' => $row['order_count'],
    ];
}

$con->close();

header('Content-Type: application/json');
echo json_encode($data);
?>
