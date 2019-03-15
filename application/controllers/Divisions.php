<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Divisions
 * @property Div_model $div
 * @property Org_model $org
 * @property Person_model $person
 */
class Divisions extends CI_Controller
{
    /**
     * @var int $user_id
     */
    private $user_id;

    /**
     * @var mixed[] $orgs
     */
    private $orgs;

    public function __construct()
    {
        parent::__construct();

        $this->load->library('ion_auth');

        if (! $this->ion_auth->logged_in()) {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }

        $this->load->model('ac/div_model', 'div');
        $this->load->model('ac/org_model', 'org');
        $this->load->model('ac/person_model', 'person');

        $this->user_id = $this->ion_auth->user()->row()->id;
        $this->org->get_list($this->user_id); //TODO
    }

    /**
     * Получает подразделения текущей организации
     */
    public function get_list()
    {
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
     */
    public function add()
    {
        if (! $this->ion_auth->in_group(2)) {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }

        $div = json_decode($this->input->post('div'));

        $this->div->name = $div->name;
        $this->div->org_id = $div->org_id;

        $this->div->save();

        header('Content-Type: application/json');

        echo json_encode(
            $this->div
        );
    }

    /**
     * Удаляет подразделение
     *
     * @param int $div_id ID подразделения
     */
    public function delete(int $div_id)
    {
        if (! $this->ion_auth->in_group(2)) {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }

        //Получаем всех людей в удаляемом подразделении
        $persons = $this->person->get_list($div_id);

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
