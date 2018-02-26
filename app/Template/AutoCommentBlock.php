<?php

namespace App\Template;

use App\Stats\AutoText;
use Carbon\Carbon;

class AutoCommentBlock extends TemplateBlockExtension
{
    public function listProperties()
    {
        return [];
    }

    public function getData($requestData, $reportId)
    {
        parent::getData($requestData, $reportId);

        $comment = "";

        $autoText = new AutoText($this->today);

        if (!empty($requestData['period'])) {
            $comment .= $autoText->getAutoText('', $requestData);
        }

        if(!empty($requestData['dop_work'])){
            $comment .= $autoText->getAutoText("dop", $requestData);
        }

        if(!empty($requestData['support'])){
            $comment .= $autoText->getSupportText($requestData['support'], $requestData['support_text']);
        }

        if(!empty($requestData['next_work'])){
            $comment .= $autoText->getNextWorkText($requestData);
        }

        $comment .= $autoText->getNoteText($requestData);

        if (!empty($requestData['period']) && !($requestData['period']%4)) {
            $comment .= view('reports.xml.paragraph', ['val' => 'Каждый месяц мы продолжаем вести постоянный мониторинг сайта на наличие технических ошибок, вирусов, взломов, нарушений и сбоев, наличие дублей и ошибок сканирования. Проводится периодическая проверка позиций сайта, анализ изменений в выдаче и внесение соответствующих корректировок.'])->render();
        }

        return view('reports.xml.block.autoComment', ['comment' => $comment])->render();
    }
}
