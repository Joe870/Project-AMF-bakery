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
            background-color: white;
            overflow-x: hidden;
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
            z-index: 10;
        }

        .box-large:hover, .box1:hover, .box2:hover, .pie-box:hover {
            background-color: #D6D4D4;
            border: 4px solid #000000;
            border-radius: 30px;
        }

        .box1 {
            grid-area: box1;
        }

        .box2 {
            grid-area: box2;
        }

        .pie-box {
            grid-area: pie;
        }

        .box-large {
            flex-direction: column;
            padding: 20px;
            grid-area: error;
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
            margin-bottom: 40px;
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
            margin-top: 8px;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
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

        .livewire-column-chart,
        .livewire-line-chart {
            max-width: 100%;
            max-height: 100%;
        }

        .search-bar select {
            padding: 10px 15px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fff;
            width: 250px;
            transition: border-color 0.3s ease;
        }

        @media (max-width: 768px) {
            .container {
                grid-template-areas:
                    "box1"
                    "box2"
                    "pie"
                    "error";
                grid-template-columns: 1fr;
                grid-template-rows: auto;
                gap: 15px;
            }

            .box1, .box2, .pie-box, .box-large {
                width: 100%;
            }

            .livewire-column-chart,
            .livewire-line-chart,
            .livewire-pie-chart {
                width: 100%;
                height: auto;
            }
        }



        .styled-checkbox input[type="checkbox"] {
            all: unset;
            width: 20px;
            height: 20px;
            border-radius: 4px;
            border: 2px solid #ed2027;
            background-color: white;
            transition: background-color 0.3s ease, border-color 0.3s ease;
            cursor: pointer;
            display: inline-block;
            vertical-align: middle;
            position: relative;
            margin-top: -3px;
            margin-right: 10px
        }


    .styled-checkbox input[type="checkbox"]:checked {
        background-color: #ed2027;
        border-color: #ed2027;
    }

    .styled-checkbox input[type="checkbox"]:checked::before {
        content: '✓';
        color: white;
        font-size: 16px;
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }

    .styled-checkbox span {
        color: #333;
    }

    .styled-checkbox:hover input[type="checkbox"] {
        border-color: #000;
    }
</style>



    <!-- Searching Form -->
    <div class="search-bar">
        <form action="/dashboard" method="GET">

            <label class="styled-checkbox">
                <span>Urgent</span>
                <input type="checkbox" name="urgentFilter" value="1">

            </label>

            <input type="text" name="searchTerm" placeholder="Search errors...">
            <input type="date" name="startDate" placeholder="Start Date">
            <input type="date" name="endDate" placeholder="End Date">
            <input type="hidden" name="searchTriggered" id="searchTriggered" value="false">
            <input type="hidden" name="errorMessage" id="errorMessage" value="false">
            <button type="submit" onclick="document.getElementById('searchTriggered').value='true'">Search</button>
        </form>
    </div>

    @if(session()->has('errorMessage'))
    <div class="error-message">
        {{ session('errorMessage') }}
    </div>
    @endif

    <div class="container">
        <div class="box1" wire:click="redirectToChart('column')">
            <livewire:livewire-column-chart
                :column-chart-model="$columnChartModel"
                key="{{ $columnChartModel->reactiveKey() }}" />
        </div>

        <div id="main-section" class="box-large" onclick="window.location.href='/dashboard/all-errors'">
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

        <div class="box2" wire:click="redirectToChart('line')">
            <livewire:livewire-line-chart
                :line-chart-model="$lineChartModel"
                key="{{ $lineChartModel->reactiveKey() }}" />
        </div>
    </div>


</div>
