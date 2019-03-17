<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Photos
 * @property Photo_model $photo
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

        $this->load->model('ac/photo_model', 'photo');
    }

    /**
     * Сохраняет фотографию
     *
     * @return void
     */
    public function save(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
            header('Content-Type: application/json');

            echo json_encode(
                $this->photo->save_file($_FILES['file'])
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
        echo $this->photo->delete_file($id);
    }
}
