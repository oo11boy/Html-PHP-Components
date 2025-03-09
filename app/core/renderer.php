<?php

class Renderer {
    private static $componentsDir = 'components/';

    /**
     * رندر کردن صفحه با پشتیبانی از تگ‌های کامپوننت
     * @param string $filePath مسیر فایل صفحه اصلی
     * @param array $params پارامترهای ارسالی به صفحه
     */
    public static function renderPage($filePath, $params = []) {
        // امن‌سازی پارامترهای ورودی
        $safeParams = [];
        foreach ($params as $key => $value) {
            $safeParams[$key] = htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
        }

        // رندر محتوای صفحه اصلی
        ob_start();
        extract($safeParams, EXTR_SKIP);
        include $filePath;
        $content = ob_get_clean();

        // شناسایی تگ‌های کامپوننت با مسیر (مثل <Dash/Header>)
        $pattern = '/<([A-Za-z\/]+)([^>]*)\/>/';
        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

        // پردازش هر تگ کامپوننت
        foreach ($matches as $match) {
            $tag = $match[0]; // کل تگ، مثل <Dash/Header title="test"/>
            $componentPath = $match[1]; // مسیر، مثل Dash/Header
            $attributes = trim($match[2]); // پراپ‌ها، مثل title="test"

            // تبدیل مسیر تگ به مسیر فایل
            $filePath = self::$componentsDir . $componentPath . '.php';

            if (file_exists($filePath)) {
                // استخراج پراپ‌ها از تگ
                $props = [];
                if (!empty($attributes)) {
                    preg_match_all('/(\w+)="([^"]*)"/', $attributes, $attrMatches, PREG_SET_ORDER);
                    foreach ($attrMatches as $attr) {
                        $props[$attr[1]] = htmlspecialchars_decode($attr[2], ENT_QUOTES);
                    }
                }

                // رندر محتوای کامپوننت
                ob_start();
                extract($props, EXTR_SKIP);
                include $filePath;
                $componentOutput = ob_get_clean();

                // جایگزینی تگ با خروجی کامپوننت
                $content = str_replace($tag, $componentOutput, $content);
            } else {
                // اگه فایل پیدا نشد، یه پیام خطا بذار
                $content = str_replace($tag, "<!-- Component '$componentPath' not found -->", $content);
            }
        }

        // نمایش محتوای نهایی
        echo $content;
    }
}