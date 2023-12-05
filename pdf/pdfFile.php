<div style="text-align: center; padding-left: 20px; padding-right: 20px;">
    <img src="<?php echo $logo; ?>"/>
</div>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <table width="100%">
        <tbody>
            <tr>
                <td class="center main bold">
                    <?php
                    // Display the title based on the selected report type
                    switch ($reportType) {
                        case 'all':
                            echo 'LIST OF COCOON PRODUCERS';
                            break;
                    }
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
    <br>

    <!-- Main function, loop through the data -->
    <table width="100%">
        <tbody>
            <tr>
                <?php
                // Display table headers based on the selected report type
                switch ($reportType) {
                    case 'all':
                        echo '<td class="center padding">Name </td>';
                        echo '<td class="center padding">Date</td>';
                        break;
                }
                ?>
            </tr>

            <?php foreach ($reportData as $row) : ?>
                <tr>
                    <?php
                    // Same Concept again
                    switch ($reportType) {
                        case 'all':
                            echo '<td class="center padding">' . htmlspecialchars($row['name']) . '</td>';
                            echo '<td class="center padding">' . htmlspecialchars($row['date']) . '</td>';
                            break;
                    }
                    ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
