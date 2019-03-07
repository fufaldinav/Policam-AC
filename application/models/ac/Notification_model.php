<?php
/**
 * Name:   Policam AC Notification Model
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 01.03.2019
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

/**
* Class Notification Model
*
* @property Person_model $person
*/
class Notification_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('ac/person_model', 'person');
	}

	/**
	* Проверка подписки
	*
	* @param int $person_id    ID человека
	* @param int|null $user_id ID пользователя
	* @return array|null
	*/
	public function check_subscription(int $person_id, int $user_id = null)
	{
		if ($user_id !== null) {
			$this->db->where('user_id', $user_id);
		}

		$query = $this->db
			->where('person_id', $person_id)
			->get('persons_users');

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	/**
	* Отправка уведомления
	*
	* @param int $person_id ID человека
	* @param int $event_id  ID события
	* @return array|null Параметры уведомления
	*/
	public function generate(int $person_id, int $event_id)
	{
		switch ($event_id) {
			case 4:
				$event = 'Вход'; //TODO перевод
				break;

			case 5:
				$event = 'Выход'; //TODO перевод
				break;

			default:
				return null;
		}

		$this->load->helper('url');

		$person = $this->person->get($person_id);

		$notification = [
			'title' => $event,
			'body' => $person->f . ' ' . $person->i,
			'icon' => base_url('/img/ac/s/' . $person_id . '.jpg'),
			'click_action' => base_url('/'),
		];

		return $notification;
	}
	/**
	* Отправка уведомления
	*
	* @param array $notification Параметры уведомления
	* @return string|bool Ответ на запрос
	*/
	public function send(array $notification)
	{
		$url = 'https://fcm.googleapis.com/fcm/send';
		//Ключ сервера
		$YOUR_API_KEY = 'AAAA6hsRfn0:APA91bFXS5t_qUC7StorR89rPP0bKbc3qDA-N6xqdeaNRn1TBSqSS-qMUx4F3HKjOwTNDRdQnpxE8uvpJLwB8dcdKlCDu1N2_35zmLkDQ1TxJXBMLzWO3MrQ7WQhBjgvT_MNBIWcOzV5';
		//Идентификатор отправителя
		$YOUR_TOKEN_ID = '1005476478589';

		$request_body = [
			'to' => $YOUR_TOKEN_ID,
			'notification' => $notification
		];
		$fields = json_encode($request_body);

		$request_headers = [
			'Content-Type: application/json',
			'Authorization: key=' . $YOUR_API_KEY,
		];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$response = curl_exec($ch);
		curl_close($ch);

		return $response;
	}

	/**
	* Получение токена
	*
	* @param string $token Токен
	* @return object|null Токен
	*/
	public function get_token(string $token) {
		$query = $this->db
			->where('token', $token)
			->get('users_tokens');

		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return null;
		}
	}

	/**
	* Получение токенов
	*
	* @param int|null $user_id ID пользователя
	* @return object[]|null Массив токенов
	*/
	public function get_all(int $user_id = null) {
		if ($user_id !== null) {
			$this->db->where('user_id', $user_id);
		}
		$query = $this->db->get('users_tokens');

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	/**
	* Добавление токена
	*
	* @param int $user_id  ID пользователя
	* @param string $token Токен
	* @return int ID токена
	*/
	public function add_token(int $user_id, string $token): int {
		$this->db->insert('users_tokens', [
			'user_id' => $user_id,
			'token' => $token
		]);

		return $this->db->insert_id();
	}

	/**
	* Удаление токена
	*
	* @param string $token Токен
	* @return bool TRUE - успешно, FALSE - ошибка
	*/
	public function delete_token(string $token): bool {
		$this->db->delete('users_tokens', ['token' => $token]);

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
}
