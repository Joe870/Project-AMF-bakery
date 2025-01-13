
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

</style>


<div>
    <input type="text" wire:model="searchTerm" placeholder="Search for an error..." />
    <button wire:click="search">Search</button>
</div>

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
<!--
        <div id="small-dynamic-box" class="box"onclick="window.location.href='sections.html'">
            <p>Klik op een section button hieronder</p>
        </div>  -->

        <!-- <div class="footer" >
            <button onclick="updateSmallBox('Section 1 Content')">Section 1</button>
            <button onclick="updateSmallBox('Section 2 Content')">Section 2</button>
            <button onclick="updateSmallBox('Section 3 Content')">Section 3</button>
            <button onclick="updateSmallBox('Section 4 Content')">Section 4</button>
        </div>  -->
    </div>


    <script>
        function updateSmallBox(content) {
            document.getElementById('small-dynamic-box').innerHTML = `<p>${content}</p>`;
        }

    </script>
</div>
