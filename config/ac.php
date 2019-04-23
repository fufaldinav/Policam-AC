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
     * Каталог с фотографиями
     */
    'img_path' => '/var/www/img_ac',

    /*
     * Каталог с фотографиями камер
     */
    'camera_path' => '/var/www/img_snapshots',
    
    /*
     * Каталог с логами
     */
    'log_path' => '/var/www/logs',
    
    /*
     * Таймаут одного long poll
     */
    'long_poll_timeout' => 10,
    
    /*
     * Firebase Cloud Messaging
     */
    'fcm' => [
        'fcm_url' => env('FCM_URL', null),
        'server_key' => env('FCM_SERVER_KEY', null),
        'api_key' => env('FCM_API_KEY', null),
        'auth_domain' => env('FCM_AUTH_DOMAIN', null),
        'database_url' => env('FCM_DB_URL', null),
        'project_id' => env('FCM_PROJECT_ID', null),
        'storage_bucket' => env('FCM_STORAGE_BUCKET', null),
        'messaging_sender_id' => env('FCM_SENDER_ID', null),
        'public_vapid_key' => env('FCM_PUBLIC_VAPID_KEY', null),
    ],

    /*
     * Камеры
     */
    'cameras' => [
        0 => 'camera',
        1 => 'dahua',
        2 => 'hikvision',
    ],
];
