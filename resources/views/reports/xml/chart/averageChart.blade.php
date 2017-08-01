@if(count($averageCharts) > 0)
    @foreach($averageCharts as $chart)
        <w:p w:rsidR="00561B65" w:rsidRDefault="00561B65" w:rsidP="00561B65">
            <w:pPr>
                <w:pStyle w:val="2" />
                <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF" />
                <w:spacing w:before="0" w:after="300" />
                <w:rPr>
                    <w:rFonts w:ascii="Open Sans" w:hAnsi="Open Sans" w:cs="Open Sans" />
                    <w:color w:val="333333" />
                    <w:sz w:val="24" />
                </w:rPr>
            </w:pPr>
            <w:r>
                <w:rPr>
                    <w:rFonts w:ascii="Open Sans" w:hAnsi="Open Sans" w:cs="Open Sans" />
                    <w:color w:val="333333" />
                    <w:sz w:val="24" />
                </w:rPr>
                <w:lastRenderedPageBreak />
                <w:t xml:space="preserve">Средняя позиция в {{ $chart['searchEngine'] }} </w:t>
            </w:r>
            <w:r>
                <w:rPr>
                    <w:noProof />
                    <w:lang w:eastAsia="ru-RU" />
                </w:rPr>
                <w:drawing>
                    <wp:inline distT="0" distB="0" distL="0" distR="0" wp14:anchorId="48B10486" wp14:editId="4A076092">
                        <wp:extent cx="171450" cy="171450" />
                        <wp:effectExtent l="0" t="0" r="0" b="0" />
                        <wp:docPr id="118" name="Рисунок 118" />
                        <wp:cNvGraphicFramePr>
                            <a:graphicFrameLocks xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" noChangeAspect="1" />
                        </wp:cNvGraphicFramePr>
                        <a:graphic xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main">
                            <a:graphicData uri="http://schemas.openxmlformats.org/drawingml/2006/picture">
                                <pic:pic xmlns:pic="http://schemas.openxmlformats.org/drawingml/2006/picture">
                                    <pic:nvPicPr>
                                        <pic:cNvPr id="1" name="" />
                                        <pic:cNvPicPr />
                                    </pic:nvPicPr>
                                    <pic:blipFill>
                                        <a:blip r:embed="{{ $chart['seKey'] }}" />
                                        <a:stretch>
                                            <a:fillRect />
                                        </a:stretch>
                                    </pic:blipFill>
                                    <pic:spPr>
                                        <a:xfrm>
                                            <a:off x="0" y="0" />
                                            <a:ext cx="171450" cy="171450" />
                                        </a:xfrm>
                                        <a:prstGeom prst="rect">
                                            <a:avLst />
                                        </a:prstGeom>
                                    </pic:spPr>
                                </pic:pic>
                            </a:graphicData>
                        </a:graphic>
                    </wp:inline>
                </w:drawing>
            </w:r>
        </w:p>
        <w:p w:rsidR="00561B65" w:rsidRDefault="00561B65" w:rsidP="00561B65">
            <w:r>
                <w:rPr>
                    <w:noProof />
                    <w:lang w:eastAsia="ru-RU" />
                </w:rPr>
                <w:drawing>
                    <wp:inline distT="0" distB="0" distL="0" distR="0" wp14:anchorId="67C960AF" wp14:editId="23DABD51">
                        <wp:extent cx="6390005" cy="2560320" />
                        <wp:effectExtent l="0" t="0" r="0" b="0" />
                        <wp:docPr id="100" name="Рисунок 100" />
                        <wp:cNvGraphicFramePr>
                            <a:graphicFrameLocks xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" noChangeAspect="1" />
                        </wp:cNvGraphicFramePr>
                        <a:graphic xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main">
                            <a:graphicData uri="http://schemas.openxmlformats.org/drawingml/2006/picture">
                                <pic:pic xmlns:pic="http://schemas.openxmlformats.org/drawingml/2006/picture">
                                    <pic:nvPicPr>
                                        <pic:cNvPr id="1" name="" />
                                        <pic:cNvPicPr />
                                    </pic:nvPicPr>
                                    <pic:blipFill>
                                        <a:blip r:embed="{{ $chart['chartId'] }}" />
                                        <a:stretch>
                                            <a:fillRect />
                                        </a:stretch>
                                    </pic:blipFill>
                                    <pic:spPr>
                                        <a:xfrm>
                                            <a:off x="0" y="0" />
                                            <a:ext cx="6390005" cy="2560320" />
                                        </a:xfrm>
                                        <a:prstGeom prst="rect">
                                            <a:avLst />
                                        </a:prstGeom>
                                    </pic:spPr>
                                </pic:pic>
                            </a:graphicData>
                        </a:graphic>
                    </wp:inline>
                </w:drawing>
            </w:r>
        </w:p>
        <w:p w:rsidR="00561B65" w:rsidRDefault="00561B65" w:rsidP="00561B65" />
    @endforeach
@endif

