<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 20.03.2019
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
 * Class Photo
 */
class Photo extends Ac
{
    /**
     * Каталог с фото
     *
     * @var string $_img_path
     */
    private $_img_path;

    /**
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->_img_path = $this->_CI->config->item('img_path', 'ac');

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
    public function save(array $file): array //TODO проверка уже имеющейся фото за человеком
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
            $this->load('Photos');

            $photo = new \Orm\Photos(['hash' => $file_hash]);

            if (! isset($photo->hash)) {
                $photo->hash = $file_hash;
            }

            $time = now();
            $photo->time = $time;
            $photo->save();

            $response['id'] = $photo->id;

            $this->clear();

            try {
                $file_path = "$this->_img_path/$photo->id.jpg";

                move_uploaded_file($file_tmp, $file_path);
                //сохранение уменьшенной копии
                $params = [
                    'src_path' => $file_path,
                    'width' => 240,
                    'height' => 320,
                    'dst_path' => "$this->_img_path/s/$photo->id.jpg"
                ];

                $this->_create_thumbnail($params);

                return $response;
            } catch (Exception $e) {
                $response['error'] = $e->getMessage();

                $this->_CI->load->library('logger');
                $this->_CI->logger->add('err', $response['error']);
                $this->_CI->logger->write();

                return $response;
            }
        } else {
            $this->_CI->load->library('logger');
            $this->_CI->logger->add('err', $response['error']);
            $this->_CI->logger->write();

            return $response;
        }
    }

    /**
     * Удаляет фото из БД и диска
     *
     * @param int $id ID фотографии
     *
     * @return int Количество успешных удалений
     */
    public function remove(int $id): int
    {
        $this->load('Photos');

        $photo = new \Orm\Photos($id);

        try {
            $file_path = "$this->_img_path/$id.jpg";
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            $file_path = "$this->_img_path/s/$id.jpg";
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            return $photo->remove();
        } catch (Exception $e) {
            $this->_CI->load->library('logger');

            $this->_CI->logger->add('err', $e->getMessage());
            $this->_CI->logger->write();

            return 0;
        }
    }

    /**
     * Удаляет старые фото из БД и диска
     *
     * @return int Количество удаленных фотографий
     */
    public function clear(): int
    {
        $this->load('Photos');

        $photos = \Orm\Photos::get_old();

        $counter = 0;

        foreach ($photos as $photo) {
            $counter += $photo->remove();
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
