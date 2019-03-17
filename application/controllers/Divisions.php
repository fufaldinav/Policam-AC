<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Divisions
 *
 * @property Div_model $div
 * @property Org_model $org
 * @property Person_model $person
 */
class Divisions extends CI_Controller
{
    /**
     * @var int
     */
    private $_user_id;

    public function __construct()
    {
        parent::__construct();

        $this->load->library('ion_auth');

        $this->_user_id = $this->ion_auth->user()->row()->id;
    }

    /**
     * Управление классами
     *
     * @return void
     */
    public function classes(): void
    {
        if (! $this->ion_auth->logged_in()) {
            redirect('auth/login');
        }

        if (! $this->ion_auth->in_group(2)) {
            redirect('/');
        }

        $this->ac->load('div');
        $this->ac->load('org');

        $this->load->library('table');

        $this->load->helper('language');

        $this->org->get_list($this->_user_id); //TODO

        $data = [
            'org_id' => $this->org->first('id'),
            'divs' => $this->div->get_list($this->org->first('id'))
        ];

        $header = [
            'org_name' => $this->org->first('name') ?? lang('missing'),
            'css_list' => ['ac', 'tables'],
            'js_list' => ['classes']
        ];

        $this->load->view('ac/header', $header);
        $this->load->view('ac/classes', $data);
        $this->load->view('ac/footer');
    }

    /**
     * Получает подразделения текущей организации
     *
     * @return void
     */
    public function get_list(): void
    {
        if (! $this->ion_auth->logged_in()) {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }

        if (! $this->ion_auth->in_group(2)) {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }

        $this->ac->load('div');
        $this->ac->load('org');

        $orgs = $this->org->get_list();

        $divs = [];

        foreach ($orgs as $org) {
            $divs = array_merge($divs, $this->div->get_list($org->id));
        }

        header('Content-Type: application/json');

        echo json_encode($divs);
    }

    /**
     * Добавляет новое подразделение
     *
     * @return void
     */
    public function add(): void
    {
        if (! $this->ion_auth->logged_in()) {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }

        if (! $this->ion_auth->in_group(2)) {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }

        $this->ac->load('div');

        $div = json_decode($this->input->post('div'));

        $this->div->name = $div->name;
        $this->div->org_id = $div->org_id;

        $this->div->save();

        header('Content-Type: application/json');

        echo json_encode($this->div);
    }

    /**
     * Удаляет подразделение
     *
     * @param int $div_id ID подразделения
     *
     * @return void
     */
    public function delete(int $div_id): void
    {
        if (! $this->ion_auth->logged_in()) {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }

        if (! $this->ion_auth->in_group(2)) {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }

        $this->ac->load('div');
        $this->ac->load('org');
        $this->ac->load('person');

        //Получаем всех людей в удаляемом подразделении
        $persons = $this->person->get_list($div_id);

        $this->org->get_list($this->_user_id); //TODO

        //"Пустое" подразделение
        $new_div = $this->div->get_list_by_type($this->org->first('id'));

        //Переносим полученных людей в "пустое" подразделение
        //TODO проверят наличие людей в других подразделениях и тогда не добавлять в пустое
        foreach ($persons as $person) {
            $this->person->add_to_div($person->id, current($new_div)->id);
        }

        echo $this->div->delete($div_id);
    }
}
