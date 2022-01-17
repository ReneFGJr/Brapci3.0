<?php

namespace App\Models\Dataverse;

use __PHP_Incomplete_Class;
use CodeIgniter\Model;

class Dataverse extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'dataverses';
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

	//var $url = 'https://vitrinedadosabertos.inep.rnp.br/';
	var $url = 'http://200.130.0.214:8080/';
	var $apiKey = 'b8fb20a6-15ed-40b1-87e1-a1da20a82c1b';


/**********************************************************************
 * TESTED *************************************************************
 ***********************************************************************/
	function PQ1()
		{
			$dd = array();
			$dd['name'] = 'Bolsistas Produtividade PQ1A';
			$dd['alias'] = 'produtividadePQ1A';
			$dd['dataverseContacts'] = array();
			array_push($dd['dataverseContacts'], array('contactEmail' => 'cnpq@cnpq.br'));
			array_push($dd['dataverseContacts'], array('contactEmail' => 'lattesdata@cnpq.br'));
	
			$dd['affiliation'] = 'CNPq';
			$dd['description'] = 'Projetos dos Bolsistas Produtividade PQ1A';
			$dd['dataverseType'] = 'LABORATORY';
			$dd['id'] = '2018';
			$sx = $this->CreateDataverse($dd);			
			return $sx;
		}		
	function test()
		{
			$sx = '';
			
			echo $sx;

			if (isset($_GET['process']))
				{
					$id = sonumero($_GET['process']);
					//$sx .= $this->PQ1();
					$file = '../../Datasets/processos_pq1a/'.trim($id).'.json';
					
					echo '<hr>'.$file.'</hr>';				
					if (file_exists($file))
						{							
							echo "OK";
							$txt = file_get_contents($file);
							$txt = json_decode($txt);
							echo '<pre>';
							print_r($txt);
						} else {
							echo " - NOT FOUND";
						}
				}
			return $sx;
		}

	function ViewDataverseCollection($collection='lattesdata')
		{
		$api = 'api/dataverses';
		$url = $this->url . $api . '/'.$collection;

		$op = array();
		$rsp = $this->curl($url,'');

		echo '<pre style="color: blue;">'; print_r($rsp); echo '</pre>';
		exit;
		}

	function ViewDataverseTree($collection='lattesdata')
		{
		$api = 'api/info/metrics/tree/';
		$url = $this->url . $api;

		$rsp = $this->curl($url);

		echo '<pre style="color: blue;">=====>';
		print_r($rsp);
		echo '</pre>';
		exit;
		}

	function CreateDataverse($dd='')	
		{
		$url = $this->url.'api/dataverses/lattesdata';
		$id = $dd['id'];

		$json = json_encode($dd, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		$id = strzero(1,8);
		$file = '.tmp/dataverse/dataverse-'.$id.'.json';
		file_put_contents($file, $json);

		$dd['AUTH'] = true;
		$dd['POST'] = true;
		$dd['FILE'] = $file;

		$rsp = $this->curlExec($dd);
		$rsp = json_decode($rsp,true);
		
		$sta = $rsp['status'];
		switch($sta)
			{
				case 'OK':
					$sx = 'OK';
				break;
				case 'ERROR':
					$sx = '<pre style="color: red;">'; 
					$sx .= $rsp['message'];	
					$sx .= '<br>Dataverse Name: <b>'.$dd['alias'].'</b>';
					$sx .= '<br><a href="'.$this->url.'dataverse/'.$dd['alias'].'" target="_blank">'.$url.'/'.$dd['alias'].'</a>';
					$sx .= '</pre>';
					break;
			}
		return $sx;
		}

function curlExec($dt)
	{
		$api = 'api/dataverses/lattesdata';

		$url = $this->url.$api;
		
		/* Comando */
		$cmd = 'curl ';
		/* APIKEY */
		if (isset($dt['AUTH']))
			{
				$cmd .= '-H X-Dataverse-key:' . $this->apiKey . ' ';
			}

		/* POST */
		if (isset($dt['POST']))
			{
				$cmd .= '-X POST '.$url.' ';
			}
		
		/* POST */
		if (isset($dt['FILE']))
			{
				if (!file_exists($dt['FILE'])) 
				{ echo '<h1>File not found - '.$dt['FILE'].'</h1>'; exit; }
		//		$cmd .= '-H "Content-Type: application/json" ';
				$cmd .= '--upload-file ' . realpath($dt['FILE']).' ';
			}
		$txt = shell_exec($cmd);
		return $txt;
	}

function curl($url,$json='')
	{
		/********* URL */
		$ch = curl_init($url);
		echo h($url,1);

		// define options
		$optArray = array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			//CURLOPT_POST => 1
		);
		curl_setopt_array($ch, $optArray);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

		/*********************** TOKEN */
		$user_token = $this->apiKey;
		$headers = array(
			"X-Dataverse-Key: $user_token",
		);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);	

		if ($json != '')
		{
			$post = array(
				//'file' => new \CURLFile(realpath($file_path), mime_content_type($file_path), basename($file_path)),
				'jsonData' => $json, // optional
			);	
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}

		/* Executa */
		$response = curl_exec($ch);

		// Testa Erros
		if (!curl_errno($ch)) {
			$response = (array)json_decode($response);
		} else {
			echo "<hr>ERRO";
			exit;
		}		
		return $response;
	}



/**********************************************************************
 **********************************************************************		
 **********************************************************************/		




	function curlError($code)
	{

		$curl = array();
		$curl[1] = 'CURLE_UNSUPPORTED_PROTOCOL';
		$curl[2] = 'CURLE_FAILED_INIT';
		$curl[3] = 'CURLE_URL_MALFORMAT';
		$curl[4] = 'CURLE_URL_MALFORMAT_USER';
		$curl[5] = 'CURLE_COULDNT_RESOLVE_PROXY';
		$curl[6] = 'CURLE_COULDNT_RESOLVE_HOST';
		$curl[7] = 'CURLE_COULDNT_CONNECT';
		$curl[8] = 'CURLE_FTP_WEIRD_SERVER_REPLY';
		$curl[9] = 'CURLE_REMOTE_ACCESS_DENIED';
		$curl[11] = 'CURLE_FTP_WEIRD_PASS_REPLY';
		$curl[13] = 'CURLE_FTP_WEIRD_PASV_REPLY';
		$curl[14] = 'CURLE_FTP_WEIRD_227_FORMAT';
		$curl[15] = 'CURLE_FTP_CANT_GET_HOST';
		$curl[17] = 'CURLE_FTP_COULDNT_SET_TYPE';
		$curl[18] = 'CURLE_PARTIAL_FILE';
		$curl[19] = 'CURLE_FTP_COULDNT_RETR_FILE';
		$curl[21] = 'CURLE_QUOTE_ERROR';
		$curl[22] = 'CURLE_HTTP_RETURNED_ERROR';
		$curl[23] = 'CURLE_WRITE_ERROR';
		$curl[25] = 'CURLE_UPLOAD_FAILED';
		$curl[26] = 'CURLE_READ_ERROR';
		$curl[27] = 'CURLE_OUT_OF_MEMORY';
		$curl[28] = 'CURLE_OPERATION_TIMEDOUT';
		$curl[30] = 'CURLE_FTP_PORT_FAILED';
		$curl[31] = 'CURLE_FTP_COULDNT_USE_REST';
		$curl[33] = 'CURLE_RANGE_ERROR';
		$curl[34] = 'CURLE_HTTP_POST_ERROR';
		$curl[35] = 'CURLE_SSL_CONNECT_ERROR';
		$curl[36] = 'CURLE_BAD_DOWNLOAD_RESUME';
		$curl[37] = 'CURLE_FILE_COULDNT_READ_FILE';
		$curl[38] = 'CURLE_LDAP_CANNOT_BIND';
		$curl[39] = 'CURLE_LDAP_SEARCH_FAILED';
		$curl[41] = 'CURLE_FUNCTION_NOT_FOUND';
		$curl[42] = 'CURLE_ABORTED_BY_CALLBACK';
		$curl[43] = 'CURLE_BAD_FUNCTION_ARGUMENT';
		$curl[45] = 'CURLE_INTERFACE_FAILED';
		$curl[47] = 'CURLE_TOO_MANY_REDIRECTS';
		$curl[48] = 'CURLE_UNKNOWN_TELNET_OPTION';
		$curl[49] = 'CURLE_TELNET_OPTION_SYNTAX';
		$curl[51] = 'CURLE_PEER_FAILED_VERIFICATION';
		$curl[52] = 'CURLE_GOT_NOTHING';
		$curl[53] = 'CURLE_SSL_ENGINE_NOTFOUND';
		$curl[54] = 'CURLE_SSL_ENGINE_SETFAILED';
		$curl[55] = 'CURLE_SEND_ERROR';
		$curl[56] = 'CURLE_RECV_ERROR';
		$curl[58] = 'CURLE_SSL_CERTPROBLEM';
		$curl[59] = 'CURLE_SSL_CIPHER';
		$curl[60] = 'CURLE_SSL_CACERT';
		$curl[61] = 'CURLE_BAD_CONTENT_ENCODING';
		$curl[62] = 'CURLE_LDAP_INVALID_URL';
		$curl[63] = 'CURLE_FILESIZE_EXCEEDED';
		$curl[64] = 'CURLE_USE_SSL_FAILED';
		$curl[65] = 'CURLE_SEND_FAIL_REWIND';
		$curl[66] = 'CURLE_SSL_ENGINE_INITFAILED';
		$curl[67] = 'CURLE_LOGIN_DENIED';
		$curl[68] = 'CURLE_TFTP_NOTFOUND';
		$curl[69] = 'CURLE_TFTP_PERM';
		$curl[70] = 'CURLE_REMOTE_DISK_FULL';
		$curl[71] = 'CURLE_TFTP_ILLEGAL';
		$curl[72] = 'CURLE_TFTP_UNKNOWNID';
		$curl[73] = 'CURLE_REMOTE_FILE_EXISTS';
		$curl[74] = 'CURLE_TFTP_NOSUCHUSER';
		$curl[75] = 'CURLE_CONV_FAILED';
		$curl[76] = 'CURLE_CONV_REQD';
		$curl[77] = 'CURLE_SSL_CACERT_BADFILE';
		$curl[78] = 'CURLE_REMOTE_FILE_NOT_FOUND';
		$curl[79] = 'CURLE_SSH';
		$curl[80] = 'CURLE_SSL_SHUTDOWN_FAILED';
		$curl[81] = 'CURLE_AGAIN';
		$curl[82] = 'CURLE_SSL_CRL_BADFILE';
		$curl[83] = 'CURLE_SSL_ISSUER_ERROR';
		$curl[84] = 'CURLE_FTP_PRET_FAILED';
		$curl[84] = 'CURLE_FTP_PRET_FAILED';
		$curl[85] = 'CURLE_RTSP_CSEQ_ERROR';
		$curl[86] = 'CURLE_RTSP_SESSION_ERROR';
		$curl[87] = 'CURLE_FTP_BAD_FILE_LIST';
		$curl[88] = 'CURLE_CHUNK_FAILED';
		return $curl[$code];
	}
}
