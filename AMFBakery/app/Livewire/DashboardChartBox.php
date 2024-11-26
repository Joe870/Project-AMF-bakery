<?php

namespace App\Livewire;

use Livewire\Component;

class DashboardChartBox extends Component
{
    public $errors = ['ERROR 123', 'ERROR 456', 'ERROR 789'];//test errors



    public function render()
    {
        return view('livewire.dashboard-chart-box');
    }
}
