<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 29.03.2019
 *
 * Description: Приложение для систем контроля и управления доступом.
 *
 * Requirements: PHP7.3 or above
 *
 * @package Policam-AC
 * @author  Artem Fufaldin
 * @link    http://github.com/m2jest1c/Policam-AC
 * @filesource
 */

namespace app\Policam\Ac;

use App;
use Carbon\Carbon;
use Exception;

class Photo
{
    /**
     * Каталог с фото
     *
     * @var string $img_path
     */
    private $img_path;

    public function __construct()
    {
        $this->img_path = config('ac.img_path');

        if (! is_dir($this->img_path)) {
            mkdir($this->img_path, 0755, true);
        }

        if (! is_dir("$this->img_path/s")) {
            mkdir("$this->img_path/s", 0755, true);
        }
    }

    /**
     * Сохраняет фотографию
     *
     * @param object $file Файл фотографии
     *
     * @return mixed[] Отчет о сохранении
     */
    public function save(object $file): array //TODO проверка уже имеющейся фото за человеком
    {
        $response = [
            'id' => 0,
            'error' => '',
            '123' => '',
        ];

        $extensions = ['jpg', 'jpeg'];

        $file_name = $file->getClientOriginalName();
        $file_type = $file->getClientMimeType();
        $file_size = $file->getClientSize();
        $file_tmp = $file->path();
        $file_ext = $file->extension();

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
            $photo = App\Photo::firstOrCreate(['hash' => $file_hash]);

            $response['id'] = $photo->id;

            $this->clear();

            try {
                $file_path = "$this->img_path/$photo->id.jpg";

                move_uploaded_file($file_tmp, $file_path);
                //сохранение уменьшенной копии
                $params = [
                    'src_path' => $file_path,
                    'width' => 240,
                    'height' => 320,
                    'dst_path' => "$this->img_path/s/$photo->id.jpg"
                ];

                $this->createThumbnail($params);

                return $response;
            } catch (Exception $e) {
                $response['error'] = $e->getMessage();

                $logger = new Logger();
                $logger->add('err', $response['error']);
                $logger->write();

                return $response;
            }
        } else {
            $logger = new Logger();
            $logger->add('err', $response['error']);
            $logger->write();

            return $response;
        }
    }

    /**
     * Удаляет фото из БД и диска
     *
     * @param int $id ID фотографии
     *
     * @return int Количество успешных удалений
     * @throws Exception
     */
    public function remove(int $id): int
    {
        $photo = App\Photo::find($id);

        try {
            $file_path = "$this->img_path/$id.jpg";
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            $file_path = "$this->img_path/s/$id.jpg";
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            return (int)$photo->delete();
        } catch (Exception $e) {
            $logger = new Logger();
            $logger->add('err', $e->getMessage());
            $logger->write();

            return 0;
        }
    }

    /**
     * Удаляет старые фото из БД и диска
     *
     * @return void
     */
    private function clear()
    {
        App\Photo::where('person_id', null)
            ->whereTime('created_at', '<', Carbon::now()->subDay()->toDateTimeString())
            ->delete();
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
    private function createThumbnail(array $params): bool
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
