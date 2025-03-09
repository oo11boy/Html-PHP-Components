<?php

class Renderer {
    private static $componentsDir = 'components/';

    /**
     * Render a page with support for component tags
     * @param string $filePath Path to the main page file
     * @param array $params Parameters to pass to the page
     */
    public static function renderPage($filePath, $params = []) {
        // Sanitize input parameters
        $safeParams = [];
        foreach ($params as $key => $value) {
            $safeParams[$key] = htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
        }

        // Render the main page content
        ob_start();
        extract($safeParams, EXTR_SKIP);
        include $filePath;
        $content = ob_get_clean();

        // Identify component tags with paths (e.g., <Dash/Header>)
        $pattern = '/<([A-Za-z\/]+)([^>]*)\/>/';
        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

        // Process each component tag
        foreach ($matches as $match) {
            $tag = $match[0]; // Full tag, e.g., <Dash/Header title="test"/>
            $componentPath = $match[1]; // Path, e.g., Dash/Header
            $attributes = trim($match[2]); // Props, e.g., title="test"

            // Convert tag path to file path
            $filePath = self::$componentsDir . $componentPath . '.php';

            if (file_exists($filePath)) {
                // Extract props from the tag
                $props = [];
                if (!empty($attributes)) {
                    preg_match_all('/(\w+)="([^"]*)"/', $attributes, $attrMatches, PREG_SET_ORDER);
                    foreach ($attrMatches as $attr) {
                        $props[$attr[1]] = htmlspecialchars_decode($attr[2], ENT_QUOTES);
                    }
                }

                // Render the component content
                ob_start();
                extract($props, EXTR_SKIP);
                include $filePath;
                $componentOutput = ob_get_clean();

                // Replace the tag with the component output
                $content = str_replace($tag, $componentOutput, $content);
            } else {
                // If the file is not found, insert an error message
                $content = str_replace($tag, "<!-- Component '$componentPath' not found -->", $content);
            }
        }

        // Display the final content
        echo $content;
    }
}