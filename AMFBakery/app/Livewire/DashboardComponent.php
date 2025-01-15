<?php

namespace App\Livewire;

use Livewire\Component;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;
use Asantibanez\LivewireCharts\Models\LineChartModel;
use Asantibanez\LivewireCharts\Models\PieChartModel;
use App\Models\AlarmHistory;

class DashboardComponent extends Component
{

    public $searchTerm = '';
    public $errorMessage = '';
    public $errors = [];

    public function search()
    {
        $this->errorMessage = '';
        $this->errors = [];
        if (empty($this->searchTerm)) {
            $this->errorMessage = 'Please enter a search term.';
            return;
        }
        $this->getFilteredErrors();
    }

    private function getFilteredErrors()
    {
        $errors = AlarmHistory::where('Message', 'LIKE', '%' . $this->searchTerm . '%')
            ->select('Message', \DB::raw('COUNT(*) as count'))
            ->groupBy('Message')
            ->orderByDesc('count')
            ->pluck('Message')
            ->toArray();

        if (empty($errors)) {
            $this->errorMessage = 'No results found for "' . $this->searchTerm . '". Please try another term.';
        }
        $this->errors = $errors;
    }



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

    public function doesSearchTermExist($searchTerm)
    {
        // Check if the search term exists in the database
        return AlarmHistory::whereRaw('LOWER(Message) LIKE ?', ['%' . $searchTerm . '%'])->exists();
    }

    public function getColumnChartModel()
    {
            // Pak filter en urgent-status indien aangegeven
            $filter = request()->query('filter');
            $isUrgent = request()->query('urgent'); // Check of urgent is aangevinkt

            // Begin met de query
            $query = AlarmHistory::select('Message', \DB::raw('COUNT(*) as count'));

            // Voeg urgent-filter toe als urgent is aangevinkt
            if ($isUrgent) {
                $query->where('priority', 'Urgent');
            }

            // Voeg filter toe als er een filter is
            if ($filter) {
                $query->where('Message', 'LIKE', "%{$filter}%");
            }

            // Haal data op en groepeer op Message
            $data = $query->groupBy('Message')
                ->orderByDesc('count')
                ->take(8)
                ->get();

            $chart = (new ColumnChartModel())
                ->setHorizontal()
                ->setDataLabelsEnabled(true); // Added here to disable labels



            // Stel de titel in afhankelijk van de filters
            if ($isUrgent && $filter) {
                $chart->setTitle('Urgent Errors with Filter');
            } elseif ($isUrgent) {
                $chart->setTitle('Urgent Errors');
            } elseif ($filter) {
                $chart->setTitle('Errors with Filter');
            } else {
                $chart->setTitle('Error frequency');
            }

            //Voeg data toe aan de chart
            foreach ($data as $item) {
                $chart->addColumn($item->Message, $item->count, '#' . dechex(rand(0x100000, 0xFFFFFF)));
            }

            return $chart;
    }

    public function getLineChartModel()
    {
        // Pak filter en urgent-status indien aangegeven
        $filter = request()->query('filter');
        $isUrgent = request()->query('urgent'); // Check of urgent is aangevinkt

        // Begin met een basisquery
        $query = AlarmHistory::select(
            \DB::raw('DATE(EventTime) as event_date'),
            \DB::raw('COUNT(*) as alarm_count')
        );

        // Voeg urgent-filter toe als urgent is aangevinkt
        if ($isUrgent) {
            $query->where('priority', 'Urgent');
        }

        // Voeg filter toe als er een filter is
        if ($filter) {
            $query->where('Message', 'LIKE', "%{$filter}%");
        }

        // Haal data op en groepeer op event_date
        $data = $query->groupBy('event_date')
                    ->orderBy('event_date')
                    ->get();

        $chart = new LineChartModel();

        // Stel de titel in afhankelijk van de filters
        if ($isUrgent && $filter) {
            $chart->setTitle("Urgent Alarms Over Time for '{$filter}'");
        } elseif ($isUrgent) {
            $chart->setTitle('Urgent Alarms Over Time');
        } elseif ($filter) {
            $chart->setTitle("Alarms Over Time for '{$filter}'");
        } else {
            $chart->setTitle('Alarms Over Time');
        }

        // Voeg data toe aan de chart
        foreach ($data as $item) {
            $chart->addPoint($item->event_date, $item->alarm_count);
        }

        return $chart;
    }





    public function getPieChartModel()
    {
        $searchTerm = strtolower(trim($this->searchTerm));
        if ($searchTerm != '' && $this->doesSearchTermExist($searchTerm)) {
            $data = AlarmHistory::whereRaw('LOWER(Message) LIKE ?', ['%' . $searchTerm . '%'])
                ->select('Message', \DB::raw('COUNT(*) as count'))
                ->groupBy('Message')
                ->orderByDesc('count')
                ->get();

            $otherCount = AlarmHistory::whereRaw('LOWER(Message) LIKE ?', ['%' . $searchTerm . '%'])
                ->whereNotIn('Message', $data->pluck('Message'))
                ->select(\DB::raw('COUNT(*) as count'))
                ->value('count');
        } else {
            $data = AlarmHistory::select('Message', \DB::raw('COUNT(*) as count'))
                ->groupBy('Message')
                ->orderByDesc('count')
                ->take(3)
                ->get();

            $otherCount = AlarmHistory::whereNotIn('Message', $data->pluck('Message'))
                ->select(\DB::raw('COUNT(*) as count'))
                ->value('count');
        }

        // Pak filter en urgent-status indien aangegeven
        $filter = request()->query('filter');
        $isUrgent = request()->query('urgent'); // Check of urgent is aangevinkt
        // Begin met de query
        $query = AlarmHistory::select('Message', \DB::raw('COUNT(*) as count'));

        // Voeg urgent-filter toe als urgent is aangevinkt
        if ($isUrgent) {
            $query->where('priority', 'Urgent');
        }

        // Haal data op en groepeer op Message
        $data = $query->groupBy('Message')
                    ->orderByDesc('count')
                    ->take(8)
                    ->get();

        $chart = new PieChartModel();

        // Stel de titel in afhankelijk van de filters
        if ($isUrgent && $filter) {
            $chart->setTitle('Urgent Errors with Filter');
        } elseif ($isUrgent) {
            $chart->setTitle('Urgent Errors');
        } elseif ($filter) {
            $chart->setTitle('Errors with Filter');
        } else {
            $chart->setTitle('Error frequency');
        }

        // Voeg data toe aan de chart
        foreach ($data as $item) {
            $chart->addSlice($item->Message, $item->count, '#' . dechex(rand(0x100000, 0xFFFFFF)));
        }

        return $chart;
    }


        public function render()
        {
            $top3errors = AlarmHistory::select('Message', \DB::raw('COUNT(*) as count'))
                ->groupBy('Message')
                ->orderByDesc('count')
                ->take(10)
                ->pluck('Message')
                ->toArray();

        $columnChartModel = $this->getColumnChartModel();
        $lineChartModel = $this->getLineChartModel();
        $pieChartModel = $this->getPieChartModel();

        return view('livewire.dashboard-component', compact('top3errors', 'columnChartModel', 'lineChartModel', 'pieChartModel'));
    }

}
