<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Photos
 *
 * @property Photo $photo
 */
class Photos extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('ion_auth');

        if (! $this->ion_auth->logged_in()) {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }

        if (! $this->ion_auth->in_group(2)) {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }

        $this->ac->load('Users');

        $user_id = $this->ion_auth->user()->row()->id;
        $this->user = new \ORM\Users($user_id);
    }

    /**
     * Сохраняет фотографию
     *
     * @return void
     */
    public function save(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
            $this->load->library('photo');

            header('Content-Type: application/json');

            echo json_encode(
                $this->photo->save($_FILES['file'])
            );
        }
    }

    /**
     * Удаляет фотографию
     *
     * @param int $id ID фотографии
     *
     * @return void
     */
    public function delete(int $id): void
    {
        $this->load->library('photo');

        echo $this->photo->remove($id);
    }
}
