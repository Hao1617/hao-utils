<?php

namespace Hao\Utils;

/**
 * Class DateHelper
 * 日期与时间处理类
 */
class DateHelper
{
    /**
     * 获取当前时间字符串（格式化）
     *
     * @param string $format
     * @return string
     */
    public static function now(string $format = 'Y-m-d H:i:s'): string
    {
        return date($format);
    }

    /**
     * 将时间戳转换为日期
     *
     * @param int $timestamp
     * @param string $format
     * @return string
     */
    public static function fromTimestamp(int $timestamp, string $format = 'Y-m-d H:i:s'): string
    {
        return date($format, $timestamp);
    }

    /**
     * 将日期转换为时间戳
     *
     * @param string $datetime
     * @return int
     */
    public static function toTimestamp(string $datetime): int
    {
        return strtotime($datetime);
    }

    /**
     * 判断指定时间是否为今天
     *
     * @param string|int $date 日期字符串或时间戳
     * @return bool
     */
    public static function isToday(string|int $date): bool
    {
        $timestamp = is_numeric($date) ? (int)$date : strtotime($date);
        return date('Y-m-d', $timestamp) === date('Y-m-d');
    }

    /**
     * 获取当前时间距离今天24点（午夜）的剩余秒数
     *
     * @return int 剩余秒数（单位：秒）
     *
     * @example
     * // 当前时间为 2025-07-30 15:30:00
     * DateHelper::secondsUntilMidnight(); // 返回 30600（约8.5小时）
     */
    public static function secondsUntilMidnight(): int
    {
        $now = time();
        $midnight = strtotime('tomorrow'); // 明天0点，也就是今天24点
        return $midnight - $now;
    }
}
