<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vergrote Lineaire Grafiek</title>
    @livewireStyles
    @include('includes/navbar')
    <link rel="stylesheet" href="{{ asset('css/charts.css') }}">
</head>

<body>
<div class="chart-container">

    <livewire:livewire-line-chart

    :line-chart-model="$lineChartModel"
    key="{{ $lineChartModel->reactiveKey() }}" />
    @livewireScripts
    @livewireChartsScripts
</div>
<form action="/dashboard/line-chart" method="GET">
    <input type="text" name="filter" placeholder="Enter filter"><br>
    <label>
        <input type="checkbox" name="urgent" value="1">
        <span class="slider round">Urgent</span>
    </label><br>
    <button type="submit">Submit</button>
</form>
</body>
</html>
