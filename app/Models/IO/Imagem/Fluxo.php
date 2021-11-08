<?php

namespace App\Models\Io\Imagem;

use CodeIgniter\Model;

class Fluxo extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'fluxos';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [];

	// Dates
	protected $useTimestamps        = false;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	protected $deletedField         = 'deleted_at';

	// Validation
	protected $validationRules      = [];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks       = true;
	protected $beforeInsert         = [];
	protected $afterInsert          = [];
	protected $beforeUpdate         = [];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];

   function index($txt,$color)
   {
      $zook = 10;
			$w = 128;
			$h = 32;      
      $x = 40;
      $y = 12;
      $f_size = 12;
      $svg = '<svg xmlns="http://www.w3.org/2000/svg" 
            width="'.$w.'" height="'.$h.'" 
            fill="currentColor" viewBox="0 0 '.$x.' '.$y.'">
     <path
         d="M 0,6 
         C 0,4.8954305 0.8954305,4 2,4 h 40 
         c 1.104569,0 2,0.8954305 2,2 v 4 
         c 0,1.104569 -0.895431,2 -2,2 H 2 
         C 0.8954305,12 0,11.104569 0,10 Z M 2,5 
         C 1.4477153,5 1,5.4477153 1,6 v 4 
         c 0,0.552285 0.4477153,1 1,1 h 40 
         c 0.552285,0 1,-0.447715 1,-1 V 6
         C 13,5.4477153 12.552285,5 12,5 Z M 17.076923,8.0961538 
         z"
         id="path2"
     sodipodi:nodetypes="sssssssssssssssssssccs" />
  <text
     xml:space="preserve"
     style="font-style:normal;font-weight:normal;font-size:4px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
     x="1.8846154"
     y="9.1538458"
     id="text1816"><tspan
       sodipodi:role="line"
       id="tspan1814"
       x="1.8846154"
       y="9.1538458"
       style="font-size:4px">'.$txt.'</tspan></text>
       </svg>';
      return $svg;
   }

	function index2($txt='',$color='#666666')
		{
         $id = md5($txt);
			$w = 600;
			$h = 400;
			$x = 30;
			$y = 80;
			$zoom = 20;
			$f_size = 12;
			$svg = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<svg
   width="105mm"
   height="148mm"
   viewBox="0 0 '.$w.' '.$h.'
   version="1.1"
   id="svg5"
   inkscape:version="1.1 (c68e22c387, 2021-05-23)"
   sodipodi:docname="arrow.svg"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:svg="http://www.w3.org/2000/svg">
  <sodipodi:namedview
     id="'.$id.'"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageshadow="2"
     inkscape:pageopacity="0.0"
     inkscape:pagecheckerboard="0"
     inkscape:document-units="mm"
     showgrid="false"
     showguides="true"
     inkscape:guide-bbox="true"
     inkscape:zoom="2'.$zoom.'"
     inkscape:cx="353.14033"
     inkscape:cy="272.70348"
     inkscape:window-width="'.$w.'"
     inkscape:window-height="'.$h.'"
     inkscape:window-x="-8"
     inkscape:window-y="-8"
     inkscape:window-maximized="1"
     inkscape:current-layer="layer1">
    <sodipodi:guide
       position="3.4804688,231.94201"
       orientation="0,-1"
       id="guide521" />
    <sodipodi:guide
       position="30.164063,249.87981"
       orientation="1,0"
       id="guide523" />
    <sodipodi:guide
       position="127.61718,170.98919"
       orientation="1,0"
       id="guide525" />
    <sodipodi:guide
       position="124.9399,245.59615"
       orientation="1,0"
       id="guide527" />
    <sodipodi:guide
       position="8.5673077,201.86719"
       orientation="0,-1"
       id="guide529" />
    <sodipodi:guide
       position="5.6222957,216.85998"
       orientation="0,-1"
       id="guide702" />
    <sodipodi:guide
       position="39.980768,231.94202"
       orientation="1,0"
       id="guide1377" />
    <sodipodi:guide
       position="114.94471,248.09495"
       orientation="1,0"
       id="guide1379" />
  </sodipodi:namedview>
  <defs
     id="defs2" />
  <g
     inkscape:label="Camada 1"
     inkscape:groupmode="layer"
     id="layer1">
    <path
       style="fill:none;stroke:#000000;stroke-width:0.264583px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
       d="m 30.164063,65.057991 9.816705,15.08203 -9.816705,14.99279 84.780647,0 9.99519,-14.99279 -9.99519,-15.08203 z"
       id="path439"
       sodipodi:nodetypes="ccccccc" />
    <path
       style="fill:#800000;stroke:none;stroke-width:0.337296"
       d="m 115.52374,358.71075 c 0,-0.0945 8.19678,-12.69322 18.21508,-27.99725 l 18.21507,-27.82552 -18.21507,-27.98414 c -10.0183,-15.39128 -18.21508,-28.06439 -18.21508,-28.16246 0,-0.0981 71.67952,-0.17421 159.28783,-0.16919 l 159.28784,0.009 18.63126,28.16184 18.63127,28.16185 -18.63127,27.97903 -18.63126,27.97904 -159.28784,0.01 c -87.60831,0.005 -159.28783,-0.0676 -159.28783,-0.16204 z"
       id="path1863"
       transform="scale(0.26458333)" />
    <path
       style="fill:#800000;stroke-width:0.337296"
       d="m 193.02638,358.65692 -77.14738,-0.11317 3.64463,-5.64898 c 2.00454,-3.10693 9.15952,-14.07293 15.89995,-24.36888 16.98534,-25.94496 16.52808,-25.23537 16.52808,-25.64881 0,-0.20422 -5.15437,-8.29109 -11.45415,-17.97082 -6.29978,-9.67973 -14.4001,-22.153 -18.00071,-27.71838 l -6.54656,-10.11887 81.4966,-0.16865 c 44.82313,-0.0927 116.41575,-0.1097 159.0947,-0.0377 l 77.5981,0.13098 7.27685,10.99978 c 4.00228,6.04988 12.32523,18.63103 18.49548,27.95812 l 11.21858,16.95834 -18.51557,27.80823 -18.51557,27.80822 -55.68236,0.009 c -30.6253,0.005 -67.50857,0.06 -81.96283,0.12186 -14.45425,0.0618 -60.99678,0.0615 -103.42784,-6.7e-4 z"
       id="path1939"
       transform="scale(0.26458333)" />
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:'.$f_size.';line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none;stroke-width:0.264583"
       x="46.852463"
       y="82.995796"
       id="text5910"><tspan
         sodipodi:role="line"
         id="tspan5908"
         style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Lato;-inkscape-font-specification:Lato;fill:#ffffff;stroke-width:0.164583"
         x="'.($x+($x*0.40)).'"
         y="'.($y+($y*0.05)).'">'.$txt.'</tspan></text>
  </g>
</svg>';
		return $svg;
		}
}
