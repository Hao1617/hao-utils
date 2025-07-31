<?php

namespace Hao1617\Utils;

class FileHelper
{
    /**
     * 检查扩展名是否允许
     *
     * @param string $filename 文件名
     * @param array $allowed 允许的扩展名数组，如 ['jpg', 'png']
     * @return bool
     */
    public static function isAllowedExtension(string $filename, array $allowed): bool
    {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        return in_array($ext, $allowed);
    }

    /**
     * 检查文件大小是否小于最大值（单位：字节）
     *
     * @param int $size 文件大小
     * @param int $maxSize 最大大小
     * @return bool
     */
    public static function isAllowedSize(int $size, int $maxSize): bool
    {
        return $size <= $maxSize;
    }

    /**
     * 获取扩展名
     *
     * @param string $filename
     * @return string
     */
    public static function getExtension(string $filename): string
    {
        return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    }

    /**
     * 获取 MIME 类型（使用 finfo）
     *
     * @param string $filePath
     * @return string|null
     */
    public static function getMimeType(string $filePath): ?string
    {
        if (!file_exists($filePath)) return null;

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $filePath);
        finfo_close($finfo);
        return $mime;
    }

    /**
     * 保存 base64 到文件
     *
     * @param string $base64 base64 内容，格式如：data:image/png;base64,iVBORw0...
     * @param string $savePath 保存路径，如 uploads/xx.png
     * @return bool
     */
    public static function saveBase64(string $base64, string $savePath): bool
    {
        if (preg_match('/^data:\w+\/\w+;base64,/', $base64)) {
            $base64 = preg_replace('/^data:\w+\/\w+;base64,/', '', $base64);
        }

        $data = base64_decode($base64);
        if ($data === false) return false;

        return file_put_contents($savePath, $data) !== false;
    }

    /**
     * 移动文件（如上传文件）
     *
     * @param string $tmpPath 临时路径（如 $_FILES['file']['tmp_name']）
     * @param string $savePath 保存路径
     * @return bool
     */
    public static function moveFile(string $tmpPath, string $savePath): bool
    {
        return move_uploaded_file($tmpPath, $savePath);
    }

    /**
     * 生成随机文件名（带扩展名）
     *
     * @param string $ext
     * @return string
     */
    public static function generateRandomFilename(string $ext): string
    {
        return date('YmdHis') . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
    }
}
