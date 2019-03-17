<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Util
 *
 * @property Messenger $messenger
 * @property Card_model $card
 * @property Ctrl_model $ctrl
 * @property Div_model $div
 * @property Org_model $org
 * @property Person_model $person
 * @property Task_model $task
 * @property Users_events_model $users_events
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
        $this->load->library('messenger');

        $time = $this->input->post('time');
        $events = $this->input->post('events');

        header('Content-Type: application/json');

        echo json_encode([
                'msgs' => $this->messenger->polling($events, $time),
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
        $this->load->library('logger');

        $err = $err ?? $this->input->post('error');

        $this->logger->save_errors($err);
    }

    /**
     * Обрабатывает проблемы с картами
     */
    public function card_problem()
    {
        $this->ac->load('person');
        $this->ac->load('users_events');

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
            $this->users_events->user_id = $user_id;
            $this->users_events->type = $type;
            $this->users_events->description = "{$this->person->id} {$this->person->f} {$this->person->i} forgot card";
            $this->users_events->time = now('Asia/Yekaterinburg');

            if ($this->users_events->save() > 0) {
                echo $response;
            }
        } elseif ($type == 2 || $type == 3) {
            $this->ac->load('card');
            $this->ac->load('ctrl');
            $this->ac->load('div');
            $this->ac->load('org');
            $this->ac->load('task');

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
                    $this->task->controller_id = $ctrl->id;
                    $this->task->del_cards([$card->wiegand]);
                    $this->task->save();
                }
            }
            unset($card);

            $this->card->save_list();

            $this->users_events->user_id = $user_id;
            $this->users_events->type = $type;
            $this->users_events->description = "{$this->person->id} {$this->person->f} {$this->person->i} lost/broke card";
            $this->users_events->time = now('Asia/Yekaterinburg');

            $response .= ' ' . lang('and') . ' ' . lang('card_deleted');

            if ($this->users_events->save() > 0) {
                echo $response;
            }
        }
    }
}
