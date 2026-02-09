<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AuditLogModel;

class AuditLogController extends BaseController
{
    protected $auditLogModel;

    public function __construct()
    {
        $this->auditLogModel = new AuditLogModel();
    }

    public function index()
    {
        // Get filter parameters
        $search = $this->request->getGet('search');
        $userId = $this->request->getGet('user_id');
        $action = $this->request->getGet('action');
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $page = (int) ($this->request->getGet('page') ?? 1);
        
        // Get per page from request, default to 5
        $perPageOptions = [5, 10, 50, 100, 500];
        $perPage = (int) ($this->request->getGet('per_page') ?? 5);
        if (!in_array($perPage, $perPageOptions)) {
            $perPage = 5;
        }

        // Use database service directly to ensure fresh builder instances
        $db = \Config\Database::connect();
        
        // Build query for count - use database service directly for fresh builder
        $countBuilder = $db->table('audit_logs');
        $countBuilder->select('audit_logs.id')
                     ->join('users', 'users.id = audit_logs.user_id', 'left');

        // Apply filters to count query
        if ($search) {
            $countBuilder->groupStart()
                   ->like('users.full_name', $search)
                   ->orLike('users.email', $search)
                   ->orLike('audit_logs.action', $search)
                   ->groupEnd();
        }

        if ($userId) {
            $countBuilder->where('audit_logs.user_id', $userId);
        }

        if ($action) {
            $countBuilder->like('audit_logs.action', $action);
        }

        if ($startDate) {
            $countBuilder->where('DATE(audit_logs.created_at) >=', $startDate);
        }

        if ($endDate) {
            $countBuilder->where('DATE(audit_logs.created_at) <=', $endDate);
        }
        
        // Get total count
        $total = $countBuilder->countAllResults(false);

        // Build main query for results - use database service directly for fresh builder
        $builder = $db->table('audit_logs');
        $builder->select('audit_logs.*, users.full_name, users.email')
                ->join('users', 'users.id = audit_logs.user_id', 'left');

        // Apply filters to main query
        if ($search) {
            $builder->groupStart()
                   ->like('users.full_name', $search)
                   ->orLike('users.email', $search)
                   ->orLike('audit_logs.action', $search)
                   ->groupEnd();
        }

        if ($userId) {
            $builder->where('audit_logs.user_id', $userId);
        }

        if ($action) {
            $builder->like('audit_logs.action', $action);
        }

        if ($startDate) {
            $builder->where('DATE(audit_logs.created_at) >=', $startDate);
        }

        if ($endDate) {
            $builder->where('DATE(audit_logs.created_at) <=', $endDate);
        }

        $builder->orderBy('audit_logs.created_at', 'DESC');

        // Get paginated results
        $offset = ($page - 1) * $perPage;
        $logs = $builder->limit($perPage, $offset)->get()->getResultArray();

        // Calculate pagination
        $totalPages = ceil($total / $perPage);

        $data = [
            'title'         => 'Activity Log',
            'logs'          => $logs,
            'search'        => $search,
            'userId'        => $userId,
            'action'        => $action,
            'startDate'     => $startDate,
            'endDate'       => $endDate,
            'page'          => $page,
            'perPage'       => $perPage,
            'perPageOptions' => $perPageOptions,
            'total'         => $total,
            'totalPages'    => $totalPages,
        ];

        return view('admin/audit_logs/index', $data);
    }
}

