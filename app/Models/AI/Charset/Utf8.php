<?php

namespace App\Models\AI\Charset;

use CodeIgniter\Model;

class Utf8 extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = '*';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id','text','description'
	];
	protected $typeFields        = [
		'hidden','text','none'
	];

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

	function convert($txt,$from='utf8',$to='iso-8859-1')
        {
            $from = mb_strtolower($from);
            $from = str_replace('-','',$from);

            switch($from)
                {
                    case 'utf8':
                    $txt = $this->utf8_iso8859($txt);
                    break;
                }
            return $txt;
        }


    function utf8_iso8859($txt)
        {
            return utf8_decode($txt);
        }
}
