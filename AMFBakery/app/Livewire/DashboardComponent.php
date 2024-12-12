<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AlarmHistory;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;
use Asantibanez\LivewireCharts\Models\lineChartModel;
use Asantibanez\LivewireCharts\Models\PieChartModel;
use App\Livewire\DashboardComponent;

class DashboardComponent extends Component
{
    public $errors = ['ERROR 123', 'ERROR 456', 'ERROR 789']; 


    public function redirectToChart($chartType)
    {
        // De juiste route bepalen op basis van het grafiektype
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
        return (new ColumnChartModel())
            ->setTitle('test staafdiagram')
            ->addColumn('test1', 100, '#f6ad55')
            ->addColumn('test2', 200, '#fc8181')
            ->addColumn('test3', 300, '#90cdf4');
    }

    public function getLineChartModel()
    {
        $data = AlarmHistory::select(\DB::raw('DATE(EventTime) as event_date'), \DB::raw('COUNT(*) as alarm_count'))
            ->groupBy('event_date')
            ->orderBy('event_date')
            ->get();

        $chart = new LineChartModel();
        $chart->setTitle('Alarms Over Time');

        foreach ($data as $item) {
            $chart->addPoint($item->event_date, $item->alarm_count);
        }

        return $chart;
    }

    public function getPieChartModel()
    {
        $filter = request()->query('filter');

        //Als er een filter is aangegeven pak alle errors met die filter
        if ($filter) {
            $data = AlarmHistory::select('Message', \DB::raw('COUNT(*) as count'))
                ->where('Message', 'LIKE', "%{$filter}%")
                ->groupBy('Message')
                ->orderByDesc('count')
                ->get();
        } else {
            $data = AlarmHistory::select('Message', \DB::raw('COUNT(*) as count'))
                ->groupBy('Message')
                ->orderByDesc('count')
                ->take(3)
                ->get();

            $otherCount = AlarmHistory::select(\DB::raw('COUNT(*) as count'))
                ->whereNotIn('Message', $data->pluck('Message'))
                ->value('count');
        }

        // Maak de PieChart
        $chart = new PieChartModel();
        $chart->setTitle('Errors with {$filter}');

        foreach ($data as $item) {
            $chart->addSlice($item->Message, $item->count, '#' . dechex(rand(0x100000, 0xFFFFFF)));
        }

        if (isset($otherCount) && $otherCount > 0) {
            $chart->addSlice('Other', $otherCount, '#cccccc');
        }

        return $chart;
    }



    public function render()
    {
        $columnChartModel = $this->getColumnChartModel();
        $lineChartModel = $this->getLineChartModel();
        $pieChartModel = $this->getPieChartModel();
        return view('livewire.dashboard-component', compact('columnChartModel', 'lineChartModel', 'pieChartModel'));
    }
}