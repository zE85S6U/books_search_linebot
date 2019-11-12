<?php


use App\Utils\Line;
use PHPUnit\Framework\TestCase;

class LineTest extends TestCase
{
    protected $line;

    public function setUp(): void
    {
        $this->line = new Line();
    }


    /**
     * @dataProvider additionProvider
     * @param $data
     */
    public function test_trimSearchData($data)
    {
        $expected = ['type' => 'text', 'text' => "見つかりませんでした..."];

        $actual = $this->line->trimSearchData($data);

        $this->assertEquals($expected, $actual);
    }

    public function additionProvider()
    {
        return [
            [0, 0, 0],
            [0, 1, 1],
            [1, 0, 1]
        ];
    }
}
