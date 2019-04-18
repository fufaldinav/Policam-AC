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

use App;
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
    public function store(Request $request)
    {
        $file = $request->file('file')->getRealPath();

        $photo = App\Photo::saveFileThenCreate($file);

        return response()->json($photo);
    }

    /**
     * Удаляет фотографию
     *
     * @param int $id
     *
     * @return int
     * @throws \Exception
     */
    public function destroy(int $id): int
    {
        $photo = App\Photo::find($id);

        abort_if(! $photo, 404);

        abort_if($photo->person_id, 403);

        $photo->deleteFile();
        return (int)$photo->delete();
    }
}
