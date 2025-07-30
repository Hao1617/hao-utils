<?php
namespace Gongying\Utils;

class NetHelper
{
    /**
     * 发起 GET 请求
     *
     * @param string $url 请求地址
     * @param array $headers 请求头数组（如 ['Authorization: Bearer token']）
     * @param int $timeout 请求超时时间（秒）
     * @return string 返回响应内容
     * @throws \Exception 请求失败抛出异常
     */
    public static function get(string $url, array $headers = [], int $timeout = 10): string {
        return self::request('GET', $url, null, $headers, $timeout);
    }

    /**
     * 发起 POST 请求（表单格式）
     *
     * @param string $url 请求地址
     * @param array|string $data 表单数据或已编码的数据
     * @param array $headers 请求头
     * @param int $timeout 超时时间（秒）
     * @return string 响应内容
     * @throws \Exception
     */
    public static function post(string $url, array|string $data, array $headers = [], int $timeout = 10): string {
        return self::request('POST', $url, $data, $headers, $timeout);
    }

    /**
     * 发起 POST JSON 请求（Content-Type: application/json）
     *
     * @param string $url 请求地址
     * @param array $data 要发送的数组数据
     * @param array $headers 额外头部
     * @param int $timeout 超时时间（秒）
     * @return string 响应内容
     * @throws \Exception
     */
    public static function postJson(string $url, array $data, array $headers = [], int $timeout = 10): string {
        $headers[] = 'Content-Type: application/json';
        return self::request('POST', $url, json_encode($data), $headers, $timeout);
    }

    /**
     * 通用请求方法（支持 GET/POST/PUT/DELETE）
     *
     * @param string $method 请求方法
     * @param string $url 请求地址
     * @param array|string|null $data 请求数据
     * @param array $headers 请求头
     * @param int $timeout 超时时间
     * @return string 响应内容
     * @throws \Exception
     */
    public static function request(string $method, string $url, array|string|null $data = null, array $headers = [], int $timeout = 10): string
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        if ($method !== 'GET' && $data !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new \Exception("cURL Error: {$error}");
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 400) {
            throw new \Exception("HTTP Request failed: HTTP {$httpCode}, Response: {$response}");
        }

        return $response;
    }
}
