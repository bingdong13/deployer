<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Project;
use App\Deployment;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * The main page of the dashboard
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get the latest 15 deployments
        $deployments = Deployment::take(15)
                                 ->orderBy('started_at', 'DESC')
                                 ->get();

        $grouped_by_date = [];
        foreach ($deployments as $deployment) {
            $date = $deployment->started_at->format('Y-m-d');

            if (!isset($grouped_by_date[$date])) {
                $grouped_by_date[$date] = [];
            }

            $grouped_by_date[$date][] = $deployment;
        }

        return view('dashboard.index', [
            'title'    => 'Dashboard',
            'latest'   => $grouped_by_date,
            'projects' => Project::orderBy('name')->get()
        ]);
    }
}
