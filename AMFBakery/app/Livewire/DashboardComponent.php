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

    public function search()
    {
        $this->errorMessage = '';
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

    public function getColumnChartModel()
    {
        $filter = request()->query('filter');

        if ($filter){
            $data = AlarmHistory::select('Message', \DB::raw('COUNT(*) as count'))
                ->where('Message', 'LIKE', "%{$filter}%")
                ->groupBy('Message')
                ->orderByDesc('count')
                ->get();

                $chart = new LineChartModel();
                $chart->setTitle('Alarms Over Time from {filter}');
        } else
            $data = AlarmHistory::select('Message', \DB::raw('COUNT(*) as count'))
                ->groupBy('Message')
                ->orderByDesc('count')
                ->get();

            $chart = new ColumnChartModel();
            $chart->setTitle('Errors with priority');

        foreach ($data as $item) {
            $chart->addColumn($item->Message, $item->count, '#' . dechex(rand(0x100000, 0xFFFFFF)));
        }

        if (isset($otherCount) && $otherCount > 0) {
            $chart->addColumn('Other', $otherCount, '#cccccc');
        }

        return $chart;
    }

    public function getLineChartModel()
    {
        $filter = request()->query('filter');

        if ($filter) {
            //Select de data die aan de filter voldoet
            $data = AlarmHistory::select('Message', \DB::raw('COUNT(*) as count'))
                ->where('Message', 'LIKE', "%{$filter}%")
                ->groupBy('Message')
                ->orderByDesc('count')
                ->get();

                $chart = new LineChartModel();
                $chart->setTitle('Alarms Over Time from {filter}');//Verbeter zodat de $filter goed word gelaten zien

        } else {
            //Select algemene data [Bespreek nog wat de standaard setting voor deze chart word]
            $data = AlarmHistory::select(\DB::raw('DATE(EventTime) as event_date'), \DB::raw('COUNT(*) as alarm_count'))
            ->groupBy('event_date')
            ->orderBy('event_date')
            ->get();

            $chart = new LineChartModel();
            $chart->setTitle('Alarms Over Time');
        }

        foreach ($data as $item) {
            $chart->addPoint($item->event_date, $item->alarm_count);
        }

        return $chart;
    }




    public function getPieChartModel()
    {
        $searchTerm = strtolower(trim($this->searchTerm));

        if ($searchTerm != '') {
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

        $chart = new PieChartModel();
        $chart->setTitle('Top 3 Errors with Others');

        foreach ($data as $item) {
            $chart->addSlice($item->Message, $item->count, '#' . dechex(rand(0x100000, 0xFFFFFF)));
        }

        if ($otherCount > 0) {
            $chart->addSlice('Other', $otherCount, '#cccccc');
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












