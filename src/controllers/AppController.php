<?php

class AppController {

    protected function isGet(): bool
    {
        return $_SERVER["REQUEST_METHOD"] === 'GET';
    }

    protected function isPost(): bool
    {
        return $_SERVER["REQUEST_METHOD"] === 'POST';
    }

    protected function render(string $template = null, array $variables = [])
    {
        $templatePath = 'public/views/'. $template.'.html';
        $templatePath404 = 'public/views/404.html';
        $output = "";
                 
        if(file_exists($templatePath)){
            extract($variables);

            ob_start();
            include $templatePath;
            $output = ob_get_clean();
        } else {
            $htmlPath = 'public/views/'. $template.'.php';
            if (file_exists($htmlPath)) {
                extract($variables);
                ob_start();
                include $htmlPath;
                $output = ob_get_clean();
            } else {
                ob_start();
                include 'public/views/error/404.html';
                $output = ob_get_clean();
            }
        }
        echo $output;
    }

}