<?php
/**
 * Name:   Policam AC
 *
 * Created: 01.04.2019
 *
 * Description: Приложение для систем контроля и управления доступом.
 *
 * Requirements: PHP7.3 or above
 */

namespace App\Policam\Ac;

use App;
use Carbon\Carbon;

class Snapshot
{
    /**
     * Подготавливает фото для отображения
     *
     * @param $notification
     *
     * @return array
     */
    public static function getByNotification($notification): array
    {
        $datetime = Carbon::parse($notification->created_at);

        $cameras = $notification->controller->cameras;

        $camera_path = config('ac.camera_path');

        $start = clone $datetime;
        $start->subSeconds(5);
        $end = clone $datetime;
        $end->addSeconds(5);

        $photos = [];

        foreach ($cameras as $camera) {
            $path = $camera_path . DIRECTORY_SEPARATOR . config("ac.cameras.$camera->type");
            $path .= DIRECTORY_SEPARATOR . $camera->name;
            $photos = array_merge($photos, self::findInTimeRange($camera->type, $path, $start, $end));
        }

        return $photos;
    }

    /**
     * Сканирует директорию и возвращает полные пути к файлам
     *
     * @param int $type
     * @param string $path
     * @param Carbon $start
     * @param Carbon $end
     * @param array  $result
     *
     * @return array
     */
    private static function findInTimeRange(int $type, string $path, Carbon $start, Carbon $end, array &$result = []): array
    {
        if ($type == 1) {
            self::dahuaSearch($path, $start, $end, $result);
        } else {
            self::scanDir($path, $result);
        }

        return $result;
    }

    /**
     * Сканирует путь на файлы и возвращает полные пути файлов
     *
     * @param string $path
     * @param array $result
     *
     * @return array
     */
    private static function scanDir(string $path, &$result = []): array
    {
        if (is_dir($path)) {
            $content = array_diff(scandir($path), ['..', '.', 'DVRWorkDirectory']);
            foreach ($content as $item) {
                $file = realpath($path . DIRECTORY_SEPARATOR . $item);

                if (is_file($file)) {
                    $result[] = $file;
                } else {
                    self::scanDir($path, $result);
                }
            }
        }

        return $result;
    }

    /**
     * Поиск в пути камер Dahua
     *
     * @param string $path
     * @param Carbon $start
     * @param Carbon $end
     * @param array  $result
     *
     * @return array
     */
    private static function dahuaSearch(string $path, Carbon $start, Carbon $end, array &$result = []): array
    {
        $start = clone $start;
        $end = clone $end;

        do {
            $path .= DIRECTORY_SEPARATOR . $start->toDateString();
            $path .= DIRECTORY_SEPARATOR . '001' . DIRECTORY_SEPARATOR . 'jpg';
            $path .= DIRECTORY_SEPARATOR . $start->isoFormat('HH');
            $path .= DIRECTORY_SEPARATOR . $start->isoFormat('mm');
            if (is_dir($path)) {
                $seconds = [];
                for ($i = $start->second; $i < 60; $i++) {
                    $seconds[] = $i;
                    if ($start == $end) {
                        break;
                    }
                    $start->addSecond();
                }
                $content = array_diff(scandir($path), ['..', '.', 'DVRWorkDirectory']);
                foreach ($content as $item) {
                    $file = realpath($path . DIRECTORY_SEPARATOR . $item);
                    $item = substr($item, 0, 2);
                    if (! is_dir($file) && in_array($item, $seconds)) {
                        $result[] = $file;
                    }
                }
            } else {
                $start->second(0);
                $start->addMinute();
            }
        } while ($start < $end);

        return $result;
    }
}
