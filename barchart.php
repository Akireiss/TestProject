<?php
include('conn.php');
if (isset($_GET['category']) && isset($_GET['year'])) {
    $selectedCategory = $_GET['category'];
    $selectedYear = $_GET['year'];

    $sql = "SELECT DATE_FORMAT(o.date, '%Y-%m') AS month, COUNT(o.id) AS order_count 
            FROM orders o
            INNER JOIN product p ON o.productId = p.id
            WHERE p.categoryId = $selectedCategory AND YEAR(o.date) = $selectedYear
            GROUP BY month";
} else {
    $sql = "SELECT DATE_FORMAT(date, '%Y-%m') AS month, COUNT(id) AS order_count FROM orders GROUP BY month";
}

$result = $con->query($sql);

$data = [];

$allMonths = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

foreach ($allMonths as $month) {
    $data[] = [
        'month' => $month,
        'order_count' => 0, // Dec-Jan//Depende sa gusto ng client
    ];
}

while ($row = $result->fetch_assoc()) {
    $monthIndex = (int)date('n', strtotime($row['month'])); // Extract month index (1 to 12) from the date
    $data[$monthIndex - 1]['order_count'] = $row["order_count"];
}

$con->close();

header('Content-Type: application/json');
echo json_encode($data);
?>
