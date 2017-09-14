@if(isset($conversionsCharts) && count($conversionsCharts) > 0)
    <w:p w:rsidR="00104A38" w:rsidRDefault="00104A38" w:rsidP="00104A38">
        <w:pPr>
            <w:spacing w:after="0" w:line="240" w:lineRule="auto" />
            <w:rPr>
                <w:rFonts w:ascii="Open Sans" w:hAnsi="Open Sans" w:cs="Open Sans" />
                <w:b />
                <w:color w:val="333333" />
                <w:sz w:val="24" />
                <w:szCs w:val="20" />
                <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF" />
            </w:rPr>
        </w:pPr>
        <w:r>
            <w:rPr>
                <w:rFonts w:ascii="Open Sans" w:hAnsi="Open Sans" w:cs="Open Sans" />
                <w:b />
                <w:color w:val="333333" />
                <w:sz w:val="24" />
                <w:szCs w:val="20" />
                <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF" />
            </w:rPr>
            <w:t>Конверсии из поисковых систем</w:t>
        </w:r>
    </w:p>
    <w:p w:rsidR="00104A38" w:rsidRDefault="00104A38" w:rsidP="00104A38">
        <w:pPr>
            <w:spacing w:after="0" w:line="240" w:lineRule="auto" />
            <w:rPr>
                <w:rFonts w:ascii="Open Sans" w:hAnsi="Open Sans" w:cs="Open Sans" />
                <w:b />
                <w:color w:val="333333" />
                <w:sz w:val="24" />
                <w:szCs w:val="20" />
                <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF" />
            </w:rPr>
        </w:pPr>
    </w:p>
    @foreach($conversionsCharts as $chart)
        <w:p w:rsidR="00104A38" w:rsidRPr="00454DDC" w:rsidRDefault="00454DDC" w:rsidP="00104A38">
            <w:pPr>
                <w:spacing w:after="0" w:line="240" w:lineRule="auto"/>
                <w:rPr>
                    <w:rFonts w:ascii="Open Sans" w:hAnsi="Open Sans" w:cs="Open Sans"/>
                    <w:b/>
                    <w:color w:val="333333"/>
                    <w:szCs w:val="20"/>
                    <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF"/>
                </w:rPr>
            </w:pPr>
            <w:r>
                <w:rPr>
                    <w:noProof/>
                    <w:lang w:eastAsia="ru-RU"/>
                </w:rPr>
                <w:drawing>
                    <wp:anchor distT="0" distB="0" distL="114300" distR="114300" simplePos="0" relativeHeight="251660288" behindDoc="0" locked="0" layoutInCell="1" allowOverlap="1">
                        <wp:simplePos x="0" y="0"/>
                        <wp:positionH relativeFrom="margin">
                            <wp:align>right</wp:align>
                        </wp:positionH>
                        <wp:positionV relativeFrom="paragraph">
                            <wp:posOffset>192465</wp:posOffset>
                        </wp:positionV>
                        <wp:extent cx="3351004" cy="1664898"/>
                        <wp:effectExtent l="0" t="0" r="1905" b="0"/>
                        <wp:wrapNone/>
                        <wp:docPr id="17" name="Рисунок 17"/>
                        <wp:cNvGraphicFramePr>
                            <a:graphicFrameLocks xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" noChangeAspect="1"/>
                        </wp:cNvGraphicFramePr>
                        <a:graphic xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main">
                            <a:graphicData uri="http://schemas.openxmlformats.org/drawingml/2006/picture">
                                <pic:pic xmlns:pic="http://schemas.openxmlformats.org/drawingml/2006/picture">
                                    <pic:nvPicPr>
                                        <pic:cNvPr id="1" name=""/>
                                        <pic:cNvPicPr/>
                                    </pic:nvPicPr>
                                    <pic:blipFill>
                                        <a:blip r:embed="{{ $chart['chartId'] }}">
                                            <a:extLst>
                                                <a:ext uri="{28A0092B-C50C-407E-A947-70E740481C1C}">
                                                    <a14:useLocalDpi xmlns:a14="http://schemas.microsoft.com/office/drawing/2010/main" val="0"/>
                                                </a:ext>
                                            </a:extLst>
                                        </a:blip>
                                        <a:stretch>
                                            <a:fillRect/>
                                        </a:stretch>
                                    </pic:blipFill>
                                    <pic:spPr>
                                        <a:xfrm>
                                            <a:off x="0" y="0"/>
                                            <a:ext cx="3351004" cy="1664898"/>
                                        </a:xfrm>
                                        <a:prstGeom prst="rect">
                                            <a:avLst/>
                                        </a:prstGeom>
                                    </pic:spPr>
                                </pic:pic>
                            </a:graphicData>
                        </a:graphic>
                        <wp14:sizeRelH relativeFrom="page">
                            <wp14:pctWidth>0</wp14:pctWidth>
                        </wp14:sizeRelH>
                        <wp14:sizeRelV relativeFrom="page">
                            <wp14:pctHeight>0</wp14:pctHeight>
                        </wp14:sizeRelV>
                    </wp:anchor>
                </w:drawing>
            </w:r>
            <w:r w:rsidR="00104A38" w:rsidRPr="00104A38">
                <w:rPr>
                    <w:rFonts w:ascii="Open Sans" w:hAnsi="Open Sans" w:cs="Open Sans"/>
                    <w:b/>
                    <w:color w:val="333333"/>
                    <w:szCs w:val="20"/>
                    <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF"/>
                </w:rPr>
                <w:t>{{ $chart['conversion'] }}</w:t>
            </w:r>
        </w:p>
        <w:p w:rsidR="00561B65" w:rsidRDefault="00561B65" w:rsidP="00104A38">
            <w:pPr>
                <w:spacing w:after="0" w:line="240" w:lineRule="auto"/>
                <w:rPr>
                    <w:rFonts w:ascii="Open Sans" w:hAnsi="Open Sans" w:cs="Open Sans"/>
                    <w:b/>
                    <w:color w:val="333333"/>
                    <w:sz w:val="24"/>
                    <w:szCs w:val="20"/>
                    <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF"/>
                </w:rPr>
            </w:pPr>
        </w:p>
        <w:tbl>
            <w:tblPr>
                <w:tblStyle w:val="af7"/>
                <w:tblW w:w="0" w:type="auto"/>
                <w:tblBorders>
                    <w:top w:val="none" w:sz="0" w:space="0" w:color="auto"/>
                    <w:left w:val="none" w:sz="0" w:space="0" w:color="auto"/>
                    <w:bottom w:val="none" w:sz="0" w:space="0" w:color="auto"/>
                    <w:right w:val="none" w:sz="0" w:space="0" w:color="auto"/>
                    <w:insideH w:val="none" w:sz="0" w:space="0" w:color="auto"/>
                    <w:insideV w:val="none" w:sz="0" w:space="0" w:color="auto"/>
                </w:tblBorders>
                <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/>
            </w:tblPr>
            <w:tblGrid>
                <w:gridCol w:w="2322"/>
                <w:gridCol w:w="2322"/>
            </w:tblGrid>
            <w:tr w:rsidR="00561B65" w:rsidTr="00454DDC">
                <w:trPr>
                    <w:trHeight w:val="930"/>
                </w:trPr>
                <w:tc>
                    <w:tcPr>
                        <w:tcW w:w="2322" w:type="dxa"/>
                    </w:tcPr>
                    <w:p w:rsidR="00561B65" w:rsidRDefault="00561B65" w:rsidP="00454DDC">
                        <w:pPr>
                            <w:jc w:val="center"/>
                            <w:rPr>
                                <w:rFonts w:ascii="Open Sans" w:hAnsi="Open Sans" w:cs="Open Sans"/>
                                <w:color w:val="616161"/>
                                <w:sz w:val="18"/>
                                <w:szCs w:val="21"/>
                                <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF"/>
                            </w:rPr>
                        </w:pPr>
                        <w:r>
                            <w:rPr>
                                <w:noProof/>
                                <w:lang w:eastAsia="ru-RU"/>
                            </w:rPr>
                            <w:drawing>
                                <wp:inline distT="0" distB="0" distL="0" distR="0" wp14:anchorId="559C2363" wp14:editId="620672DE">
                                    <wp:extent cx="438150" cy="361950"/>
                                    <wp:effectExtent l="0" t="0" r="0" b="0"/>
                                    <wp:docPr id="21" name="Рисунок 21"/>
                                    <wp:cNvGraphicFramePr>
                                        <a:graphicFrameLocks xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" noChangeAspect="1"/>
                                    </wp:cNvGraphicFramePr>
                                    <a:graphic xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main">
                                        <a:graphicData uri="http://schemas.openxmlformats.org/drawingml/2006/picture">
                                            <pic:pic xmlns:pic="http://schemas.openxmlformats.org/drawingml/2006/picture">
                                                <pic:nvPicPr>
                                                    <pic:cNvPr id="1" name=""/>
                                                    <pic:cNvPicPr/>
                                                </pic:nvPicPr>
                                                <pic:blipFill>
                                                    <a:blip r:embed="rId14"/>
                                                    <a:stretch>
                                                        <a:fillRect/>
                                                    </a:stretch>
                                                </pic:blipFill>
                                                <pic:spPr>
                                                    <a:xfrm>
                                                        <a:off x="0" y="0"/>
                                                        <a:ext cx="438150" cy="361950"/>
                                                    </a:xfrm>
                                                    <a:prstGeom prst="rect">
                                                        <a:avLst/>
                                                    </a:prstGeom>
                                                </pic:spPr>
                                            </pic:pic>
                                        </a:graphicData>
                                    </a:graphic>
                                </wp:inline>
                            </w:drawing>
                        </w:r>
                    </w:p>
                    <w:p w:rsidR="00561B65" w:rsidRDefault="00561B65" w:rsidP="00454DDC">
                        <w:pPr>
                            <w:jc w:val="center"/>
                            <w:rPr>
                                <w:rFonts w:ascii="Open Sans" w:hAnsi="Open Sans" w:cs="Open Sans"/>
                                <w:color w:val="616161"/>
                                <w:sz w:val="18"/>
                                <w:szCs w:val="21"/>
                                <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF"/>
                            </w:rPr>
                        </w:pPr>
                    </w:p>
                    <w:p w:rsidR="00561B65" w:rsidRDefault="00561B65" w:rsidP="00454DDC">
                        <w:pPr>
                            <w:jc w:val="center"/>
                            <w:rPr>
                                <w:rFonts w:ascii="Open Sans" w:hAnsi="Open Sans" w:cs="Open Sans"/>
                                <w:color w:val="333333"/>
                                <w:sz w:val="32"/>
                                <w:szCs w:val="20"/>
                                <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF"/>
                            </w:rPr>
                        </w:pPr>
                        <w:r>
                            <w:rPr>
                                <w:rFonts w:ascii="Open Sans" w:hAnsi="Open Sans" w:cs="Open Sans"/>
                                <w:color w:val="616161"/>
                                <w:sz w:val="18"/>
                                <w:szCs w:val="21"/>
                                <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF"/>
                            </w:rPr>
                            <w:t>Достижения цели</w:t>
                        </w:r>
                        <w:r w:rsidRPr="00157162">
                            <w:rPr>
                                <w:rFonts w:ascii="Open Sans" w:hAnsi="Open Sans" w:cs="Open Sans"/>
                                <w:color w:val="616161"/>
                                <w:sz w:val="18"/>
                                <w:szCs w:val="21"/>
                                <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF"/>
                            </w:rPr>
                            <w:t>:</w:t>
                        </w:r>
                    </w:p>
                </w:tc>
                <w:tc>
                    <w:tcPr>
                        <w:tcW w:w="2322" w:type="dxa"/>
                    </w:tcPr>
                    <w:p w:rsidR="00561B65" w:rsidRPr="00561B65" w:rsidRDefault="00561B65" w:rsidP="00454DDC">
                        <w:pPr>
                            <w:jc w:val="center"/>
                            <w:rPr>
                                <w:rFonts w:ascii="Open Sans" w:hAnsi="Open Sans" w:cs="Open Sans"/>
                                <w:color w:val="616161"/>
                                <w:sz w:val="6"/>
                                <w:szCs w:val="4"/>
                                <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF"/>
                            </w:rPr>
                        </w:pPr>
                    </w:p>
                    <w:p w:rsidR="00561B65" w:rsidRDefault="00561B65" w:rsidP="00454DDC">
                        <w:pPr>
                            <w:jc w:val="center"/>
                            <w:rPr>
                                <w:rFonts w:ascii="Open Sans" w:hAnsi="Open Sans" w:cs="Open Sans"/>
                                <w:color w:val="616161"/>
                                <w:sz w:val="18"/>
                                <w:szCs w:val="21"/>
                                <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF"/>
                            </w:rPr>
                        </w:pPr>
                        <w:r>
                            <w:rPr>
                                <w:noProof/>
                                <w:lang w:eastAsia="ru-RU"/>
                            </w:rPr>
                            <w:drawing>
                                <wp:inline distT="0" distB="0" distL="0" distR="0" wp14:anchorId="2C7189B0" wp14:editId="1ABBF304">
                                    <wp:extent cx="495300" cy="314325"/>
                                    <wp:effectExtent l="0" t="0" r="0" b="9525"/>
                                    <wp:docPr id="22" name="Рисунок 22"/>
                                    <wp:cNvGraphicFramePr>
                                        <a:graphicFrameLocks xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" noChangeAspect="1"/>
                                    </wp:cNvGraphicFramePr>
                                    <a:graphic xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main">
                                        <a:graphicData uri="http://schemas.openxmlformats.org/drawingml/2006/picture">
                                            <pic:pic xmlns:pic="http://schemas.openxmlformats.org/drawingml/2006/picture">
                                                <pic:nvPicPr>
                                                    <pic:cNvPr id="1" name=""/>
                                                    <pic:cNvPicPr/>
                                                </pic:nvPicPr>
                                                <pic:blipFill>
                                                    <a:blip r:embed="rId15"/>
                                                    <a:stretch>
                                                        <a:fillRect/>
                                                    </a:stretch>
                                                </pic:blipFill>
                                                <pic:spPr>
                                                    <a:xfrm>
                                                        <a:off x="0" y="0"/>
                                                        <a:ext cx="495300" cy="314325"/>
                                                    </a:xfrm>
                                                    <a:prstGeom prst="rect">
                                                        <a:avLst/>
                                                    </a:prstGeom>
                                                </pic:spPr>
                                            </pic:pic>
                                        </a:graphicData>
                                    </a:graphic>
                                </wp:inline>
                            </w:drawing>
                        </w:r>
                    </w:p>
                    <w:p w:rsidR="00561B65" w:rsidRDefault="00561B65" w:rsidP="00454DDC">
                        <w:pPr>
                            <w:jc w:val="center"/>
                            <w:rPr>
                                <w:rFonts w:ascii="Open Sans" w:hAnsi="Open Sans" w:cs="Open Sans"/>
                                <w:color w:val="616161"/>
                                <w:sz w:val="18"/>
                                <w:szCs w:val="21"/>
                                <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF"/>
                            </w:rPr>
                        </w:pPr>
                    </w:p>
                    <w:p w:rsidR="00561B65" w:rsidRDefault="00561B65" w:rsidP="00454DDC">
                        <w:pPr>
                            <w:jc w:val="center"/>
                            <w:rPr>
                                <w:rFonts w:ascii="Open Sans" w:hAnsi="Open Sans" w:cs="Open Sans"/>
                                <w:color w:val="333333"/>
                                <w:sz w:val="32"/>
                                <w:szCs w:val="20"/>
                                <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF"/>
                            </w:rPr>
                        </w:pPr>
                        <w:r>
                            <w:rPr>
                                <w:rFonts w:ascii="Open Sans" w:hAnsi="Open Sans" w:cs="Open Sans"/>
                                <w:color w:val="616161"/>
                                <w:sz w:val="18"/>
                                <w:szCs w:val="21"/>
                                <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF"/>
                            </w:rPr>
                            <w:t>Конверсия</w:t>
                        </w:r>
                        <w:r w:rsidRPr="00157162">
                            <w:rPr>
                                <w:rFonts w:ascii="Open Sans" w:hAnsi="Open Sans" w:cs="Open Sans"/>
                                <w:color w:val="616161"/>
                                <w:sz w:val="18"/>
                                <w:szCs w:val="21"/>
                                <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF"/>
                            </w:rPr>
                            <w:t>:</w:t>
                        </w:r>
                    </w:p>
                </w:tc>
            </w:tr>
            <w:tr w:rsidR="00561B65" w:rsidTr="00454DDC">
                <w:trPr>
                    <w:trHeight w:val="930"/>
                </w:trPr>
                <w:tc>
                    <w:tcPr>
                        <w:tcW w:w="2322" w:type="dxa"/>
                    </w:tcPr>
                    <w:p w:rsidR="00561B65" w:rsidRPr="00685116" w:rsidRDefault="00561B65" w:rsidP="00454DDC">
                        <w:pPr>
                            <w:jc w:val="center"/>
                            <w:rPr>
                                <w:rFonts w:ascii="Open Sans" w:hAnsi="Open Sans" w:cs="Open Sans"/>
                                <w:color w:val="333333"/>
                                <w:sz w:val="16"/>
                                <w:szCs w:val="16"/>
                                <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF"/>
                            </w:rPr>
                        </w:pPr>
                    </w:p>
                    <w:p w:rsidR="00561B65" w:rsidRDefault="00561B65" w:rsidP="00454DDC">
                        <w:pPr>
                            <w:jc w:val="center"/>
                            <w:rPr>
                                <w:rFonts w:ascii="Open Sans" w:hAnsi="Open Sans" w:cs="Open Sans"/>
                                <w:color w:val="333333"/>
                                <w:sz w:val="32"/>
                                <w:szCs w:val="20"/>
                                <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF"/>
                            </w:rPr>
                        </w:pPr>
                        <w:r>
                            <w:rPr>
                                <w:rFonts w:ascii="Open Sans" w:hAnsi="Open Sans" w:cs="Open Sans"/>
                                <w:color w:val="333333"/>
                                <w:sz w:val="32"/>
                                <w:szCs w:val="20"/>
                                <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF"/>
                            </w:rPr>
                            <w:t>{{ $chart['total']['goals'] }}</w:t>
                        </w:r>
                    </w:p>
                </w:tc>
                <w:tc>
                    <w:tcPr>
                        <w:tcW w:w="2322" w:type="dxa"/>
                    </w:tcPr>
                    <w:p w:rsidR="00561B65" w:rsidRPr="00685116" w:rsidRDefault="00561B65" w:rsidP="00454DDC">
                        <w:pPr>
                            <w:jc w:val="center"/>
                            <w:rPr>
                                <w:rFonts w:ascii="Open Sans" w:hAnsi="Open Sans" w:cs="Open Sans"/>
                                <w:color w:val="333333"/>
                                <w:sz w:val="16"/>
                                <w:szCs w:val="16"/>
                                <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF"/>
                            </w:rPr>
                        </w:pPr>
                    </w:p>
                    <w:p w:rsidR="00561B65" w:rsidRPr="00561B65" w:rsidRDefault="00561B65" w:rsidP="00454DDC">
                        <w:pPr>
                            <w:jc w:val="center"/>
                            <w:rPr>
                                <w:rFonts w:ascii="Open Sans" w:hAnsi="Open Sans" w:cs="Open Sans"/>
                                <w:color w:val="333333"/>
                                <w:sz w:val="32"/>
                                <w:szCs w:val="20"/>
                                <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF"/>
                            </w:rPr>
                        </w:pPr>
                        <w:r>
                            <w:rPr>
                                <w:rFonts w:ascii="Open Sans" w:hAnsi="Open Sans" w:cs="Open Sans"/>
                                <w:color w:val="333333"/>
                                <w:sz w:val="32"/>
                                <w:szCs w:val="20"/>
                                <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF"/>
                            </w:rPr>
                            <w:t>{{ $chart['total']['conversions'] }}</w:t>
                        </w:r>
                    </w:p>
                    <w:p w:rsidR="00561B65" w:rsidRDefault="00561B65" w:rsidP="00454DDC">
                        <w:pPr>
                            <w:jc w:val="center"/>
                            <w:rPr>
                                <w:rFonts w:ascii="Open Sans" w:hAnsi="Open Sans" w:cs="Open Sans"/>
                                <w:color w:val="333333"/>
                                <w:sz w:val="32"/>
                                <w:szCs w:val="20"/>
                                <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF"/>
                            </w:rPr>
                        </w:pPr>
                    </w:p>
                </w:tc>
            </w:tr>
        </w:tbl>
        <w:p w:rsidR="00454DDC" w:rsidRDefault="00454DDC" w:rsidP="00104A38">
            <w:pPr>
                <w:spacing w:after="0" w:line="240" w:lineRule="auto"/>
                <w:rPr>
                    <w:rFonts w:ascii="Open Sans" w:hAnsi="Open Sans" w:cs="Open Sans"/>
                    <w:b/>
                    <w:color w:val="333333"/>
                    <w:szCs w:val="20"/>
                    <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF"/>
                </w:rPr>
            </w:pPr>
        </w:p>
    @endforeach
    <w:p w:rsidR="00685116" w:rsidRDefault="00685116" w:rsidP="00DC50B3">
        <w:pPr>
            <w:spacing w:after="0" w:line="240" w:lineRule="auto" />
            <w:rPr>
                <w:rFonts w:ascii="Open Sans" w:hAnsi="Open Sans" w:cs="Open Sans" />
                <w:color w:val="333333" />
                <w:sz w:val="20" />
                <w:szCs w:val="20" />
                <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF" />
            </w:rPr>
        </w:pPr>
    </w:p>
@endif