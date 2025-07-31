<?php

namespace Hao1617\Utils;

class ValidatorHelper
{
    /**
     * 执行验证
     *
     * @param array $data 输入数据，如 ['name' => '张三', 'age' => 20]
     * @param array $rules 验证规则
     * [
     *     'name' => 'required|min:2|max:20',
     *     'email' => 'required|email',
     *     'age' => 'numeric|between:18,60',
     *     'status' => 'in:enabled,disabled'
     * ]
     * @return array [
     *     'passed' => true|false,
     *     'errors' => ['name' => 'name 字段是必须的']
     * ]
     */
    public static function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $ruleString) {
            $value = $data[$field] ?? null;
            $rulesArray = explode('|', $ruleString);

            foreach ($rulesArray as $rule) {
                $params = null;
                if (strpos($rule, ':') !== false) {
                    [$rule, $params] = explode(':', $rule, 2);
                }

                $method = 'validate' . ucfirst($rule);
                if (method_exists(__CLASS__, $method)) {
                    $result = self::$method($value, $params, $data);
                    if ($result !== true) {
                        $errors[$field] = $result;
                        break; // 每个字段只返回第一条错误
                    }
                }
            }
        }

        return [
            'passed' => empty($errors),
            'errors' => $errors,
        ];
    }

    /** 验证：字段必须存在且不为空 */
    protected static function validateRequired($value, $params, $data)
    {
        if ($value === null || $value === '') {
            return '字段不能为空';
        }
        return true;
    }

    /** 验证：最小长度或数值 */
    protected static function validateMin($value, $param)
    {
        if (is_numeric($value)) {
            return $value >= $param ? true : "不能小于 {$param}";
        }
        return mb_strlen((string) $value) >= $param ? true : "长度不能小于 {$param}";
    }

    /** 验证：最大长度或数值 */
    protected static function validateMax($value, $param)
    {
        if (is_numeric($value)) {
            return $value <= $param ? true : "不能大于 {$param}";
        }
        return mb_strlen((string) $value) <= $param ? true : "长度不能大于 {$param}";
    }

    /** 验证：是否为数字 */
    protected static function validateNumeric($value)
    {
        return is_numeric($value) ? true : "必须是数字";
    }

    /** 验证：是否为合法邮箱 */
    protected static function validateEmail($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) ? true : "邮箱格式错误";
    }

    /** 验证：值是否在指定集合中 */
    protected static function validateIn($value, $param)
    {
        $allowed = explode(',', $param);
        return in_array($value, $allowed) ? true : "只能为 " . implode('/', $allowed);
    }

    /** 验证：数值是否在范围内 */
    protected static function validateBetween($value, $param)
    {
        [$min, $max] = explode(',', $param);
        if (!is_numeric($value)) return "必须是数字";
        return ($value >= $min && $value <= $max) ? true : "必须在 {$min} ~ {$max} 之间";
    }

    /** 验证：必须是字符串或数字类型 */
    protected static function validateString_or_numeric($value)
    {
        return (is_string($value) || is_numeric($value)) ? true : "必须是字符串或数字";
    }
}
