<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 01.03.2019
 *
 * Description: Приложение для систем контроля и управления доступом.
 *
 * Requirements: PHP7.2 or above
 *
 * @package Policam-AC
 * @author  Artem Fufaldin
 * @link    http://github.com/m2jest1c/Policam-AC
 * @filesource
 */
defined('BASEPATH') or exit('No direct script access allowed');

/**
* Class Notification Model
* @property Person_model $person
* @property Photo_model $photo
*/
class Notification_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('ac/person_model', 'person');
		$this->load->model('ac/photo_model', 'photo');
	}

	/**
	* Проверяет подписки
	*
	* @param int $person_id    ID человека
	* @param int|null $user_id ID пользователя
	* @return array Список подписок
	*/
	public function check_subscription(int $person_id, int $user_id = null): array
	{
		if ($user_id !== null) {
			$this->db->where('user_id', $user_id);
		}

		$query = $this->db
			->where('person_id', $person_id)
			->get('persons_users');

		return $query->result();
	}

	/**
	* Генерирует уведомление
	*
	* @param int $person_id ID человека
	* @param int $event_id  ID события
	* @return array Параметры уведомления
	*/
	public function generate(int $person_id, int $event_id): array
	{
		switch ($event_id) {
			case 4:
				$event = 'Вход'; //TODO перевод
				break;

			case 5:
				$event = 'Выход'; //TODO перевод
				break;

			default:
				return [];
		}

		$this->load->helper('url');

		$person = $this->person->get($person_id);
		$photo = $this->photo->get_by_person($person->id);

		$notification = [
			'title' => $event,
			'body' => $person->f . ' ' . $person->i,
			'icon' => ($photo !== null) ? ('https://' . $_SERVER['HTTP_HOST'] . '/img/ac/s/' . $photo->id . '.jpg') : '',
			'click_action' => base_url('/')
		];

		return $notification;
	}
	/**
	* Отправляет уведомление
	*
	* @param array $notification Параметры уведомления
	* @param int|null $user_id   ID пользователя
	* @return string Ответ на запрос
	*/
	public function send(array $notification, int $user_id = null): string //TODO перенести кое-что в конфиг
	{
		$url = 'https://fcm.googleapis.com/fcm/send';
		//Ключ сервера
		$YOUR_API_KEY = 'AAAA6hsRfn0:APA91bFXS5t_qUC7StorR89rPP0bKbc3qDA-N6xqdeaNRn1TBSqSS-qMUx4F3HKjOwTNDRdQnpxE8uvpJLwB8dcdKlCDu1N2_35zmLkDQ1TxJXBMLzWO3MrQ7WQhBjgvT_MNBIWcOzV5';
		//Идентификатор отправителя

		$registration_ids = [];

		$tokens = $this->get_all($user_id);

		foreach ($tokens as $token) {
			$registration_ids[] = $token->token;
		}

		$request_body = [
			'notification' => $notification,
			'registration_ids' => $registration_ids
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
	* Получает токен
	*
	* @param string $token Токен
	* @return object|null Токен
	*/
	public function get_token(string $token): ?object
	{
		$query = $this->db
			->where('token', $token)
			->get('users_tokens');

		return $query->row();
	}

	/**
	* Получает все токены пользователя
	*
	* @param int|null $user_id ID пользователя
	* @return object[] Массив с токенами или пустой массив
	*/
	public function get_all(int $user_id = null): array
	{
		if ($user_id !== null) {
			$this->db->where('user_id', $user_id);
		}
		$query = $this->db->get('users_tokens');

		return $query->result();
	}

	/**
	* Добавляет токен
	*
	* @param int $user_id  ID пользователя
	* @param string $token Токен
	* @return int ID токена
	*/
	public function add_token(int $user_id, string $token): int
	{
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
	* @return int Количество успешных удалений
	*/
	public function delete_token(string $token): int {
		$this->db->delete('users_tokens', ['token' => $token]);

		return $this->db->affected_rows();
	}
}
