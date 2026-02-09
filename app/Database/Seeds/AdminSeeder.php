<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Create roles: Admin, Staff, Manager
        $roles = ['admin', 'manager', 'staff', 'customer'];
        
        foreach ($roles as $roleName) {
            $role = $this->db->table('roles')
                            ->where('role_name', $roleName)
                            ->get()
                            ->getRow();

            if (!$role) {
                $this->db->table('roles')->insert(['role_name' => $roleName]);
                echo ucfirst($roleName) . " role created successfully!\n";
            }
        }

        // Get role IDs
        $adminRole = $this->db->table('roles')
                              ->where('role_name', 'admin')
                              ->get()
                              ->getRow();
        $adminRoleId = $adminRole ? $adminRole->id : null;
        
        $managerRole = $this->db->table('roles')
                                ->where('role_name', 'manager')
                                ->get()
                                ->getRow();
        $managerRoleId = $managerRole ? $managerRole->id : null;
        
        $staffRole = $this->db->table('roles')
                              ->where('role_name', 'staff')
                              ->get()
                              ->getRow();
        $staffRoleId = $staffRole ? $staffRole->id : null;

        // Create admin user
        $userData = [
            'full_name'  => 'Administrator',
            'email'      => 'admin@gmail.com',
            'password'   => password_hash('admin123', PASSWORD_DEFAULT),
            'phone'      => null,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        // Check if admin already exists
        $existingAdmin = $this->db->table('users')
                                  ->where('email', $userData['email'])
                                  ->get()
                                  ->getRow();

        // Create or update Admin user
        if (!$existingAdmin) {
            $this->db->table('users')->insert($userData);
            $userId = $this->db->insertID();

            // Assign admin role
            $this->db->table('user_roles')->insert([
                'user_id' => $userId,
                'role_id' => $adminRoleId,
            ]);

            echo "Admin user created successfully!\n";
            echo "Email: admin@gmail.com\n";
            echo "Password: admin123\n";
        } else {
            // Check if admin has role assigned
            $userRole = $this->db->table('user_roles')
                                ->where('user_id', $existingAdmin->id)
                                ->where('role_id', $adminRoleId)
                                ->get()
                                ->getRow();

            if (!$userRole) {
                $this->db->table('user_roles')->insert([
                    'user_id' => $existingAdmin->id,
                    'role_id' => $adminRoleId,
                ]);
                echo "Admin role assigned to existing user.\n";
            } else {
                echo "Admin user already exists with role.\n";
            }
        }
        
        // Create Manager user (always check, regardless of admin)
        if ($managerRoleId) {
            $managerData = [
                'full_name'  => 'Manager User',
                'email'      => 'manager@gmail.com',
                'password'   => password_hash('manager123', PASSWORD_DEFAULT),
                'phone'      => null,
                'created_at' => date('Y-m-d H:i:s'),
            ];
            
            $existingManager = $this->db->table('users')
                                       ->where('email', $managerData['email'])
                                       ->get()
                                       ->getRow();
            
            if (!$existingManager) {
                $this->db->table('users')->insert($managerData);
                $managerUserId = $this->db->insertID();
                
                $this->db->table('user_roles')->insert([
                    'user_id' => $managerUserId,
                    'role_id' => $managerRoleId,
                ]);
                
                echo "Manager user created successfully!\n";
                echo "Email: manager@gmail.com\n";
                echo "Password: manager123\n";
            } else {
                // Check if manager has role assigned
                $managerRole = $this->db->table('user_roles')
                                       ->where('user_id', $existingManager->id)
                                       ->where('role_id', $managerRoleId)
                                       ->get()
                                       ->getRow();
                
                if (!$managerRole) {
                    $this->db->table('user_roles')->insert([
                        'user_id' => $existingManager->id,
                        'role_id' => $managerRoleId,
                    ]);
                    echo "Manager role assigned to existing user.\n";
                } else {
                    echo "Manager user already exists with role.\n";
                }
            }
        }
        
        // Create Staff user (always check, regardless of admin)
        if ($staffRoleId) {
            $staffData = [
                'full_name'  => 'Staff User',
                'email'      => 'staff@gmail.com',
                'password'   => password_hash('staff123', PASSWORD_DEFAULT),
                'phone'      => null,
                'created_at' => date('Y-m-d H:i:s'),
            ];
            
            $existingStaff = $this->db->table('users')
                                     ->where('email', $staffData['email'])
                                     ->get()
                                     ->getRow();
            
            if (!$existingStaff) {
                $this->db->table('users')->insert($staffData);
                $staffUserId = $this->db->insertID();
                
                $this->db->table('user_roles')->insert([
                    'user_id' => $staffUserId,
                    'role_id' => $staffRoleId,
                ]);
                
                echo "Staff user created successfully!\n";
                echo "Email: staff@gmail.com\n";
                echo "Password: staff123\n";
            } else {
                // Check if staff has role assigned
                $staffRole = $this->db->table('user_roles')
                                     ->where('user_id', $existingStaff->id)
                                     ->where('role_id', $staffRoleId)
                                     ->get()
                                     ->getRow();
                
                if (!$staffRole) {
                    $this->db->table('user_roles')->insert([
                        'user_id' => $existingStaff->id,
                        'role_id' => $staffRoleId,
                    ]);
                    echo "Staff role assigned to existing user.\n";
                } else {
                    echo "Staff user already exists with role.\n";
                }
            }
        }
    }
}

