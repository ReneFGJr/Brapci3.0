<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Roboti extends BaseController
{
	public function index($d1='',$d2='',$d3='',$d4='')
	{
		$Roboti = new \App\Models\Roboti\Index();
		$sx = $Roboti->task($d1,$d2,$d3,$d4);
		return $sx;
	}
}
