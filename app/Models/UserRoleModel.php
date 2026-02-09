<?php

namespace App\Models;

use CodeIgniter\Model;

class UserRoleModel extends Model
{
    protected $table            = 'user_roles';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id', 'role_id'
    ];

    // Dates
    protected $useTimestamps = false;

    // Validation
    protected $validationRules = [
        'user_id' => 'required|integer',
        'role_id' => 'required|integer',
    ];

    protected $skipValidation = false;

    public function assignRole($userId, $roleId)
    {
        // Check if already assigned
        $existing = $this->where(['user_id' => $userId, 'role_id' => $roleId])->first();
        if ($existing) {
            return false;
        }

        return $this->insert([
            'user_id' => $userId,
            'role_id' => $roleId,
        ]);
    }

    public function removeRole($userId, $roleId)
    {
        return $this->where(['user_id' => $userId, 'role_id' => $roleId])->delete();
    }

    public function getUserRoles($userId)
    {
        return $this->select('roles.*')
                   ->join('roles', 'roles.id = user_roles.role_id')
                   ->where('user_roles.user_id', $userId)
                   ->findAll();
    }
}

