<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 17.03.2019
 *
 * Description: Приложение для систем контроля и управления доступом.
 *
 * Requirements: PHP7.2 or above
 *
 * @package Policam-AC
 * @author  Artem Fufaldin
 * @link    http://github.com/m2jest1c/Policam-AC
 * @filesource
 */
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Jsparser
 */
class Jsparser extends Ac
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Парсинг JS. Ищет [PREFIX_VAR] в тексте,
     * заменяет соответственно найденым префиксу и имени переменной
     *
     * @param string $file Файл JS
     *
     * @return string|null Готовый к выводу в браузер JS
     */
    public function parse(string $file): ?string
    {
        $this->_CI->load->helper('file');

        $contents = read_file("./js/ac/$file");

        if (! $contents) {
            return null;
        }

        //строка вида [ci_VAR], [config_VAR] или [lang_VAR]
        preg_match_all('/\[(ci|config|lang)_[a-zA-Z0-9_]+\]/', $contents, $matches, PREG_PATTERN_ORDER);

        foreach ($matches[0] as $match) {
            $var = trim($match, '[]');

            $value = $this->_parse_variable($var);

            if ($value) {
                $contents = str_replace($match, $value, $contents);
            }
        }

        return $contents;
    }

    /**
     * Заменяет переменную значением
     *
     * @param string $var Строка с префиксом для подмены
     *
     * @return string Строка с подставленным значением
     */
    private function _parse_variable(string $var): string
    {
        $prefix = strstr($var, '_', true);

        $var = substr($var, strlen($prefix) + 1);

        if ($prefix === 'ci') {
            switch ($var) {
                case 'base_url':
                    $this->_CI->load->helper('url');

                    $value = base_url();

                    break;

                case 'site_url':
                    $this->_CI->load->helper('url');

                    $value = site_url();

                    break;
                default:
                    $value = '';

                    break;
            }
        } elseif ($prefix === 'config') {
            $value = $this->_CI->config->item($var, 'ac');
        } elseif ($prefix === 'lang') {
            $this->_CI->lang->load('ac');

            $this->_CI->load->helper('language');

            $value = lang($var);
        }

        return $value;
    }
}
