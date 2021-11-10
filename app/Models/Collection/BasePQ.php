<?php

namespace App\Models\Collection;

use CodeIgniter\Model;

class BasePQ extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'brapci_pq.bolsistas';
	protected $primaryKey           = 'id_bs';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_bs','bs_nome','bs_rdf_id','bs_lattes'
	];
	protected $typeFields        = [
		'hidden','string:100','hidden','string:50'
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
	
		function index($d1='',$d2='',$d3='')
			{
				$tela = '';
				switch($d2)
					{
						case 'viewid':
							$tela .= $this->viewid($d3);
						break;

						case 'harvesting':
							$tela .= $this->harvesting($d3);
						break;						

						case 'corpus':
							$tela .= $this->corpus();
						break;						

						default:
							$tela = $this->tableview();
							break;
					}	
				return $tela;
			}

		function harvesting($p)
			{
				$p = round($p);
				$sql = "select * from ".$this->table." order by id_bs limit 1 offset $p ";
				$query = $this->query($sql);
				$rlt = (array)$query->getResult();
				if (count($rlt) > 0)
					{
						$line = (array)$rlt[0];
						$lattes = $line['bs_lattes'];
						echo '===>'.$lattes;
						$Lattes = new \App\Models\Lattes\LattesXML();
						$Lattes->xml($lattes);
						$tela = bsmessage('Coleta completa de <b>'.$line['bs_nome'].'</b>',1);
						$url = PATH.MODULE.'research/pq/harvesting/'.($p+1);
						$tela .= metarefresh($url,1);
						$tela .= $url;

						return $tela;
					}	
				exit;
			}

		function corpus()
			{
				$sql = "select bs_nome, bs_lattes, bs_rdf_id
							from brapci_pq.bolsas as bolsas
							INNER JOIN brapci_pq.bolsistas ON bolsas.bb_person = bolsistas.id_bs
						where bs_finish >= '2017-01-01' 
						group by bs_nome, bs_lattes, bs_rdf_id
						order by bs_nome, bs_lattes, bs_rdf_id";
				$rst = $this->query($sql)->getresult();
				
				$sep = ';';
				$csv = 'name'.$sep.'lattes'.$sep.'brapci_id'.cr();
				$wh = '';
				
				for ($r=0;$r< count($rst);$r++)
					{
						$line = (array)$rst[$r];
						$csv .= '"'.$line['bs_nome'].'"'.$sep;
						$csv .='"hhttp://lattes.cnpq.br/'.$line['bs_lattes'].'"'.$sep;
						$csv .= $line['bs_rdf_id'];
						if (strlen($wh) > 0)
							{
								$wh .= ' OR ';
							}
						$wh .= " (lp_author = '".$line['bs_lattes']."') ";
						$csv .= cr();
					}
				dircheck('.tmp');
				dircheck('.tmp/.files');
				$csv = utf8_decode($csv);
				$filename = 'brapci_pq_'.date("Ymd_His").'.csv';
				$file = '.tmp/.files/'.$filename;
				file_put_contents($file,$csv);
				$sx = anchor_popup(URL.$file,'Base PQ');

				$sql = "select 
						concat('lattes_',id_lp) as id
						lp_authors as author,
						lp_title as title,
						lp_ano as year,
						lp_url as eprint,
						lp_doi as doi,
						lp_issn as issn,
						lp_journal as journal,
						lp_vol as volume,
						lp_nr as number,
						lp_place as place
						from brapci_lattes.LattesProducao 
						where (".$wh.")
						and (lp_ano >= 2017) 
						and (lp_ano <= 2021) 
						";

				$BibText = new \App\Models\Metadata\Bibtex();
				$txt = '';
				$rp = $this->query($sql)->getresult();
				for($r=0;$r < count($rp);$r++)
					{
						$line = (array)$rp[$r];
						$txt .= $BibText->BibtexArticle($line);
						echo $txt;
						exit;
					}
				
				return $sx;
			}			
		// http://brapci3/ai/research/pq/viewid/1
		function viewid($id)
			{
				$tela = $id;
				$dt = $this->find($id);

				$AuthorityNames = new \App\Models\Authority\AuthorityNames();
				$da = $AuthorityNames->where('a_prefTerm',$dt['bs_nome'])->findAll();
				if (count($da) == 0)
					{
						$d['a_class'] = 'P';
						$d['a_uri'] = 'htps://brapci.inf.br/index.php/res/v/'.$dt['bs_rdf_id'];
						$d['a_use'] = 'P';
						$d['a_prefTerm'] = $dt['bs_nome'];
						$d['a_lattes'] = $dt['bs_lattes'];
						$d['a_brapci'] = $dt['bs_rdf_id'];
						$d['a_orcid'] = 'P';			
						$AuthorityNames->insert($d);
						$da = $AuthorityNames->where('a_prefTerm',$dt['bs_nome'])->findAll();
					}
				$url = 'htps://brapci.inf.br/index.php/res/v/'.$dt['bs_rdf_id'];
				$url_lattes = 'hhttp://lattes.cnpq.br/'.$dt['bs_lattes'];
				$da = $da[0];
				$url_brapci = PATH.'authority/index/viewid/'.$da['id_a'];
				$tela = bsc(h($dt['bs_nome'],1),12);
				$tela .= bsc('<small>'.anchor($url).'</small>',12);
				$tela .= bsc('<small>'.anchor($url_lattes).'</small>',12);
				$tela .= bsc('<small>'.anchor($url_brapci).'</small>',12);

				return $tela;
			}

		function edit($id)
			{
				$this->path = PATH.MODULE.'research/pq';
				$this->path_back = PATH.MODULE.'research/pq';
				$tela = form($this);
				return $tela;
			}

		function tableview()
			{
				$this->path = PATH.MODULE.'research/pq';
				$tela = tableview($this);
				return $tela;
			}	

}
