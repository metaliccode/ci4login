<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Myth\Auth\Models\UserModel;

class Admin extends BaseController
{
	protected $db, $builder;
	public function __construct()
	{
		$this->db      = \Config\Database::connect();
		$this->builder = $this->db->table('users');
	}
	public function index()
	{
		$data['title'] = 'User List';

		// $users = new UserModel();
		// $data['users'] = $users->findAll();

		$this->builder->select('users.id as userid, username, email, name');
		$this->builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
		$this->builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
		$query = $this->builder->get();

		// result object
		$data['users'] = $query->getResult();
		return view('admin/index', $data);
	}
	public function detail($id = 0)
	{
		$data['title'] = 'User details';

		$this->builder->select('users.id as userid, username, email, name, fullname, user_image');
		$this->builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
		$this->builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
		$this->builder->where('users.id', $id);
		$query = $this->builder->get();

		// result object satu ssaj pake getRow
		$data['user'] = $query->getRow();

		if (empty($data['user'])) {
			return redirect()->to('/admin');
		}

		return view('admin/detail', $data);
	}
}
