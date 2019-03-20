<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Util
 *
 * @property Messenger $messenger
 * @property Logger $logger
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

        $this->ac->load('Users');

        $user_id = $this->ion_auth->user()->row()->id;
        $this->_user = new \Orm\Users($user_id);
    }

    /**
     * Получает время сервера
     *
     * @return void
     */
    public function get_time(): void
    {
        echo time();
    }

    /**
     * Получает события и реализует long polling
     *
     * @return void
     */
    public function get_events(): void
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
     *
     * @return void
     */
    public function save_js_errors(string $err = null): void
    {
        $this->load->library('logger');

        $err = $err ?? $this->input->post('error') ?? 'Неизвестная ошибка или ошибка не указана';

        $this->logger->add('err', $err);
        $this->logger->write();
    }

    /**
     * Обрабатывает проблемы с картами
     *
     * @return void
     */
    public function card_problem(): void
    {
        $this->ac->load('Persons');
        $this->ac->load('User_events');

        $this->load->helper('language');

        $problem_type = $this->input->post('type');
        $person_id = $this->input->post('person_id');

        if (! isset($problem_type) || ! isset($person_id)) {
            exit;
        }

        $response = lang('registred');

        $person = new \Orm\Persons($person_id);

        $description = "{$person->id} {$person->f} {$person->i} ";

        if ($problem_type === 1) {
            $description .= 'forgot card';
        } elseif ($problem_type === 2 || $problem_type === 3) {
            $this->ac->load('Cards');
            $this->ac->load('Controllers');
            $this->ac->load('Divisions');
            $this->ac->load('Organizations');

            $this->load->library('task');

            $cards = $person->cards;

            if (! $cards) {
                exit;
            }

            $ctrls = [];
            $cards_to_delete = [];

            foreach ($cards as &$card) {
                $cards_to_delete[] = $card->wiegand;

                $card->person_id = 0;
                $card->save();
            }
            unset($card);

            $this->task->del_cards($cards_to_delete);

            $divs = $person->divisions;

            foreach ($divs as $div) {
                $org = $div->organization;
                $ctrls = array_merge($ctrls, $org->controllers);
            }

            foreach ($ctrls as $ctrl) {
                $this->task->add($ctrl->id);
            }

            $this->task->send();

            $response .= ' ' . lang('and') . ' ' . lang('card_deleted');

            $description .= 'lost/broke card';
        } else {
            exit;
        }

        $event = new \Orm\User_events();

        $event->user_id = $this->_user->id;
        $event->type = $problem_type;
        $event->description = $description;
        $event->time = now('Asia/Yekaterinburg');

        if ($event->save() > 0) {
            echo $response;
        }
    }
}
