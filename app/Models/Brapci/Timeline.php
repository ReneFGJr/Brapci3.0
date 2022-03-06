<?php

namespace App\Models\Brapci;

use CodeIgniter\Model;

class Timeline extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'timelines';
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

	function timeline($meta)
	{
		helper('highchart');
		$RDF = new \App\Models\RDF\Rdf;
		$serie = array();

		/*********************************************************** Cria matriz zerada de produção */
		$prod = array();
		$prod[0] = array();
		$prod_types = array();
		$cats = '';
		$yeari = date("Y")-30;
		$yearf = date("Y")+1;
		for ($r = $yeari; $r <= $yearf; $r++) {
			$prod[$r] = array();
			$cats .= '"' . $r . '",';
		}

		/******************************************************************** Recupera ano produção */
		for ($r = 0; $r < count($meta); $r++) {
			$idp = $meta[$r];
			$dir = $RDF->directory($idp);
			$file = $dir . 'year.nm';
			$filec = $dir . 'class.nm';

			/*************************************************** Localiza Dados nos Arquivos *******/
			if (file_exists($file)) {
				$type = trim(file_get_contents($filec));
				$serie[$type] = '';
				$prod_types[$type] = '';
				if (file_exists($file)) {
					$year = file_get_contents($file);					
					if (isset($prod[$year][$type])) {
						$prod[$year][$type]++;
					} else {
						if (!isset($prod[$year])) { $prod[$year] = array(); }
						$prod[$year][$type] = 1;
					}
				} else {
					echo '<br>'.$file.' not found';
					exit;
				}
			}
		}
		$dados = '';
		ksort($prod);

		for ($r = $yeari; $r <= $yearf; $r++) {
			if (isset($prod[$r]))
				{
					$data = $prod[$r];
					foreach($prod_types as $types=>$vlr)
						{
							if (!isset($types)) { $serie[$types] = ''; }
							if (isset($data[$types])) {
								$serie[$types] .= $data[$types] . ',';
							} else {
								$serie[$types] .= '0,';
							}
						}					
				} else {
					echo "ERRO";
				}
				
		}

		foreach ($serie as $type => $vlrs) {
			$dados .= cr() . '{name: "' . $type . '",' . cr() . 'data: [' . $vlrs . ']},' . cr();
		}

		/*********************************************** Monta Gráfico */
		$data['id'] = 'TimeLine';
		$data['title'] = '';
		$data['categorias'] = "'Artigos', 'Eventos'";
		$data['categorias'] = $cats;
		$data['dados'] = 'series: [' . $dados . ']';
		$data['height'] = '400';
		$sx = bs(bsc(highchart_column($data), 12));
		$sx .= '<style> #' . $data['id'] . ' { width: 100%; height: ' . $data['height'] . 'px; } </style>';
		return $sx;
	}
}
