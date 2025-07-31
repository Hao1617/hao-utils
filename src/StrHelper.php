<?php
namespace Hao\Utils;

/**
 * Class StrHelper
 * 字符串与ID生成的常用工具函数集合
 */
class StrHelper
{
    protected static $snowflakeInstances = [];
    /**
     * 判断字符串是否以指定子串开头
     *
     * @param string $haystack 原字符串
     * @param string $needle 要匹配的前缀
     * @return bool 如果以 $needle 开头返回 true，否则 false
     *
     * @example
     * StrHelper::startsWith('hello world', 'hello'); // true
     */
    public static function startsWith(string $haystack, string $needle): bool {
        return strncmp($haystack, $needle, strlen($needle)) === 0;
    }

    /**
     * 判断字符串是否以指定子串结尾
     *
     * @param string $haystack 原字符串
     * @param string $needle 要匹配的后缀
     * @return bool 如果以 $needle 结尾返回 true，否则 false
     *
     * @example
     * StrHelper::endsWith('index.php', '.php'); // true
     */
    public static function endsWith(string $haystack, string $needle): bool {
        return substr($haystack, -strlen($needle)) === $needle;
    }

    /**
     * 生成指定长度的随机字符串（包含大小写字母与数字）
     *
     * @param int $length 字符串长度（默认8位）
     * @return string 返回生成的随机字符串
     *
     * @example
     * StrHelper::randomString(12); // "Ab39deL0Ps9X"
     */
    public static function randomString(int $length = 8): string {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $str;
    }

    /**
     * 生成唯一ID（由毫秒时间戳 + 4位随机数组成，可选前缀）
     *
     * @param string $prefix 可选前缀，例如 "order_"
     * @return string 返回唯一ID字符串
     *
     * @example
     * StrHelper::uniqueId('user_'); // "user_17224094456131234"
     */
    public static function uniqueId(string $prefix = ''): string {
        $time = microtime(true);
        $milliseconds = sprintf('%.0f', $time * 1000); // 毫秒级时间戳
        $random = mt_rand(1000, 9999); // 四位随机数
        return $prefix . $milliseconds . $random;
    }

    /**
     * 生成标准格式的 UUID v4 字符串（基于随机数）
     *
     * @return string UUID 字符串（形如 "550e8400-e29b-41d4-a716-446655440000"）
     *
     * @example
     * StrHelper::uuid(); // "e2a1f455-003c-47a3-83bc-4f6f0abdc001"
     */
    public static function uuid(): string {
        $data = random_bytes(16);
        // 设置版本和变体位
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // version 4
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // variant
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
    

    /**
     * 生成雪花ID（Snowflake ID，返回唯一的64位整数）
     *
     * 雪花ID结构：时间戳 + 数据中心ID + 机器ID + 序列号
     * 使用前确保你的服务器时间正常，不会回拨。
     *
     * @param int $datacenterId 数据中心ID（范围：0-31）
     * @param int $workerId 机器节点ID（范围：0-31）
     * @return int 返回唯一雪花ID（整数）
     *
     * @throws \Exception 如果时间回拨或参数错误会抛出异常
     *
     * @example
     * StrHelper::snowflakeId(); // 14254558870249472
     */
    public static function snowflakeId(int $datacenterId = 1, int $workerId = 1): int
    {
        $key = $datacenterId . '-' . $workerId;
        if (!isset(self::$snowflakeInstances[$key])) {
            self::$snowflakeInstances[$key] = new Snowflake($datacenterId, $workerId);
        }

        return self::$snowflakeInstances[$key]->nextId();
    }
}
