<div>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }

        .container {
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* Three equal columns */
    grid-template-rows: repeat(2, 260px); /* Two rows, each 260px */
    gap: 20px;
    width: 100%;
    margin: 0 auto;
}

        .box, .box-large, .pie-box {
            background-color: #e0e0e0;
            border: 4px solid #ed2027;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 18px;
            color: #333;
            cursor: pointer;
            border-radius: 30px;
        }

        .search-bar {
            margin-top: 20px;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .search-bar input {
            padding: 10px 15px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fff;
            transition: border-color 0.3s ease;
            width: 250px;
        }

        .search-bar input:focus {
            border-color: #ed2027;
            outline: none;
        }

        .search-bar button {
            padding: 8px 20px;
            background-color: #ed2027;
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .search-bar button:hover {
            background-color: #000000;
        }

        .box-large:hover, .box:hover, .pie-box:hover {
            background-color: #D6D4D4;
            border: 4px solid #000000;
            border-radius: 30px;
        }

        #box {
            grid-column: span 1;
        }

        .pie-box {
            grid-column: span 1;
            grid-row: span 2;
        }

        .pie-box:hover {
            background-color: #D6D4D4;
            border: 4px solid #000000;
            border-radius: 30px;
        }

        #pie-box {
            grid-column: span 1;
        }

        .box-large {
            grid-column: span 1;
            grid-row: span 2;
            display: flex;
            flex-direction: column;
            padding: 20px;
        }

        .box-large h2 {
            margin-bottom: 10px;
        }

        .box-large ul {
            list-style-type: none;
            color: red;
        }

        .footer {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .footer button {
            padding: 10px 20px;
            border: none;
            background-color: #ddd;
            cursor: pointer;
            border-radius: 20px;
        }

        .footer button:hover {
            background-color: #ccc;
            border-radius: 20px;
            border: 3px solid #000000;
        }
        .error-message {
            color: red;
    margin-top: 10px;
    padding: 10px;
    max-width: 50%;
    font-size: 16px;
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    border-radius: 8px;
    margin-bottom: 20px;
    text-align: center;
    margin-left: auto;
    margin-right: auto;

    }
    .livewire-column-chart {
    max-width: 100%;
    max-height: 100%;
}


    </style>

    <!-- zoeken -->
    <div class="search-bar">
            <input type="text" wire:model="searchTerm" placeholder="Search errors...">

                <input type="date" wire:model="startDate" placeholder="Start Date">
                <input type="date" wire:model="endDate" placeholder="End Date">

                <button wire:click="search">Search</button>
        </div>

    @if ($errorMessage)
        <p class="error-message">{{ $errorMessage }}</p>
    @endif

    <div class="container">
        <div class="box" wire:click="redirectToChart('column')">
            <livewire:livewire-column-chart
                :column-chart-model="$columnChartModel"
                key="{{ $columnChartModel->reactiveKey() }}" />
        </div>

        <div id="main-section" class="box-large" onclick="window.location.href='alle-errors.html'">
            <h2>All Errors</h2>
            <ul>
                @foreach ($top3errors as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>

        <div class="pie-box" wire:click="redirectToChart('pie')">
            <livewire:livewire-pie-chart
                :pie-chart-model="$pieChartModel"
                key="{{ $pieChartModel->reactiveKey() }}" />
        </div>

        <div class="box" wire:click="redirectToChart('line')">
            <livewire:livewire-line-chart
                :line-chart-model="$lineChartModel"
                key="{{ $lineChartModel->reactiveKey() }}" />
        </div>
    </div>

</div>


