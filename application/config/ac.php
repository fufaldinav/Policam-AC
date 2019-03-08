<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 03.03.2019
 *
 * Description: Приложение для систем контроля и управления доступом.
 *
 * Requirements: PHP7.0 or above
 *
 * @package Policam-AC
 * @author  Artem Fufaldin
 * @link    http://github.com/m2jest1c/Policam-AC
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| Каталог с фотографиями
*/
$config['img_path'] = '/var/www/img_ac';

/*
| Каталог с логами
*/
$config['log_path'] = '/var/www/logs';

/*
| Таймаут одного long poll
*/
$config['long_poll_timeout'] = 10;

/*
|	Firebase Cloud Messaging
*/
$config['fcm_url'] = 'https://fcm.googleapis.com/fcm/send';
$config['server_key'] = 'AAAA6hsRfn0:APA91bFXS5t_qUC7StorR89rPP0bKbc3qDA-N6xqdeaNRn1TBSqSS-qMUx4F3HKjOwTNDRdQnpxE8uvpJLwB8dcdKlCDu1N2_35zmLkDQ1TxJXBMLzWO3MrQ7WQhBjgvT_MNBIWcOzV5';
