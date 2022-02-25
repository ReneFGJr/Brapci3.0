<?php

namespace App\Models\Oaipmh;

use CodeIgniter\Model;

class OaipmhRegister extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'source_listrecords';
	protected $primaryKey           = 'id_lr';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_lr', 'lr_identifier', 'lr_datestamp',
		'lr_status', 'lr_jnl', '	lr_setSpec',
		'lr_procees', 'lr_local_file','lr_work'
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

	function le($id)
	{
		$this->join('source_listsets', 'lr_setSpec = id_ls');
		$dt = $this->find($id);
		return $dt;
	}

	function set_status($id, $dt)
	{
		$this->set($dt);
		$this->where('id_lr', $id);
		$this->update();
	}

	function record($id)
	{
		$sx = '';
		$dt = $this->le($id);

		$sx1 = small(lang('brapci.oai_identifier'));
		$sx1 .= h($dt['lr_identifier'], 4);

		$sx1 .= small(lang('brapci.oai_datastamp'), 'mt-3');
		$sx1 .= h($dt['lr_datestamp'], 6);

		$sx1 .= small(lang('brapci.oai_status'), 'mt-3');
		$sx1 .= h($dt['lr_status'], 6);

		$sx1 .= small(lang('brapci.oai_setSpec'), 'mt-3');
		$sx1 .= h($dt['ls_setName'] . ' (' . $dt['ls_setSpec'] . ')', 6);

		$sx1 .= small(lang('brapci.lr_procees'), 'mt-3');
		$sx1 .= h(lang('brapci.oai_status_' . $dt['lr_procees']), 6);

		$sx1 .= small(lang('brapci.lr_local_file'), 'mt-3');
		$sx1 .= h($this->file_link($dt['lr_local_file']) . '&nbsp;', 6);

		$sx2 = $this->actions($dt, 'B');


		$sx .= bsc($sx1, 10);
		$sx .= bsc($sx2, 2);

		$sx = bs($sx);

		return $sx;
	}

	function file_link($file)
	{
		if ($file == '') {
			return '';
		}

		$sx = '<a href="' . URL . $file . '" target="_blank">' . $file . '</a>';
		return $sx;
	}

	function actions($dt, $tp = 'L')
	{
		$sx = '';
		$class = 'link-action small';
		$sep = '';
		if ($tp == 'L') {
			$sep = ' | ';
		}
		if ($tp == 'B') {
			$class = 'mb-3 btn btn-outline-primary btn-sm" style="width:100%';
		}
		$ret = '<a href="' . PATH . MODULE . 'admin/oai/set_status/' . $dt['id_lr'] . '/0' . '" class="' . $class . '">' . lang('brapci.oai_return_harvesting') . '</a> ';
		switch ($dt['lr_procees']) {
			case 0:
				$sx .= '<a href="' . PATH . MODULE . 'admin/oai/get_record/' . $dt['id_lr'] . '" class="' . $class . '">' . lang('brapci.get_oai_register') . '</a> ';
				break;
			case 1:
				$sx .= '<a href="' . PATH . MODULE . 'admin/oai/process_record/' . $dt['id_lr'] . '" class="' . $class . '">' . lang('brapci.process_oai_register') . '</a> ';
				$sx .= $sep . $ret;
				break;
		}
		if (($sx != '') and ($tp == 'B')) {
			$sx = small(lang('brapci.actions')) . $sx;
		}
		return $sx;
	}

	function next($id, $st = 0)
	{
		$di = $this
			->where('lr_procees', $st)
			->where('lr_jnl', $id)
			->first();

		if ($this->db->resultID->num_rows == 0) {
			return 0;
		}
		if (count($di) == 0) {
			return 0;
		}
		return $di['id_lr'];
	}

	function get_record($id)
	{
		$dt = $this->find($id);
		$sx = $this->get_record_execute($dt);
		return $sx;
	}

	function get_record_jnl($id)
	/******************xxxx arrumaer */
	{
		$OaipmhListSetSepc = new \App\Models\Oaipmh\OaipmhListSetSepc();
		$sx = '';
		/* recupera proximo */
		$DI = $this->next($id, 0);

		/* Reload page */
		/*
		$sx .= '<meta http-equiv="refresh" content="1">';
		} else {
			$sx .= bsmessage('Process Finish', 3);
		}
		*/
	}

	function get_record_execute($dz)
	{
		$sx = '';
		$id = $dz['id_lr'];
		$ID = $dz['lr_identifier'];
		$jnl = $dz['lr_jnl'];
		$issue = $dz['lr_issue'];

		if ($issue == 0) {
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
		dircheck($dir);
		$dir .= 'oai/';
		dircheck($dir);
		$dir .= date("y") . '/';
		dircheck($dir);
		$dir .= date("m") . '/';
		dircheck($dir);
		$file = str_replace(array(' ', ':', '?', '/', '\\'), '_', $ID);
		$file = strzero($jnl, 6) . '_' . strzero($issue, 6) . '_' . $file;
		$file .= '.xml';

		/* Load Url */
		if (file_exists($dir . $file)) {
			$xml = file_get_contents($dir . $file);
			$sx .= bsmessage('Already harvested OAIPMH ' . $ID);
		} else {
			$xml = file_get_contents($url);
			file_put_contents($dir . $file, $xml);
			$sx .= bsmessage('Harvested OAIPMH ' . $ID);
		}
		$xml = simplexml_load_string($xml);


		/* Update register */
		$dts['lr_procees'] = 1;
		$dts['lr_local_file'] = $dir . $file;


		$dd['lr_procees'] = 1;
		$dd['lr_local_file'] = $dir . $file;
		$this->set_status($id, $dd);

		return $sx;
	}

	function le_xml($data)
	{
		if (isset($data['lr_local_file'])) {
			$file = $data['lr_local_file'];
		} else {
			$file = $data['li_local_file'];
		}


		if (file_exists($file)) {
			$txt = file_get_contents($file);
			$txt = str_replace(array('<dc:'), '<dc_', $txt);
			$txt = str_replace(array('</dc:'), '</dc_', $txt);
			$txt = str_replace(array('<oai_dc:'), '<oai_dc_', $txt);
			$txt = str_replace(array('</oai_dc:'), '</oai_dc_', $txt);
			$txt = str_replace(array('xml:'), '', $txt);

			$xml = (array)simplexml_load_string($txt);
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

	function process_record($id)
	{
		$sx = $this->method_01($id);
		return ($sx);
		/*
				$prep = new \App\Models\OpenDataPreposition();
				$country = new \App\Models\OpenDataCountry();				

				$prep->export();
				$country->export();
				*/



		$RDFData = new \App\Models\RDF\RDFData();
		$RDFClass = new \App\Models\RDF\RDFClass();
		$RDFLiteral = new \App\Models\RDF\RDFLiteral();
		$RDFConcept = new \App\Models\RDF\RDFConcept();

		$Journals = new \App\Models\Journal\Journals();

		$RDFClass = new \App\Models\RDF\RDFClass();

		$sx = '';

		if ($id == 0) {
			$id = $this->next($id, 1);
			if ($id == 0) {
				return 'FIM';
			}
		}
	}

	function class_type($dt)
	{
		$Journals = new \App\Models\Journal\Journals();
		/************************************** Classes */
		$jnl = $Journals->le_rdf($dt['lr_jnl']);
		$dt = array();
		if (count($jnl) > 0) {
			$jnl = $jnl[0];
			if ($jnl['jnl_collection'] == 'EV') {
				$dt['class'] = "brapci:Proceeding";
				$dt['tp'] = 'EV';
				$dt['jnl_frbr'] = $jnl['jnl_frbr'];
				$dt['section_class'] = 'brapci:ProceedingSection';
				$dt['issue'] = 'brapci:IssueProceeding';
				$dt['propIssue'] = 'brapci:hasIssueProceeding';
				
			} else {
				$dt['class'] = "brapci:Article";
				$dt['tp'] = 'AR';
				$dt['jnl_frbr'] = $jnl['jnl_frbr'];
				$dt['section_class'] = 'dc:ArticleSection';
				$dt['issue'] = 'dc:Issue';
				$dt['propIssue'] = 'dc:hasIssue';
			}
		}
		return $dt;
	}

	function xml_read($xml,$var)
		{
			$Language = new \App\Models\AI\NLP\Language();
			$rsp = array();
			if (isset($xml[$var]))
				{
					$xmlv = $xml[$var];
					if (is_array($xmlv))
						{
							for ($r=0;$r < count($xmlv);$r++)
								{
									$lang = $Language->getTextLanguage($xmlv[$r]);
									$rsp[$xmlv[$r]] = $lang;
								}
						} else {
							$lang = $Language->getTextLanguage($xmlv);
							$rsp[$xmlv] = $lang;
						}
				} else {
					echo "OPS";
					$rsp = array();
				}
			return $rsp;
		}

	function Method_01($id)
	{
		$sx = '';
		$Language = new \App\Models\AI\NLP\Language();
		$RDF = new \App\Models\RDF\RDF();

		$dt = $this->find($id);

		if ($dt=='')
			{
				return "NOT FOUND - XXX";
			}
		
		$type = $this->class_type($dt);
		$class = $type['class'];
		$tp = $type['tp'];
		$jnl_frbr = $type['jnl_frbr'];
		$section_class = $type['section_class'];
		$issue = $type['issue'];
		$propIssue = $type['propIssue'];

		/********************************************************************************************* CREATE WORK */
		$IDW = strzero($jnl_frbr, 8) . '_' . $dt['lr_identifier'];
		$IDW = $RDF->RDF_concept($IDW, $class);
		
		$xml = $this->le_xml($dt);

		/********************************************************************************************* TITULOS *****/
		//brapci:hasTitle

		$GetRecord = (array)$xml['GetRecord'];
		$record = (array)$GetRecord['record'];
		$metadata = (array)$record['metadata'];
		$metadata = (array)$metadata['oai_dc_dc'];

		/***********************************************************************/
		$OaipmhListSetSepc = new \App\Models\OaiPmh\OaipmhListSetSepc();
		$set = $OaipmhListSetSepc->find($dt['lr_setSpec']);
		$section = $set['ls_setName'];
		//echo '<pre>';
		//print_r($dt);
		//echo '</pre>';
		$sx .= anchor(PATH.'res/v/'.$IDW);

		/********************************************* ISSUE **/
		$source =$this->xml_read($metadata, 'dc_source');		
		$issueName = 'ISSUE_PROCESS'.':'.strzero($dt['lr_jnl'],6).':'.strzero($dt['lr_issue'],6);
		$id_issue = $RDF->RDF_concept($issueName, $issue);
		$RDF->RDP_property($jnl_frbr, $propIssue, $id_issue);
		$RDF->RDP_property($id_issue, $propIssue.'Of', $IDW);
		foreach($source as $issue_name=>$lang)
			{
				$source_literal = $RDF->RDF_literal($issue_name,$lang,$id_issue,'skos:altLabel');
			}
		

		/********************************************* Titulo */
		$title = $this->xml_read($metadata,'dc_title');
		$prop = 'brapci:hasTitle';
		foreach($title as $vlr=>$lang)
			{
				$titulo = nbr_title($vlr);	
				$RDF->RDF_literal($titulo, $lang, $IDW, $prop);
			}

		/*********************************************** Section */			
		$classSection = $section_class;
		$propSection = 'dc:hasSectionOf';
		$id_section = $RDF->RDF_concept($section, $classSection);
		$RDF->RDP_property($IDW, $propSection, $id_section);


		/********************************************* Titulo */
		$authors = $this->xml_read($metadata,'dc_creator');
		$aff = array();

		$classAuthor = 'brapci:Author';
		$propAuthor = 'brapci:hasAuthor';
		foreach($authors as $vlr=>$lang)
			{
				$nome = '';
				if (strpos($vlr,';') > 0)
					{
						$vlr = explode(';',$vlr);
						$nome = $vlr[0];
						if (isset($vlr[1]))
							{
								$aff = trim($vlr[1]);
							}
					}
				$nome = nbr_author($nome,1);	
				$id_author = $RDF->RDF_concept($nome, $classAuthor);
				$RDF->RDP_property($IDW, $propAuthor, $id_author);

				if (strlen($aff) > 0)
					{
						$classAdff= 'frbr:CorporateBody';
						$propAff = 'dc:affiliatedWith';
						$id_aff = $RDF->RDF_concept($aff, $classAdff);
						$RDF->RDP_property($id_author, $propAff, $id_aff);
					}
			}			

		/********************************************* Description */
		$abstract = $this->xml_read($metadata,'dc_description');
		$prop = 'dc:hasAbstract';
		foreach($abstract as $vlr=>$lang)
			{
				$RDF->RDF_literal($vlr, $lang, $IDW, $prop);
			}	

		$ids = $this->xml_read($metadata,'dc_identifier');
		$prop = 'brapci:hasRegisterId';
		foreach($ids as $vlr=>$lang)
			{
				$RDF->RDF_literal($vlr, $lang, $IDW, $prop);
			}	
		$dt['lr_procees'] = 2;
		$dt['lr_work'] = $IDW;
		$this->set_status($id, $dt);
		$sx = bs(bsc($sx));
		return $sx;

		/*************************************************************************************** JOURNAL AND ISSUE */
		/***********************************************************************************************************/
		/***********************************************************************************************************/

		/******************************** EVENTO */
		if (isset($di['id_lr'])) {
			/************************************************** Journal Publication */
			$IDJNL = $jnl['jnl_frbr'];

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
