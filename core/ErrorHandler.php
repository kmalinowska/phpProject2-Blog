<?php
namespace Core;

class ErrorHandler {
    public static function handleException(\Throwable $exception) {
        // 1) Log the error
        // php bin/load_schema.php
        if(php_sapi_name() === 'cli') { //error in w command line enviroment
            static::renderCliError($exception);
        } else { //error on a web server, need to render an error page
           // static::renderErrorPage($exception);
        }
    }

    private static function renderCliError(\Throwable $exception):void {
        $isDebug = App::get('config')['app']['debug'] ?? false;

        if($isDebug) { //in debug mode
            $errorMessage = static::formatErrorMessage(
                $exception,
                "\033[31m[%s] Error:\033[0m %s: %s in %s on line %d\n" //%s = string; %d = decimal; red color text: \0ss[31m ... \033[0m
            );
            $trace = $exception->getTraceAsString();
        } else { //not in the debug mode
            $errorMessage = "\033[31mAn unexpected error occurred. Please check error log for details.\033[0m\n";
            $trace = "";
        }

        fwrite(STDERR, $errorMessage);

        if($trace) {
            fwrite(STDERR, "\nStack trace:\n$trace\n");
        }
        exit(1); //exit code for CLI programs; 0 = everything is fine, 1 = an error
    }

    public static function handleError($level, $message, $file, $line) {
        $exception = new \ErrorException($message, 0, $level, $file, $line);
        self::handleException($exception);
    }

    //formatting error message; output the error on CLI and on the website
    private static function formatErrorMessage(\Throwable $exception, string $format): string {
        return sprintf(
            $format,
            date('Y-m;d H:i:s'),
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        );
    }
}