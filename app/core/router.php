<?php
require_once 'renderer.php';

class Router {
    public static function route($url) {
        // مسیر پایه برای صفحات
        $basePath = 'app/pages/';
        
        // اگر URL خالی باشه، به صورت پیش‌فرض home رو لود می‌کنیم
        if (empty($url) || $url === 'home') {
            $filePath = $basePath . 'home.php';
            if (file_exists($filePath)) {
                Renderer::renderPage($filePath);
                return;
            }
        }

        // جدا کردن بخش‌های URL
        $urlParts = explode('/', $url);
        $currentPath = $basePath;
        $params = [];
        $fileToInclude = null;

        // حلقه روی بخش‌های URL برای پیدا کردن فایل یا پارامتر داینامیک
        for ($i = 0; $i < count($urlParts); $i++) {
            $part = $urlParts[$i];
            
            // بررسی فایل معمولی (مثل about.php)
            $staticFile = $currentPath . $part . '.php';
            // بررسی پوشه (مثل article/)
            $folderPath = $currentPath . $part . '/';
            // بررسی فایل داینامیک (مثل [id].php)
            $dynamicFile = $currentPath . '[id].php';

            if (file_exists($staticFile)) {
                $fileToInclude = $staticFile;
                break;
            } elseif (file_exists($folderPath)) {
                $currentPath = $folderPath;
                // اگر به آخر URL رسیدیم و فایل index.php وجود داره
                if ($i === count($urlParts) - 1 && file_exists($currentPath . 'index.php')) {
                    $fileToInclude = $currentPath . 'index.php';
                }
            } elseif (file_exists($dynamicFile)) {
                $params['id'] = $part; // ذخیره پارامتر داینامیک
                $fileToInclude = $dynamicFile;
                break;
            } else {
                // اگر هیچ‌کدوم از موارد بالا نبود، 404 نشون بده
                break;
            }
        }

        // رندر کردن فایل پیدا شده
        if ($fileToInclude && file_exists($fileToInclude)) {
            Renderer::renderPage($fileToInclude, $params);
        } else {
            echo "404 - Page Not Found";
        }
    }
}
?>