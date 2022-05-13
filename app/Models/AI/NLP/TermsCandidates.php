<?php

namespace App\Models\AI\NLP;

use CodeIgniter\Model;

class TermsCandidates extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = '*';
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

	function painel()
		{
			$sx = '';
			$MyFiles = new \App\Models\Brapci\MyFiles();
			$Socials = new \App\Models\Socials();
			$user = $Socials->getID();

			$sx = h(lang('ai.my_files_area'));

			$sa = $MyFiles->list($user);

			$sx .= $sa;
			
			return $sx;

			

		}

	function JoinSentences($txt)
	{
		$dir = '../_documments/IA/Treino/';
		$d = scandir($dir);
		$id = '1';
		$file = $dir.'000'.$id.'.txt';
		$file_dest = $dir.'000'.$id.'C.txt';
		$txtd = '';
		$ln = '';
		if (file_exists($file))
			{
				$handle = fopen($file, "r");
				if ($handle) {
					while (($line = fgets($handle)) !== false) {
						$line = trim($line);
						$lastChar = substr($line,strlen($line)-1,1);
						$line = troca($line,chr(255),' ');
						switch($lastChar)
							{
								case '-':
									$line = substr($line,0,strlen($line)-1);	
									$ln .= $line;							
									break;
								case '.';
									$txtd .= $ln.cr();
									$ln = '';
									break;
								default:
									$ln .= $line.' ';
									break;
									
							} 								
						}
					}					
					fclose($handle);
					file_put_contents($file_dest,$txtd);
			} else {
				echo "File not found - ".$file;
			}
	}
}
