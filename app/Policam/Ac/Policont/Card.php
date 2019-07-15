<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 09.07.2019
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

namespace App\Policam\Ac\Policont;

use JsonSerializable;

final class Card implements JsonSerializable
{
    protected $card;
    protected $flags = 32;
    protected $tz = 255;
    protected $active = false;

    public function __construct(string $code)
    {
        $this->card = $code;
    }

    public function jsonSerialize(): array
    {
        $response = ['card' => $this->card];

        if ($this->active) {
            $response['flags'] = $this->flags;
            $response['tz'] = $this->tz;
        }

        return $response;
    }

    public function setActive(): void
    {
        $this->active = true;
    }
}
