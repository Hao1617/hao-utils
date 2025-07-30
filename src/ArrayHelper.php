<?php
namespace Gongying\Utils;

/**
 * Class ArrayHelper
 * 提供数组处理相关常用方法
 */
class ArrayHelper
{
    /**
     * 判断数组是否为关联数组
     *
     * @param array $array
     * @return bool
     */
    public static function isAssoc(array $array): bool
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }

    /**
     * 从多维数组中取值，支持 "a.b.c" 方式
     *
     * @param array $array
     * @param string $key 路径，例如 'user.name.first'
     * @param mixed $default
     * @return mixed
     */
    public static function get(array $array, string $key, mixed $default = null): mixed
    {
        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return $default;
            }
            $array = $array[$segment];
        }
        return $array;
    }

    /**
     * 根据指定键从数组中提取键值映射（类似 array_column）
     *
     * @param array $array
     * @param string $keyField
     * @param string|null $valueField
     * @return array
     */
    public static function pluck(array $array, string $keyField, ?string $valueField = null): array
    {
        $result = [];
        foreach ($array as $item) {
            $key = $item[$keyField] ?? null;
            $value = $valueField ? ($item[$valueField] ?? null) : $item;
            if ($key !== null) {
                $result[$key] = $value;
            }
        }
        return $result;
    }
}
