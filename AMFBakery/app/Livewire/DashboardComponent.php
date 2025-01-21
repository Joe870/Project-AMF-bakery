<?php

namespace App\Livewire;

use Livewire\Component;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;
use Asantibanez\LivewireCharts\Models\lineChartModel;
use Asantibanez\LivewireCharts\Models\PieChartModel;
use App\Models\AlarmHistory;
use Carbon\Carbon;

class DashboardComponent extends Component
{
    public $searchTerm = '';
    public $errorMessage = '';
    public $errors = [];
    public $startDate = '';
    public $endDate = '';
    public $urgentFilter = '';

    // stuurt je naar de bijbehorende webpagina
    public function redirectToChart($chartType)
    {
        if ($chartType == 'column') {
            return redirect()->route('charts.column');
        } elseif ($chartType == 'line') {
            return redirect()->route('charts.line');
        } elseif ($chartType == 'pie') {
            return redirect()->route('charts.pie');
        }
    }

    // checkt of de zoekterm bestaat in de database en returnt een boolean
    public function doesSearchTermExist($searchTerm)
    {
        return AlarmHistory::whereRaw('LOWER(Message) LIKE ?', ['%' . $searchTerm . '%'])->exists();
    }

    //checkt of er data is tussen de 2 gegeven tijden
    public function doesDaterangeExist($startDate, $endDate){
        return AlarmHistory::whereBetween('EventTime', [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay(),
        ])->exists();

    }

    public function applyFiltersAndErrors($query, $searchTriggered = false)
    {
        // maakt the session errormessage leeg
        session()->forget('errorMessage');

        $urgentFilter = request()->query('urgentFilter', false);
        $startDate = request()->query('startDate');
        $endDate = request()->query('endDate');
        $searchTerm = request()->query('searchTerm');
        $this->errors = [];

        $searchTriggered = filter_var(request()->query('searchTriggered', false), FILTER_VALIDATE_BOOLEAN);

        // als er niks is ingevult en op search is geklikt
        if ($searchTriggered && empty($searchTerm) && !$urgentFilter && (empty($startDate) || empty($endDate))) {
            session()->put('errorMessage', 'Please provide a search term or a date range.');
        }

        // als de eerste datum na de tweede datum is
        if ($searchTriggered && !empty($startDate) && !empty($endDate)) {
            if (Carbon::parse($startDate)->gt(Carbon::parse($endDate))) {
                session()->put('errorMessage', 'Invalid date range. Start date must be earlier than or equal to the end date.');
            }
        }

        // als er geen resultaten gevonden
        if (!AlarmHistory::whereRaw('LOWER(Message) LIKE ?', ['%' . $searchTerm . '%'])->exists() ) {
            session()->put('errorMessage','No results found for the given searchterm.');
        }

        // pas de tijd filters toe
        if (!empty($startDate) && !empty($endDate) && $this->doesDateRangeExist($startDate, $endDate)) {
            if (Carbon::parse($startDate)->lte(Carbon::parse($endDate))) {
                $query->whereBetween('EventTime', [
                    Carbon::parse($startDate)->startOfDay(),
                    Carbon::parse($endDate)->endOfDay(),
                ]);
            }
        }

        // pas de zoekfilter toe
        if (!empty($searchTerm) && $this->doesSearchTermExist($searchTerm)) {
            $searchTerm = strtolower(trim($searchTerm));
            $query->whereRaw('LOWER(Message) LIKE ?', ['%' . $searchTerm . '%']);
        }

        // pas de urgent filter toe
        if ($urgentFilter) {
            $query->where('priority', 'Urgent');
        }
    }


    public function getColumnChartModel()
    {
        $chart = (new ColumnChartModel())
            ->setHorizontal()
            ->setDataLabelsEnabled(true);

        $query = AlarmHistory::query();
        $searchTriggered = filter_var(request()->query('searchTriggered', false), FILTER_VALIDATE_BOOLEAN);
        $this->applyFiltersAndErrors($query, $searchTriggered);

        // Group by Message instead of event_date
        $data = $query->select(
            'Message',  // Grouping by message now
            \DB::raw('COUNT(*) as alarm_count'),
            \DB::raw('GROUP_CONCAT(CONCAT(DATE_FORMAT(EventTime, "%H:%i"), " - ", Message) SEPARATOR "\n") as messages')
        )
        ->groupBy('Message')  // Grouping by Message
        ->orderByDesc(\DB::raw('COUNT(*)'))  // Sorting by the most frequent messages
        ->get();

        foreach ($data as $item) {
            $chart->addColumn(
                (string) $item->Message,  // Using the message as the label for the column
                (int) $item->alarm_count,
                '#' . substr(md5($item->Message), 0, 6),  // Generate color based on the message
                ['tooltip' => '<div class="custom-tooltip">' . nl2br($item->messages) . '</div>']
            );
        }

        return $chart;
    }


    // lineiare grafiek
    public function getLineChartModel()
    {
        $chart = new LineChartModel();

        // maak de query aan en pas filters toe
        $query = AlarmHistory::query();
        $this->applyFiltersAndErrors($query);

        // data ophalen uit de database

        $data = $query->select(
            'Message',
            \DB::raw('COUNT(*) as alarm_count'),
            \DB::raw('GROUP_CONCAT(CONCAT(DATE_FORMAT(EventTime, "%H:%i"), " - ", Message) SEPARATOR "\n") as messages')
        )
        ->groupBy('Message')
        ->orderBy(\DB::raw('COUNT(*)'), 'desc')
        ->get();

        if (!empty($this->startDate) && !empty($this->endDate)) {

            $chart->setTitle('Alarms between ' . $this->startDate . 'and ' . $this->endDate);
        }else{
            $chart->setTitle('Alarms over time');
        }

        foreach ($data as $item) {
            $shortLabel = strlen($item->messages) > 15 ? substr($item->messages, 0, 12) . '...' : $item->messages;

            $chart->addPoint($shortLabel,
            (int) $item->alarm_count,
            ['tooltip' => '<div class="custom-tooltip">' . nl2br($item->messages) . '</div>']
        );
        }

        return $chart;
    }

    public function getPieChartModel()
    {
        $chart = (new PieChartModel())
        ->setDataLabelsEnabled(true);


        $query = AlarmHistory::query();
        $this->applyFiltersAndErrors($query);


        $data = $query->select(
            'Message',
            \DB::raw('COUNT(*) as alarm_count'),
            \DB::raw('GROUP_CONCAT(CONCAT(DATE_FORMAT(EventTime, "%H:%i"), " - ", Message) SEPARATOR "\n") as messages')
        )
        ->groupBy('Message')
        ->orderByDesc(\DB::raw('COUNT(*)'))
        ->get();

        if (empty($this->searchTerm) && !$this->doesSearchTermExist($this->searchTerm) && !$this->doesDateRangeExist($this->startDate, $this->endDate)) {

            $otherCount = AlarmHistory::whereNotIn('Message', $data->pluck('Message'))
            ->select(\DB::raw('COUNT(*) as count'))
            ->value('count');


            if ($otherCount > 0) {
                $chart->addSlice('Other', $otherCount, '#cccccc');
            }

        }
        foreach ($data as $item) {
            $chart->addSlice($item->Message, $item->alarm_count, '#' . dechex(rand(0x100000, 0xFFFFFF)));
        }

        return $chart;
    }


    public function render()
    {
        $top3errors = AlarmHistory::select('Message', \DB::raw('COUNT(*) as count'))
            ->groupBy('Message')
            ->orderByDesc('count')
            ->take(3)
            ->pluck('Message')
            ->toArray();

        $columnChartModel = $this->getColumnChartModel();
        $lineChartModel = $this->getLineChartModel();
        $pieChartModel = $this->getPieChartModel();
        $errorMessage = $this->errorMessage ?? null;

        return view('livewire.dashboard-component', compact('top3errors', 'columnChartModel', 'lineChartModel', 'pieChartModel','errorMessage'));
    }

    public function allError()
    {
        $query = AlarmHistory::query();
    
        $this->applyFiltersAndErrors($query);
    
        $errors = $query->select('Message', 'EventTime', 'priority')
            ->orderBy('EventTime', 'desc')
            ->get();
    
        return $errors;
    }
    
}