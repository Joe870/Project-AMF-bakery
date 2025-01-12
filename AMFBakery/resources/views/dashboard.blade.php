<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    @livewireStyles
</head>
<body>


<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

@include('includes/navbar')

<livewire:dashboard-component />
    @livewireScripts
    @livewireChartsScripts
    @livewireChartsScripts

</body>
</html>
