<?php

namespace App\Core;

class Session
{
    protected const FLASH_KEY = 'flash_messages';

    public function __construct()
    {
        session_start();
        $flashMsgs = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMsgs as $key => &$msg) {
            $msg['remove'] = true;
        }

        $_SESSION[self::FLASH_KEY] = $flashMsgs;
    }

    public function setFlash($key, $msg)
    {
        $_SESSION[self::FLASH_KEY][$key] = [
            'value' => $msg,
            'remove' => false
        ];
    }

    public function getFlash($key)
    {
        return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
    }

    public function __destruct()
    {
        $flashMsgs = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMsgs as $key => &$msg) {
            if ($msg['remove']) {
                unset($flashMsgs[$key]);
            }
        }

        $_SESSION[self::FLASH_KEY] = $flashMsgs;
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key)
    {
        return $_SESSION[$key] ?? false;
    }

    public function remove($key)
    {
        if (key_exists($key, $_SESSION)) {
            unset($_SESSION[$key]);
        }
    }
}