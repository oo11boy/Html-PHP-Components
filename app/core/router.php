<?php
require_once 'renderer.php';

class Router {
    public static function route($url) {
        // Base path for pages
        $basePath = 'app/pages/';
        
        // If the URL is empty or 'home', load the home page by default
        if (empty($url) || $url === 'home') {
            $filePath = $basePath . 'home.php';
            if (file_exists($filePath)) {
                Renderer::renderPage($filePath);
                return;
            }
        }

        // Split URL parts
        $urlParts = explode('/', $url);
        $currentPath = $basePath;
        $params = [];
        $fileToInclude = null;

        // Loop through URL parts to find a file or dynamic parameter
        for ($i = 0; $i < count($urlParts); $i++) {
            $part = $urlParts[$i];
            
            // Check for a static file (e.g., about.php)
            $staticFile = $currentPath . $part . '.php';
            // Check for a folder (e.g., article/)
            $folderPath = $currentPath . $part . '/';
            // Check for a dynamic file (e.g., [id].php)
            $dynamicFile = $currentPath . '[id].php';

            if (file_exists($staticFile)) {
                $fileToInclude = $staticFile;
                break;
            } elseif (file_exists($folderPath)) {
                $currentPath = $folderPath;
                // If we reach the end of the URL and index.php exists
                if ($i === count($urlParts) - 1 && file_exists($currentPath . 'index.php')) {
                    $fileToInclude = $currentPath . 'index.php';
                }
            } elseif (file_exists($dynamicFile)) {
                $params['id'] = $part; // Store dynamic parameter
                $fileToInclude = $dynamicFile;
                break;
            } else {
                // If none of the above match, show 404
                break;
            }
        }

        // Render the found file
        if ($fileToInclude && file_exists($fileToInclude)) {
            Renderer::renderPage($fileToInclude, $params);
        } else {
            echo "404 - Page Not Found";
        }
    }
}
?>