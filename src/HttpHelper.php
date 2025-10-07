<?php

namespace Hao1617\Utils;

class HttpHelper
{
    /**
     * 发起 GET 请求
     *
     * @param string $url 请求地址
     * @param array $headers 请求头数组
     * @param int $timeout 请求超时时间（秒）
     * @param array $proxy 代理配置 ['type'=>'http|socks5', 'host'=>'127.0.0.1', 'port'=>8080, 'user'=>'username', 'pass'=>'password']
     * @return string 返回响应内容
     * @throws \Exception 请求失败抛出异常
     */
    public static function get(string $url, array $headers = [], int $timeout = 10, array $proxy = []): string
    {
        return self::request('GET', $url, null, $headers, $timeout, $proxy);
    }

    /**
     * 发起 POST 请求（表单格式）
     *
     * @param string $url 请求地址
     * @param array|string $data 表单数据或已编码的数据
     * @param array $headers 请求头
     * @param int $timeout 超时时间（秒）
     * @param array $proxy 代理配置
     * @return string 响应内容
     * @throws \Exception
     */
    public static function post(string $url, array|string $data, array $headers = [], int $timeout = 10, array $proxy = []): string
    {
        return self::request('POST', $url, $data, $headers, $timeout, $proxy);
    }

    /**
     * 发起 POST JSON 请求
     *
     * @param string $url 请求地址
     * @param array $data 要发送的数组数据
     * @param array $headers 额外头部
     * @param int $timeout 超时时间（秒）
     * @param array $proxy 代理配置
     * @return string 响应内容
     * @throws \Exception
     */
    public static function postJson(string $url, array $data, array $headers = [], int $timeout = 10, array $proxy = []): string
    {
        $headers[] = 'Content-Type: application/json';
        return self::request('POST', $url, json_encode($data), $headers, $timeout, $proxy);
    }

    /**
     * 上传文件（multipart/form-data）
     *
     * @param string $url 请求地址
     * @param string $filePath 文件路径
     * @param string $fieldName 表单字段名
     * @param array $extraData 额外表单数据 ['key'=>'value']
     * @param array $headers 额外请求头
     * @param int $timeout 超时时间（秒）
     * @param array $proxy 代理配置 ['type'=>'http|socks5', 'host'=>'127.0.0.1', 'port'=>8080, 'user'=>'', 'pass'=>'']
     */
    public static function uploadFile(string $url, string $filePath, string $fieldName = 'file', array $extraData = [], array $headers = [], int $timeout = 30, array $proxy = []): string
    {
        if (!file_exists($filePath)) {
            throw new \Exception("File does not exist: {$filePath}");
        }

        $postData = $extraData;
        $postData[$fieldName] = new \CURLFile($filePath);

        return self::request('POST', $url, $postData, $headers, $timeout, $proxy);
    }
    /**
     * 通用请求方法
     *
     * @param string $method 请求方法
     * @param string $url 请求地址
     * @param array|string|null $data 请求数据
     * @param array $headers 请求头
     * @param int $timeout 超时时间
     * @param array $proxy 代理配置 ['type'=>'http|socks5', 'host'=>'127.0.0.1', 'port'=>8080, 'user'=>'', 'pass'=>'']
     * @return string
     * @throws \Exception
     */
    public static function request(string $method, string $url, array|string|null $data = null, array $headers = [], int $timeout = 10, array $proxy = []): string
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

        // 设置代理
        if (!empty($proxy)) {
            $typeMap = [
                'http' => CURLPROXY_HTTP,
                'socks5' => CURLPROXY_SOCKS5,
                'socks5h' => CURLPROXY_SOCKS5_HOSTNAME,
            ];
            $proxyType = $typeMap[$proxy['type'] ?? 'http'] ?? CURLPROXY_HTTP;
            curl_setopt($ch, CURLOPT_PROXYTYPE, $proxyType);
            curl_setopt($ch, CURLOPT_PROXY, $proxy['host'] . ':' . $proxy['port']);

            if (!empty($proxy['user']) && !empty($proxy['pass'])) {
                curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxy['user'] . ':' . $proxy['pass']);
            }
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
