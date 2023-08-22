<?php

namespace Dulannadeeja\Mvc;

class Session
{
    protected const FLASH_KEY = 'flash_messages';

    public function __construct()
    {
        // start session
        session_start();

        // get flash messages
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];

        // loop through flash messages and set them to remove
        foreach ($flashMessages as $key => $flashMessage) {
            // mark to be removed
            $flashMessages[$key]['remove'] = true;
        }

        // set flash messages
        $_SESSION[self::FLASH_KEY] = $flashMessages;

    }

    // set flash message
    public function setFlash(string $key, string $message): void
    {
        $_SESSION[self::FLASH_KEY][$key] = ['remove'=>false,'value'=>$message];
    }

    // get flash message
    public function getFlash(string $key): string
    {
        return $_SESSION[self::FLASH_KEY][$key]['value'] ?? '';
    }

    public function __destruct()
    {
        //remove flash messages marked to be removed
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        // loop through flash messages and set them to remove
        foreach ($flashMessages as $key => $flashMessage) {
            // remove flash message
            if ($flashMessage['remove']) {
                unset($flashMessages[$key]);
            }
        }

        // set flash messages
        $_SESSION[self::FLASH_KEY] = $flashMessages;

        // close session
        session_write_close();

    }

    public function set(string $string, int $id): void
    {
        $_SESSION[$string] = $id;
    }

    public function get(string $string): bool|string
    {
        return $_SESSION[$string] ?? false;
    }

    public function remove(string $string): void
    {
        unset($_SESSION[$string]);
    }

}