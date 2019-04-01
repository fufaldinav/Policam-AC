<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 28.03.2019
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

namespace App\Http\Controllers;

use App\Policam\Ac\Photo;
use Illuminate\Http\Request;

class PhotosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    /**
     * Сохраняет фотографию
     *
     * @param Request $request
     *
     * @return
     */
    public function save(Request $request)
    {
        $photo = new Photo();

        $file = $request->file('file');

        return response()->json(
            $photo->save($file)
        );
    }

    /**
     * Удаляет фотографию
     *
     * @param Request $request
     *
     * @return int
     * @throws \Exception
     */
    public function delete(Request $request): int
    {
        $photo = new Photo();

        $id = $request->input('photo_id');

        return $photo->remove($id);
    }
}
