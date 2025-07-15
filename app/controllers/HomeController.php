<?php
class HomeController
{
    public function index()
    {
        include ROOT . 'app/views/templates/header.php';
        include ROOT . 'app/views/home.php';
    }
}
