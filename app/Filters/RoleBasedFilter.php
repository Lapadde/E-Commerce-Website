<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;

class RoleBasedFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if user is logged in
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login')
                ->with('error', 'Silakan login terlebih dahulu');
        }

        // If no roles specified, allow access (just check login)
        if (empty($arguments)) {
            return;
        }

        // Get user ID from session
        $userId = session()->get('admin_id');
        
        if (!$userId) {
            return redirect()->to('/admin/login')
                ->with('error', 'Session expired. Silakan login kembali');
        }

        // Check if user has required role
        $userModel = new UserModel();
        $userRoles = $userModel->getUserRoles($userId);
        
        // Extract role names
        $userRoleNames = array_column($userRoles, 'role_name');
        
        // Check if user has any of the required roles
        $hasAccess = false;
        foreach ($arguments as $requiredRole) {
            if (in_array(strtolower($requiredRole), array_map('strtolower', $userRoleNames))) {
                $hasAccess = true;
                break;
            }
        }

        // If user doesn't have required role, show 403
        if (!$hasAccess) {
            // Set status code to 403
            $response = service('response');
            $response->setStatusCode(403);
            
            // Get the forbidden view
            $view = view('errors/html/error_403');
            
            return $response->setBody($view);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}

