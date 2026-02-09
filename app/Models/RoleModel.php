<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table            = 'roles';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'role_name'
    ];

    // Dates
    protected $useTimestamps = false;

    // Validation
    protected $validationRules = [
        'role_name' => 'required|min_length[2]|max_length[100]',
    ];

    protected $validationMessages = [
        'role_name' => [
            'required'   => 'Nama role harus diisi',
            'min_length' => 'Nama role minimal 2 karakter',
        ],
    ];

    protected $skipValidation = false;

    public function getUsersByRole($roleId)
    {
        return $this->db->table('user_roles')
                       ->select('users.*')
                       ->join('users', 'users.id = user_roles.user_id')
                       ->where('user_roles.role_id', $roleId)
                       ->get()
                       ->getResultArray();
    }
}

