<?php

namespace App\Http\Controllers;

use App\ClassMap;
use App\Template\{Template, TemplateBlock, TotalVisitsBlock};

use Illuminate\Http\Request;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $templates = Template::all();
        return view('templates.index')->with('templates', $templates);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('templates.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //Создаем шаблон
        $template = new Template();
        $template->name = $request->get('name');
        $template->save();

        //Список блоков выбранных для шаблона
        $blocks = $request->get('blocks');

        if(count($blocks)){
            //Значение это название класса, ключ это порядок в котором расположили блок
            foreach ($blocks as $index => $blockClassName) {
                $className = 'App\Template\\' . $blockClassName;
                if(class_exists($className)){
                    //создаем "абстрактный" блок и присоединяем к шаблону
                    $baseBlock = new TemplateBlock();
                    $baseBlock->sortIndex = $index;
                    $baseBlock->class_key = $blockClassName;
                    $template->blocks()->save($baseBlock);
                    //Создаем блок выбранного класса и присоединяем к "абстрактному"
                    $block = new $className;
                    $baseBlock->hasOne($className)->save($block);
                }
            }
        }

        return \Redirect::route('templates.index')->with('message', 'Шаблон создан!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $template = Template::find($id);
        //return
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $template = Template::find($id);
        $blocks = ClassMap::where('parent_class', 'TemplateBlock')->get();


        dd($blocks->diff($template->blocks)->all());
        foreach ($template->blocks() as $block) {
            if($blocks){

            }
            $block->class_key;
        }

        return view('templates.edit')->with('template', $template)->with('blocks', $blocks);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
