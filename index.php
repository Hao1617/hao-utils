<?php
// index.php - 工具库统一入口

require __DIR__ . '/src/ArrayHelper.php';
require __DIR__ . '/src/DateHelper.php';
require __DIR__ . '/src/DebugHelper.php';
require __DIR__ . '/src/FileHelper.php';
require __DIR__ . '/src/HttpHelper.php';
require __DIR__ . '/src/ResponseHelper.php';
require __DIR__ . '/src/Snowflake.php';
require __DIR__ . '/src/StrHelper.php';
require __DIR__ . '/src/ValidatorHelper.php';

use Hao1617\Utils\ArrayHelper;
use Hao1617\Utils\DateHelper;
use Hao1617\Utils\DebugHelper;
use Hao1617\Utils\FileHelper;
use Hao1617\Utils\HttpHelper;
use Hao1617\Utils\ResponseHelper;
use Hao1617\Utils\Snowflake;
use Hao1617\Utils\StrHelper;
use Hao1617\Utils\ValidatorHelper;

/**
 * 获取工具类实例
 *
 * 示例：
 *   $arrayHelper = Utils::array();
 *   $dateHelper  = Utils::date();
 */
class Utils
{
    public static function array(): ArrayHelper
    {
        return new ArrayHelper();
    }

    public static function date(): DateHelper
    {
        return new DateHelper();
    }

    public static function debug(): DebugHelper
    {
        return new DebugHelper();
    }

    public static function file(): FileHelper
    {
        return new FileHelper();
    }

    public static function http(): HttpHelper
    {
        return new HttpHelper();
    }

    public static function response(): ResponseHelper
    {
        return new ResponseHelper();
    }

    public static function str(): StrHelper
    {
        return new StrHelper();
    }

    public static function validator(): ValidatorHelper
    {
        return new ValidatorHelper();
    }

    // Snowflake 工具类
    // $datacenterId 和 $workerId 可以默认写，也可以在调用时覆盖
    protected static ?Snowflake $snowflakeInstance = null;
    public static function snowflake(int $datacenterId = 1, int $workerId = 1): Snowflake
    {
        if (self::$snowflakeInstance === null) {
            self::$snowflakeInstance = new Snowflake($datacenterId, $workerId);
        }
        return self::$snowflakeInstance;
    }
}
