<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug View</title>
</head>
<body>
    <h1>Debugging Information</h1>
    <?php print_r("Order ID: " . $orderId); ?>
    <br>
    <br>
    <?php print_r("Status: " . $status); ?>
    <script>
        // Log the PHP variables to the browser console
        console.log("Order ID: <?php echo $orderId; ?>");
        console.log("Status: <?php echo $status; ?>");
    </script>
</body>
</html>
