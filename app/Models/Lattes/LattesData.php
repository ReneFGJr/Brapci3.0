<?php

namespace App\Models\Lattes;

use CodeIgniter\Model;

class LattesData extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'lattesdatas';
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

	function Process()
		{
			$file = '.tmp/LattesData/20113023806.json';
			$file = '.tmp/LattesData/20113059030.json';
			
			$dt = file_get_contents($file);
			$dt = (array)json_decode($dt);

			$projeto = (array)$dt['projeto'];
			$titulo = (string)$projeto['titulo'];
			$titulo = nbr_author($titulo,7);
			$dti = brtos($dt['dataInicioVigencia']);
			$dtf = brtos($dt['dataTerminoVigencia']);

			$processo = (string)$dt['numeroProcesso'];

			/**************************************************/
			$key = (string)$dt['palavrasChave'];
			$key = troca($key,', ',';');
			$key = troca($key,'. ',';');
			$key = explode(';',$key);
			$keys = '<ul>';
			foreach($key as $word)
				{
					$word = nbr_author($word,7);
					$keys .= '<li>'.$word.'</li>';
				}
			$keys .= '</ul>';
			echo h($titulo,2);
			echo h($processo,6);
			echo '<p>'.$dti.'-'.$dtf.'</p>';
			echo $keys;
			echo '<pre>';
			print_r($dt);
			echo '</pre>';
		}
}
