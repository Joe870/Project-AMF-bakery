<?php

namespace App\Livewire;

use Livewire\Component;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;
use Asantibanez\LivewireCharts\Models\lineChartModel;
use Asantibanez\LivewireCharts\Models\PieChartModel;
use App\Models\AlarmHistory;

class DashboardComponent extends Component
{
    public $searchTerm = '';
    public $errorMessage = '';
    public $errors = []; // Store filtered errors for rendering

    public function search()
    {
        $this->errorMessage = '';
        $this->errors = [];

        if (empty($this->searchTerm)) {
            $this->errorMessage = 'Please enter a search term.';
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
        $data = AlarmHistory::select(\DB::raw('DATE(EventTime) as event_date'), \DB::raw('COUNT(*) as alarm_count'))
            ->groupBy('event_date')
            ->orderBy('event_date')
            ->get();

        $chart = new ColumnChartModel();
        $chart->setTitle('Alarms Over Time');

        foreach ($data as $item) {
            $chart->addColumn((string) $item->event_date, (int) $item->alarm_count, '#' . dechex(rand(0x100000, 0xFFFFFF)));
        }

        return $chart;
    }

public function doesSearchTermExist($searchTerm)
{
    // Check if the search term exists in the database
    return AlarmHistory::whereRaw('LOWER(Message) LIKE ?', ['%' . $searchTerm . '%'])->exists();
}

    public function getLineChartModel()
    {
        $searchTerm = strtolower(trim($this->searchTerm));

        if ($searchTerm != '' && $this->doesSearchTermExist($searchTerm)) {
            $data = AlarmHistory::whereRaw('LOWER(Message) LIKE ?', ['%' . $searchTerm . '%'])
                ->select('Message', \DB::raw('COUNT(*) as count'))
                ->groupBy('Message')
                ->orderByDesc('count')
                ->get();
        } else {
            $data = AlarmHistory::select('Message', \DB::raw('COUNT(*) as count'))
                ->groupBy('Message')
                ->orderByDesc('count')
                ->take(10)
                ->get();
        }

        $chart = new LineChartModel();

        $counter = 1;
        foreach ($data as $item) {
            $shortLabel = strlen($item->Message) > 15 ? substr($item->Message, 0, 12) . '...' : $item->Message;

            $chart->addPoint($shortLabel, (int) $item->count, [
                'tooltip' => $item->Message . ': ' . $item->count . ' times',
            ]);

            $counter++;
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
