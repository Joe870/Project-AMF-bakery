<?php

namespace App\Livewire;

use Livewire\Component;
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
        return (new LineChartModel())
            ->setTitle('test linaire grafiek')
            ->addPoint('test1', 100)
            ->addPoint('test2', 200)
            ->addPoint('test3', 300);
    }

    public function getPieChartModel(){
        return(new PieChartModel())
            ->setTitle("test Cirkeldiagram")
            ->addSlice('test1', 100, '#f6ad55')
            ->addSlice('test2', 200, '#fc8181')
            ->addSlice('test3', 300, '#90cdf4');
    }


    public function render()
    {
        $columnChartModel = $this->getColumnChartModel();
        $lineChartModel = $this->getLineChartModel();
        $pieChartModel = $this->getPieChartModel();
        return view('livewire.dashboard-component', compact('columnChartModel', 'lineChartModel', 'pieChartModel'));
    }
}
