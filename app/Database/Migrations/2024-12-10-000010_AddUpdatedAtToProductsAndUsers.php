<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUpdatedAtToProductsAndUsers extends Migration
{
    public function up()
    {
        // Add updated_at to products table
        $this->forge->addColumn('products', [
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
                'default' => null,
                'on update' => 'CURRENT_TIMESTAMP',
            ],
        ]);

        // Add updated_at to users table
        $this->forge->addColumn('users', [
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
                'default' => null,
                'on update' => 'CURRENT_TIMESTAMP',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('products', 'updated_at');
        $this->forge->dropColumn('users', 'updated_at');
    }
}

