<?php

namespace App\Tests\Utils;

use App\Utils\DBconnect;
use PHPUnit\Framework\TestCase;

class DBconnectTest extends TestCase
{
    protected $dbcon;

    public function setUp(): void
    {
        $this->dbcon = new DBconnect();
    }

    public function testGetConnection()
    {
        $this->assertEquals($this->dbcon, $this->dbcon);
    }

    public function testUpdateUser()
    {
    }

    public function testGetStonesByUserId()
    {

    }

    public function testRegisterUser()
    {
        $userid = 'test00000000';
        $expected = $this->dbcon->registerUser($userid);
    }

    public function testDeleteUser()
    {
        $userid = 'test00000000';
        $expected = $this->dbcon->deleteUser($userid);
    }
}
