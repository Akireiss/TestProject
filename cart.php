<?php
include('conn.php');
$user_id = 2;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <title>Document</title>
</head>
<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Product Information</h5>
            
            <form id="cartForm" method="post" action="updateTotal.php">
            <?php
$sql = "SELECT * FROM cart WHERE user_id = $user_id";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $product = $row['product'];
        $price = $row['price'];
        $total = $row['total'];
        $user_id = $row['user_id'];

        // Calculate total price for each item
        $item_total_price = $price * $total;
?>

                        <div class="form-group" data-index="<?php echo $row['user_id']; ?>">
                        <input type="hidden" class="form-control product" name="user_id[]" value="<?php echo $user_id; ?>" readonly>

                            <label for="product">Product</label>
                            <input type="text" class="form-control product" name="product[]" value="<?php echo $product; ?>" readonly>
                        </div>


                    <div class="form-group">
    <label for="price">Price</label>
    <input type="number" class="form-control price" name="price[]" value="<?php echo $price; ?>" readonly>
</div>



                        <div class="form-group">
                            <label for="total">Total Item</label>
                            <div class="input-group">
                                <input type="text" class="form-control total" name="total[]" value="<?php echo $total; ?>" readonly>
                                <button class="btn btn-outline-secondary subtractBtn" type="button">-</button>
                                <button class="btn btn-outline-secondary addBtn" type="button">+</button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="item_total_price">Total Price</label>
                            <input type="number" class="form-control item_total_price" name="item_total_price[]" value="<?php echo $item_total_price; ?>" readonly>
                        </div>


                        <hr>
                        <?php
                    }
                } else {
                }

                $result->close();
                $con->close();
                ?>

                <div class="d-flex justify-content-end mt-3">
                  <span id="response" class="mx-2"></span>
                    <button type="button" class="btn btn-success btn-sm" id="updateCartBtn">Update Cart</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
  $(document).ready(function () {
    $('.addBtn, .subtractBtn').on('click', function () {
        var container = $(this).closest('.form-group');
        var totalInput = container.find('.total');
        var priceInput = container.find('.price');
        var itemTotalPriceInput = container.find('.item_total_price');
        var currentValue = parseInt(totalInput.val());
        var price = parseFloat(priceInput.val());

        if ($(this).hasClass('addBtn')) {
            totalInput.val(currentValue + 1);
        } else if ($(this).hasClass('subtractBtn') && currentValue > 0) {
            totalInput.val(currentValue - 1);
        }

        // Recalculate total price
        var newTotal = parseInt(totalInput.val());
        var newTotalPrice = price * newTotal;
        itemTotalPriceInput.val(newTotalPrice.toFixed(2));
    });

    $('#updateCartBtn').off('click').on('click', function () {
        var formData = $('#cartForm').serializeArray();

        $.ajax({
            type: 'POST',
            url: 'updateTotal.php',
            data: formData,
            success: function (response) {
                // Display the response in the #response span
                $('#response').text(response);
            },
            error: function (error) {
                console.log(error);
            }
        });
    });
});

</script>