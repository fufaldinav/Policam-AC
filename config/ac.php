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
return [
    /*
    | Каталог с фотографиями
    */
    'img_path' => '/var/www/img_ac',

    /*
    | Каталог с фотографиями камер
    */
    'camera_path' => '/var/www/img_snapshots',
    
    /*
    | Каталог с логами
    */
    'log_path' => '/var/www/logs',
    
    /*
    | Таймаут одного long poll
    */
    'long_poll_timeout' => 10,
    
    /*
    | Firebase Cloud Messaging
    */
    'fcm' => [
        'fcm_url' => 'https://fcm.googleapis.com/fcm/send',
        'server_key' => 'AAAA6hsRfn0:APA91bFXS5t_qUC7StorR89rPP0bKbc3qDA-N6xqdeaNRn1TBSqSS-qMUx4F3HKjOwTNDRdQnpxE8uvpJLwB8dcdKlCDu1N2_35zmLkDQ1TxJXBMLzWO3MrQ7WQhBjgvT_MNBIWcOzV5',
        'api_key' => 'AIzaSyDI_-AwpqcTclSXCyXgYJzvaTNC-dky9iY',
        'auth_domain' => 'policam-ac.firebaseapp.com',
        'database_url' => 'https://policam-ac.firebaseio.com',
        'project_id' => 'policam-ac',
        'storage_bucket' => 'policam-ac.appspot.com',
        'messaging_sender_id' => '1005476478589',
        'public_vapid_key' => 'BPKQjI8lJAE9pymLNyKm5fsJSsu-7vXlPZivaRvR52lxGWgsxF2TN5s_iaIKQ1LWNZPh0S8arKNOXfq9nAAB3Yg',
    ],
    
];
