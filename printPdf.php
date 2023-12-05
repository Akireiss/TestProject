<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <title>Document</title>
</head>

<body>
<form class="row g-3 needs-validation" action="pdf/pdf.php" method="GET" id="reportForm" target="_blank">
                <div class="col-md-6 position-relative">
                  <label class="form-label">Choose Report<font color="red">*</font></label>
                  <select class="form-select" aria-label="Default select example" name="report_type" id="report" required>
                    <option value="" selected>Select Report</option>
                    <option value="all">All orders</option>
                  </select>
                </div>
                <div class="col-md-3 justify-center">

                    <button type="submit" name="submit" >Create</button>
                </div>
</body>
</html>