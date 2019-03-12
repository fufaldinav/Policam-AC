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

		$contents = $this->parse_contents($contents);

		header('Content-Type: text/javascript');

		echo $contents;
	}

	/**
	 * Ищет [PREFIX_VAR] в тексте, заменяет соответственно найденым префиксу и имени переменной
	 *
	 * @param string $text Входящий текст
	 *
	 * @return string Текст с подставленными значениями
	 */
	private function parse_contents(string $text): string
	{
		preg_match_all('/\[(ci|lang)_[a-zA-Z0-9_]+\]/', $text, $matches, PREG_PATTERN_ORDER); //строка вида [ci_VAR] или [lang_VAR]

		foreach ($matches[0] as $match) {
			$var = trim($match, '[]');

			$value = $this->parse_variable($var);

			if ($value) {
				$text = str_replace($match, $value, $text);
			}
		}

		return $text;
	}

	/**
	 * Заменяет переменную значением
	 *
	 * @param string $var Строка с префиксом для подмены
	 *
	 * @return string Строка с подставленным значением
	 */
	private function parse_variable(string $var): string
	{
		$prefix = strstr($var, '_', true);

		$var = substr($var, strlen($prefix) + 1);

		if ($prefix == 'ci') {
			switch ($var) {
				case 'base_url':
					$this->load->helper('url');

					$value = base_url();

					break;

				default:
					$value = '';

					break;
			}
		} elseif ($prefix == 'lang') {
			$this->lang->load('ac');

			$this->load->helper('language');

			$value = lang($var);
		}

		return $value;
	}
}
