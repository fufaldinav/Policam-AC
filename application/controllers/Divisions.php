<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Divisions
 * @property Division_model $division
 * @property Organization_model $organization
 */
class Divisions extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->helper('language');

		$this->load->model('ac/division_model', 'division');
		$this->load->model('ac/organization_model', 'organization');
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

		$user_id = $this->ion_auth->user()->row()->id; //TODO
		$organizations = $this->organization->get_all($user_id);

		$divisions = [];
		foreach ($organizations as $org) {
			$divisions = array_merge($divisions, $this->division->get_all($org->id));
		}
		
		echo json_encode($divisions);
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
