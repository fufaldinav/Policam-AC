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

class DevController extends Controller
{
    public function index()
    {
    }

    public function test()
    {
        return view('ac.test', [
            'org_name' => '123',
            'css_list' => [],
            'js_list' => ['test'],
        ]);
    }
}
