<?php
// Maakt de connectie naar de database
$servername = "mariadb";
$username = "root";
$password = "mysql";
$dbname = "amfbakery";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Securely retrieve and sanitize the filter input
$filter = isset($_GET['name']) ? $_GET['name'] : '';
$filter = "%" . htmlspecialchars($filter, ENT_QUOTES, 'UTF-8') . "%";

// Prepare and execute the SQL query
$sql = $conn->prepare("SELECT Message, COUNT(*) as error_count FROM alarm_histories WHERE Message LIKE :filter GROUP BY Message ORDER BY error_count DESC");
$sql->bindParam(':filter', $filter, PDO::PARAM_STR);
$sql->execute();

// Fetch results
$results = $sql->fetchAll(PDO::FETCH_ASSOC);

// Test print
foreach ($results as $row) {
    echo "Message: " . $row['Message'] . " - Error Count: " . $row['error_count'] . "<br>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vergrote Staaf Diagram</title>
    @livewireStyles
    @include('includes/navbar')
    <link rel="stylesheet" href="{{ asset('css/charts.css') }}">
</head>
<body>
<div class="chart-container">
    <livewire:livewire-column-chart
    :column-chart-model="$columnChartModel"
    key="{{ $columnChartModel->reactiveKey() }}" />
    @livewireScripts
    @livewireChartsScripts
</div>
<form action="column.blade.php" method="GET">
    <input type="text" name="name" placeholder="Enter filter" required><br>
    <button type="submit">Submit</button>
</form>
</body>
</html>
