<?php
namespace App\Helpers;

class Uploader
{
	static function upload($file, $dest, $name = false)
	{
		$ext = pathinfo($file->name, PATHINFO_EXTENSION);
		$name = $name == false ? $file->name : $name.'.'.$ext;
		$dest = $dest."/".$name;
		if(copy($file->tmp_name, $dest))
			return base_url().'/'.$dest;
		return false;
	}
}