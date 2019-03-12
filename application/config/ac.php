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
defined('BASEPATH') or exit('No direct script access allowed');

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
| Firebase Cloud Messaging
*/
$config['fcm_url'] = 'https://fcm.googleapis.com/fcm/send';
$config['server_key'] = 'AAAA6hsRfn0:APA91bFXS5t_qUC7StorR89rPP0bKbc3qDA-N6xqdeaNRn1TBSqSS-qMUx4F3HKjOwTNDRdQnpxE8uvpJLwB8dcdKlCDu1N2_35zmLkDQ1TxJXBMLzWO3MrQ7WQhBjgvT_MNBIWcOzV5';
$config['api_key'] = 'AIzaSyDI_-AwpqcTclSXCyXgYJzvaTNC-dky9iY';
$config['auth_domain'] = 'policam-ac.firebaseapp.com';
$config['database_url'] = 'https://policam-ac.firebaseio.com';
$config['project_id'] = 'policam-ac';
$config['storage_bucket'] = 'policam-ac.appspot.com';
$config['messaging_sender_id'] = '1005476478589';
$config['public_vapid_key'] = 'BPKQjI8lJAE9pymLNyKm5fsJSsu-7vXlPZivaRvR52lxGWgsxF2TN5s_iaIKQ1LWNZPh0S8arKNOXfq9nAAB3Yg';
