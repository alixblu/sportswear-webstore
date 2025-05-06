<?php
require_once dirname(__FILE__) . '/../../vendor/autoload.php';

class UserUtils
{
    public function getUserId()
    {
        if (isset($_SESSION['user']['id'])) {
            return $_SESSION['user']['id'];
        } else {
            throw new Exception("Chưa đăng nhập");
        }
    }
}
