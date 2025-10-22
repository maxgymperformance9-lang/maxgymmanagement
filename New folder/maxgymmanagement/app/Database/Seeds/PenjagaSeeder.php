<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Database\Seeder;
use Config\Database;

class PenjagaSeeder extends Seeder
{
    private \Faker\Generator $faker;
    private array $di;

    public function __construct(Database $config, ?BaseConnection $db = null)
    {
        parent::__construct($config, $db);
        $this->faker = \Faker\Factory::create('id_ID');
        $this->di = $this->db->table('tb_di')->get()->getResultArray();
    }

    public function run()
    {
        $this->db->table('tb_penjaga')->insertBatch(
            $this->createSiswa(20)
        );
    }

    protected function createSiswa($count = 1)
    {
        $data = [];
        for ($i = 0; $i < $count; $i++) {
            $gender = $this->faker->randomElement(['Laki-laki', 'Perempuan']);

            array_push($data, [
                'nip' => $this->faker->numerify('#######'),
                'nama_penjaga' => $this->faker->name($gender == 'Laki-laki' ? 'male' : 'female'),
                'id_di' => $this->faker->randomElement($this->di)['id_di'],
                'jenis_kelamin' => $gender,
                'no_hp' => $this->faker->numerify('08##########'),
                'unique_code' => $this->faker->uuid()
            ]);
        }

        return $data;
    }
}
