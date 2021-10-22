<?php

namespace App\Models\Oaipmh;

use CodeIgniter\Model;

class OaipmhRegister extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'brapci.source_listrecords';
	protected $primaryKey           = 'id_lr';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_lr', 'lr_identifier', 'lr_datestamp',
		'lr_status', 'lr_jnl', '	lr_setSpec',
		'lr_procees','lr_local_file'
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

	function set_status($id, $dt)
	{
		$this->set($dt);
		$this->where('id_lr', $id);
		$this->update();
	}

	function next($id, $st = 0)
	{
		$di = $this
			->where('lr_procees', $st)
			->where('lr_jnl', $id)
			->first();

		if ($this->db->resultID->num_rows == 0)
			{
				return 0;
			}
		if (count($di) == 0) {
			return 0;
		}
		return $di['id_lr'];
	}

	function process_00($id)
	{
		$OaipmhListSetSepc = new \App\Models\Oaipmh\OaipmhListSetSepc();
		$sx = '';
		/* recupera proximo */
		$DI = $this->next($id, 0);

		if ($DI > 0) {
			$dz = $this
				->where('id_lr', $DI)
				->first();
			$ID = $dz['lr_identifier'];
			
			if ($dz['lr_issue'] == 0) {
				/******************************************* Le os dados do envento */
				$Journal = new \App\Models\Journal\Journals();
				$dt = $Journal->Find($di);
				$url = $dt['jnl_url_oai'];
			} else {
				$di = $dz['lr_issue'];
				$JournalIssue = new \App\Models\Journal\JournalIssue();
				$dt = $JournalIssue->Find($di);
				$url = $dt['is_url_oai'];
			}			

			$url = trim($url) . '?verb=GetRecord';
			$url .= '&metadataPrefix=oai_dc';
			$url .= '&identifier=' . $ID;

			/* Checar se já nao foi coletado */
			$dir = '.tmp/';
			if (!is_dir($dir)) {
				mkdir($dir);
			}
			$dir .= 'oai/';
			if (!is_dir($dir)) {
				mkdir($dir);
			}
			$dir .= date("y") . '/';
			if (!is_dir($dir)) {
				mkdir($dir);
			}
			$dir .= date("m") . '/';
			if (!is_dir($dir)) {
				mkdir($dir);
			}
			$file = str_replace(array(' ', ':', '?', '/', '\\'), '_', $ID);

			/* Load Url */
			if (file_exists($dir . $file)) {
				$xml = file_get_contents($dir . $file);
			} else {
				$xml = file_get_contents($url);
				file_put_contents($dir . $file, $xml);
			}
			$xml = simplexml_load_string($xml);
			$sx .= bsmessage('Process ' . $ID);

			/* Update register */
			$dts['lr_procees'] = 1;
			$dts['lr_local_file'] = $dir . $file;
			
			$this->set_status($DI, $dts);

			/* Reload page */
			$sx .= '<meta http-equiv="refresh" content="1">';
		} else {
			$sx .= bsmessage('Process Finish', 3);
		}
		return $sx;
	}

	function le_xml($data)
	{
		$file = $data['li_local_file'];

		if (file_exists($file)) {
			$txt = file_get_contents($file);
			$txt = str_replace(array('<dc:'), '<dc_', $txt);
			$txt = str_replace(array('</dc:'), '</dc_', $txt);
			$txt = str_replace(array('<oai_dc:'), '<oai_dc_', $txt);
			$txt = str_replace(array('</oai_dc:'), '</oai_dc_', $txt);
			$txt = str_replace(array('xml:lang'), 'lang', $txt);

			$xml = simplexml_load_string($txt);
			return $xml;
		} else {
			return array();
		}
	}

	function process_02($id)
	{
		$di = $this->next($id, 2);

		/* Reload page */
		$sx .= '<meta http-equiv="refresh" content="1">';
	}

	function process_01($id)
	{
		$sx = '';
		/*
				$prep = new \App\Models\OpenDataPreposition();
				$country = new \App\Models\OpenDataCountry();				

				$prep->export();
				$country->export();
				*/

		$RDF = new \App\Models\RDF\RDF();
		$RDFData = new \App\Models\RDF\RDFData();
		$RDFClass = new \App\Models\RDF\RDFClass();
		$RDFLiteral = new \App\Models\RDF\RDFLiteral();
		$RDFConcept = new \App\Models\RDF\RDFConcept();

		$RDFClass = new \App\Models\RDF\RDFClass();
		$RDF_Work = new \App\Models\RDF_Work();
		$RDF_Date = new \App\Models\RDF_Date();
		$RDF_Place = new \App\Models\RDF_Place();
		$RDF_Issue = new \App\Models\RDF_Issue();		
		
		$RDF_Section = new \App\Models\RDF_Section();
		$RDF_Journal = new \App\Models\RDF_Journal();

		$EventProceedings = new \App\Models\EventProceedings();
		$EventProceedingsIssue = new \App\Models\EventProceedingsIssue();

		$sx = '';
		$di = $this->next($id,1);
		if ($di == 0)
			{
				return 'FIM';
			}
		$dt = $this->find($di);

		/*************************************************************************************** JOURNAL AND ISSUE */
		/***********************************************************************************************************/
		/***********************************************************************************************************/

		if (isset($di['id_lr'])) {
			/************************************************** Journal Publication */
			$djnl = $EventProceedings->le($di['li_journal']);
			$dt = array();
			$dt['Class'] = 'brapci:Event';
			$dt['Literal']['skos:prefLabel'] = $djnl['ep_nome'];
			$dt['Literal']['thesa:abbreviation'] = $djnl['ep_abrev'];
			$dt['Literal']['brapci:url'] = $djnl['ep_url'];
			$IDJNL = $RDFConcept->concept($dt);
			$sx .= 'Source: ' . $djnl['ep_nome'] . '<br>';

			/******************************************************* Journal ISSUE */
			$dissue = $EventProceedingsIssue->le($di['li_issue']);
			$dt = array();
			$dt['Class'] = 'brapci:EventIssue';
			$eventName = $djnl['ep_nome'];
			if (strlen($dissue['epi_edition']) > 0) {
				$eventName = trim($dissue['epi_edition']) . ' ' . $eventName;
			}
			if (strlen($dissue['epi_year']) > 0) {
				$eventName = $eventName . ', ' . trim($dissue['epi_year']);
			}
			$dt['Literal']['skos:prefLabel'] = $eventName;

			/**************************************************************** YEAR */
			$IDY = $RDF_Date->check($dissue['epi_year']);

			/*************************************************************** PLACE */
			$IDPlace = $RDF_Place->check($dissue['epi_place']);

			$dt['Relation']['brapci:isEventPart'] = $IDJNL;
			$dt['Relation']['brapci:isEventYear'] = $IDY;
			$dt['Relation']['brapci:isEventPlace'] = $IDPlace;
			$sx .= 'Issue: ' . $djnl['ep_nome'] . '<br>';
			echo '<pre>';
			print_r($dt);
			exit;
			$IDISSUE = $RDFConcept->concept($dt);

			$xml = $this->le_xml($di);

			/**************************************************************************** Publication Article ***********/
			/***********************************************************************************************************/
			/***********************************************************************************************************/

			/**************************************************** ID *****************/
			$head = (array)$xml->GetRecord->record->header->identifier;
			$ID = $head[0];
			$ID = str_pad($di['li_issue'], 5, '0', STR_PAD_LEFT) . '-' . $ID;
			$ID = str_pad($di['li_journal'], 5, '0', STR_PAD_LEFT) . '-' . $ID;
			/* Criar o ID do trabalho */
			$IDW = $RDF_Work->create($ID);

			/**************************************************** Languages **********/
			$language = (array)$xml->GetRecord->record->metadata->oai_dc_dc->dc_language;

			/**************************************************** Título *************/
			/* Associa título a trabalho ***************************************/
			$prop = $RDFClass->class('dc:title');
			$data = array();
			$data['d_r1'] = $IDW;
			$data['d_r2'] = 0;
			$data['d_p'] = $prop;
			$data['d_library'] = LIBRARY;

			$dc_title = $xml->GetRecord->record->metadata->oai_dc_dc->dc_title;
			foreach ($dc_title as $meta => $value) {
				$attr = (array)$value->attributes();
				$lang = $attr['@attributes']['lang'];

				if (sonumero($lang) == $lang) {
					$lang = $language[$lang];
				}
				$tit = (string)$value[0];
				$tit = nbr_title($tit);

				$IDL = $RDFLiteral->name($tit, $lang);
				$data['d_literal'] = $IDL;
				$RDFData->check($data);
			}


			/**************************************************** Authors ************/
			$authors = (array)$xml->GetRecord->record->metadata->oai_dc_dc->dc_creator;
			if (count($authors) > 0) {
				$RDF_Authority = new \App\Models\RDF_Authority();
				$AUTH = $RDF_Authority->prepare($authors);

				/* Salva relações de autoria */
				for ($z = 0; $z < count($AUTH); $z++) {
					$prop = $RDFClass->class('dc:creator');
					$data = array();
					$data['d_r1'] = $IDW;
					$data['d_r2'] = $AUTH[$z];
					$data['d_p'] = $prop;
					$data['d_library'] = LIBRARY;
					$RDFData->check($data);
				}
			}

			/********************************************* Associa Artigo ao evento */
			$prop = $RDFClass->class('brapci:isWorkOf');
			$data = array();
			$data['d_r1'] = $IDW;
			$data['d_r2'] = $IDISSUE;
			$data['d_p'] = $prop;
			$data['d_library'] = LIBRARY;
			$RDFData->check($data);

			/************************************************** Publication Section */
			$section = (string)$xml->GetRecord->record->header->setSpec;
			$IDSec = $RDF_Section->check($section, $jnl);
			$data = array();
			$prop = $RDFClass->class('brapci:hasSection');
			$data['d_r1'] = $IDW;
			$data['d_r2'] = $IDSec;
			$data['d_p'] = $prop;
			$data['d_library'] = LIBRARY;
			$RDFData->check($data);


			//IDISSUE


			/* Update register */
			$id = $di['id_ls'];
			$dts['li_process'] = 2;
			$this->set_status($id, $dts);

			$sx .= '<h1>' . $IDW . '</h1>';

			/* Reload page */
			$sx .= '<meta http-equiv="refresh" content="1">';
		}

		return $sx;
	}
}
