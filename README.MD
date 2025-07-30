# Gongying Utils 工具库

这是一个由个人维护的 PHP 原生工具类集合，适用于日常开发中的常见需求，如：

- 字符串操作
- 网络请求封装（基于 cURL）
- 调试工具（`dd()`、日志输出）
- 唯一 ID 生成（支持 UUID、Snowflake 雪花ID）

---

## 📦 安装方式（通过 GitHub 私有仓库）

在你的项目 `composer.json` 中添加：

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/Hao1617/Utils.git"
    }
  ],
  "require": {
    "hao1617/utils": "dev-main"
  },
  "autoload": {
    "psr-4": {
      "Gongying\\Utils\\": "vendor/hao1617/utils/src/"
    }
  }
}
