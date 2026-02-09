<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditLogModel extends Model
{
    protected $table            = 'audit_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id', 'action', 'created_at'
    ];

    // Dates
    protected $useTimestamps = false; // Disable auto timestamps, we handle created_at manually
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = null;

    // Validation
    protected $validationRules = [
        'user_id' => 'required|integer',
        'action'  => 'required|max_length[255]',
    ];

    protected $skipValidation = false;

    public function logAction($userId, $action)
    {
        return $this->insert([
            'user_id'    => $userId,
            'action'     => $action,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function getUserLogs($userId, $limit = 50)
    {
        return $this->where('user_id', $userId)
                   ->orderBy('created_at', 'DESC')
                   ->findAll($limit);
    }

    public function getRecentLogs($limit = 100)
    {
        return $this->select('audit_logs.*, users.full_name, users.email')
                   ->join('users', 'users.id = audit_logs.user_id')
                   ->orderBy('audit_logs.created_at', 'DESC')
                   ->findAll($limit);
    }
}

