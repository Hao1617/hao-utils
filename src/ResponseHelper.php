<?php

namespace Hao1617\Utils;

class ResponseHelper
{
    /**
     * 默认成功状态码
     */
    protected static int $defaultSuccessCode = 200;

    /**
     * 默认失败状态码
     */
    protected static int $defaultErrorCode = 500;

    /**
     * 构造统一返回格式
     *
     * @param int $code    状态码
     * @param string $msg  提示信息
     * @param mixed $data  数据内容
     * @param array $extra 额外字段
     * @return array
     */
    public static function format(int $code, string $msg, $data = null, array $extra = []): array
    {
        return array_merge([
            'code' => $code,
            'msg'  => $msg,
            'data' => $data,
        ], $extra);
    }

    /**
     * 成功响应
     *
     * @param mixed $data 返回数据
     * @param string $msg 成功信息
     * @param int|null $code 自定义状态码
     * @param array $extra 附加字段
     * @return array
     */
    public static function success($data = null, string $msg = '操作成功', ?int $code = null, array $extra = []): array
    {
        return self::format($code ?? self::$defaultSuccessCode, $msg, $data, $extra);
    }

    /**
     * 错误响应
     *
     * @param string $msg 错误信息
     * @param int|null $code 自定义错误码
     * @param mixed $data 附带数据
     * @param array $extra 附加字段
     * @return array
     */
    public static function error(string $msg = '操作失败', ?int $code = null, $data = null, array $extra = []): array
    {
        return self::format($code ?? self::$defaultErrorCode, $msg, $data, $extra);
    }

    /**
     * 返回 JSON 字符串（成功）
     */
    public static function successJson($data = null, string $msg = '操作成功', ?int $code = null, array $extra = []): string
    {
        return json_encode(self::success($data, $msg, $code, $extra), JSON_UNESCAPED_UNICODE);
    }

    /**
     * 返回 JSON 字符串（失败）
     */
    public static function errorJson(string $msg = '操作失败', ?int $code = null, $data = null, array $extra = []): string
    {
        return json_encode(self::error($msg, $code, $data, $extra), JSON_UNESCAPED_UNICODE);
    }

    /**
     * 输出并终止（成功）
     */
    public static function successExit($data = null, string $msg = '操作成功', ?int $code = null, array $extra = []): void
    {
        header('Content-Type: application/json');
        exit(self::successJson($data, $msg, $code, $extra));
    }

    /**
     * 输出并终止（失败）
     */
    public static function errorExit(string $msg = '操作失败', ?int $code = null, $data = null, array $extra = []): void
    {
        header('Content-Type: application/json');
        exit(self::errorJson($msg, $code, $data, $extra));
    }
}
