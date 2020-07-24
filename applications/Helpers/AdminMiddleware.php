<?php
namespace App\Helpers;
if (!defined('Z_MVC')) die ("Not Allowed");

class AdminMiddleware
{
	function __construct()
	{
		if(!session()->get('id') || session()->user()->level != "admin")
		{
			redirect(base_url());
			die();
		}
	}
}