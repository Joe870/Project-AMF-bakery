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

    <div class="search-bar">
        <form action="/dashboard/column-chart" method="GET">

            <div class="priority-dropdown">
                <label for="priority">Priority:</label>
                <select name="priority" id="priority">
                    <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                    <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>

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
    <livewire:livewire-column-chart

    :column-chart-model="$columnChartModel"
    key="{{ $columnChartModel->reactiveKey() }}" />
    @livewireScripts
    @livewireChartsScripts
</div>

</body>
</html>
