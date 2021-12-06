<?php

namespace App\Models\Google;

use CodeIgniter\Model;

class CloudTranslation extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'cloudtranslations';
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

	function translate()
	{
	$clientId = "{your client id}";
	$clientSecret = "{your client secret}";
	$clientRedirectURL = "{your redirect URL}";
	$login_url = 'https://accounts.google.com/o/oauth2/v2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/cloud-translation') . '&redirect_uri=' . urlencode($clientRedirectURL) . '&response_type=code&client_id=' . $clientId . '&access_type=online';

	if (!isset($_GET['code']))
	{
	    header("location: $login_url");
	} else {
	    $code = filter_var($_GET['code'], FILTER_SANITIZE_STRING);  
	    $curlGet = '?client_id=' . $clientId . '&redirect_uri=' . $clientRedirectURL . '&client_secret=' . $clientSecret . '&code='. $code . '&grant_type=authorization_code';
	    $url = 'https://www.googleapis.com/oauth2/v4/token' . $curlGet;

	    $ch = curl_init($url);      
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);        
	    curl_setopt($ch, CURLOPT_POST, 1);      
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    $data = curl_exec($ch); 
	    $data = json_decode($data, true);   
	    curl_close($ch);

	    $accessToken = $data['access_token'];
	    $apiKey = "{your api key}";
	    $projectID = "{your project id}";

	    $target = "https://translation.googleapis.com/v3/projects/$projectID:translateText?key=$apiKey";

	    $headers = array( 
        	"Content-Type: application/json; charset=utf-8", 
        	"Authorization: Bearer " . $accessToken,
        	"x-goog-encode-response-if-executable: base64",
        	"Accept-language: en-US,en;q=0.9,es;q=0.8"
    	);

	    $requestBody = array();
	    $requestBody['sourceLanguageCode'] = "en";
	    $requestBody['targetLanguageCode'] = "pt";
	    $requestBody['contents'] = array("So, I guess this thing works?");
	    $requestBody['mimeType'] = "text/plain";

	    $ch = curl_init($target);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody)); 
	    $data = curl_exec($ch);

	    curl_close($ch);
	    echo $data;
	}
}
