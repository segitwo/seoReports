<?php

namespace App\Template;

use App\Report\Chart;
use App\Report\XMLBuilder;
use App\Stats\SERanking;
use Carbon\Carbon;

class AveragePositionsBlock extends TemplateBlockExtension
{
    public function listProperties()
    {
        return [
            'hide_if_reduce' => [
                'type' => 'select',
                'value' => [
                    '0' => __('templates.AveragePositions_0'),
                    '1' => __('templates.AveragePositions_1'),
                    '2' => __('templates.AveragePositions_2')
                ]
            ]
        ];
    }

    public function getData($requestData, $reportId)
    {
        parent::getData($requestData, $reportId);


        $relsPath = app_path('Stats/generated/' . $reportId . '/word/_rels/document.xml.rels');

        if (!file_exists($relsPath)) {
            exit('Не удалось открыть файл ' . $relsPath);
        }

        $rels = simplexml_load_file($relsPath);

        //Статистика по запросам
        $keyWordsStat = SERanking::getData($params = [
            'method' => 'stat',
            'data' => [
                'siteid' => $this->rankingKey,
                'dateStart' => $this->prevDay->format('Y-m-d'),
                'dateEnd' => $this->today->format('Y-m-d'),
            ]
        ]);

        $lines = [];
        $positionsRow = [];
        foreach ($keyWordsStat as $key => $stat) {

            if ($stat->seID == 411) {
                $key = 'Яндекс';
                //} elseif(in_array($stat->seID, [474, 339])) {
            } else {
                $key = 'Google';
            }

            if (isset($key)) {
                $region = $stat->region_name;

                //название региона яндекса SE Ranking не передает, но передает id региона, поэтому тянем так.
                if ($key == 'Яндекс') {

                    $region = $this->getRegion($stat->regionID);
                }

                foreach ($stat->keywords as $keyword) {
                    foreach ($keyword->positions as $k => $position) {
                        $positionsRow[$k]['date'] = $position->date;
                        $position = intval($position->pos) > 0 ? intval($position->pos) : 100;

                        if (isset($positionsRow[$k]['pos'])) {
                            $positionsRow[$k]['pos'] += $position;
                        } else {
                            $positionsRow[$k]['pos'] = $position;
                        }

                    }
                }

                foreach ($positionsRow as $positionsRowData) {
                    $region_key = (isset($region)) ? $key . ' (' . $region . ')' : $key;
                    $lines[$region_key]['charts'][] = $positionsRowData['pos'] / count($stat->keywords);
                    $lines[$region_key]['se'] = $key;
                }
            }

        }

        $averageChart = [];
        foreach ($lines as $key => $line) {
            $firstPosition = reset($line['charts']);
            $lastPosition = end($line['charts']);

            //если рост менее 10%, то не показываем график
            if (($firstPosition * 0.9) < $lastPosition && $requestData['templateBlock']->hide_if_reduce) {
                //если роста нет, но есть пометка показывать запросы в ТОП10 в любом случае, то не обраываем генерацию
                if(!($requestData['templateBlock']->hide_if_reduce == 2 && $lastPosition <= 10)){
                    continue;
                }
            }

            //id для названия картинки
            $imageId = random_int(1000, 9999);

            //создаем график с именем $imageId . '.png'
            $chart = new Chart();
            $axis = $chart->makeAxis($positionsRow);
            $data = $chart->makeLineChartData([$key => $line['charts']], $axis);

            $chart->makeLineChartNegativeDisplay([$key => $line['charts']], $data, $imageId . '.png', $reportId);

            //Создаем в document.xml.rels отношение где привязываем изображение с графиком идентийкатору.
            $xmlId = 'rId' . $imageId;
            $relationshipXML = new \SimpleXMLElement('<Relationship/>');
            $relationshipXML->addAttribute('Type', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/image');
            $relationshipXML->addAttribute('Id', $xmlId);
            $relationshipXML->addAttribute('Target', 'media/' . $imageId . '.png');

            XMLBuilder::sxml_append($rels, $relationshipXML);
            $rels->asXml($relsPath);

            //Добавляем график
            $seKey = $line['se'] == 'Яндекс' ? 'rId31' : 'rId33';


            $averageChart[] = ['searchEngine' => $key, 'seKey' => $seKey, 'chartId' => $xmlId];
        }

        return view('reports.xml.chart.averageChart', ['averageCharts' => $averageChart]);

    }

    private function getRegion($id)
    {
        $regions = [
            0 => 'Регионы',
            1 => 'Москва и область',
            2 => 'Санкт-Петербург',
            3 => 'Центр',
            4 => 'Белгород',
            5 => 'Иваново',
            6 => 'Калуга',
            7 => 'Кострома',
            8 => 'Курск',
            9 => 'Липецк',
            10 => 'Орел',
            11 => 'Рязань',
            12 => 'Смоленск',
            13 => 'Тамбов',
            14 => 'Тверь',
            15 => 'Тула',
            16 => 'Ярославль',
            17 => 'Северо-Запад',
            18 => 'Петрозаводск',
            19 => 'Сыктывкар',
            20 => 'Архангельск',
            21 => 'Вологда',
            22 => 'Калининград',
            23 => 'Мурманск',
            24 => 'Великий Новгород',
            25 => 'Псков',
            26 => 'Юг',
            28 => 'Махачкала',
            30 => 'Нальчик',
            33 => 'Владикавказ',
            35 => 'Краснодар',
            36 => 'Ставрополь',
            37 => 'Астрахань',
            38 => 'Волгоград',
            39 => 'Ростов-на-Дону',
            40 => 'Поволжье',
            41 => 'Йошкар-Ола',
            42 => 'Саранск',
            43 => 'Казань',
            44 => 'Ижевск',
            45 => 'Чебоксары',
            46 => 'Киров',
            47 => 'Нижний Новгород',
            48 => 'Оренбург',
            49 => 'Пенза',
            50 => 'Пермь',
            51 => 'Самара',
            52 => 'Урал',
            53 => 'Курган',
            54 => 'Екатеринбург',
            55 => 'Тюмень',
            56 => 'Челябинск',
            57 => 'Ханты-Мансийск',
            58 => 'Салехард',
            59 => 'Сибирь',
            62 => 'Красноярск',
            63 => 'Иркутск',
            64 => 'Кемерово',
            65 => 'Новосибирск',
            66 => 'Омск',
            67 => 'Томск',
            68 => 'Чита',
            73 => 'Дальний Восток',
            74 => 'Якутск',
            75 => 'Владивосток',
            76 => 'Хабаровск',
            77 => 'Благовещенск',
            78 => 'Петропавловск-Камчатский',
            79 => 'Магадан',
            80 => 'Южно-Сахалинск',
            84 => 'США',
            86 => 'Атланта',
            87 => 'Вашингтон',
            89 => 'Детройт',
            90 => 'Сан-Франциско',
            91 => 'Сиэтл',
            93 => 'Аргентина',
            94 => 'Бразилия',
            95 => 'Канада',
            96 => 'Германия',
            97 => 'Гейдельберг',
            98 => 'Кельн',
            99 => 'Мюнхен',
            100 => 'Франкфурт-на-Майне',
            101 => 'Штутгарт',
            102 => 'Великобритания',
            111 => 'Европа',
            113 => 'Австрия',
            114 => 'Бельгия',
            115 => 'Болгария',
            116 => 'Венгрия',
            117 => 'Литва',
            118 => 'Нидерланды',
            119 => 'Норвегия',
            120 => 'Польша',
            121 => 'Словакия',
            122 => 'Словения',
            123 => 'Финляндия',
            124 => 'Франция',
            125 => 'Чехия',
            126 => 'Швейцария',
            127 => 'Швеция',
            129 => 'Беэр-Шева',
            130 => 'Иерусалим',
            131 => 'Тель-Авив',
            132 => 'Хайфа',
            134 => 'Китай',
            135 => 'Корея',
            137 => 'Япония',
            138 => 'Австралия и Океания',
            139 => 'Новая Зеландия',
            141 => 'Днепр',
            142 => 'Донецк',
            143 => 'Киев',
            144 => 'Львов',
            145 => 'Одесса',
            146 => 'Симферополь',
            147 => 'Харьков',
            148 => 'Николаев',
            149 => 'Беларусь',
            153 => 'Брест',
            154 => 'Витебск',
            155 => 'Гомель',
            157 => 'Минск',
            158 => 'Могилев',
            159 => 'Казахстан',
            162 => 'Алматы',
            163 => 'Астана',
            164 => 'Караганда',
            165 => 'Семей',
            166 => 'СНГ',
            167 => 'Азербайджан',
            168 => 'Армения',
            169 => 'Грузия',
            170 => 'Туркмения',
            171 => 'Узбекистан',
            172 => 'Уфа',
            177 => 'Берлин',
            178 => 'Гамбург',
            179 => 'Эстония',
            180 => 'Сербия',
            181 => 'Израиль',
            183 => 'Азия',
            187 => 'Украина',
            190 => 'Павлодар',
            191 => 'Брянск',
            192 => 'Владимир',
            193 => 'Воронеж',
            194 => 'Саратов',
            195 => 'Ульяновск',
            197 => 'Барнаул',
            198 => 'Улан-Удэ',
            200 => 'Лос-Анджелес',
            202 => 'Нью-Йорк',
            203 => 'Дания',
            204 => 'Испания',
            205 => 'Италия',
            206 => 'Латвия',
            207 => 'Киргизия',
            208 => 'Молдова',
            209 => 'Таджикистан',
            210 => 'Объединенные Арабские Эмираты',
            211 => 'Австралия',
            213 => 'Москва',
            214 => 'Долгопрудный',
            215 => 'Дубна',
            217 => 'Пущино',
            219 => 'Черноголовка',
            221 => 'Чимкент',
            222 => 'Луганск',
            223 => 'Бостон',
            225 => 'Россия',
            235 => 'Магнитогорск',
            236 => 'Набережные Челны',
            237 => 'Новокузнецк',
            238 => 'Новочеркасск',
            239 => 'Сочи',
            240 => 'Тольятти',
            241 => 'Африка',
            245 => 'Арктика и Антарктика',
            246 => 'Греция',
            318 => 'Универсальное',
            349 => 'Москва и область/Другие города региона',
            350 => 'Москва и область/Универсальное',
            381 => 'Россия/Прочее',
            382 => 'Россия/Общероссийские',
            413 => 'Центр/Другие города региона',
            414 => 'Центр/Универсальное',
            445 => 'Северо-Запад/Другие города региона',
            446 => 'Северо-Запад/Универсальное',
            477 => 'Юг/Другие города региона',
            478 => 'Юг/Универсальное',
            509 => 'Поволжье/Другие города региона',
            510 => 'Поволжье/Универсальное',
            541 => 'Урал/Другие города региона',
            542 => 'Урал/Универсальное',
            573 => 'Сибирь/Другие города региона',
            574 => 'Сибирь/Универсальное',
            605 => 'Дальний Восток/Другие города региона',
            606 => 'Дальний Восток/Универсальное',
            637 => 'США/Прочее',
            638 => 'США/Универсальное',
            669 => 'Прочее',
            670 => 'Универсальное',
            701 => 'Германия/Прочее',
            702 => 'Германия/Универсальное',
            733 => 'Европа/Прочее',
            734 => 'Европа/Универсальное',
            765 => 'Израиль/Прочее',
            766 => 'Израиль/Универсальное',
            797 => 'Азия/Прочее',
            798 => 'Азия/Универсальное',
            829 => 'Австралия и Океания/Прочее',
            830 => 'Австралия и Океания/Универсальное',
            861 => 'Украина/Прочее',
            862 => 'Украина/Универсальное',
            893 => 'Беларусь/Прочее',
            894 => 'Беларусь/Универсальное',
            925 => 'Казахстан/Прочее',
            926 => 'Казахстан/Универсальное',
            957 => 'СНГ/Прочее',
            958 => 'СНГ/Универсальное',
            959 => 'Севастополь',
            960 => 'Запорожье',
            961 => 'Хмельницкий',
            962 => 'Херсон',
            963 => 'Винница',
            964 => 'Полтава',
            965 => 'Сумы',
            966 => 'Чернигов',
            967 => 'Обнинск',
            968 => 'Череповец',
            969 => 'Выборг',
            970 => 'Новороссийск',
            971 => 'Таганрог',
            972 => 'Дзержинск',
            973 => 'Сургут',
            974 => 'Находка',
            975 => 'Бийск',
            976 => 'Братск',
            977 => 'Крым',
            978 => 'Крым/Другие города региона',
            979 => 'Крым/Универсальное',
            983 => 'Турция',
            994 => 'Индия',
            995 => 'Таиланд',
            1048 => 'Канада/Прочее',
            1049 => 'Канада/Универсальное',
            1056 => 'Египет',
            1058 => 'Туапсе',
            1091 => 'Нижневартовск',
            1092 => 'Назрань',
            1093 => 'Майкоп',
            1094 => 'Элиста',
            1095 => 'Абакан',
            1104 => 'Черкесск',
            1106 => 'Грозный',
            1107 => 'Анапа',
            10002 => 'Северная Америка',
            10003 => 'Южная Америка',
            10069 => 'Мальта',
            10083 => 'Хорватия',
            10174 => 'Санкт-Петербург и Ленинградская область',
            10176 => 'Ненецкий АО',
            10231 => 'Республика Алтай',
            10233 => 'Республика Тыва',
            10243 => 'Еврейская автономная область',
            10251 => 'Чукотский автономный округ',
            10274 => 'Гродно',
            10303 => 'Талдыкорган',
            10306 => 'Усть-Каменогорск',
            10313 => 'Кишинев',
            10314 => 'Бельцы',
            10315 => 'Бендеры',
            10317 => 'Тирасполь',
            10343 => 'Житомир',
            10345 => 'Ивано-Франковск',
            10347 => 'Кривой Рог',
            10355 => 'Ровно',
            10357 => 'Тернополь',
            10358 => 'Ужгород',
            10363 => 'Черкассы',
            10365 => 'Черновцы',
            10366 => 'Мариуполь',
            10367 => 'Мелитополь',
            10369 => 'Белая Церковь',
            10645 => 'Белгородская область',
            10649 => 'Старый Оскол',
            10650 => 'Брянская область',
            10656 => 'Александров',
            10658 => 'Владимирcкая область',
            10661 => 'Гусь-Хрустальный',
            10664 => 'Ковров',
            10668 => 'Муром',
            10671 => 'Суздаль',
            10672 => 'Воронежcкая область',
            10687 => 'Ивановская область',
            10693 => 'Калужская область',
            10699 => 'Костромская область',
            10705 => 'Курская область',
            10712 => 'Липецкая область',
            10716 => 'Балашиха',
            10719 => 'Видное',
            10723 => 'Дмитров',
            10725 => 'Домодедово',
            10733 => 'Клин',
            10734 => 'Коломна',
            10735 => 'Красногорск',
            10738 => 'Люберцы',
            10740 => 'Мытищи',
            10742 => 'Ногинск',
            10743 => 'Одинцово',
            10745 => 'Орехово-Зуево',
            10746 => 'Павловский Посад',
            10747 => 'Подольск',
            10748 => 'Пушкино',
            10750 => 'Раменское',
            10752 => 'Сергиев Посад',
            10754 => 'Серпухов',
            10755 => 'Солнечногорск',
            10756 => 'Ступино',
            10758 => 'Химки',
            10761 => 'Чехов',
            10765 => 'Щелково',
            10772 => 'Орловская область',
            10776 => 'Рязанская область',
            10795 => 'Смоленская область',
            10802 => 'Тамбовская область',
            10819 => 'Тверская область',
            10820 => 'Ржев',
            10830 => 'Новомосковск',
            10832 => 'Тульская область',
            10837 => 'Переславль',
            10838 => 'Ростов',
            10839 => 'Рыбинск',
            10840 => 'Углич',
            10841 => 'Ярославская область',
            10842 => 'Архангельская область',
            10849 => 'Северодвинск',
            10853 => 'Вологодская область',
            10857 => 'Калининградская область',
            10867 => 'Гатчина',
            10894 => 'Апатиты',
            10897 => 'Мурманская область',
            10904 => 'Новгородская область',
            10926 => 'Псковская область',
            10928 => 'Великие Луки',
            10933 => 'Республика Карелия',
            10937 => 'Сортавала',
            10939 => 'Республика Коми',
            10945 => 'Ухта',
            10946 => 'Астраханская область',
            10950 => 'Волгоградская область',
            10951 => 'Волжский',
            10987 => 'Армавир',
            10990 => 'Геленджик',
            10993 => 'Ейск',
            10995 => 'Краснодарский край',
            11004 => 'Республика Адыгея',
            11010 => 'Республика Дагестан',
            11012 => 'Республика Ингушетия',
            11013 => 'Республика Кабардино-Балкария',
            11015 => 'Республика Калмыкия',
            11020 => 'Карачаево-Черкесская Республика',
            11021 => 'Республика Северная Осетия-Алания',
            11024 => 'Чеченская Республика',
            11029 => 'Ростовская область',
            11036 => 'Волгодонск',
            11043 => 'Каменск-Шахтинский',
            11053 => 'Шахты',
            11057 => 'Ессентуки',
            11062 => 'Кисловодск',
            11063 => 'Минеральные Воды',
            11064 => 'Невинномысск',
            11067 => 'Пятигорск',
            11069 => 'Ставропольский край',
            11070 => 'Кировская область',
            11071 => 'Кирово-Чепецк',
            11077 => 'Республика Марий Эл',
            11079 => 'Нижегородская область',
            11080 => 'Арзамас',
            11083 => 'Саров',
            11084 => 'Оренбургская область',
            11091 => 'Орск',
            11095 => 'Пензенская область',
            11108 => 'Пермский край',
            11110 => 'Соликамск',
            11111 => 'Республика Башкортостан',
            11114 => 'Нефтекамск',
            11115 => 'Салават',
            11116 => 'Стерлитамак',
            11117 => 'Республика Мордовия',
            11119 => 'Татарстан',
            11121 => 'Альметьевск',
            11122 => 'Бугульма',
            11123 => 'Елабуга',
            11125 => 'Зеленодольск',
            11127 => 'Нижнекамск',
            11129 => 'Чистополь',
            11131 => 'Самарская область',
            11132 => 'Жигулевск',
            11139 => 'Сызрань',
            11143 => 'Балаково',
            11146 => 'Саратовская область',
            11147 => 'Энгельс',
            11148 => 'Удмуртская республика',
            11150 => 'Глазов',
            11152 => 'Сарапул',
            11153 => 'Ульяновская область',
            11155 => 'Димитровград',
            11156 => 'Чувашская республика',
            11158 => 'Курганская область',
            11162 => 'Свердловская область',
            11164 => 'Каменск-Уральский',
            11168 => 'Нижний Тагил',
            11170 => 'Новоуральск',
            11171 => 'Первоуральск',
            11173 => 'Ишим',
            11175 => 'Тобольск',
            11176 => 'Тюменская область',
            11193 => 'Ханты-Мансийский АО',
            11202 => 'Златоуст',
            11212 => 'Миасс',
            11214 => 'Озерск',
            11217 => 'Сатка',
            11218 => 'Снежинск',
            11225 => 'Челябинская область',
            11232 => 'Ямало-Ненецкий АО',
            11235 => 'Алтайский край',
            11251 => 'Рубцовск',
            11256 => 'Ангарск',
            11266 => 'Иркутская область',
            11273 => 'Усть-Илимск',
            11282 => 'Кемеровская область',
            11287 => 'Междуреченск',
            11291 => 'Прокопьевск',
            11302 => 'Ачинск',
            11306 => 'Кайеркан',
            11309 => 'Красноярский край',
            11311 => 'Норильск',
            11314 => 'Бердск',
            11316 => 'Новосибирская область',
            11318 => 'Омская область',
            11319 => 'Горно-Алтайск',
            11330 => 'Республика Бурятия',
            11333 => 'Кызыл',
            11340 => 'Республика Хакасия',
            11341 => 'Саяногорск',
            11351 => 'Северск',
            11353 => 'Томская область',
            11374 => 'Белогорск',
            11375 => 'Амурская область',
            11391 => 'Тында',
            11393 => 'Биробиджан',
            11398 => 'Камчатский край',
            11403 => 'Магаданская область',
            11409 => 'Приморский край',
            11426 => 'Уссурийск',
            11443 => 'Республика Саха (Якутия)',
            11450 => 'Сахалинская область',
            11453 => 'Комсомольск-на-Амуре',
            11457 => 'Хабаровский край',
            11458 => 'Анадырь',
            11463 => 'Евпатория',
            11464 => 'Керчь',
            11469 => 'Феодосия',
            11470 => 'Ялта',
            11471 => 'Алушта',
            20040 => 'Выкса',
            20044 => 'Кстово',
            20086 => 'Железногорск',
            20221 => 'Кропивницкий',
            20222 => 'Луцк',
            20258 => 'Сатис',
            20271 => 'Мексика',
            20273 => 'Актобе',
            20523 => 'Электросталь',
            20529 => 'Львовская область',
            20530 => 'Закарпатская область',
            20531 => 'Тернопольская область',
            20532 => 'Украина/Ивано-Франковская область',
            20533 => 'Черновицкая область',
            20534 => 'Ровенская область',
            20535 => 'Хмельницкая область',
            20536 => 'Донецкая область',
            20537 => 'Днепропетровская область',
            20538 => 'Харьковская область',
            20539 => 'Запорожская область',
            20540 => 'Луганская область',
            20541 => 'Одесская область',
            20542 => 'Херсонская область',
            20543 => 'Николаевская область',
            20544 => 'Киевская область',
            20545 => 'Винницкая область',
            20546 => 'Черкасская область',
            20547 => 'Житомирская область',
            20548 => 'Кировоградская область',
            20549 => 'Полтавская область',
            20550 => 'Волынская область',
            20551 => 'Черниговская область',
            20552 => 'Сумская область',
            20554 => 'Краматорск',
            20571 => 'Жуковский',
            20574 => 'Кипр',
            20728 => 'Королёв',
            20809 => 'Кокшетау',
            21609 => 'Кременчуг',
            21610 => 'Черногория',
            21621 => 'Реутов',
            21622 => 'Железнодорожный',
            21777 => 'Сахалинская область/Универсальное',
            21778 => 'Сахалинская область/Прочее',
            21779 => 'Приморский край/Универсальное',
            21780 => 'Приморский край/Прочее',
            21781 => 'Магаданская область/Универсальное',
            21782 => 'Магаданская область/Прочее',
            21783 => 'Еврейская автономная область/Универсальное',
            21784 => 'Еврейская автономная область/Прочее',
            21785 => 'Чукотский автономный округ/Универсальное',
            21786 => 'Чукотский автономный округ/Прочее',
            21787 => 'Республика Саха (Якутия)/Универсальное',
            21788 => 'Республика Саха (Якутия)/Прочее',
            21789 => 'Хабаровский край/Универсальное',
            21790 => 'Хабаровский край/Прочее',
            21791 => 'Амурская область/Универсальное',
            21792 => 'Амурская область/Прочее',
            21793 => 'Камчатский край/Универсальное',
            21794 => 'Камчатский край/Прочее',
            21796 => 'Алтайский край/Универсальное',
            21797 => 'Алтайский край/Прочее',
            21798 => 'Иркутская область/Универсальное',
            21799 => 'Иркутская область/Прочее',
            21800 => 'Кемеровская область/Универсальное',
            21801 => 'Кемеровская область/Прочее',
            21802 => 'Красноярский край/Универсальное',
            21803 => 'Красноярский край/Прочее',
            21804 => 'Новосибирская область/Универсальное',
            21805 => 'Новосибирская область/Прочее',
            21806 => 'Омская область/Универсальное',
            21807 => 'Омская область/Прочее',
            21808 => 'Республика Алтай/Универсальное',
            21809 => 'Республика Алтай/Прочее',
            21810 => 'Республика Бурятия/Универсальное',
            21811 => 'Республика Бурятия/Прочее',
            21812 => 'Республика Тыва/Универсальное',
            21813 => 'Республика Тыва/Прочее',
            21814 => 'Республика Хакасия/Универсальное',
            21815 => 'Республика Хакасия/Прочее',
            21816 => 'Томская область/Универсальное',
            21817 => 'Томская область/Прочее',
            21818 => 'Забайкальский край/Универсальное',
            21819 => 'Забайкальский край/Прочее',
            21825 => 'Курганская область/Универсальное',
            21826 => 'Курганская область/Прочее',
            21827 => 'Свердловская область/Универсальное',
            21828 => 'Свердловская область/Прочее',
            21829 => 'Тюменская область/Универсальное',
            21830 => 'Тюменская область/Прочее',
            21831 => 'Ханты-Мансийский АО/Универсальное',
            21832 => 'Ханты-Мансийский АО/Прочее',
            21833 => 'Челябинская область/Универсальное',
            21834 => 'Челябинская область/Прочее',
            21835 => 'Ямало-Ненецкий АО/Универсальное',
            21836 => 'Ямало-Ненецкий АО/Прочее',
            21837 => 'Кировская область/Универсальное',
            21838 => 'Кировская область/Прочее',
            21839 => 'Республика Марий Эл/Универсальное',
            21840 => 'Республика Марий Эл/Прочее',
            21841 => 'Нижегородская область/Универсальное',
            21842 => 'Нижегородская область/Прочее',
            21843 => 'Оренбургская область/Универсальное',
            21844 => 'Оренбургская область/Прочее',
            21845 => 'Пензенская область/Универсальное',
            21846 => 'Пензенская область/Прочее',
            21847 => 'Пермский край/Универсальное',
            21848 => 'Пермский край/Прочее',
            21849 => 'Республика Башкортостан/Универсальное',
            21850 => 'Республика Башкортостан/Прочее',
            21852 => 'Республика Мордовия/Универсальное',
            21853 => 'Республика Мордовия/Прочее',
            21854 => 'Татарстан/Универсальное',
            21855 => 'Татарстан/Прочее',
            21856 => 'Самарская область/Универсальное',
            21857 => 'Самарская область/Прочее',
            21858 => 'Саратовская область/Универсальное',
            21859 => 'Саратовская область/Прочее',
            21860 => 'Удмуртская республика/Универсальное',
            21861 => 'Удмуртская республика/Прочее',
            21862 => 'Ульяновская область/Универсальное',
            21863 => 'Ульяновская область/Прочее',
            21864 => 'Чувашская республика/Универсальное',
            21865 => 'Чувашская республика/Прочее',
            21866 => 'Астраханская область/Универсальное',
            21867 => 'Астраханская область/Прочее',
            21868 => 'Волгоградская область/Универсальное',
            21869 => 'Волгоградская область/Прочее',
            21870 => 'Краснодарский край/Универсальное',
            21871 => 'Краснодарский край/Прочее',
            21872 => 'Республика Адыгея/Универсальное',
            21873 => 'Республика Адыгея/Прочее',
            21874 => 'Республика Дагестан/Универсальное',
            21875 => 'Республика Дагестан/Прочее',
            21876 => 'Республика Ингушетия/Универсальное',
            21877 => 'Республика Ингушетия/Прочее',
            21878 => 'Республика Кабардино-Балкария/Универсальное',
            21879 => 'Республика Кабардино-Балкария/Прочее',
            21880 => 'Республика Калмыкия/Универсальное',
            21881 => 'Республика Калмыкия/Прочее',
            21882 => 'Карачаево-Черкесская Республика/Универсальное',
            21883 => 'Карачаево-Черкесская Республика/Прочее',
            21884 => 'Республика Северная Осетия-Алания/Универсальное',
            21885 => 'Республика Северная Осетия-Алания/Прочее',
            21886 => 'Ростовская область/Универсальное',
            21887 => 'Ростовская область/Прочее',
            21888 => 'Ставропольский край/Универсальное',
            21889 => 'Ставропольский край/Прочее',
            21890 => 'Чеченская Республика/Универсальное',
            21891 => 'Чеченская Республика/Прочее',
            21892 => 'Санкт-Петербург и Ленинградская область/Универсальное',
            21893 => 'Санкт-Петербург и Ленинградская область/Прочее',
            21894 => 'Архангельская область/Универсальное',
            21895 => 'Архангельская область/Прочее',
            21896 => 'Вологодская область/Универсальное',
            21897 => 'Вологодская область/Прочее',
            21898 => 'Калининградская область/Универсальное',
            21899 => 'Калининградская область/Прочее',
            21900 => 'Мурманская область/Универсальное',
            21901 => 'Мурманская область/Прочее',
            21902 => 'Новгородская область/Универсальное',
            21903 => 'Новгородская область/Прочее',
            21904 => 'Псковская область/Универсальное',
            21905 => 'Псковская область/Прочее',
            21906 => 'Республика Карелия/Универсальное',
            21907 => 'Республика Карелия/Прочее',
            21908 => 'Республика Коми/Универсальное',
            21909 => 'Республика Коми/Прочее',
            21910 => 'Белгородская область/Универсальное',
            21911 => 'Белгородская область/Прочее',
            21912 => 'Брянская область/Универсальное',
            21913 => 'Брянская область/Прочее',
            21914 => 'Владимирcкая область/Универсальное',
            21915 => 'Владимирcкая область/Прочее',
            21916 => 'Воронежcкая область/Универсальное',
            21917 => 'Воронежcкая область/Прочее',
            21918 => 'Ивановская область/Универсальное',
            21919 => 'Ивановская область/Прочее',
            21920 => 'Калужская область/Универсальное',
            21921 => 'Калужская область/Прочее',
            21922 => 'Костромская область/Универсальное',
            21923 => 'Костромская область/Прочее',
            21924 => 'Курская область/Универсальное',
            21925 => 'Курская область/Прочее',
            21926 => 'Липецкая область/Универсальное',
            21927 => 'Липецкая область/Прочее',
            21928 => 'Орловская область/Универсальное',
            21929 => 'Орловская область/Прочее',
            21930 => 'Рязанская область/Универсальное',
            21931 => 'Рязанская область/Прочее',
            21932 => 'Смоленская область/Универсальное',
            21933 => 'Смоленская область/Прочее',
            21934 => 'Тамбовская область/Универсальное',
            21935 => 'Тамбовская область/Прочее',
            21936 => 'Тверская область/Универсальное',
            21937 => 'Тверская область/Прочее',
            21938 => 'Тульская область/Универсальное',
            21939 => 'Тульская область/Прочее',
            21940 => 'Ярославская область/Универсальное',
            21941 => 'Ярославская область/Прочее',
            21942 => 'Универсальное',
            21943 => 'Прочее',
            21949 => 'Забайкальский край',
            24876 => 'Макеевка',
            26034 => 'Жодино',
            29386 => 'Абхазия',
            29387 => 'Южная Осетия',
            29403 => 'Акмолинская область',
            29404 => 'Актюбинская область',
            29406 => 'Алматинская область',
            29407 => 'Атырауская область',
            29408 => 'Восточно-Казахстанская область',
            29409 => 'Жамбылская область',
            29410 => 'Западно-Казахстанская область',
            29411 => 'Карагандинская область',
            29412 => 'Костанайская область',
            29413 => 'Кызылординская область',
            29414 => 'Мангистауская область',
            29415 => 'Павлодарская область',
            29416 => 'Северо-Казахстанская область',
            29417 => 'Южно-Казахстанская область',
            29629 => 'Могилевская область',
            29630 => 'Минская область',
            29631 => 'Гомельская область',
            29632 => 'Брестская область',
            29633 => 'Витебская область',
            29634 => 'Гродненская область',
            33883 => 'Комрат',
            101852 => 'Минская область/Универсальное',
            101853 => 'Минская область/Прочее',
            101854 => 'Гомельская область/Универсальное',
            101855 => 'Гомельская область/Прочее',
            101856 => 'Витебская область/Универсальное',
            101857 => 'Витебская область/Прочее',
            101858 => 'Брестская область/Универсальное',
            101859 => 'Брестская область/Прочее',
            101860 => 'Гродненская область/Универсальное',
            101861 => 'Гродненская область/Прочее',
            101862 => 'Могилевская область/Универсальное',
            101863 => 'Могилевская область/Прочее',
            101864 => 'Киевская область/Прочее',
            101865 => 'Киевская область/Универсальное',
            102444 => 'Северный Кавказ',
            102445 => 'Северный Кавказ/Другие города региона',
            102446 => 'Северный Кавказ/Универсальное',
            102450 => 'Полтавская область/Прочее',
            102451 => 'Черкасская область/Прочее',
            102452 => 'Винницкая область/Прочее',
            102453 => 'Кировоградская область/Прочее',
            102454 => 'Житомирская область/Прочее',
            102455 => 'Харьковская область/Прочее',
            102456 => 'Донецкая область/Прочее',
            102457 => 'Днепропетровская область/Прочее',
            102458 => 'Луганская область/Прочее',
            102459 => 'Запорожская область/Прочее',
            102460 => 'Одесская область/Прочее',
            102461 => 'Николаевская область/Прочее',
            102462 => 'Херсонская область/Прочее',
            102464 => 'Львовская область/Прочее',
            102465 => 'Хмельницкая область/Прочее',
            102466 => 'Тернопольская область/Прочее',
            102467 => 'Ровенская область/Прочее',
            102468 => 'Черновицкая область/Прочее',
            102469 => 'Прочее',
            102470 => 'Закарпатская область/Прочее',
            102471 => 'Ивано-Франковская область/Прочее',
            102472 => 'Сумская область/Прочее',
            102473 => 'Черниговская область/Прочее',
            102475 => 'Полтавская область/Универсальное',
            102476 => 'Черкасская область/Универсальное',
            102477 => 'Винницкая область/Универсальное',
            102478 => 'Кировоградская область/Универсальное',
            102479 => 'Житомирская область/Универсальное',
            102480 => 'Харьковская область/Универсальное',
            102481 => 'Донецкая область/Универсальное',
            102482 => 'Днепропетровская область/Универсальное',
            102483 => 'Луганская область/Универсальное',
            102484 => 'Запорожская область/Универсальное',
            102485 => 'Одесская область/Универсальное',
            102486 => 'Николаевская область/Универсальное',
            102487 => 'Херсонская область/Универсальное',
            102489 => 'Львовская область/Универсальное',
            102490 => 'Хмельницкая область/Универсальное',
            102491 => 'Тернопольская область/Универсальное',
            102492 => 'Ровенская область/Универсальное',
            102493 => 'Черновицкая область/Универсальное',
            102494 => 'Волынская область/Универсальное',
            102495 => 'Закарпатская область/Универсальное',
            102496 => 'Ивано-Франковская область/Универсальное',
            102497 => 'Сумская область/Универсальное',
            102498 => 'Черниговская область/Универсальное',
            102499 => 'Алматинская область/Прочее',
            102500 => 'Карагандинская область/Прочее',
            102501 => 'Акмолинская область/Прочее',
            102502 => 'Восточно-Казахстанская область/Прочее',
            102503 => 'Павлодарская область/Прочее',
            102504 => 'Костанайская область/Прочее',
            102505 => 'Западно-Казахстанская область/Прочее',
            102506 => 'Северо-Казахстанская область/Прочее',
            102507 => 'Южно-Казахстанская область/Прочее',
            102508 => 'Актюбинская область/Прочее',
            102509 => 'Атырауская область/Прочее',
            102510 => 'Мангистауская область/Прочее',
            102511 => 'Жамбылская область/Прочее',
            102512 => 'Кызылординская область/Прочее',
            102513 => 'Алматинская область/Универсальное',
            102514 => 'Карагандинская область/Универсальное',
            102515 => 'Акмолинская область/Универсальное',
            102516 => 'Восточно-Казахстанская область/Универсальное',
            102517 => 'Павлодарская область/Универсальное',
            102518 => 'Костанайская область/Универсальное',
            102519 => 'Западно-Казахстанская область/Универсальное',
            102520 => 'Северо-Казахстанская область/Универсальное',
            102521 => 'Южно-Казахстанская область/Универсальное',
            102522 => 'Актюбинская область/Универсальное',
            102523 => 'Атырауская область/Универсальное',
            102524 => 'Мангистауская область/Универсальное',
            102525 => 'Жамбылская область/Универсальное',
            102526 => 'Кызылординская область/Универсальное',
            115092 => 'Крымский федеральный округ',
            115674 => 'Молдова/Прочее',
            115675 => 'Молдова/Универсальное',
            155296 => 'Прочее',
            155297 => 'Универсальное'
        ];

        return isset($regions[$id]) ? $regions[$id] : '';
    }
}
