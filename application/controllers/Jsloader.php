<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Jsloader
 *
 * @property Util_model $util
 */
class Jsloader extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('ac/util_model', 'util');
	}

	/**
	 * Принимает файл и отправляет на поиск переменных
	 *
	 * @param mixed $file
	 */
	public function file($file = null): void
	{
		if (! isset($file)) {
			header('HTTP/1.1 404 Not Found');
			exit;
		}

		$contents = $this->util->parse_js($file);

		if (! $contents) {
			header('HTTP/1.1 404 Not Found');
			exit;
		} else {
			header('Content-Type: text/javascript');
			echo $contents;
		}
	}
}
