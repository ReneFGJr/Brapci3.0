<?php
# version: 0.21.07.30

namespace App\Models;

use CodeIgniter\Model;
use \app\Model\MainModel;

class Socials extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'users2';
	protected $primaryKey           = 'id_us';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        =
	[
		'id_us', 'us_nome', 'us_email',
		'us_image', 'us_genero', 'us_verificado',
		'us_login', 'us_password', 'us_password_method',
		'us_oauth2', 'us_lastaccess'
	];

	protected $typeFields        = [
		'hi',
		'st100*',
		'st100*',
		'hi', 'hi', 'hi',
		'st50', 'st50', 'hi',
		'hi', 'up'
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

	function user()
		{
			$sx = '';
			if (isset($_SESSION['user']['name']))
			{
				$user = $_SESSION['user']['name'];
				$sx = '
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						'.$user.'
						</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdown">
						<a class="dropdown-item" href="'.base_url(PATH.'social/perfil').'">Perfil</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="'.base_url(PATH.'social/logout').'">Logout</a>
						</div>
					</li>				
				';
			}
			return($sx);
		}

	function index($d1, $id, $dt=array(),$cab='')
	{
		switch ($d1) {
			case 'perfil':
				$sx = $cab;
				$sx .= $this->perfil();
				break;
			case 'view':
				$sx = $cab;
				$sx .= h("Usuários - View", 1);
				$this->id = $id;
				$dt =
					[
						'services' => $this->paginate(3),
						'pages' => $this->pager
					];
				$sx .= tableview($this,$dt);
				break;
			case 'edit':
				$sx = $cab;
				$sx .= bs(12);
				$sx .= h("Users - Editar", 1);
				$this->id = $id;				
				$sx .= form($this);
				$sx .= bsdivclose(3);
				break;
			case 'signup':
					$sx = $cab;
					$sx .= bs(12);
					$sx .= h("Users - Signup", 1);
					$this->id = $id;					
					$sx .= form($this);
					$sx .= bsdivclose(3);
					break;				
			case 'access_denied':
				$sx = view('access_denied');
				break;				
			case 'delete':
				$sx = $cab;
				$sx .= h("Serviços - Excluir", 1);
				$this->Social->id = $id;
				$sx .= form_del($this);
				break;
			case 'logout':
				return $this->logout();
				break;
			case 'login_local':
				return $this->login_local($dt);
				break;
			default:
				$sx = $cab;
				$sx .= bs(12);
				$sx .= h('Service not found - ' . $d1, 1);
				$sx .= anchor("main/social/access_denied","Acesso Negado (Page)",["class"=>"btn btn-outline-primary"]);
				$sx .= bsclose(3);
				break;
		}
		return $sx;
	}

	function perfil()
		{
			$id = $_SESSION['user']['id'];
			$sx = '';
			$sx .= bscontainer();
			$sx .= bsrow();

			$dt = $this->find($id);
			$sx .= bscol(10);
			/****************** Perfil */
			$sx .= h('Perfil '.$dt['us_nome'],1);
			$sx .= '<hr>';

			/****************** Dados **/
			$sx .= bscontainer(1);
			$sx .= bsrow();
			
			/****************** E-mail **/
			$sx .= bscol(6);
			$sx .= '<span clas="small">email</span>: <b>'.$dt['us_email'].'</b>';
			$sx .= bsdivclose();

			/****************** Login **/
			$sx .= bscol(6);
			$sx .= '<span clas="small">login</span>: <b>'.$dt['us_login'].'</b>';
			$sx .= bsdivclose();

			$sx .= bsdivclose();
			$sx .= bsdivclose();
			$sx .= bsdivclose();
			$sx .= '<hr>';

			/****************** Imagem **/
			$sx .= bscol(2);
			$sx .= 'IMAGE';
			$sx .= bsdivclose();

			$sx .= bsdivclose();
			$sx .= bsdivclose();
			$sx .= bsdivclose();
			//$sx .= '<style> div { border: 1px solid #000000; } </style>';
			return $sx;
		}

	function logout()
	{
		$session = \Config\Services::session();
		$url = \Config\Services::url();
		$session->destroy();

		return (redirect()->to('/'));
	}

	function login_local($dt)
	{
		$sx = '';
		$ok = 0;
		$d = $this->where('us_login', $dt['user_login'])->findAll();
		if (count($d) > 0) {
			$d = $d[0];
			$method = $d['us_password_method'];
			switch ($method) {
				default:
					$pw1 = md5($d['us_password']);
					$pw2 = md5($dt['user_password']);
					if ($pw1 == $pw2) {
						$ok = 1;
					} else {
						$sx = $this->login('Erro de Login');
					}
			}
		} else {
			$sx = $this->login('Erro de Login');
		}
		if ($ok == 1) {
			$_SESSION['user']['id'] = $d['id_us'];
			$_SESSION['user']['name'] = $d['us_nome'];
			return (redirect()->to('/'));
		}
		return ($sx);
	}

	function login($err = '')
	{
		global $msg;

		$sx = '';
		$bk = '#0093DD';
		$bknav = '#FFFFFF';
		$sx .= '
		<style>
		* {
		  box-sizing: border-box;
		}
		
		body {
		  font-family: Tahoma, Verdana, Segoe, sans-serif;
		  font-size: 14px;
		  text-align: center;
		}
		
		.wrapper {
		  width: 250px;
		  height: 350px;
		  margin: 30px auto;
		  perspective: 600px;
		  text-align: left;
		}
		
		.rec-prism {
		  width: 100%;
		  height: 100%;
		  position: relative;
		  transform-style: preserve-3d;
		  transform: translateZ(-100px);
		  transition: transform 0.5s ease-in;
		}
		
		.face {
		  position: absolute;
		  width: 250px;
		  height: 350px;
		  padding: 20px;
		  background: rgba(250, 250, 250, 0.96);
		  border: 3px solid '.$bk.';
		  border-radius: 3px;
		}
		.face .content {
		  color: #666;
		}
		.face .content h2 {
		  font-size: 1.2em;
		  color: '.$bk.';
		}
		.face .content .field-wrapper {
		  margin-top: 30px;
		  position: relative;
		}
		.face .content .field-wrapper label {
		  position: absolute;
		  pointer-events: none;
		  font-size: 0.85em;
		  top: 40%;
		  left: 0;
		  transform: translateY(-50%);
		  transition: all ease-in 0.25s;
		  color: #999999;
		}
		.face .content .field-wrapper input[type=text], .face .content .field-wrapper input[type=password], .face .content .field-wrapper input[type=submit], .face .content .field-wrapper textarea {
		  -webkit-appearance: none;
		  appearance: none;
		}
		.face .content .field-wrapper input[type=text]:focus, .face .content .field-wrapper input[type=password]:focus, .face .content .field-wrapper input[type=submit]:focus, .face .content .field-wrapper textarea:focus {
		  outline: none;
		}
		.face .content .field-wrapper input[type=text], .face .content .field-wrapper input[type=password], .face .content .field-wrapper textarea {
		  width: 100%;
		  border: none;
		  background: transparent;
		  line-height: 2em;
		  border-bottom: 1px solid '.$bk.';
		  color: #666;
		}
		.face .content .field-wrapper input[type=text]::-webkit-input-placeholder, .face .content .field-wrapper input[type=password]::-webkit-input-placeholder, .face .content .field-wrapper textarea::-webkit-input-placeholder {
		  opacity: 0;
		}
		.face .content .field-wrapper input[type=text]::-moz-placeholder, .face .content .field-wrapper input[type=password]::-moz-placeholder, .face .content .field-wrapper textarea::-moz-placeholder {
		  opacity: 0;
		}
		.face .content .field-wrapper input[type=text]:-ms-input-placeholder, .face .content .field-wrapper input[type=password]:-ms-input-placeholder, .face .content .field-wrapper textarea:-ms-input-placeholder {
		  opacity: 0;
		}
		.face .content .field-wrapper input[type=text]:-moz-placeholder, .face .content .field-wrapper input[type=password]:-moz-placeholder, .face .content .field-wrapper textarea:-moz-placeholder {
		  opacity: 0;
		}
		.face .content .field-wrapper input[type=text]:focus + label, .face .content .field-wrapper input[type=text]:not(:placeholder-shown) + label, .face .content .field-wrapper input[type=password]:focus + label, .face .content .field-wrapper input[type=password]:not(:placeholder-shown) + label, .face .content .field-wrapper textarea:focus + label, .face .content .field-wrapper textarea:not(:placeholder-shown) + label {
		  top: -35%;
		  color: #42509e;
		}
		.face .content .field-wrapper input[type=submit] {
		  -webkit-appearance: none;
		  appearance: none;
		  cursor: pointer;
		  width: 100%;
		  background: '.$bk.';
		  line-height: 2em;
		  color: #fff;
		  border: 1px solid '.$bk.';
		  border-radius: 3px;
		  padding: 5px;
		}
		.face .content .field-wrapper input[type=submit]:hover {
		  opacity: 0.9;
		}
		.face .content .field-wrapper input[type=submit]:active {
		  transform: scale(0.96);
		}
		.face .content .field-wrapper textarea {
		  resize: none;
		  line-height: 1em;
		}
		.face .content .field-wrapper textarea:focus + label, .face .content .field-wrapper textarea:not(:placeholder-shown) + label {
		  top: -25%;
		}
		.face .thank-you-msg {
		  position: absolute;
		  width: 200px;
		  height: 130px;
		  text-align: center;
		  font-size: 2em;
		  color: '.$bk.';
		  left: 50%;
		  top: 50%;
		  -webkit-transform: translate(-50%, -50%);
		}
		.face .thank-you-msg:after {
		  position: absolute;
		  content: "";
		  width: 50px;
		  height: 25px;
		  border: 10px solid '.$bk.';
		  border-right: 0;
		  border-top: 0;
		  left: 50%;
		  top: 50%;
		  -webkit-transform: translate(-50%, -50%) rotate(0deg) scale(0);
		  transform: translate(-50%, -50%) rotate(0deg) scale(0);
		  -webkit-animation: success ease-in 0.15s forwards;
		  animation: success ease-in 0.15s forwards;
		  animation-delay: 2.5s;
		}
		.face-front {
		  transform: rotateY(0deg) translateZ(125px);
		}
		.face-top {
		  height: 250px;
		  transform: rotateX(90deg) translateZ(125px);
		}
		.face-back {
		  transform: rotateY(180deg) translateZ(125px);
		}
		.face-right {
		  transform: rotateY(90deg) translateZ(125px);
		}
		.face-left {
		  transform: rotateY(-90deg) translateZ(125px);
		}
		.face-bottom {
		  height: 250px;
		  transform: rotateX(-90deg) translateZ(225px);
		}
		
		.nav {
		  padding: 0;
		  text-align: center;
		}

		.nav li {
		  display: inline-block;
		  list-style-type: none;
		  font-size: 1em;
		  margin: 0 10px;
		  color: '.$bknav.';
		  position: relative;
		  cursor: pointer;
		}
		.nav li:after {
		  content: "";
		  position: absolute;
		  bottom: 0;
		  left: 0;
		  width: 20px;
		  border-bottom: 1px solid '.$bknav.';
		  transition: all ease-in 0.25s;
		}
		.nav li:hover:after {
		  width: 100%;
		}
		
		.psw, .signup, .singin {
		  display: block;
		  margin: 20px 0;
		  font-size: 0.75em;
		  text-align: center;
		  color: #42509e;
		  cursor: pointer;
		}
		
		small {
		  font-size: 0.7em;
		}
		
		@-webkit-keyframes success {
		  from {
			-webkit-transform: translate(-50%, -50%) rotate(0) scale(0);
		  }
		  to {
			-webkit-transform: translate(-50%, -50%) rotate(-45deg) scale(1);
		  }
		}
		</style>
		
		<script>
		  window.console = window.console || function(t) {};
		  if (document.location.search.match(/type=embed/gi)) {
			window.parent.postMessage("resize", "*");
		  }
		</script>
		';
		$lg = '		
		<ul class="nav center" style="margin: 0% 20%; display: none;">
		<li onclick="showLogin()">'.lang('socials.social_login').'</li>
		<li onclick="showSignup()">'.lang('socials.social_sign_up').'</li>
		<li onclick="showForgotPassword()">'.lang('socials.social_forgot_password').'</li>
		<li onclick="showSubscribe()">'.lang('socials.social_subscrime').'</li>
		<li onclick="showContactUs()">'.lang('socials.social_contact_us').'</li>
		</ul>
		
		<div class="wrapper">
		  <div class="rec-prism">
			<div class="face face-top">
			  <div class="content">
				<h2>'.lang('socials.social_subscribe').'</h2>
				<small>'.lang('socials.social_subscribe_inf').'</small>
				<form onsubmit="event.preventDefault()">
				  <div class="field-wrapper">
					<input type="text" name="email" placeholder="email">
					<label>e-mail</label>
				  </div>
				  <div class="field-wrapper">
					<input type="submit" onclick="showThankYou()">
				  </div>
				</form>
			  </div>
			</div>
			<div class="face face-front">
			  <div class="content">
				<h2>'.lang('socials.social_sign_in').'</h2>
				<form action="' . base_url(PATH . 'social/login_local') . '" method="post">
				  <div class="field-wrapper">
					<input type="text" name="user_login" placeholder="'.lang('socials.user_login').'">
					<label>'.lang('socials.social_type_login').'</label>
				  </div>
				  <div class="field-wrapper">
					<input type="password" name="user_password" placeholder="'.lang('socials.user_password').'" autocomplete="new-password">
					<label>'.lang('socials.social_type_password').'</label>
				  </div>
				  <div class="field-wrapper">
					<input type="submit" onclick="showThankYou()">
				  </div>
				  <span class="psw" onclick="showForgotPassword()">'.lang('socials.social_forgot_password').'</span>
				  <span class="signup" onclick="showSignup()">'.lang('socials.social_not_user').'  '.lang('socials.social_sign_up').'</span>
				</form>
			  </div>
			</div>
			<div class="face face-back">
			  <div class="content">
				<h2>'.lang('socials.social_forgot_password').'</h2>
				<small>'.lang('socials.social_forgot_password_info').'</small>
				<form onsubmit="event.preventDefault()">
				  <div class="field-wrapper">
					<input type="text" name="email" placeholder="email">
					<label>e-mail</label>
				  </div>
				  <div class="field-wrapper">
					<input type="submit" onclick="showThankYou()">
				  </div>
				</form>
			  </div>
			</div>
			<div class="face face-right">
			  <div class="content">
				<h2>'.lang('socials.social_sign_up').'</h2>
				<form onsubmit="event.preventDefault()">
				  <div class="field-wrapper">
					<input type="text" name="email" placeholder="email">
					<label>e-mail</label>
				  </div>
				  <div class="field-wrapper">
					<input type="password" name="password" placeholder="password" autocomplete="new-password">
					<label>'.lang('socials.social_type_password').'</label>
				  </div>
				  <div class="field-wrapper">
					<input type="password" name="password2" placeholder="password" autocomplete="new-password">
					<label>'.lang('socials.social_retype_password').'</label>
				  </div>
				  <div class="field-wrapper">
					<input type="submit" onclick="showThankYou()">
				  </div>
				  <span class="singin" onclick="showLogin()">'.lang('socials.social_alread_user').'  '.lang('socials.social_sign_in').'</span>
				</form>
			  </div>
			</div>
			<div class="face face-left">
			  <div class="content">
				<h2>'.lang('socials.social_contact_us').'</h2>
				<form onsubmit="event.preventDefault()">
				  <div class="field-wrapper">
					<input type="text" name="name" placeholder="name">
					<label>'.lang('socials.social_name').'</label>
				  </div>
				  <div class="field-wrapper">
					<input type="text" name="email" placeholder="email">
					<label>e-mail</label>
				  </div>
				  <div class="field-wrapper">
					<textarea placeholder="'.lang('socials.social_yourmessage').'" rows=4></textarea>
					<label>'.lang('socials.social_yourmessage').'</label>
				  </div>
				  <div class="field-wrapper">
					<input type="submit" onclick="showThankYou()">
				  </div>
				</form>
			  </div>
			</div>
			<div class="face face-bottom">
			  <div class="content">
				<div class="thank-you-msg">
		  			REGISTRANDO!<br>
				  '.lang('socials.thank you').'
				</div>
			  </div>
			</div>
		  </div>
		</div>	
		';
		
		$sx .= '
		<script>
		  window.HUB_EVENTS={ASSET_ADDED:"ASSET_ADDED",ASSET_DELETED:"ASSET_DELETED",ASSET_DESELECTED:"ASSET_DESELECTED",ASSET_SELECTED:"ASSET_SELECTED",ASSET_UPDATED:"ASSET_UPDATED",CONSOLE_CHANGE:"CONSOLE_CHANGE",CONSOLE_CLOSED:"CONSOLE_CLOSED",CONSOLE_EVENT:"CONSOLE_EVENT",CONSOLE_OPENED:"CONSOLE_OPENED",CONSOLE_RUN_COMMAND:"CONSOLE_RUN_COMMAND",CONSOLE_SERVER_CHANGE:"CONSOLE_SERVER_CHANGE",EMBED_ACTIVE_PEN_CHANGE:"EMBED_ACTIVE_PEN_CHANGE",EMBED_ACTIVE_THEME_CHANGE:"EMBED_ACTIVE_THEME_CHANGE",EMBED_ATTRIBUTE_CHANGE:"EMBED_ATTRIBUTE_CHANGE",EMBED_RESHOWN:"EMBED_RESHOWN",FORMAT_FINISH:"FORMAT_FINISH",FORMAT_ERROR:"FORMAT_ERROR",FORMAT_START:"FORMAT_START",IFRAME_PREVIEW_RELOAD_CSS:"IFRAME_PREVIEW_RELOAD_CSS",IFRAME_PREVIEW_URL_CHANGE:"IFRAME_PREVIEW_URL_CHANGE",KEY_PRESS:"KEY_PRESS",LINTER_FINISH:"LINTER_FINISH",LINTER_START:"LINTER_START",PEN_CHANGE_SERVER:"PEN_CHANGE_SERVER",PEN_CHANGE:"PEN_CHANGE",PEN_EDITOR_CLOSE:"PEN_EDITOR_CLOSE",PEN_EDITOR_CODE_FOLD:"PEN_EDITOR_CODE_FOLD",PEN_EDITOR_ERRORS:"PEN_EDITOR_ERRORS",PEN_EDITOR_EXPAND:"PEN_EDITOR_EXPAND",PEN_EDITOR_FOLD_ALL:"PEN_EDITOR_FOLD_ALL",PEN_EDITOR_LOADED:"PEN_EDITOR_LOADED",PEN_EDITOR_REFRESH_REQUEST:"PEN_EDITOR_REFRESH_REQUEST",PEN_EDITOR_RESET_SIZES:"PEN_EDITOR_RESET_SIZES",PEN_EDITOR_SIZES_CHANGE:"PEN_EDITOR_SIZES_CHANGE",PEN_EDITOR_UI_CHANGE_SERVER:"PEN_EDITOR_UI_CHANGE_SERVER",PEN_EDITOR_UI_CHANGE:"PEN_EDITOR_UI_CHANGE",PEN_EDITOR_UI_DISABLE:"PEN_EDITOR_UI_DISABLE",PEN_EDITOR_UI_ENABLE:"PEN_EDITOR_UI_ENABLE",PEN_EDITOR_UNFOLD_ALL:"PEN_EDITOR_UNFOLD_ALL",PEN_ERROR_INFINITE_LOOP:"PEN_ERROR_INFINITE_LOOP",PEN_ERROR_RUNTIME:"PEN_ERROR_RUNTIME",PEN_ERRORS:"PEN_ERRORS",PEN_LIVE_CHANGE:"PEN_LIVE_CHANGE",PEN_LOGS:"PEN_LOGS",PEN_MANIFEST_CHANGE:"PEN_MANIFEST_CHANGE",PEN_MANIFEST_FULL:"PEN_MANIFEST_FULL",PEN_PREVIEW_FINISH:"PEN_PREVIEW_FINISH",PEN_PREVIEW_START:"PEN_PREVIEW_START",PEN_SAVED:"PEN_SAVED",POPUP_CLOSE:"POPUP_CLOSE",POPUP_OPEN:"POPUP_OPEN",POST_CHANGE:"POST_CHANGE",POST_SAVED:"POST_SAVED",PROCESSING_FINISH:"PROCESSING_FINISH",PROCESSING_START:"PROCESSED_STARTED"},"object"!=typeof window.CP&&(window.CP={}),window.CP.PenTimer={programNoLongerBeingMonitored:!1,timeOfFirstCallToShouldStopLoop:0,_loopExits:{},_loopTimers:{},START_MONITORING_AFTER:2e3,STOP_ALL_MONITORING_TIMEOUT:5e3,MAX_TIME_IN_LOOP_WO_EXIT:2200,exitedLoop:function(E){this._loopExits[E]=!0},shouldStopLoop:function(E){if(this.programKilledSoStopMonitoring)return!0;if(this.programNoLongerBeingMonitored)return!1;if(this._loopExits[E])return!1;var _=this._getTime();if(0===this.timeOfFirstCallToShouldStopLoop)return this.timeOfFirstCallToShouldStopLoop=_,!1;var o=_-this.timeOfFirstCallToShouldStopLoop;if(o<this.START_MONITORING_AFTER)return!1;if(o>this.STOP_ALL_MONITORING_TIMEOUT)return this.programNoLongerBeingMonitored=!0,!1;try{this._checkOnInfiniteLoop(E,_)}catch(N){return this._sendErrorMessageToEditor(),this.programKilledSoStopMonitoring=!0,!0}return!1},_sendErrorMessageToEditor:function(){try{if(this._shouldPostMessage()){var E={topic:HUB_EVENTS.PEN_ERROR_INFINITE_LOOP,data:{line:this._findAroundLineNumber()}};parent.postMessage(E,"*")}else this._throwAnErrorToStopPen()}catch(_){this._throwAnErrorToStopPen()}},_shouldPostMessage:function(){return document.location.href.match(/boomboom/)},_throwAnErrorToStopPen:function(){throw"We found an infinite loop in your Pen. We´ve stopped the Pen from running. More details and workarounds at https://blog.codepen.io/2016/06/08/can-adjust-infinite-loop-protection-timing/"},_findAroundLineNumber:function(){var E=new Error,_=0;if(E.stack){var o=E.stack.match(/boomboom\S+:(\d+):\d+/);o&&(_=o[1])}return _},_checkOnInfiniteLoop:function(E,_){if(!this._loopTimers[E])return this._loopTimers[E]=_,!1;var o;if(_-this._loopTimers[E]>this.MAX_TIME_IN_LOOP_WO_EXIT)throw"Infinite Loop found on loop: "+E},_getTime:function(){return+new Date}},window.CP.shouldStopExecution=function(E){var _=window.CP.PenTimer.shouldStopLoop(E);return!0===_&&console.warn("[CodePen]: An infinite loop (or a loop taking too long) was detected, so we stopped its execution. Sorry!"),_},window.CP.exitedLoop=function(E){window.CP.PenTimer.exitedLoop(E)};
		</script>
		<script id="rendered-js" >
		let prism = document.querySelector(".rec-prism");
		
		function showSignup() {
		  prism.style.transform = "translateZ(-100px) rotateY( -90deg)";
		}
		function showLogin() {
		  prism.style.transform = "translateZ(-100px)";
		}
		function showForgotPassword() {
		  prism.style.transform = "translateZ(-100px) rotateY( -180deg)";
		}
		
		function showSubscribe() {
		  prism.style.transform = "translateZ(-100px) rotateX( -90deg)";
		}
		
		function showContactUs() {
		  prism.style.transform = "translateZ(-100px) rotateY( 90deg)";
		}
		
		function showThankYou() {
		  prism.style.transform = "translateZ(-100px) rotateX( 90deg)";
		}
		//# sourceURL=pen.js
			</script>
		';
		$sx .= bs($lg);
		return $sx;
	}

	function access_denied()
		{
			$sx = $this->view('access_denied.php');
		}
}
