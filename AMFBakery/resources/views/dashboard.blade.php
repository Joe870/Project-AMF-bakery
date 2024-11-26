<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    @livewireStyles
</head>
<body>
@include('includes/navbar')

    <livewire:dashboard-chart-box />
    @livewireScripts
</body>
</html>
