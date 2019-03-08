<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Jsloader
 */
class Jsloader extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Принимает файл и отправляет на поиск переменных
	 *
	 * @param mixed $file
	 */
	public function file($file = null): void
	{
		if (!$file) {
			header('HTTP/1.1 404 Not Found');
			exit;
		}

		$this->load->helper('file');

		$contents = read_file('./js/ac/' . $file);

		if (!$contents) {
			header('HTTP/1.1 404 Not Found');
			exit;
		}

		$contents = $this->parse_variables($contents);

		header('Content-Type: text/javascript');

		echo $contents;
	}

	/**
	 * Ищет переменные [VAR] в скрипте, заменяет на найденый перевод или base_url
	 *
	 * @param string $text Скрипт
	 * @return string Готовый скрипт
	 */
	private function parse_variables(string $text): string
	{
		$this->lang->load('ac');

		preg_match_all("/\[@{0,1}[a-zA-Z0-9_]+[\s]*[a-zA-Z0-9_]*\]/", $text, $matches, PREG_PATTERN_ORDER);

		foreach ($matches[0] as $match) {
			$varname = str_replace('[', '', $match);
			$varname = str_replace(']', '', $varname);

			if ($varname == 'base_url') {
				$value = base_url('/');
			} else {
				$value = lang($varname);
			}

			if ($value) {
				$text = str_replace($match, $value, $text);
			}
		}

		return $text;
	}
}
