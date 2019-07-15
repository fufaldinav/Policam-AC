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

use App\Policam\Ac\Z5RWEB;
use App\Policam\Ac\Policont;
use Illuminate\Http\Request;

class ServersController extends Controller
{
    /**
     * @param Request $request
     *
     * @return string|null
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $handler = new Z5RWEB\Request($request->getContent());
        return $handler->handle();
    }

    /**
     * @param Request $request
     *
     * @return string|null
     * @throws \Exception
     */
    public function policont(Request $request)
    {
        $handler = new Policont\Request($request->getContent());
        return $handler->handle();
    }
}
