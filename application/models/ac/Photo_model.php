<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 01.03.2019
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
 * Class Photo Model
 * @property Util_model $util
 */
class Photo_model extends MY_Model
{
    /**
     * MD5 хэш фотографии
     *
     * @var string
     */
    public $hash;

    /**
     * Человек, которому принадлежит фотография
     *
     * @var int
     */
    public $person_id;

    /**
     * Время сохранения фотографии
     *
     * @var int
     */
    public $time;

    /**
     * Каталог с фото
     *
     * @var string $_img_path
     */
    private $_img_path;

    public function __construct()
    {
        parent::__construct();

        $this->_table = 'photo';
        $this->_foreing_key = 'person_id';

        $this->CI->config->load('ac', true);

        $this->_img_path = $this->CI->config->item('img_path', 'ac');

        if (! is_dir($this->_img_path)) {
            mkdir($this->_img_path, 0755, true);
        }
        if (! is_dir("$this->_img_path/s")) {
            mkdir("$this->_img_path/s", 0755, true);
        }
    }

    /**
     * Сохраняет фотографию
     *
     * @param mixed[] $file Файл фотографии
     *
     * @return mixed[] Отчет о сохранении
     */
    public function save_file(array $file): array //TODO проверка уже имеющейся фото за человеком
    {
        $response = [
            'id' => 0,
            'error' => ''
        ];

        $extensions = ['jpg', 'jpeg'];

        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_type = $file['type'];
        $file_size = $file['size'];


        $file_ext = explode('.', $file['name']);
        $file_ext = end($file_ext);
        $file_ext = strtolower($file_ext);
        $file_hash = hash_file('md5', $file_tmp);

        if (! in_array($file_ext, $extensions)) {
            $response['error'] = "Extension not allowed: $file_name $file_type"; //TODO перевод
        }

        if ($file_size > 8388608) {
            $response['error'] = "File size exceeds limit: $file_name $file_type"; //TODO перевод
        }

        if ($file_size === 0) {
            $response['error'] = 'Wrong file or file not exists'; //TODO перевод
        }

        if ($response['error'] === '') {
            $time = now('Asia/Yekaterinburg');

            $this->get_by('hash', $file_hash);

            $this->hash = $file_hash;
            $this->time = $time;

            $this->save();

            $response['id'] = $this->id;

            $this->delete_old();

            try {
                $file_path = "$this->_img_path/$this->id.jpg";

                move_uploaded_file($file_tmp, $file_path);
                //сохранение уменьшенной копии
                $params = [
                    'src_path' => $file_path,
                    'width' => 240,
                    'height' => 320,
                    'dst_path' => "$this->_img_path/s/$this->id.jpg"
                ];

                $this->_create_thumbnail($params);

                return $response;
            } catch (Exception $e) {
                $response['error'] = $e;

                $this->CI->load->library('logger');
                $this->CI->logger->save_errors($response['error']);

                return $response;
            }
        } else {
            $this->CI->load->library('logger');
            $this->CI->logger->save_errors($response['error']);

            return $response;
        }
    }

    /**
     * Удаляет фото из БД и диска
     *
     * @param int|null $id ID фотографии
     *
     * @return int Количество успешных удалений
     */
    public function delete_file($id = null): int
    {
        $id = $id ?? $this->id;

        try {
            $file_path = "$this->_img_path/$id.jpg";
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            $file_path = "$this->_img_path/s/$id.jpg";
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            return $this->delete($id);
        } catch (Exception $e) {
            $this->CI->load->library('logger');
            $this->CI->logger->save_errors($e);

            return 0;
        }
    }

    /**
     * Удаляет старые фото из БД и диска
     *
     * @return int Количество удаленных фотографий
     */
    private function delete_old(): int
    {
        $query = $this->CI->db->where('person_id', null)
                              ->where('time <', now('Asia/Yekaterinburg') - 86400)
                              ->get('photo')
                              ->result();

        $counter = 0;

        foreach ($query as $photo) {
            $counter += $this->delete($photo->id);
        }

        return $counter;
    }

    /**
     * Создает уменьшенную копию изображения
     * $params = [
     *   'src_path' => string,
     *   'width' => int,
     *   'height' => int,
     *   'dst_path' => string
     * ];
     * @param array $params
     *
     * @return bool TRUE - успешно, FALSE - ошибка
     */
    private function _create_thumbnail(array $params): bool
    {
        $src_img = imagecreatefromjpeg($params['src_path']);

        list($width, $height) = getimagesize($params['src_path']);

        $delta = max([
            ($width / $params['width']),
            ($height / $params['height'])
        ]);

        $new_width = $width / $delta;
        $new_height = $height / $delta;

        $new_img = imagecreatetruecolor($new_width, $new_height);

        imagecopyresampled($new_img, $src_img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        return imagejpeg($new_img, $params['dst_path']);
    }
}
