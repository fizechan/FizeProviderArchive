<?php

namespace Tests;

use Fize\Provider\Archive\Bz2;
use PHPUnit\Framework\TestCase;

class TestBz2 extends TestCase
{

    public function test__construct()
    {
        $bz2 = new Bz2(__DIR__ . '/data/测试.bz2', 'w');
        var_dump($bz2);
        self::assertIsObject($bz2);
    }

    public function test__destruct()
    {
        $bz2 = new Bz2(__DIR__ . '/data/测试.bz2', 'w');
        unset($bz2);
        self::assertTrue(true);
    }

    public function testClose()
    {
        $bz2 = new Bz2(__DIR__ . '/data/测试.bz2', 'w');
        $rst = $bz2->close();
        var_dump($rst);
        self::assertTrue($rst);
    }

    public function testCompress()
    {
        $str = Bz2::compress('这是待压缩字符串');
        var_dump($str);
        self::assertIsString($str);
    }

    public function testDecompress()
    {
        $str = Bz2::compress('这是待压缩字符串');

        $str1 = Bz2::decompress($str);
        self::assertEquals('这是待压缩字符串', $str1);

        $str2 = Bz2::decompress($str, true);
        self::assertEquals('这是待压缩字符串', $str2);

        $str3 = Bz2::decompress($str, 1);
        self::assertEquals('这是待压缩字符串', $str3);
    }

    public function testErrno()
    {
        $bz2 = new Bz2(__DIR__ . '/data/测试.bz2', 'w');
        $errno = $bz2->errno();
        var_dump($errno);
        self::assertEquals(0, $errno);
    }

    public function testError()
    {
        $bz2 = new Bz2(__DIR__ . '/data/测试.bz2', 'w');
        $error = $bz2->error();
        var_dump($error);
        self::assertIsArray($error);
    }

    public function testErrstr()
    {
        $bz2 = new Bz2(__DIR__ . '/data/测试.bz2', 'w');
        $errstr = $bz2->errstr();
        var_dump($errstr);
        self::assertIsString($errstr);
    }

    public function testFlush()
    {
        $bz2 = new Bz2(__DIR__ . '/data/测试.bz2', 'w');
        $bz2->write('写入试试咯');
        $rst = $bz2->flush();
        var_dump($rst);
        self::assertTrue($rst);
    }

    public function testOpen()
    {
        $bz2 = new Bz2(__DIR__ . '/data/测试.bz2', 'w');
        $bz2->open(__DIR__ . '/data/测试2.bz2', 'w');
        var_dump($bz2);
        self::assertIsObject($bz2);
    }

    public function testRead()
    {
        $bz2 = new Bz2(__DIR__ . '/data/测试.bz2', 'w');
        $bz2->write('这是写入的一些字符串');
        $bz2->close();
        $bz2 = new Bz2(__DIR__ . '/data/测试.bz2', 'r');
        $str = $bz2->read();
        self::assertEquals('这是写入的一些字符串', $str);
    }

    public function testWrite()
    {
        $bz2 = new Bz2(__DIR__ . '/data/测试.bz2', 'w');
        $len = $bz2->write('这是写入的一些字符串');
        var_dump($len);
        self::assertIsInt($len);
    }
}
