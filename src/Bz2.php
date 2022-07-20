<?php

namespace Fize\Provider\Archive;

use Exception;

/**
 * bzip2 压缩包操作类
 */
class Bz2
{

    /**
     * @var resource bzip2文件指针
     */
    private $bz = null;

    /**
     * 构造
     *
     * 参数 `$file` :
     *   指定文件不存在时将尝试创建
     * 参数 `$mode` :
     *   和 fopen() 函数类似，但仅仅支持 'r'（读）和 'w'（写）。
     * @param string $file 待打开的文件的文件名，或者已经存在的资源流。
     * @param string $mode 模式
     */
    public function __construct($file, $mode)
    {
        $this->open($file, $mode);
    }

    /**
     * 析构函数
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * 关闭 bzip2 文件
     * @return bool
     */
    public function close()
    {
        if (!$this->bz) {
            return false;
        }
        $result = bzclose($this->bz);
        if ($result) {
            $this->bz = null;
        }
        return $result;
    }

    /**
     * 把一个字符串压缩成 bzip2 编码数据
     *
     * 参数 `$blocksize` :
     *   应该是一个 1-9 的数字。9 可以有最高的压缩比，但会使用更多的资源。
     * 参数 `$workfactor` :
     *   值可以是在 0 至 250 之间，0 是一个特殊的情况。
     * @param string $source     待压缩的字符串。
     * @param int    $blocksize  指定压缩时使用的块大小
     * @param int    $workfactor 控制压缩阶段出现最坏的重复性高的情况下输入数据时的行为
     * @return string 压缩后的字符串
     */
    public static function compress($source, $blocksize = 4, $workfactor = 0)
    {
        return bzcompress($source, $blocksize, $workfactor);
    }

    /**
     * 解压经 bzip2 编码过的数据
     * @param string $source 编码过的数据
     * @param int    $small  是否使用一种内存开销更小的替代算法
     * @return string 解压后的字符串
     */
    public static function decompress($source, $small = 0)
    {
        return bzdecompress($source, $small);
    }

    /**
     * 返回一个 bzip2 错误码
     * @return int
     */
    public function errno()
    {
        return bzerrno($this->bz);
    }

    /**
     * 返回包含 bzip2 错误号和错误字符串的一个数组
     * @return array
     */
    public function error()
    {
        return bzerror($this->bz);
    }

    /**
     * 返回一个 bzip2 的错误字符串
     * @return string
     */
    public function errstr()
    {
        return bzerrstr($this->bz);
    }

    /**
     * 强制写入所有写缓冲区的数据
     * @return bool 成功时返回 TRUE， 或者在失败时返回 FALSE。
     */
    public function flush()
    {
        return bzflush($this->bz);
    }

    /**
     * 打开一个经 bzip2 压缩过的文件
     *
     * 参数 `$mode` :
     *   和 `fopen()` 函数类似，但仅仅支持 'r'（读）和 'w'（写）。
     *   其他任何模式都会导致 bzopen 返回 FALSE。
     * @param string $file 待打开的文件
     * @param string $mode 模式
     */
    public function open($file, $mode)
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $file = iconv('UTF-8', 'GBK', $file);
        }
        try {
            $this->close();
            $this->bz = bzopen($file, $mode);
        } catch (Exception $e) {
            $code = $e->getCode();
            $msg = $e->getMessage();
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $msg = iconv('GBK', 'UTF-8', $msg);
            }
            throw new Exception($msg, $code);
        }
    }

    /**
     * 从文件读取数据
     *
     * 参数 `$length` :
     *   读取到 length（未经压缩的长度）个字节，或者到文件尾，取决于先到哪个。
     *   如果没有提供该参数， read()  方法一次会读入 1024 个字节（未经压缩的长度）。
     *   一次最大可读入 8192 个未压缩的字节。
     * @param int $length 读取字节长度
     * @return string 返回解压的数据
     */
    public function read($length = 1024)
    {
        return bzread($this->bz, $length);
    }

    /**
     * 二进制安全地写入 bzip2 文件
     *
     * 注意不能多次调用该方法，bz2文件是一次性写入并覆盖的
     * 参数 `$length` :
     *   如果提供了参数 `$length` ，将仅仅写入 length（未压缩）个字节，
     *   若 data 小于该指定的长度则写入全部数据。
     * @param string $data   要写入的数据
     * @param int    $length 写入字节长度
     * @return int 返回写入的数据字节数
     */
    public function write($data, $length = null)
    {
        if ($length === null) {
            $rst = bzwrite($this->bz, $data);
        } else {
            $rst = bzwrite($this->bz, $data, $length);
        }
        return $rst;
    }
}
