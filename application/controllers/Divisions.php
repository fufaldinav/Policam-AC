<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Divisions
 * @property Div_model $div
 * @property Org_model $org
 */
class Divisions extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('ac/div_model', 'div');
		$this->load->model('ac/org_model', 'org');
	}

	/**
	 * Получение информации о подразделении
	 */
	public function get_all()
	{
		if (!$this->ion_auth->logged_in()) {
			$this->output->set_header("HTTP/1.1 401 Unauthorized");
			exit;
		}

		$user_id = $this->ion_auth->user()->row()->id; //TODO
		$orgs = $this->org->get_all($user_id);

		$divs = [];
		foreach ($orgs as $org) {
			$divs = array_merge($divs, $this->div->get_all($org->id));
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($divs));
	}

	/**
	 * Сохранение нового подразделения
	 */
	public function add()
	{
		if (!$this->ion_auth->logged_in()) {
			$this->output->set_header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			$this->output->set_header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$div = json_decode($this->input->post('div'));

		$div_id = $this->div->add($div);

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($this->div->get($div_id)));
	}

	/**
	 * Удаление подразделения
	 *
	 * @param int $div_id ID подразделения
	 */
	public function delete($div_id)
	{
		if (!$this->ion_auth->logged_in()) {
			$this->output->set_header("HTTP/1.1 401 Unauthorized");
			exit;
		}
		if (!$this->ion_auth->in_group(2)) {
			$this->output->set_header('HTTP/1.1 403 Forbidden');
			exit;
		}

		$this->output->set_output($this->div->delete($div_id));
	}
}
