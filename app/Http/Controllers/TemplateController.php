<?php

namespace App\Http\Controllers;

use App\ClassMap;
use App\Http\Requests\TemplateFormRequest;
use App\Template\{
    Template, TemplateBlock, TemplateBlockExtension, TotalVisitsBlock, SourcesSummary
};

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

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

        //Получаем из таблицы классов коллекцию классов являющихся блоками шаблона
        $blocks = ClassMap::where('parent_class', '=', 'TemplateBlock')->get();

        //Теперь нужно сформировать коллекцию блоков шаблона для вывода в представление
        $outputBlocks = [];
        foreach ($blocks as $block) {
            //Создаем объект класса для доступа к его свойствам
            $class = 'App\Template\\' . $block->name;
            $blockItem = new $class;
            $blockItem->added = false;

            $outputBlocks[] = $blockItem;
        }


        return view('templates.create')->with('blocks', $outputBlocks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TemplateFormRequest $request)
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
                $this->createTemplateBlock($template, $blockClassName, ['sortIndex' => $index]);
            }
        }

        return \Redirect::route('template.index')->with('message', 'Шаблон создан!');
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
        //Шаблон с которым работаем
        $template = Template::find($id);

        //Получаем из таблицы классов коллекцию классов являющихся блоками шаблона
        $blocks = ClassMap::where('parent_class', '=', 'TemplateBlock')->get();

        //Теперь нужно сформировать коллекцию блоков шаблона для вывода в представление
        $outputBlocks = [];
        foreach ($blocks as $block) {
            //Проходим по всем классам и проверяем добавлен ли блок этого класса в шаблон
            if($template->blocks->contains('class_key', '=', $block->name)){
                //Если добавлен то вытаскиваем этот объект и помечаем что он уже добавлен
                $blockObject = $template->blocks->filter(function($item) use ($block) {
                    return $item->class_key == $block->name;
                })->first();

                $blockItem = $blockObject->getOne($block->name)->first();

                //Вытаскиваем из родительского блока значение sortIndex чтобы по нему сортировать
                $blockItem->sortIndex = $blockItem->templateBlock->sortIndex;
                $blockItem->added = true;
            } else {
                //Иначе создаем объект класса для доступа к его свойствам
                $class = 'App\Template\\' . $block->name;
                $blockItem = new $class;
                $blockItem->added = false;
            }

            $outputBlocks[] = $blockItem;
        }

        return view('templates.edit')->with('template', $template)->with('blocks', $outputBlocks);

        //dd((new $class)->getProperties());

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TemplateFormRequest $request, $id)
    {
        //Шаблон
        $template = Template::findOrFail($id);
        $template->name = $request->get('name');
        $template->save();
        //Список блоков выбранных для шаблона
        $blocks = $request->get('blocks');

        //Массив классов блоков, которые уже добавлены в шаблон.
        $actualBlocks = $template->blocks->pluck('class_key')->all();
        //Блоки которые нужно будет удалить
        $blocksToDelete = array_diff($actualBlocks, $blocks);
        if (count($blocksToDelete)) {
            foreach ($blocksToDelete as $blockClassName) {
                $template->blocks->filter(function($item) use ($blockClassName) {
                    return $item->class_key == $blockClassName;
                })->first()->delete();
            }
        }

        if(count($blocks)){
            foreach ($blocks as $index => $blockClassName) {
                //Проверяем есть ли среди отправленных блоков уже добавленные к шаблону
                if(in_array($blockClassName, $actualBlocks)){
                    //Если есть - вытаскиваем его и прописываем новый sortIndex
                    $block = $template->blocks->filter(function($item) use ($blockClassName) {
                        return $item->class_key == $blockClassName;
                    })->first();
                    $block->sortIndex = $index;

                    //Если есть настройки для блока - записываем
                    if($blockProperties = $request->get($blockClassName)){
                        //Получаем объект расширяющего класса
                        $blockClassNameObject = $block->getOne($blockClassName)->first();
                        $this->storeBlockSettings($blockClassNameObject, $blockProperties);
                    }
                    $block->save();

                //Проверяем отсутствует ли среди отправленных блоков
                } else {
                    //Если нет - то создаем
                    $blockClassNameObject = $this->createTemplateBlock($template, $blockClassName, ['sortIndex' => $index]);
                    //Если есть настройки для блоков то отправляем на запись
                    //dump($blockClassNameObject, $blockClassName, $request->all(), $request->get($blockClassName));
                    if($blockClassNameObject && $blockProperties = $request->get($blockClassName)){
                        $this->storeBlockSettings($blockClassNameObject, $blockProperties);
                    }
                }
            }
        }

        return \Redirect::route('template.edit', ['id' => $id])->with('message', 'Шаблон обновлен!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Template::destroy($id);

        return \Redirect::route('template.index')->with('message', 'Шаблон удален!');
    }

    //Метод для записи параметров блока
    private function storeBlockSettings(TemplateBlockExtension $block, $properties){

        foreach ($properties as $property => $value) {
            if(Schema::hasColumn($block->getTable(), $property)){
                $block->setAttribute($property, $value);
            }
        }
        $block->save();
    }

    //Метод для создания блока шаблона
    private function createTemplateBlock($template, $blockClassName, $attributes){

        $className = 'App\Template\\' . $blockClassName;

        if(class_exists($className)){
            //создаем "абстрактный" блок и присоединяем к шаблону
            $baseBlock = new TemplateBlock();
            $baseBlock->sortIndex = $attributes['sortIndex'];
            $baseBlock->class_key = $blockClassName;
            $template->blocks()->save($baseBlock);
            //Создаем блок выбранного класса и присоединяем к "абстрактному"
            $block = new $className;

            $particularBlock = $baseBlock->hasOne($className)->save($block);
            if($particularBlock){
                $block->save();
                return $particularBlock;
            }

        }

        return false;
    }
}
