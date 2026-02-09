<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPaymentFieldsToOrders extends Migration
{
    public function up()
    {
        $this->forge->addColumn('orders', [
            'payment_status' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
                'default'    => 'pending',
                'after'      => 'order_status',
            ],
            'payment_method' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
                'after'      => 'payment_status',
            ],
            'snap_token' => [
                'type'       => 'TEXT',
                'null'       => true,
                'after'      => 'payment_method',
            ],
            'transaction_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'after'      => 'snap_token',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('orders', ['payment_status', 'payment_method', 'snap_token', 'transaction_id']);
    }
}

