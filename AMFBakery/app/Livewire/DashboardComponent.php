<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AlarmHistory;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;
use Asantibanez\LivewireCharts\Models\LineChartModel;
use Asantibanez\LivewireCharts\Models\PieChartModel;
//use App\Livewire\DashboardComponent;

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


        $data = AlarmHistory::select(\DB::raw('DATE(EventTime) as event_date'), \DB::raw('COUNT(*) as alarm_count'))
            ->groupBy('event_date')
            ->orderBy('event_date')
            ->get();

        $chart = new ColumnChartModel();
        $chart->setTitle('Alarms Over Time');

        foreach ($data as $item) {
            $chart->addColumn((string) $item->event_date, (int) $item->alarm_count, '#'.dechex(rand(0x100000, 0xFFFFFF)));
        }
        return $chart;

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
        // Fetch the three most common errors and count the rest as "Other"
        $data = AlarmHistory::select('Message', \DB::raw('COUNT(*) as count'))
            ->groupBy('Message')
            ->orderByDesc('count')
            ->take(3) // Get the top 3 most common errors
            ->get();

        // Count the rest as "Other"
        $otherCount = AlarmHistory::select(\DB::raw('COUNT(*) as count'))
            ->whereNotIn('Message', $data->pluck('Message'))
            ->value('count');

        // Initialize the pie chart
        $chart = new PieChartModel();
        $chart->setTitle('Top 3 Errors with Others');

        // Add slices for the top 3 errors
        foreach ($data as $item) {
            $chart->addSlice($item->Message, $item->count, '#'.dechex(rand(0x100000, 0xFFFFFF))); // Random colors
        }

        // Add a slice for "Other" if there are remaining errors
        if ($otherCount > 0) {
            $chart->addSlice('Other', $otherCount, '#cccccc'); // Gray color for "Other"
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
