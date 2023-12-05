<?php
include('conn.php');

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

<body class="bg-black text-white">

    <div class="container mt-5">
        <div class="row justify-content-center">

            <div class="col-md-3">
                <label for="categoryYear" class="form-label">Product</label>

                <select id="categorySelect" class="form-select mb-3" aria-label="Default select example">
                    <?php
                    // Fetch categories from the database
                    $categorySql = "SELECT id, category FROM category";
                    $categoryResult = $con->query($categorySql);

                    // Display category options
                    while ($categoryRow = $categoryResult->fetch_assoc()) {
                        $categoryId = $categoryRow['id'];
                        $categoryName = $categoryRow['category'];
                        echo "<option value=\"$categoryId\">$categoryName</option>";
                    }
                    ?>
                </select>

                </select>
            </div>
            <div class="col-md-3">
                <label for="categoryYear" class="form-label">Select Year</label>
                <div class="input-group mb-3">
                    <select id="categoryYear" class="form-select"></select>
                    <button id="addYearBtn" class="btn btn-success">Add Year</button>
                </div>
            </div>






        </div>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mt-3">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <canvas id="myPie"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script>
        // Declare the chart variable outside the functions to make it accessible
        var myChart;

        function updateChart(data) {
            var xValues = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            var yValues = data.map(item => item.order_count);

            var monthColors = [
                'rgba(75, 192, 192, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 205, 86, 0.2)',
                //add ka additaonal color doty
            ];
            var barColors = xValues.map((month, index) => monthColors[index % monthColors.length]);

            var ctx = document.getElementById('myChart').getContext('2d');

            if (myChart) {
                // Update the existing chart's data
                myChart.data.labels = xValues;
                myChart.data.datasets[0].data = yValues;
                myChart.data.datasets[0].backgroundColor = barColors;
                myChart.update(); // Update the existing chart
            } else {
                myChart = new Chart(ctx, {
                    type: "bar",
                    data: {
                        labels: xValues,
                        datasets: [{
                            backgroundColor: barColors,
                            data: yValues
                        }]
                    },
                    options: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: "Orders Per Month"
                        }
                    }
                });
            }
        }

        $(document).ready(function() {
            fetchChartData();

            $('#categorySelect, #categoryYear').change(function() {
                fetchChartData();
            });

            function fetchChartData() {
                var selectedCategory = $('#categorySelect').val();
                var selectedYear = $('#categoryYear').val();
                $.ajax({
                    url: 'barchart.php',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        category: selectedCategory,
                        year: selectedYear
                    }, // Include the selected category and year as parameters
                    success: function(data) {
                        console.log('Data received:', data);
                        updateChart(data);
                    },
                    error: function(error) {
                        console.log('Error fetching data:', error);
                    }
                });
            }
        });
    </script>



    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

    <script>
        function populateYearDropdown() {
            var currentYear = new Date().getFullYear();
            var yearDropdown = document.getElementById('categoryYear');
            //local storage lattan pagsaven instead t database
            var storedYears = JSON.parse(localStorage.getItem('addedYears')) || [];
            storedYears.sort(function(a, b) {
                return a - b;
            });

            addYearToDropdown(currentYear, yearDropdown, storedYears);

            storedYears.forEach(year => {
                addYearToDropdown(year, yearDropdown, storedYears, currentYear);
            });

        }

        function addYearToDropdown(year, dropdown, storedYears, currentYear) {
            if (!dropdown.options.namedItem(year)) {
                var option = document.createElement('option');
                option.value = year;
                option.text = year;

                if (year === currentYear) {
                    option.selected = true;
                }

                dropdown.add(option);
            }
        }

        function addNewYear() {
            var yearDropdown = document.getElementById('categoryYear');
            var newYear = prompt('Enter the year:'); // Prompt the user to enter a year

            if (newYear && !isNaN(newYear) && ![...yearDropdown.options].some(option => option.value === newYear)) {
                addYearToDropdown(newYear, yearDropdown, storedYears);

                var storedYears = JSON.parse(localStorage.getItem('addedYears')) || [];
                storedYears.push(newYear);
                localStorage.setItem('addedYears', JSON.stringify(storedYears));

                var event = new Event('change');
                yearDropdown.dispatchEvent(event);

                populateYearDropdown();
            }
        }

        document.getElementById('addYearBtn').addEventListener('click', function() {
            addNewYear();
        });


        document.addEventListener('DOMContentLoaded', function() {
            populateYearDropdown();
            fetchChartData(); // Fetch initial data using AJAX
        });

        $('#categoryYear').change(function() {
            fetchChartData(); // Fetch data when the year is changed
        });
    </script>
    <script>
        var myPieChart;

        function updatePieChart(data) {
            const xValues = data.map(item => item.product);
            const yValues = data.map(item => item.order_count);
            const barColors = ["#b91d47", "#00aba9", "#2b5797", "#e8c3b9", "#1e7145"];

            if (myPieChart) {
                myPieChart.destroy();
            }

            myPieChart = new Chart("myPie", {
                type: "pie",
                data: {
                    labels: xValues,
                    datasets: [{
                        backgroundColor: barColors,
                        data: yValues
                    }]
                },
                options: {
                    title: {
                        display: true,
                        text: "Top 5 Ordered Products"
                    }
                }
            });
        }

        function fetchChartData() {
            var selectedYear = $('#categoryYear').val();
            $.ajax({
                url: 'piechart.php',
                type: 'GET',
                dataType: 'json',
                data: {
                    year: selectedYear
                }, // Include the selected year as a parameter
                success: function(data) {
                    console.log('Data received:', data);
                    updatePieChart(data);
                },
                error: function(error) {
                    console.log('Error fetching data:', error);
                }
            });
        }
    </script>



</body>

</html>