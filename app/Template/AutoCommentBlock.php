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

        $comment .= $autoText->getNoteText($requestData);

        if(!empty($requestData['next_work'])){
            $comment .= $autoText->getNextWorkText($requestData);
        }

        if (!empty($requestData['period']) && !($requestData['period']%4)) {
            $comment .= view('reports.xml.paragraph', ['val' => __('text.every_forth_month')])->render();
        }

        return view('reports.xml.block.autoComment', ['comment' => $comment])->render();
    }
}
