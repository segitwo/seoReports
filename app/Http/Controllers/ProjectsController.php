<?php

namespace App\Http\Controllers;

use App\Project;
use App\Stats\SERanking;
use App\Stats\YMetric;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::all();
        return view('projects.index')->with('projects', $projects);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $project = new Project();
        $project->name = $request->get('name');
        $project->url = $request->get('url');
        $project->metric = $request->get('metric');
        $project->se_ranking = $request->get('se_ranking');

        $project->save();

        return \Redirect::route('projects.index')->with('message', 'Проект создан!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /*Вместо show перекидываем на edit*/
        return \Redirect::route('projects.edit', [$id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = Project::find($id);

        return view('projects.edit')->with('project', $project);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $project->name = $request->get('name');
        $project->url = $request->get('url');
        $project->metric = $request->get('metric');
        $project->se_ranking = $request->get('se_ranking');

        $project->save();

        return \Redirect::route('projects.index')->with('message', 'Проект обновлен!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Project::destroy($id);

        return \Redirect::route('projects.index')->with('message', 'Проект удален!');
    }

    public function metricsList(){

        //Сайты уже добавленные
        $projectsList = Project::all();

        $projects = [];
        foreach ($projectsList as $project) {
            $projects[] = $project->metric;
        }

        //Сайты из SERanking
        $rankingList = SERanking::getData($params = [
            'method' => 'sites'
        ]);
        $ranking = [];
        foreach ($rankingList as $site) {
            $ranking[$site->name] = $site->id;
        }

        //Сайты из метрики
        $countersList = YMetric::getMetricsList();
        $list = [];
        foreach ($countersList->counters as $counter) {
            if(isset($ranking[$counter->site])) {

                $list[] = [
                    'id' => $counter->id,
                    'name' => $counter->name,
                    'site' => $counter->site,
                    'se_ranking' => $ranking[$counter->site],
                    'added' => (in_array($counter->id, $projects)) ? true : false
                ];
            }


        }

        return view('metric.list')->with('list', $list);
    }
}
