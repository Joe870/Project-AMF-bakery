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


    // regelt alle fouten na klikken op de zoek button
    public function search()
    {
        $this->errorMessage = '';
        $this->errors = [];

        if (empty($this->searchTerm) && (empty($this->startDate) || empty($this->endDate))) {
            $this->errorMessage = 'Please provide a search term or a date range.';
            return;
        }

        if (!empty($this->startDate) && !empty($this->endDate)) {
            if (Carbon::parse($this->startDate)->gt(Carbon::parse($this->endDate))) {
                $this->errorMessage = 'Invalid date range. Start date must be earlier than or equal to the end date.';
                return;
            }
        }

        $query = AlarmHistory::query();

        $this->errors = $query->select('Message', \DB::raw('COUNT(*) as count'))
            ->groupBy('Message')
            ->orderByDesc('count')
            ->pluck('Message')
            ->toArray();

        if (empty($this->errors)) {
            $this->errorMessage = 'No results found for the given criteria.';
            return;
        }

        if (!AlarmHistory::whereRaw('LOWER(Message) LIKE ?', ['%' . $this->searchTerm . '%'])->exists() && empty($this->startDate) ) {
            $this->errorMessage = 'No results found for the given searchterm.';
            return;

        }
    }

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


    // past de filters toe op de query
    public function applyFilters($query)
    {
        // checkt of de 2 tijden bestaan
        if (!empty($this->startDate) && !empty($this->endDate) && $this->doesDateRangeExist($this->startDate, $this->endDate)) {
            // lte = less than or equal, het checkt of de eerste datum minder is dan de wteede
            if (Carbon::parse($this->startDate)->lte(Carbon::parse($this->endDate))) {
                // wherebteween past de query aan zodat het alleen data tussen start en eind heeft
                $query->whereBetween('EventTime', [
                    Carbon::parse($this->startDate)->startOfDay(),
                    Carbon::parse($this->endDate)->endOfDay(),
                ]);
            } else {
                $this->errorMessage = 'Invalid date range. Start date must be earlier than or equal to the end date.';
            }
        }

        // zoekfilter toepassen
        if (!empty($this->searchTerm) && $this->doesSearchTermExist($this->searchTerm)) {
            $searchTerm = strtolower(trim($this->searchTerm));
            $query->whereRaw('LOWER(Message) LIKE ?', ['%' . $searchTerm . '%']);
        }
    }




    public function getColumnChartModel()
    {

        $chart = new ColumnChartModel();
        $chart->setTitle('Alarms Over Time');

        $query = AlarmHistory::query();

        $this->applyFilters($query);

        $data = $query->select(
            \DB::raw('DATE(EventTime) as event_date'),
            \DB::raw('COUNT(*) as alarm_count'),
            \DB::raw('GROUP_CONCAT(CONCAT(DATE_FORMAT(EventTime, "%H:%i"), " - ", Message) SEPARATOR "\n") as messages')
        )
        ->groupBy('event_date')
        ->orderBy('event_date')
        ->get();

        foreach ($data as $item) {
            $chart->addColumn(
                (string) $item->event_date,
                (int) $item->alarm_count,
                '#' . substr(md5($item->event_date), 0, 6),
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
        $this->applyFilters($query);

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
        $chart = new PieChartModel();
        $query = AlarmHistory::query();
        $this->applyFilters($query);


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

        return view('livewire.dashboard-component', compact('top3errors', 'columnChartModel', 'lineChartModel', 'pieChartModel'));
    }
}
