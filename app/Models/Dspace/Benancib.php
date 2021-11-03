<?php

namespace App\Models\Dspace;

use CodeIgniter\Model;

class Benancib extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'benancibs';
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

	function harvesting($id)
		{
			//http://repositorios.questoesemrede.uff.br/repositorios/handle/123456789/5?show=full
			//http://repositorios.questoesemrede.uff.br/oai/request?verb=Identify

			$txt = file_get_contents('http://repositorios.questoesemrede.uff.br/repositorios/handle/123456789/'.$id.'?show=full');
			$meta = array('dc.contributor.author',
							'dc.date.accessioned',
							'dc.date.available',
							'dc.date.issued',
							'dc.identifier.uri',
							'dc.description.abstract',
							'dc.language.iso',
							'dc.subject',
							'dc.title',
							'dc.title.alternative',
							'dc.type',
							'dc.ano.evento',
							'dc.cidade.evento',
							'dc.edicao.evento',
							'dc.numero.gt',
							'dc.titulo.gt',
							'dc.keywords',
							'dc.resumo',
							'dc.referencias',
							'dc.como.citar',
							'file-link'
							);

		}			
}
