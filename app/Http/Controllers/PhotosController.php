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
        $file = $request->file('file');

        return response()->json(
            (new Photo())->save($file)
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
        $id = $request->input('photo_id');

        $photo = App\Photo::find($id);

        abort_if(! $photo, 404);

        if ($photo->person_id === null) {
            return (new Photo())->remove($id);
        }

        $person = $request->user()->persons()->where('person_id', $photo->person_id)->first();

        abort_if(! $person, 403);

        return (new Photo())->remove($id);
    }
}
