<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username' => 'admin',
                'password' => password_hash('admin', PASSWORD_DEFAULT),
                'role' => 'superuser',
            ],
            [
                'username' => 'admin_aceh',
                'password' => password_hash('admin_aceh', PASSWORD_DEFAULT),
                'role' => 'aceh',
            ],
            [
                'username' => 'admin_sumut',
                'password' => password_hash('admin_sumut', PASSWORD_DEFAULT),
                'role' => 'sumut',
            ],
            [
                'username' => 'admin_sumbar',
                'password' => password_hash('admin_sumbar', PASSWORD_DEFAULT),
                'role' => 'sumbar',
            ],
            [
                'username' => 'admin_riau',
                'password' => password_hash('admin_riau', PASSWORD_DEFAULT),
                'role' => 'riau',
            ],
            [
                'username' => 'admin_kepri',
                'password' => password_hash('admin_kepri', PASSWORD_DEFAULT),
                'role' => 'kepri',
            ],
            [
                'username' => 'admin_jambi',
                'password' => password_hash('admin_jambi', PASSWORD_DEFAULT),
                'role' => 'jambi',
            ],
            [
                'username' => 'admin_bengkulu',
                'password' => password_hash('admin_bengkulu', PASSWORD_DEFAULT),
                'role' => 'bengkulu',
            ],
            [
                'username' => 'admin_sumsel',
                'password' => password_hash('admin_sumsel', PASSWORD_DEFAULT),
                'role' => 'sumsel',
            ],
            [
                'username' => 'admin_babel',
                'password' => password_hash('admin_babel', PASSWORD_DEFAULT),
                'role' => 'babel',
            ],
            [
                'username' => 'admin_lampung',
                'password' => password_hash('admin_lampung', PASSWORD_DEFAULT),
                'role' => 'lampung',
            ],
            
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
