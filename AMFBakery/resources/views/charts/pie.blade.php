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

    <div class="search-bar">
        <form action="/dashboard/pie-chart" method="GET">

            <input type="text" name="searchTerm" placeholder="Search errors...">

                <input type="date" name="startDate" placeholder="Start Date">
                <input type="date" name="endDate" placeholder="End Date">
                <input type="hidden" name="searchTriggered" id="searchTriggered" value="false">

                <button type="submit" onclick="document.getElementById('searchTriggered').value='true'">Search</button>


            </form>
        </div>
        @if(session()->has('errorMessage'))
        <div class="error-message">
            {{ session('errorMessage') }}
        </div>
        @endif

<div class="chart-container">

    <livewire:livewire-pie-chart

    :pie-chart-model="$pieChartModel"
    key="{{ $pieChartModel->reactiveKey() }}" />
    @livewireScripts
    @livewireChartsScripts
</div>

</body>
</html>

