<w:p w:rsidR="00B35318" w:rsidRDefault="002524E9" w:rsidP="002524E9">
    <w:pPr>
        <w:rPr>
            <w:lang w:val="en-US" />
        </w:rPr>
    </w:pPr>
    <w:proofErr w:type="spellStart" />
    <w:r>
        <w:rPr>
            <w:lang w:val="en-US" />
        </w:rPr>
        <w:t>{{ $work }}</w:t>
    </w:r>
    <w:proofErr w:type="spellEnd" />
</w:p>
@include('reports.xml.listRow', ['val' => 'Проверка корректности использования редиректов;'])
@include('reports.xml.listRow', ['val' => 'Проверка корректности использования 4** ошибок;'])
@include('reports.xml.listRow', ['val' => 'Поиск ссылок на 404 страницы;'])
@include('reports.xml.listRow', ['val' => 'Поиск ссылок на редиректы;'])
@include('reports.xml.listRow', ['val' => 'Поиск страниц с кодами ответа 3**, 4**;'])
@include('reports.xml.listRow', ['val' => 'Анализ сайта на отказоустойчивость;'])
@include('reports.xml.listRow', ['val' => 'Анализ сайта на скорость загрузки страниц;'])
@include('reports.xml.listRow', ['val' => 'Анализ логов сайта, поиск страниц, которые не посещал робот.'])
@include('reports.xml.listRow', ['val' => 'Проверка наличия дублей мета-тегов title, description на сайте, страниц.'])
@include('reports.xml.listRow', ['val' => 'Проверка наличия орфографических ошибок и опечаток в текстах, title и description;'])
@include('reports.xml.listRow', ['val' => 'Проверка склейки зеркал сайта (с www и без, а также дублей страниц);'])
@include('reports.xml.listRow', ['val' => 'Проверка корректности настройки каноничных страниц для документов с пагинацией.'])

@include('reports.xml.paragraph', ['val' => ''])