<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Divisions
 */
class Divisions extends CI_Controller
{
    /** @var object Текущий пользователь */
    private $user;

    public function __construct()
    {
        parent::__construct();

        $this->load->library('ion_auth');

        $this->ac->load('Users');

        $user_id = $this->ion_auth->user()->row()->id;
        $this->user = new \ORM\Users($user_id);
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

        $this->ac->load(['Divisions', 'Organizations']);

        $this->load->library('table');

        $this->load->helper('language');

        $orgs = $this->user->organizations->get();
        $org = $this->user->organizations->first();

        $divs = $org->divisions
            ->orderBy('type ASC, CAST(name AS UNSIGNED) ASC, name ASC')
            ->get();
        $data = [
            'org_id' => $org->id ?? 0,
            'divs' => $divs
        ];

        $header = [
            'org_name' => $org->name ?? lang('missing'),
            'css_list' => ['ac', 'tables'],
            'js_list' => ['classes']
        ];

        $this->load->view('ac/header', $header);
        $this->load->view('ac/classes', $data);
        $this->load->view('ac/footer');
    }

    /**
     * Получает подразделения организаций пользователя
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

        $this->ac->load(['Divisions', 'Organizations']);

        $orgs = $this->user->organizations->get();

        $divs = [];

        foreach ($orgs as $org) {
            $divs = array_merge(
              $divs,
              $org->divisions
                  ->orderBy('type ASC, CAST(name AS UNSIGNED) ASC, name ASC')
                  ->get()
            );
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

        $this->ac->load('Divisions');

        $div_data = json_decode($this->input->post('div'));

        $div = new \ORM\Divisions;

        $div->set($div_data);
        $div->save();

        header('Content-Type: application/json');

        echo json_encode($div);
    }

    /**
     * Удаляет подразделение
     *
     * @param int|null $div_id ID подразделения
     *
     * @return void
     */
    public function delete(int $div_id = null): void
    {
        if (! $this->ion_auth->logged_in()) {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }

        if (! $this->ion_auth->in_group(2)) {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }

        if (is_null($div_id)) {
            echo 0;
            exit;
        }

        $this->ac->load(['Divisions', 'Organizations', 'Persons']);

        $orgs = $this->user->organizations->get();
        $org = $this->user->organizations->first();

        $cur_div = new \ORM\Divisions($div_id);

        //"Пустое" подразделение
        $empty_div = new \ORM\Divisions([
            'org_id' => $org->id ?? 0,
            'type' => 0
        ]);

        //Переносим полученных людей в "пустое" подразделение
        foreach ($cur_div->persons->get() as $person) {
            $person->unbind($cur_div);

            if (! $person->divisions->get()) {
                $empty_div->bind($person);
            }
        }

        echo $cur_div->remove();
    }
}
