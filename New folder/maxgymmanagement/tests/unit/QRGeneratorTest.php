<?php

use App\Controllers\Admin\QRGenerator;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

class QRGeneratorTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    // For Migrations
    protected $migrate     = true;
    protected $migrateOnce = true;
    protected $refresh     = true;
    protected $namespace   = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->db->table('tb_wilayah')->insert([
            'wilayah' => 'Z',
        ]);

        $this->db->table('tb_di')->insert([
            'di' => 'Z',
            'id_wilayah' => $this->db->table('tb_wilayah')->get(1)->getRowArray()['id'],
        ]);

        $this->db->table('tb_penjaga')->insert([
            'nip' => '1234567890',
            'nama_penjaga' => 'John Doe',
            'id_di' => $diId ?? 1,
            'no_hp' => '081234567890',
            'unique_code' => '1234567890',
        ]);
    }

    public function testGenerateQrCode(): void
    {
        $di = $this->db->table('tb_di')->get(1)->getRowArray();
        $penjaga = $this->db->table('tb_penjaga')
            ->where('id_di', $di['id_di'])
            ->get(1)
            ->getRowArray();

        $generator = new QRGenerator;
        $generator->setQrCodeFilePath(QRGenerator::UPLOADS_PATH . "test/");

        $result = $generator->generate(
            $penjaga['nama_penjaga'],
            $penjaga['nip'],
            $penjaga['unique_code']
        );

        $this->assertIsString($result);
        $this->assertTrue(file_exists($result));
        $this->assertStringContainsString('public/uploads/test/', $result);
        $this->assertStringContainsString('.png', $result);
    }
}
