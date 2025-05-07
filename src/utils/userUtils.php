<?php
require_once dirname(__FILE__) . '/../../src/config/exception/exceptionHandler.php';

class UserUtils
{
    public function getUserId()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user']['id'])) {
            return $_SESSION['user']['id'];
        } else {
            throw new Exception("Chưa đăng nhập");
        }
    }
    public function getUserName()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user']['username'])) {
            return $_SESSION['user']['username'];
        } else {
            throw new Exception("Chưa đăng nhập");
        }
    }
}
