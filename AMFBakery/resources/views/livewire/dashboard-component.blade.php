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
            background-color: #f0f0f0;
        }

        .container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(2, 200px);
            gap: 20px;
            margin: 20px;
            max-width: 100%;
        }

        .box, .box-large {
            background-color: #e0e0e0;
            border: 4px solid #ff6666;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 18px;
            color: #333;
            cursor: pointer;
            border-radius: 30px;
        }

        .box-large:hover{
            background-color: #D6D4D4;
            border: 4px solid #000000;
            border-radius: 30px;
        }

        .box:hover{
            background-color: #D6D4D4;
            border: 4px solid #000000;
            border-radius: 30px;
        }

        #box{
            grid-column: span 1;
        }

        .box-large {
            grid-column: span 4;
            display: flex;
            flex-direction: column;
            padding: 20px;
        }

        .box-large h2 {
            margin-bottom: 10px;
        }

        .box-large ul {
            list-style-type: none;
            color: #ed2027;
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

    </style>



    <div class="container">


        <div class="box" wire:click="redirectToChart('column')">
            <livewire:livewire-column-chart
                :column-chart-model="$columnChartModel"
                key="{{ $columnChartModel->reactiveKey() }}" />
        </div>


        <div class="box" wire:click="redirectToChart('line')">
            <livewire:livewire-line-chart
                :line-chart-model="$lineChartModel"
                key="{{ $lineChartModel->reactiveKey() }}" />
        </div>

        <div class="box" wire:click="redirectToChart('pie')">
            <livewire:livewire-pie-chart
                :pie-chart-model="$pieChartModel"
                key="{{ $pieChartModel->reactiveKey() }}" />
        </div>

        <!--
                <div id="small-dynamic-box" class="box"onclick="window.location.href='sections.html'">
                    <p>Klik op een section button hieronder</p>
                </div>  -->

        <div id="main-section" class="box-large" onclick="window.location.href='alle-errors.html'">
            <h2>Alle Errors</h2>
            <ul>
                @foreach ($errors as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
        <!-- <div class="footer" >
            <button onclick="updateSmallBox('Section 1 Content')">Section 1</button>
            <button onclick="updateSmallBox('Section 2 Content')">Section 2</button>
            <button onclick="updateSmallBox('Section 3 Content')">Section 3</button>
            <button onclick="updateSmallBox('Section 4 Content')">Section 4</button>
        </div>  -->
    </div>


    <script>
        function updateSmallBox(content) {
            document.getElementById('small-dynamic-box').innerHTML = <p>${content}</p>;
        }




    </script>
</div>
