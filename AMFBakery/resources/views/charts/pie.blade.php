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

<div>
    <input type="text" wire:model="searchTerm" placeholder="Search for an error..." />
    <button wire:click="search">Search</button>
</div>
    <livewire:livewire-pie-chart
    
    :pie-chart-model="$pieChartModel"
    key="{{ $pieChartModel->reactiveKey() }}" />
    @livewireScripts
    @livewireChartsScripts
</div>
</body>
</html>
