<?php

namespace App\Models\OaiPmhServer;

use CodeIgniter\Model;

class Index extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'indices';
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

	var $email = 'renefgj@gmail.com';

	// Validador = https://validator.oaipmh.com/
	function index($d1 = '', $d2 = '', $d3 = '', $d4 = '', $d5 = '')
	{
		$verb = get("verb");
		$oai = array();
		switch ($verb) {

			case 'Identify':
				$this->Identify($oai);
				break;
			case 'ListMetadataFormats':
				$this->ListMetadataFormats($oai);
				break;
			default:
				$oai['@code'] = 'badVerb';
				$oai['error'] = 'badVerb';
				$this->xml($oai);
				exit;
		}
	}
	function ListMetadataFormats()
	{
		$oai = array();
		$oai['ListMetadataFormats']['metadataFormat']['metadataPrefix'] = 'oai_dc';
		$oai['ListMetadataFormats']['metadataFormat']['schema'] = 'http://www.openarchives.org/OAI/2.0/oai_dc.xsd';
		$oai['ListMetadataFormats']['metadataFormat']['metadataNamespace'] = 'http://www.openarchives.org/OAI/2.0/oai_dc/';
		return $this->xml($oai);
	}
	function identify($oai)
	{
		$oai['Identify']['repositoryName'] = 'Brapci OAI-PMH Server';
		$oai['Identify']['baseURL'] = URL . MODULE . 'oai';
		$oai['Identify']['protocolVersion'] = '2.0';
		$oai['Identify']['adminEmail'] = $this->email;
		$oai['Identify']['earliestDatestamp'] = '2010-01-01T00:00:00Z';
		$oai['Identify']['deletedRecord'] = 'persistent';
		$oai['Identify']['granularity'] = 'YYYY-MM-DDThh:mm:ssZ';
		$oai['Identify']['compression'] = 'deflate';

		$oai['Identify']['description']['oai-identifier']['@xmlns'] = 'http://www.openarchives.org/OAI/2.0/oai-identifier';
		$oai['Identify']['description']['oai-identifier']['@xmlns:xsi'] = 'http://www.w3.org/2001/XMLSchema-instance';
		$oai['Identify']['description']['oai-identifier']['@xsi:schemaLocation'] = 'http://www.openarchives.org/OAI/2.0/oai-identifier http://www.openarchives.org/OAI/2.0/oai-identifier.xsd';
		$oai['Identify']['description']['oai-identifier']['scheme'] = 'oai';
		$oai['Identify']['description']['oai-identifier']['repositoryIdentifier'] = 'bracpi.inf.br';
		$oai['Identify']['description']['oai-identifier']['delimiter'] = ':';
		$oai['Identify']['description']['oai-identifier']['sampleIdentifier'] = 'oai:brapci.inf.br:work/1';
		//$oai['Identify']['description']['description']['@xmlns'] = 'http://www.openarchives.org/OAI/2.0/oai-identifier';
		//$oai['Identify']['description']['description']['@xmlns:xsi'] = 'http://www.w3.org/2001/XMLSchema-instance';

		$oai['Identify']['toolkit']['@xmlns'] = 'http://oai.dlib.vt.edu/OAI/metadata/toolkit';
		$oai['Identify']['toolkit']['@xsi:schemaLocation'] = 'http://oai.dlib.vt.edu/OAI/metadata/toolkit http://oai.dlib.vt.edu/OAI/metadata/toolkit.xsd';
		$oai['Identify']['toolkit']['title'] = 'Brapci OAI-PMH Server';
		$oai['Identify']['toolkit']['author']['name'] = 'Rene Faustino Gabriel Junior';
		$oai['Identify']['toolkit']['author']['email'] = 'renefgj@gmail.com';
		$oai['Identify']['toolkit']['version'] = 'v0.21.12.02';
		$oai['Identify']['toolkit']['url'] = URL . MODULE;

		return $this->xml($oai);
	}


	function xml($dt)
	{
		$schema = 1;
		$xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . cr();
		if ($schema != '') {
			$xml .= '<?xml-stylesheet type="text/xsl" href="' . URL . 'xml/oai2.xsl' . '"?>' . cr();
		}
		$oai = array();
		$oai['OAI-PMH']['@xmlns'] = 'http://www.openarchives.org/OAI/2.0/';
		$oai['OAI-PMH']['@xmlns:xsi'] = 'http://www.w3.org/2001/XMLSchema-instance';
		$oai['OAI-PMH']['@xsi:schemaLocation'] = 'http://www.openarchives.org/OAI/2.0/ http://www.openarchives.org/OAI/2.0/OAI-PMH.xsd';
		$oai['OAI-PMH']['responseDate'] = date('Y-m-d\TH:i:s\Z');
		$rq['@verb'] = get("verb");
		$rq['request'] = URL . MODULE . 'oai';
		$rq = $this->convert($rq);
		$ln = troca($rq[0], '<request>', '<request' . $rq[1] . '>');
		$oai['OAI-PMH']['request'] = '_' . $ln;
		//if (isset($dt['error'])) { $oai['OAI-PMH']['error'] = $dt['error']; }
		if (isset($dt['error'])) {
			$oai['OAI-PMH']['@code'] = $dt['@code'];
			$oai['OAI-PMH']['error'] = $dt['error'];
		}

		/*****************************************************************************/
		if (isset($dt['Identify'])) {
			$oai['OAI-PMH']['Identify'] = $dt['Identify'];
		}
		if (isset($dt['ListMetadataFormats'])) {
			$oai['OAI-PMH']['ListMetadataFormats'] = $dt['ListMetadataFormats'];
		}
		if (isset($dt['ListSets'])) {
			$oai['OAI-PMH']['ListSets'] = $dt['ListSets'];
		}
		if (isset($dt['ListRecords'])) {
			$oai['OAI-PMH']['ListMetadataFormats'] = $dt['ListMetadataFormats'];
		}
		if (isset($dt['GetRecord'])) {
			$oai['OAI-PMH']['GetRecord'] = $dt['GetRecord'];
		}						
		if (isset($dt['ListIdentifiers'])) {
			$oai['OAI-PMH']['ListIdentifiers'] = $dt['ListIdentifiers'];
		}						
		if (isset($dt['ListRecords'])) {
			$oai['OAI-PMH']['ListRecords'] = $dt['ListRecords'];
		}						
		$rst = $this->convert($oai);
		$xml .= $rst[0];
		header('Content-Type: application/xml; charset=utf-8');
		echo $xml;
		exit;
		return '';
	}
	function convert($v)
	{
		$xml = '';
		$attr = '';
		//print_r($v);
		foreach ($v as $key => $value) {
			if (is_array($value)) {
				$rst = $this->convert($value);
				$xml .= '<' . $key . $rst[1] . '>' . cr();
				$xml .= $rst[0];
				$xml .= '</' . $key . '>' . cr();
			} else {
				if (substr($key, 0, 1) == '@') {
					$key = substr($key, 1, strlen($key));
					$attr .= cr().' ' . $key . '="' . $value . '"';
				} else {
					if (substr($value,0,1) == '_')
						{
							$xml .= substr($value,1,strlen($value));
						} else {
							$xml .= '<' . $key . '>';
							$xml .= $value;
							$xml .= '</' . $key . '>' . cr();
						}
				}
			}
		}
		return array($xml, $attr);
	}
}
