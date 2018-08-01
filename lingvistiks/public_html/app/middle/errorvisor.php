<?php

class errorvisor
{
    function __construct()
    {
        // регистрация ошибок
        set_error_handler(array($this, 'OtherErrorCatcher'));

        // перехват критических ошибок
        register_shutdown_function(array($this, 'FatalErrorCatcher'));

        // создание буфера вывода
        ob_start();
    }

    public function OtherErrorCatcher($errno, $errstr, $errfile, $errline)
    {

        switch ($errno) {
            case E_USER_ERROR:
                $err_name = twig::$lang['stat_e_user_error'];
            break;

            case E_USER_WARNING:
                $err_name = twig::$lang['stat_e_user_warning'];
            break;

            case E_USER_NOTICE:
                $err_name = twig::$lang['stat_e_user_notice'];
            break;

            case E_USER_DEPRECATED:
                $err_name = twig::$lang['stat_e_user_deprecated'];
            break;

            default:
                $err_name = twig::$lang['stat_e_user_default'];
            break;
        }

        if(config::app('app_write_log')) {
            $message = $err_name."<br>file: ".$errfile."<br>line: ".$errline."<br>desc: ".$errstr;

            logs::add(
                $message,
                1,
                1
            );
        }

        return false;
    }

    public function FatalErrorCatcher()
    {
        $error = error_get_last();
        if (isset($error))
            if($error['type'] == E_ERROR
                || $error['type'] == E_PARSE
                || $error['type'] == E_COMPILE_ERROR
                || $error['type'] == E_CORE_ERROR)
            {
                ob_end_clean(); // сбросить буфер, завершить работу буфера

                // контроль критических ошибок:
                // - записать в лог
                // - вернуть заголовок 500
                // - вернуть после заголовка данные для пользователя
            }
            else
            {
                ob_end_flush(); // вывод буфера, завершить работу буфера
            }
        else
        {
            ob_end_flush(); // вывод буфера, завершить работу буфера
        }
    }
}

?>
