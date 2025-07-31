<?php
namespace Hao1617\Utils;

/**
 * Class DebugHelper
 * 调试工具类，用于开发过程中快速查看数据结构
 */
class DebugHelper
{
    /**
     * 打印数据并中止程序（类似 Laravel 的 dd）
     *
     * @param mixed $data 任意类型的数据
     * @return void
     *
     * @example
     * DebugHelper::dd(['a' => 1, 'b' => 2]);
     */
    public static function dd(mixed $data): void {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        exit;
    }

    /**
     * 打印数据但不中止程序（类似 Laravel 的 dump）
     *
     * @param mixed $data 任意类型的数据
     * @return void
     *
     * @example
     * DebugHelper::dump($data);
     */
    public static function dump(mixed $data): void {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }

    /**
     * 将数据以格式化 JSON 字符串输出
     *
     * @param mixed $data 任意类型（数组/对象）
     * @return string 格式化 JSON 字符串
     *
     * @example
     * echo DebugHelper::prettyJson($arr);
     */
    public static function prettyJson(mixed $data): string {
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * 将数据以紧凑 JSON 字符串输出（不格式化）
     *
     * @param mixed $data 任意类型（数组/对象）
     * @return string 紧凑 JSON 字符串
     *
     * @example
     * echo DebugHelper::toJson($arr);
     */
    public static function toJson(mixed $data): string {
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 将调试内容写入日志文件（可选）
     *
     * @param mixed $data 数据
     * @param string $filename 日志文件路径（默认 runtime/debug.log）
     * @return void
     *
     * @example
     * DebugHelper::log($data);
     */
    public static function log(mixed $data, string $filename = __DIR__ . '/../../runtime/debug.log'): void {
        $json = self::prettyJson($data);
        $content = "[" . date('Y-m-d H:i:s') . "]\n" . $json . "\n\n";
        file_put_contents($filename, $content, FILE_APPEND);
    }
}
