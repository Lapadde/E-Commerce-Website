<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'full_name', 'email', 'password', 'phone'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'full_name' => 'required|min_length[3]|max_length[255]',
        'email'      => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password'   => 'required|min_length[6]',
    ];

    protected $validationMessages = [
        'full_name' => [
            'required'   => 'Nama lengkap harus diisi',
            'min_length' => 'Nama lengkap minimal 3 karakter',
        ],
        'email' => [
            'required'    => 'Email harus diisi',
            'valid_email' => 'Format email tidak valid',
            'is_unique'   => 'Email sudah terdaftar',
        ],
        'password' => [
            'required'   => 'Password harus diisi',
            'min_length' => 'Password minimal 6 karakter',
        ],
    ];

    protected $skipValidation = false;

    public function getUserRoles($userId)
    {
        return $this->db->table('user_roles')
                       ->select('roles.*')
                       ->join('roles', 'roles.id = user_roles.role_id')
                       ->where('user_roles.user_id', $userId)
                       ->get()
                       ->getResultArray();
    }

    public function hasRole($userId, $roleName)
    {
        $result = $this->db->table('user_roles')
                          ->select('roles.*')
                          ->join('roles', 'roles.id = user_roles.role_id')
                          ->where('user_roles.user_id', $userId)
                          ->where('roles.role_name', $roleName)
                          ->get()
                          ->getRowArray();
        
        return !empty($result);
    }

    public function verifyPassword($email, $password)
    {
        $user = $this->where('email', $email)->first();
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function getCustomersPaginated($customerRoleId, $search = null, $perPage = 10, $page = 1)
    {
        $builder = $this->select('users.*')
                       ->join('user_roles', 'user_roles.user_id = users.id')
                       ->where('user_roles.role_id', $customerRoleId)
                       ->groupBy('users.id');
        
        // Add search filter
        if ($search) {
            $builder->groupStart()
                   ->like('users.full_name', $search)
                   ->orLike('users.email', $search)
                   ->orLike('users.phone', $search)
                   ->groupEnd();
        }
        
        $offset = ($page - 1) * $perPage;
        $customers = $builder->orderBy('users.created_at', 'DESC')
                           ->limit($perPage, $offset)
                           ->findAll();
        
        // Get roles for each customer
        foreach ($customers as &$customer) {
            $customer['roles'] = $this->getUserRoles($customer['id']);
        }
        
        return $customers;
    }

    public function getCustomersCount($customerRoleId, $search = null)
    {
        $builder = $this->select('users.id')
                       ->join('user_roles', 'user_roles.user_id = users.id')
                       ->where('user_roles.role_id', $customerRoleId)
                       ->groupBy('users.id');
        
        // Add search filter
        if ($search) {
            $builder->groupStart()
                   ->like('users.full_name', $search)
                   ->orLike('users.email', $search)
                   ->orLike('users.phone', $search)
                   ->groupEnd();
        }
        
        return $builder->countAllResults();
    }
}

