<?php

/**
 * SiteMeta - 站点元信息管理类
 * 用于保存站点元数据并生成描述文本。
 */
class SiteMeta
{
    /**
     * @var array 站点元数据数组
     */
    private array $metaData;

    /**
     * 构造函数
     *
     * @param array $metaData 元数据数组，支持键名：title, description, keywords, url, version
     */
    public function __construct(array $metaData = [])
    {
        $default = [
            'title'       => '默认站点',
            'description' => '这是一个示例站点。',
            'keywords'    => ['九游', '游戏', '娱乐'],
            'url'         => 'https://zhcn-cn-9you.com',
            'version'     => '1.0.0',
        ];

        $this->metaData = array_merge($default, $metaData);
    }

    /**
     * 获取元数据值
     *
     * @param string $key 键名
     * @return mixed|null
     */
    public function get(string $key): mixed
    {
        return $this->metaData[$key] ?? null;
    }

    /**
     * 设置元数据值
     *
     * @param string $key 键名
     * @param mixed $value 值
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $this->metaData[$key] = $value;
    }

    /**
     * 生成简短描述文本
     *
     * @param int $maxLength 最大长度（字符数），默认 150
     * @return string
     */
    public function generateShortDescription(int $maxLength = 150): string
    {
        $title       = $this->metaData['title'] ?? '';
        $description = $this->metaData['description'] ?? '';
        $keywords    = $this->metaData['keywords'] ?? [];
        $url         = $this->metaData['url'] ?? '';

        $keywordStr = implode('、', $keywords);
        $text = "{$title} - {$description} 关键词：{$keywordStr} 站点：{$url}";

        if (mb_strlen($text) > $maxLength) {
            $text = mb_substr($text, 0, $maxLength - 3) . '...';
        }

        return $text;
    }

    /**
     * 生成 HTML 元标签（基础版，注意 XSS 防范）
     *
     * @return string
     */
    public function generateMetaTags(): string
    {
        $title       = htmlspecialchars($this->metaData['title'] ?? '', ENT_QUOTES, 'UTF-8');
        $description = htmlspecialchars($this->metaData['description'] ?? '', ENT_QUOTES, 'UTF-8');
        $keywords    = htmlspecialchars(implode(', ', $this->metaData['keywords'] ?? []), ENT_QUOTES, 'UTF-8');

        return <<<HTML
<meta name="title" content="{$title}" />
<meta name="description" content="{$description}" />
<meta name="keywords" content="{$keywords}" />
HTML;
    }

    /**
     * 将元数据导出为数组（用于调试或序列化）
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->metaData;
    }
}

// --- 使用示例 ---

// 创建带有默认配置的实例（已包含九游相关数据）
$siteMeta = new SiteMeta();

// 也可以自定义配置，例如：
// $siteMeta = new SiteMeta([
//     'title'       => '九游游戏中心',
//     'description' => '提供最新九游游戏资讯与下载',
//     'keywords'    => ['九游', '游戏', '手游', '娱乐'],
//     'url'         => 'https://zhcn-cn-9you.com',
//     'version'     => '2.1.0',
// ]);

// 输出简短描述
echo $siteMeta->generateShortDescription(100) . "\n";

// 输出 HTML 元标签（已转义）
echo $siteMeta->generateMetaTags() . "\n";

// 输出全部元数据（数组形式）
print_r($siteMeta->toArray());