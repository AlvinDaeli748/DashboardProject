<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePenjualanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'provinsi' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'jenis' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'total_penjualan' => [
                'type' => 'INT',
                'constraint' => '255',
            ],
            'tgl_penjualan' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('penjualan');

        $seeder = \Config\Database::seeder();
        $seeder->call('PenjualanSeeder');
    }

    public function down()
    {
        $this->forge->dropTable('penjualan');
    }
}
