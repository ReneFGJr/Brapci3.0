<?php

namespace App\Models\Book\API;

use CodeIgniter\Model;

class MercadoEditorial extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'mercadoeditorials';
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

	var $api_key = 'chaveAPI';
	var $URL = '';
	/* Link para testes */
	//var $url = 'https://sandbox.mercadoeditorial.org/api/v1/requisitar_livro_unico';
	//https://api.mercadoeditorial.org/documentacao/v1.2

	function __construct()
	{
		$this->api_key = getenv("api_key_mercadoeditorial");
		//$this->URL = 'https://sandbox.mercadoeditorial.org/api/v1.2/book';
		$this->URL = 'https://api.mercadoeditorial.org/api/v1.2/book';
	}

	function cacheFile($isbn)
		{
			return '';
		}

	function book($isbn,$id=0) {

		$API = new \App\Models\Book\API\Index();
		$ISBN = new \App\Models\Book\Isbn();
		$Language = new \App\Models\Book\Language();
		$rsp = array();

		$file = $this->cacheFile($isbn);

		if (($file != '') and (file_exists($file)))
			{

			} else {

			}

		/************************* Recupera Dados */
		$url = $this->URL.'?isbn='.$isbn;
		$t = $API->call($url,'GET');
		$w = array();
		if (isset($t['books'][0])) {
			$w = $t['books'][0];
		} else {
			return array();
		}
		if (count($w) == 0) { return array(); }
		$erro = $t['status']['code'];
		if ($erro == '101')
		{
			pre($rsp);
			return($rsp);
		}
		$w = (array)$w;
		$rsp['serie'] = '';
		$rsp['cover'] = '';
		$rsp['editora'] = '';
		$rsp['subject']= array();
		$rsp['item'] = $id;
		//$rsp['url'] = 'https://mercadoeditorial.org/books/view/'.$isbn;
		$rsp['url'] = 'https://api.mercadoeditorial.org/api/v1.2/book?isbn='.$isbn;
		/*******************************************************************************/

		$rsp['expressao']['genere'] = (string)$w['formato'];
		$rsp['title'] = trim((string)$w['titulo']);
		if ((isset($w['subtitulo']) and strlen($w['subtitulo']) > 0)) {
			$rsp['title'] .= ': ' . trim($w['subtitulo']);
		}
		$rsp['title'] = troca($rsp['title'], ' - ',': ');
		$rsp['title'] = nbr_author($rsp['title'],8);
		/********** Autores ****************************/
		for ($q=0;$q < count($w['contribuicao']);$q++)
		{
			$au = (array)$w['contribuicao'][$q];
			$autor = trim($au['nome']).' '.trim($au['sobrenome']);
			$type = trim($au['tipo_de_contribuicao']);

			if (!isset($rsp[$type][0]))
				{
					$rsp[$type] = array();
				}
			array_push($rsp[$type],nbr_author($autor,7));
		}

		/********** DESCRICAO **************************/
		$rsp['descricao'] = $w['sinopse'];

		/********** IMAGENS **************************/
		$cover = (array)$w['imagens'];
		$cover = (array)$cover['imagem_primeira_capa'];
		if (isset($cover['grande']))
		{
			$cover = $cover['media'];
			$cover = troca($cover,'hmlgfl.','fl.');
		}		
		$rsp['cover'] = $cover;

		/********* medidas **************************/
		$m = (array)$w['medidas'];
		/********** Páginas ****************************/
		if (isset($m['paginas'])) {
			$rsp['pages'] = $m['paginas'];
		} else {
			$rsp['pages'] = '';
		}		

		/********** Idioma ****************************/
		
		if (isset($w['idioma'])) {
			$rsp['expressao']['idioma'] = $Language->code($w['idioma']);
		} else {
			$rsp['expressao']['idioma'] = '';
		}	
		
		/********** Data ****************************/
		if (isset($w['ano_edicao'])) {
			$rsp['data'] = $w['ano_edicao'];
		} else {
			$rsp['data'] = '';
		}

		/*********************************************/
		
		if (isset($w['colecao'])) {
			$rsp['serie'] = $w['colecao'];
		} else {
			$rsp['serie'] = '';
		}	


		/******* editora ******************************/
		$ed = (array)$w['editora'];
		if (isset($ed['nome_editora']))
		{
			$editora = $ed['nome_editora'];
			$editora = $ed['selo_editorial'];
			$rsp['editora'] = $editora;
		}	

		/******* selo ******************************/
		$ed = (array)$w['selo'];
		if (isset($ed['nome_do_selo_editorial']))
		{
			$selo = $ed['nome_do_selo_editorial'];
			$rsp['selo'] = $selo;
		}		
		/******** assuntos ****************************/
		
		$cat = (array)$w['catalogacao'];
		$sub = troca($cat['palavras_chave'],',',';');
		$sub = explode(';',$sub);
		$rsp['subject'] = $sub;

		$sub = troca($cat['areas'],',',';');
		$sub = explode(';',$sub);
		$rsp['areas'] = $sub;

		$sub = troca($cat['cdd'],',',';');
		$sub = explode(';',$sub);
		$rsp['CDD'] = $sub;		

		/*************** Medidas */
		$m = $w['medidas'];
		$rsp['medidas'] = array();
		$rsp['medidas']['altura'] = $m['altura'];
		$rsp['medidas']['largura'] = $m['largura'];
		$rsp['medidas']['espessura'] = $m['espessura'];		
		$rsp['medidas']['peso'] = $m['peso'];

		$rsp['paginas'] = $m['paginas'];


		$rsp['totalItems'] = 1;	

		return($rsp);		
	}

	function lista_editoras()
			{
				$rdf = new rdf;
				$idioma = 'pt';

				$url = $this->url;
				$url = troca($url,'requisitar_livro_unico','requisitar_lista_de_editoras');
				$t = read_link($url);
				$w = json_decode($t);
				$nome = 'Mercadoeditorial.org';
				$ids = $rdf->rdf_concept_create('CorporateBody', $nome, '', $idioma);
				$idn = $rdf->rdf_name('https://mercadoeditorial.org/companies/');
				$rdf->set_propriety($ids,'hasURL',0,$idn);


				$ed = (array)$w->editora;
				
				for ($r=0;$r < count($ed);$r++)
				{
					$line = (array)$ed[$r];
					$nome = $line['nome_editora'];
					$cnpj = $line['cnpj_editora'];
					
					$idc = $rdf->rdf_concept_create('Editora', $nome, '', $idioma);
					$idn = $rdf->rdf_concept_create('CNPJ', $cnpj, '', $idioma);
					$rdf->set_propriety($idc,'brgov:is_cnpj',$idn);
					$rdf->set_propriety($idc,'brgov:is_source',$ids);
				}
			}		
}
