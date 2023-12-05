<?php
include('conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'][0]; 
    $product_array = $_POST['product'];
    $total_array = $_POST['total'];

    $sql = "UPDATE cart SET total = CASE";

    for ($i = 0; $i < count($product_array); $i++) {
        $product = $product_array[$i];
        $total = $total_array[$i];
        $sql .= " WHEN product = '$product' THEN $total";
    }

    $sql .= " END WHERE user_id = $user_id";

    $result = $con->query($sql);

    if ($result === TRUE) {
        echo "Cart updated successfully";
    } else {
        echo "Error updating cart: " . $con->error;
    }
}
?>
