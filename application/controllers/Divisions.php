<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Divisions
 * @property  Division_model    $division
 */
class Divisions extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->helper('language');

		$this->load->model('ac/division_model', 'division');
	}

	/**
	 * Получение информации о подразделении
	 */
	public function get_all()
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}

		$org_id = $this->ion_auth->user()->row()->org_id; //TODO

		echo json_encode($this->division->get_all($org_id));
	}

	/**
	 * Сохранение нового подразделения
	 */
	public function add()
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$div = json_decode($this->input->post('div'));

		$div_id = $this->division->add($div);

		echo json_encode($this->division->get($div_id));
	}

	/**
	 * Удаление подразделения
	 *
	 * @param  int  $div_id
	 */
	public function delete($div_id)
	{
		if (!$this->ion_auth->logged_in()) {
			header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		echo $this->division->delete($div_id);
	}
}
