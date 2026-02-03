<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_index extends CI_Model
{
	public function get_film()
	{
		$query = $this->db->get("film");

		return $query->result_array();
	}

	public function insert($data)
	{
		return $this->db->insert('film', $data);
	}

	public function delete($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete('film');
	}
	
	public function update($id, $data)
	{
		$this->db->where('id', $id);
		return $this->db->update('film', $data);
	}

	public function get_by_id($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('film');
		return $query->row();
	}
}
