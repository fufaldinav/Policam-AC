<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Util
 * @property Card_model $card
 * @property Ctrl_model $ctrl
 * @property Div_model $div
 * @property Org_model $org
 * @property Person_model $person
 * @property Task_model $task
 * @property Util_model $util
 */
class Util extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('ion_auth');

        if (! $this->ion_auth->logged_in()) {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }

        $this->load->model('ac/card_model', 'card');
        $this->load->model('ac/ctrl_model', 'ctrl');
        $this->load->model('ac/div_model', 'div');
        $this->load->model('ac/org_model', 'org');
        $this->load->model('ac/person_model', 'person');
        $this->load->model('ac/task_model', 'task');
        $this->load->model('ac/util_model', 'util');
    }

    /**
     * Получает время сервера
     */
    public function get_time()
    {
        echo time();
    }

    /**
     * Получает события и реализует long polling
     */
    public function get_events()
    {
        $time = $this->input->post('time');
        $events = $this->input->post('events');

        header('Content-Type: application/json');

        echo json_encode([
                'msgs' => $this->util->start_polling($time, $events),
                'time' => now('Asia/Yekaterinburg')
        ]);
    }

    /**
     * Сохраняет ошибки от клиентов
     *
     * @param string|null $err Текст ошибки или NULL - получить POST-запрос
     */
    public function save_js_errors(string $err = null)
    {
        $err = $err ?? $this->input->post('error');

        $this->util->save_errors($err);
    }

    /**
     * Обрабатывает проблемы с картами
     */
    public function card_problem()
    {
        $this->lang->load('ac');

        $this->load->helper('language');

        $user_id = $this->ion_auth->user()->row()->id; //TODO

        $type = $this->input->post('type');
        $person_id = $this->input->post('person_id');

        if (! isset($type) || ! isset($person_id)) {
            return null;
        }

        $response = lang('registred');

        $this->person->get($person_id);

        if ($type == 1) {
            $desc = "{$this->person->id} {$this->person->f} {$this->person->i} forgot card";

            if ($this->util->add_user_event($user_id, $type, $desc) > 0) {
                echo $response;
            }
        } elseif ($type == 2 || $type == 3) {
            $this->card->get_list($this->person->id);

            if (count($this->card->get_list()) === 0) {
                return null;
            }

            $this->div->get($this->person->div);
            $this->org->get($this->div->org_id);
            $ctrls = $this->ctrl->get_list($this->org->id);

            foreach ($this->card->get_list() as &$card) {
                $card->person_id = 0;

                foreach ($ctrls as $ctrl) {
                    $this->task->delete_cards($ctrl->id, [$card->wiegand]);
                }
            }
            unset($card);

            $this->card->save_list();

            $desc = "{$this->person->id} {$this->person->f} {$this->person->i} lost/broke card";

            $response .= ' ' . lang('and') . ' ' . lang('card_deleted');

            if ($this->util->add_user_event($user_id, $type, $desc) > 0) {
                echo $response;
            }
        }
    }
}
