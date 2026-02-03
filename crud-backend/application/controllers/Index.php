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
		$this->index->delete($id);
	}

	public function insert()
	{
		header("Content-Type: application/json");

		$data = [
			'judul' => $this->input->post('judul'),
			'deskripsi' => $this->input->post('deskripsi'),
			'rilis' => $this->input->post('rilis'),
		];

		if (empty($data['judul']) || empty($data['deskripsi']) || empty($data['rilis'])) 
		{
			echo json_encode([
				'status' => 'error',
				'message' => 'Data tidak lengkap'
			]);
			return;
		}

		$this->index->insert($data);

		echo json_encode([
			'status' => 'success',
			'message' => 'Data berhasil ditambahkan'
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

		$data = json_decode(file_get_contents("php://input"), true);

		if (!$data) 
		{
			echo json_encode([
				'status' => 'error',
				'message' => 'Invalid JSON'
			]);
			return;
		}

		if (
			empty($data['judul']) ||
			empty($data['deskripsi']) ||
			empty($data['rilis'])
		) {
			echo json_encode([
				'status' => 'error',
				'message' => 'Data tidak lengkap'
			]);
			return;
		}

		$this->index->update($id, $data);

		echo json_encode([
			'status' => 'success',
			'message' => 'Data berhasil diupdate'
		]);
	}


}
?>
