<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('m_index', 'index');
		$this->load->database();
		$this->load->library('session');
		$this->load->library('form_validation');
		header("Access-Control-Allow-Origin: http://localhost:5173");
		header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
		header("Access-Control-Allow-Headers: Content-Type, Authorization");

		if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') 
		{
			http_response_code(200);
			exit();
		}
	}

	public function index()
	{
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($this->index->get_film());
	}

	public function delete($id)
	{
		$old = $this->index->get_by_id($id);

		if ($old && !empty($old->gambar)) {
			$path = FCPATH . 'uploads/' . $old->gambar;
			if (file_exists($path)) {
				unlink($path);
			}
		}

		$this->index->delete($id);
	}

	public function insert()
    {
        $config['upload_path']   = FCPATH . 'uploads/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size']      = 2048;
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('image')) {
            echo json_encode([
                'status' => 'error',
                'message' => $this->upload->display_errors('', '')
            ]);
            return;
        }

        $upload = $this->upload->data();

        $data = [
            'judul' => $this->input->post('judul'),
            'deskripsi' => $this->input->post('deskripsi'),
            'rilis' => $this->input->post('rilis'),
            'gambar' => $upload['file_name']
        ];

        $this->index->insert($data);

        echo json_encode([
            'status' => 'success'
        ]);
    }

	public function get_by_id($id)
	{
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($this->index->get_by_id($id));
	}

	public function update($id)
	{
		header("Content-Type: application/json");

		if (
			empty($_POST['judul']) ||
			empty($_POST['deskripsi']) ||
			empty($_POST['rilis'])
		) {
			echo json_encode([
				'status' => 'error',
				'message' => 'Data tidak lengkap'
			]);
			return;
		}

		$data = [
			'judul' => $this->input->post('judul'),
			'deskripsi' => $this->input->post('deskripsi'),
			'rilis' => $this->input->post('rilis'),
		];

		if (!empty($_FILES['image']['name'])) {

			$config['upload_path']   = FCPATH . 'uploads/';
			$config['allowed_types'] = 'jpg|jpeg|png|gif';
			$config['max_size']      = 2048;
			$config['encrypt_name']  = TRUE;

			$this->load->library('upload', $config);

			if (!$this->upload->do_upload('image')) {
				echo json_encode([
					'status' => 'error',
					'message' => $this->upload->display_errors('', '')
				]);
				return;
			}

			$upload = $this->upload->data();
			$data['gambar'] = $upload['file_name'];

			$old = $this->index->get_by_id($id);
			if ($old && file_exists(FCPATH . 'uploads/' . $old->gambar)) {
				unlink(FCPATH . 'uploads/' . $old->gambar);
			}
		}

		$this->index->update($id, $data);

		echo json_encode([
			'status' => 'success',
			'message' => 'Data berhasil diupdate'
		]);
	}



}
?>
