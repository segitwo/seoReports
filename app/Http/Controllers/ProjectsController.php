<?php

namespace App\Http\Controllers;

use App\Note;
use App\Project;
use App\Punycode\idna_convert;
use App\Stats\SERanking;
use App\Stats\YMetric;
use App\Template\Template;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->createNotesForProjects(Project::all());
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
        $project->auto = $request->get('auto', false);
        $project->upload_path = $request->get('upload_path', '');
        $project->start_date = $request->get('start_date');

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
        $templates = Template::all();

        return view('projects.edit')->with('project', $project)->with('templates', $templates->pluck('name', 'id')->toArray());
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
        $project->region = $request->get('region');
        $project->auto = $request->get('auto') ? $request->get('auto') : 0;
        $project->upload_path = $request->get('upload_path');
        $project->template_id = $request->get('template');
        $project->start_date = Carbon::createFromFormat('d-m-Y', $request->get('start_date'));

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

        if(is_array($rankingList) && isset($rankingList->error)){
            return $rankingList->error;
        }

        if(isset($rankingList->message)){
            return $rankingList->message;
        }

        $ranking = [];
        $idn = new idna_convert();
        foreach ($rankingList as $site) {
            $url = (stripos($site->name, 'xn--')!==false) ? $idn->decode($site->name) : $site->name;
            $url = str_replace(['http://', '/'], '', $url);
            $ranking[$url] = $site->id;
        }

        //Сайты из метрики
        $countersList = YMetric::getMetricsList();
        $list = [];
        foreach ($countersList->counters as $counter) {
            if(isset($ranking[$counter->site]) && !in_array($counter->id, $projects)) {

                $list[] = [
                    'id' => $counter->id,
                    'name' => $counter->name,
                    'site' => $counter->site,
                    'se_ranking' => $ranking[$counter->site]
                ];
            }
        }

        return view('metric.list')->with('list', $list);
    }

    private function createNotesForProjects($projects){

        foreach ($projects as $project) {
            if(!count($project->note)){
                $note = new Note();
                $project->note()->save($note);
            }
        }
    }
}
