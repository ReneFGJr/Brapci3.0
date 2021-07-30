<?php

        function graph_demo()
            {
                $file = 'D:\GoogleDrive\Artigos\2021\EmQuestão - Identidade\cocitacao.txt';
                $txt = file_get_contents($file);
                
                $ln = explode(chr(13),$txt);
                $rv = array();
                for ($r=0;$r < count($ln);$r++)
                    {
                        $l = explode('#',$ln[$r]);
                        if (isset($l[1]))
                        {
                        if (isset($rv[$l[0]]))
                            {
                                $rv[$l[0]] .= ';'.$l[1];
                            } else {
                                $rv[$l[0]] = $l[1];
                            }
                        }
                    }
                foreach($rv as $id => $ln)
                    {
                        echo $ln.'<br>';
                    }
            }
        function demo2()
                {
                global $multi, $opacy;
                $multi = 0.4;
                $opacy = 0.5;
                $dr = array();

                $dr[0] = array('Ciência da Informação',18,7,5,24,4,18,10,22,36,26,8,26,40,43,44,56,48,50);
                $dr[1] = array('Scientometrics',5,0,0,0,0,3,0,13,13,19,2,35,22,19,41,58,37,33);
                $dr[2] = array('Perspectivas em Ciência da Informação',1,0,2,3,1,4,5,12,12,10,6,8,16,24,22,21,32,39);
                $dr[3] = array('JASIST',8,1,1,2,2,0,2,5,5,14,1,3,12,18,36,13,28,8);
                $dr[4] = array('Informação e Sociedade: Estudos',0,2,2,0,0,6,0,5,11,1,10,7,4,11,15,19,22,31);
                $dr[5] = array('DataGramaZero',0,3,2,0,0,7,0,7,21,5,12,11,10,13,13,13,8,15);
                $dr[6] = array('Encontrols Bibli',0,0,0,0,1,0,0,12,5,6,5,5,6,12,17,12,14,20);
                $dr[7] = array('Transinformação',1,0,0,2,0,9,2,11,9,1,8,1,4,6,12,15,9,23);
                $dr[8] = array('Em Questão',0,0,3,1,0,2,1,5,9,5,0,3,5,8,9,19,17,19);
                $dr[9] = array('Informação & Informação',0,0,0,0,0,2,0,2,1,0,4,2,6,8,11,8,24,16);
                $dr[10] = array('Journal of Documentation',7,0,0,1,0,4,3,1,1,8,0,2,7,3,13,11,7,5);
                $dr[11] = array('Rev. Digital de Biblioteconomia e Ciência da Informação',0,0,0,0,1,1,0,1,8,0,3,1,3,2,6,5,4,13);
                $dr[12] = array('Journal of Informetrics',0,0,0,0,0,1,0,0,2,1,0,1,1,2,10,13,7,5);
                $dr[13] = array('PontoDeAcesso',0,0,0,0,0,0,1,1,3,1,4,5,1,2,6,7,7,3);
                $dr[14] = array('Liinc em Revista',0,0,0,0,0,1,0,2,1,0,0,4,6,3,5,5,7,7);
                $dr[15] = array('Annual Review of Information Science and Technology',2,0,2,2,1,1,3,0,0,3,0,1,3,4,7,6,3,3);
                $dr[16] = array('Rev. Brasileira de Biblioteconomia e Documentação',0,0,0,1,0,0,0,0,3,0,0,2,3,1,2,3,15,9);
                $dr[17] = array('Information Processing & Management',0,0,1,6,1,2,0,2,0,1,0,1,2,4,7,2,6,4);
                $dr[18] = array('Tendencias da Pesquisa Brasileira em Ciência da Informação',0,0,0,0,0,0,0,3,3,1,1,3,0,4,5,4,5,8);
                $dr[19] = array('Journal of Information Science',6,0,1,1,2,2,1,1,0,2,0,2,2,3,2,2,5,5);
                $dr[20] = array('Reseach Policy',0,0,0,0,0,0,0,2,1,2,0,3,5,2,1,6,3,8);
                $dr[21] = array('El Profesional de la Informacion',0,0,0,0,0,1,1,1,0,2,4,1,8,1,4,2,2,5);
                $dr[22] = array('Science & Education',2,0,0,0,0,1,0,0,1,3,0,2,4,0,3,7,4,4);
                $dr[23] = array('Rev. de Biblioteconomia de Brasília',1,0,0,0,1,2,0,2,3,1,1,2,3,1,1,4,4,3);
                $dr[24] = array('Plos One',0,0,0,0,0,0,0,0,0,0,0,0,3,2,3,6,8,6);
                $dr[25] = array('INCID: Rev. de Documentação e Ciência da Informação',0,0,0,0,0,0,0,0,4,0,0,2,1,2,3,0,10,6);
                $dr[26] = array('Revista ACB',0,0,0,0,0,2,0,2,3,1,3,2,2,0,0,1,9,2);


                $sx = graph_bubble($dr);
                return $sx;
            }

        function graph_bubble($dt)
            {
                global $multi,$cv;
                $idc = 'MyCv';
                $cv = $idc;
                $cor =array('#000000','#008000','#ff0000','#ff00ff','#404000','#0000ff','#008000','#ff0000','#ff00ff','#404000','#0000ff','#008000','#ff0000','#ff00ff','#404000','#0000ff','#008000','#ff0000','#ff00ff','#404000','#0000ff','#008000','#ff0000','#ff00ff','#404000','#0000ff','#008000','#ff0000','#ff00ff','#404000','#0000ff','#008000','#ff0000','#ff00ff','#404000','#0000ff');
                $offsetx = 400;
                $offsety = 60;
                $linespace = 20;
                
                $sx = '';                
                $sx .= '<canvas id="'.$idc.'" width="1024" height="700" style="width: 100%; border:1px solid #000000;"></canvas>';

                $sx .= legends('xxxxxxxxxxxxxx');

                for ($y=0;$y < count($dt);$y++)
                {
                    $txt = $dt[$y][0];
                    $sx .= canva_text(
                                $offsetx,
                                $offsety+$y*$linespace,
                                $txt,
                                $idc,
                                $cor[0],
                                10);
                for ($r=1;$r < count($dt[$y]);$r++)
                    {
                        $vlr = round($dt[$y][$r] * $multi);
                        $sx .= canva_circle(
                                    $r*30+$offsetx,
                                    $offsety+$y*$linespace,
                                    $vlr,
                                    $idc,
                                    $cor[$y]
                                    );
                    }
                }

              
                
                return $sx;
            }

        function legends($txt,$sz = 10,$x=-10,$y=432)
            {
                global $cv;
                $xo = $x;
                $yo = $y;
                $sp = 30;
                $xcor = '#000000';
                $rt = (2 * 3.1416) * 270 / 360;
                $sx = '
                    <script>
                    var canvas = document.getElementById("'.$cv.'")
                    var context = canvas.getContext("2d");
                    context.font = "'.$sz.'pt Calibri";
                    context.globalAlpha = 1;
                    context.rotate('.$rt.');
                    // textAlign aligns text horizontally relative to placement
                    context.textAlign = "right";
                    // textBaseline aligns text vertically relative to font style
                    context.textBaseline = "middle";
                    context.fillStyle = "'.$xcor.'";
                    ';
                for ($r=2003;$r <= 2020;$r++)
                    {
                        $sx .= 'context.fillText("'.$r.'", '.$x.', '.$y.');'.cr();
                        $y = $y + $sp;
                    }

                $sx .= 'context.rotate(-'.$rt.');';
                $x = $yo-2; 
                for ($r=2003;$r <= 2020;$r++)
                    {
                        $sx .= '
                        context.beginPath();
                        context.moveTo('.$x.', 50);
                        context.lineTo('.$x.', 600);
                        context.lineWidth = 0.5;

                        // set line color
                        context.strokeStyle = "#888";
                        context.stroke();
                        ';       
                        $x = $x + $sp;
                    }

                $x = $xo+70;
                for ($r=0;$r <= 26;$r++)
                    {
                        $sx .= '
                        context.beginPath();
                        context.moveTo(410,'.$x.');
                        context.lineTo(950,'.$x.');
                        context.lineWidth = 0.4;

                        // set line color
                        context.strokeStyle = "#888";
                        context.stroke();
                        ';       
                        $x = $x + 20;
                    } 

                    $sx .= '</script>';
                return $sx;
            }
        function canva_text($x,$y,$txt,$cv,$xcor='',$sz = 12)
            {
                global $cv;
                $sx = '
                    <script>
                    var canvas = document.getElementById("'.$cv.'")
                    var context = canvas.getContext("2d");
                    var x = '.$x.';
                    var y = '.$y.';

                    context.font = "'.$sz.'pt Calibri";
                    context.globalAlpha = 1;
                    //context.rotate(Math.PI / 2);
                    // textAlign aligns text horizontally relative to placement
                    context.textAlign = "right";
                    // textBaseline aligns text vertically relative to font style
                    context.textBaseline = "middle";
                    context.fillStyle = "'.$xcor.'";
                    context.fillText("'.$txt.'", x, y);
                    </script>                
                ';
                return $sx;
            }

        function canva_circle($x,$y,$r,$cv='myCanvas',$xcor='')
            {
                global $opacy;
                $b = 1;
                $sx = '<script>
                        var canvas = document.getElementById("'.$cv.'");
                        var context = canvas.getContext("2d");
                        var centerX = '.$x.';
                        var centerY = '.$y.';
                        var radius = '.$r.';

                        context.beginPath();
                        context.arc(centerX, centerY, radius, 0, 2 * Math.PI, false);
                        context.fillStyle = "'.$xcor.'";
                        context.fill();
                        context.globalAlpha = '.$opacy.';
                        context.lineWidth = '.$b.';
                        context.strokeStyle = "#003300";
                        context.stroke();
                </script>';
                return $sx;
            }
