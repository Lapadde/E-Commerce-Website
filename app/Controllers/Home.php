<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return redirect()->to('/shop');
    }

    public function dbTest()
    {
        $response = [
            'status' => 'error',
            'message' => '',
            'database' => '',
            'tables' => [],
            'data' => []
        ];

        try {
            $db = \Config\Database::connect();
            
            // Test connection
            if ($db->connect()) {
                $response['status'] = 'success';
                $response['message'] = 'Database connected successfully!';
                $response['database'] = $db->getDatabase();
                
                // Get all tables
                $tables = $db->listTables();
                $response['tables'] = $tables;
                
                // Count data in each table
                foreach ($tables as $table) {
                    $count = $db->table($table)->countAllResults();
                    $response['data'][$table] = [
                        'count' => $count
                    ];
                }
                
                // Get sample admin user (hide password) - using user_roles for role check
                $adminUser = $db->table('users')
                                ->select('users.id, users.full_name, users.email, users.phone, users.created_at')
                                ->join('user_roles', 'user_roles.user_id = users.id')
                                ->join('roles', 'roles.id = user_roles.role_id')
                                ->where('roles.role_name', 'admin')
                                ->get()
                                ->getResultArray();
                $response['admin_users'] = $adminUser;
            }
        } catch (\Exception $e) {
            $response['status'] = 'error';
            $response['message'] = $e->getMessage();
        }

        return $this->response->setJSON($response);
    }
}
