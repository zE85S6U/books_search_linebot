<?php


use App\Line\Line;
use PHPUnit\Framework\TestCase;

class LineTest extends TestCase
{

    public function testStickerType()
    {
        $line = new Line();
        $this->assertNotEmpty($line->stickerType());
    }
}
