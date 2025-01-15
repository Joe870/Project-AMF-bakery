<div>
    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            max-width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-family: Arial, sans-serif;
            background-color:white;
        }

        .container {
            display: grid;
            grid-template-areas: "box1 box1 pie"
                         "box2 box2 pie"
                         "error error error";
            grid-template-rows: 300px 300px 300px;
            grid-template-columns: 400px 400px 400px;
            gap: 20px;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }


        .box1, .box2, .box-large, .pie-box {
            background-color: #e0e0e0;
            border: 4px solid #ff6666;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 18px;
            color: #333;
            cursor: pointer;
            border-radius: 30px;
            z-index:10;
        }

        .filter-section {
            margin-top: 15px;
            display: flex;
            flex-direction: row; /* Align items in a row */
            justify-content: center; /* Center items horizontally */
            align-items: center; /* Align items vertically */
            gap: 10px; /* Add spacing between items */
        }

        .filter-section select,
        .filter-section input {
            margin: 0; /* Remove any default margin */
            padding: 5px 10px; /* Optional: Adjust padding for better spacing */
        }

        .filter-section button {
            padding: 5px 15px; /* Adjust padding for consistency */
        }


        .box-large:hover, .box1:hover, .box2:hover, .pie-box:hover{
            background-color: #D6D4D4;
            border: 4px solid #000000;
            border-radius: 30px;
        }

        .box1{
            grid-area:box1;
        }

        .box2{
            grid-area:box2;
        }

        .pie-box{
            grid-area:pie;
        }

        .box-large {
            flex-direction: column;
            padding: 20px;
            grid-area:error;
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

        .container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(2, 260px);
            gap: 20px;
            width: 100%;
            max-width: 1200px;
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

    </style>

    <!-- zoeken -->
    <div class="search-bar">
        <input type="text" wire:model="searchTerm" placeholder="Search for an error..." />
        <button wire:click="search">Search</button>
    </div>


    <!-- error message -->
    @if ($errorMessage)
        <div class="error-message">
            {{ $errorMessage }}
            <div class="box1" wire:click="redirectToChart('column')">
                <livewire:livewire-column-chart
                    :column-chart-model="$columnChartModel"
                    key="{{ $columnChartModel->reactiveKey() }}" />
            </div>
            @endif



            <div class="container">
                <div class="box1" wire:click="redirectToChart('column')">
                    <livewire:livewire-column-chart
                        :column-chart-model="$columnChartModel"
                        key="{{ $columnChartModel->reactiveKey() }}" />
                </div>




                <div id="main-section" class="box-large" onclick="window.location.href='alle-errors.html'">
                    <h2>Alle Errors</h2>
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


                <div class="box2" wire:click="redirectToChart('line')">
                    <livewire:livewire-line-chart
                        :line-chart-model="$lineChartModel"
                        key="{{ $lineChartModel->reactiveKey() }}" />


                </div>
            </div>
        </div>
</div>
