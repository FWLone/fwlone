<?php

namespace Fwlone\Core;

class Cms
{

    public function __construct()
    {
        $this->initSystemHandlers();
    }

    public static function guard($str)
    {
        $str = strip_tags($str);
        $str = trim($str);
        $str = htmlspecialchars($str);
        $str = htmlentities($str);

        return $str;
    }

    public function accessSecure($user, $level = 0)
    {
        if (!$user && URL == '/login' && URL == '/registration') {
            header('location: /login');
            exit();
        } elseif ($user && $level == 0) {
            header('location: /');
            exit();
        } elseif ($level != 0 && $user['level'] < $level) {
            header('location: /');
            exit();
        }
        return '';
    }

    protected function initSystemHandlers()
    {
        set_exception_handler(array($this, 'handleException'));
        set_error_handler(array($this, 'handleError'), error_reporting());
    }

    public function handleError($code, $message, $file, $line)
    {
        if ($code & error_reporting()) {
            restore_error_handler();
            restore_exception_handler();
            try {
                $this->displayError($code, $message, $file, $line);
            } catch (Exception $e) {
                $this->displayException($e);
            }
        }
    }

    public function handleException($exception)
    {
        restore_error_handler();
        restore_exception_handler();
        $this->displayException($exception);
    }

    public function displayError($code, $message, $file, $line)
    {
        echo '<div style="border:1px dotted #000000; font-size:14px; font-family:tahoma,verdana,arial; background-color:#f3f3f3; color:#A73C3C; margin-bottom:5px; padding:15px;">
        <b><font style="color:#666666;">PHP Error [' . $code . ']</font></b>
        <p>' . $message . '(' . $file . ':' . $line . ')</p><pre>';
        $trace = debug_backtrace();
        if (count($trace) > 3)
            $trace = array_slice($trace, 3);

        foreach ($trace as $i => $t) {
            if (!isset($t['file']))
                $t['file'] = 'unknown';
            if (!isset($t['line']))
                $t['line'] = 0;
            if (!isset($t['function']))
                $t['function'] = 'unknown';
            echo "#$i {$t['file']}({$t['line']}): ";
            if (isset($t['object']) && is_object($t['object']))
                echo get_class($t['object']) . '->';
            echo "{$t['function']}()\n";
        }

        echo '</pre></div>';
    }

    public function displayException($exception)
    {
        echo '<div style="border:1px dotted #000000; font-size:14px; font-family:tahoma,verdana,arial; background-color:#f3f3f3; color:#A73C3C; padding:15px;">
            <b><font style="color:#666666;">' . get_class($exception) . '</font></b>
            <p>' . $exception->getMessage() . ' (' . $exception->getFile() . ':' . $exception->getLine() . ')</p>
            <pre>' . $exception->getTraceAsString() . '</pre>
        </div>';
    }
}
