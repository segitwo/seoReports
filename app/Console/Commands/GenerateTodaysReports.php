<?php

namespace App\Console\Commands;

use App\Project;
use App\Report\Report;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Laravelrus\LocalizedCarbon\LocalizedCarbon;

class GenerateTodaysReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload todays reports to dropbox';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $today = Carbon::today();

        //$projects = Project::where('auto', 1)->where('report_day', intval($today->format('d')))->get();

        $projects = Project::whereDay('start_date',  '=', intval($today->format('d')))->where('auto', 1)->get();

        //SELECT DAYOFMONTH(`start_date`) as `start_date` FROM `projects` WHERE DAYOFMONTH(`start_date`) = 22

        $report = new Report();
        foreach ($projects as $project) {

            $period = Carbon::parse($project->start_date)->modify('-1 day')->diffInMonths($today);
            if(!$period){
                continue;
            }

            $months = [
                "January" => "Январь",
                "February" => "Февраль",
                "March" => "Март",
                "April" => "Апрель",
                "May" => "Май",
                "June" => "Июнь",
                "July" => "Июль",
                "August" => "Август",
                "September" => "Сентябрь",
                "October" => "Октябрь",
                "November" => "Ноябрь",
                "December" => "Декабрь",
            ];


            if(intval($today->format('d')) < 15){
                $monthName = Carbon::today()->modify('-1 month')->format('F');
            } else {
                $monthName = $today->format('F');
            }

            $docname = $today->format('d.m.Y') . ' (' . $months[$monthName] . ' ' . $today->format('Y') . ')';
            //$this->info($docname);

            $report->upload([
                'id' => $project->id,
                'siteid' => $project->metric,
                'se_ranking' => $project->se_ranking,
                'sitename' => $project->name,
                'date' => $today->format('d-m-Y'),
                'regionName' => $project->region,
                'doc_name' => $docname,
                'period' => $period,
                'next_work' => $period + 1
            ], $project->upload_path . '/' . $today->format('Y'));
        }


    }
}
