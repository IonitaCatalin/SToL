<?php

class CDownload extends Controller
{
	private $model;

	public function __construct()
	{
		$this->model=$this->model('mdownload');
	}

	public function testFunction($user_id)
	{
		try
		{
			//$this->model->testDownloadGoogledrive($user_id);
			$this->model->testDownloadDropbox($user_id);
		}
		catch(Exception $exception)
		{
			echo $exception;
		}
	}
}

?>