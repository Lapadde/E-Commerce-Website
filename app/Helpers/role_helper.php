<?php

if (!function_exists('hasRole')) {
    /**
     * Check if current user has a specific role
     *
     * @param string|array $roleName Role name(s) to check
     * @return bool
     */
    function hasRole($roleName)
    {
        if (!session()->get('admin_logged_in')) {
            return false;
        }

        $userRoles = session()->get('admin_roles', []);
        
        if (is_array($roleName)) {
            foreach ($roleName as $role) {
                if (in_array(strtolower($role), array_map('strtolower', $userRoles))) {
                    return true;
                }
            }
            return false;
        }

        return in_array(strtolower($roleName), array_map('strtolower', $userRoles));
    }
}

if (!function_exists('isAdmin')) {
    /**
     * Check if current user is admin
     *
     * @return bool
     */
    function isAdmin()
    {
        return hasRole('admin');
    }
}

if (!function_exists('isManager')) {
    /**
     * Check if current user is manager
     *
     * @return bool
     */
    function isManager()
    {
        return hasRole('manager');
    }
}

if (!function_exists('isStaff')) {
    /**
     * Check if current user is staff
     *
     * @return bool
     */
    function isStaff()
    {
        return hasRole('staff');
    }
}

if (!function_exists('canAccess')) {
    /**
     * Check if user can access based on role hierarchy
     * Admin > Manager > Staff
     *
     * @param string $requiredRole Minimum required role
     * @return bool
     */
    function canAccess($requiredRole)
    {
        if (!session()->get('admin_logged_in')) {
            return false;
        }

        $userRoles = session()->get('admin_roles', []);
        $roleHierarchy = ['admin' => 3, 'manager' => 2, 'staff' => 1];
        
        $userMaxLevel = 0;
        foreach ($userRoles as $role) {
            $roleLower = strtolower($role);
            if (isset($roleHierarchy[$roleLower])) {
                $userMaxLevel = max($userMaxLevel, $roleHierarchy[$roleLower]);
            }
        }

        $requiredLevel = $roleHierarchy[strtolower($requiredRole)] ?? 0;
        
        return $userMaxLevel >= $requiredLevel;
    }
}

