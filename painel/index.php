<?php
    session_start();

    require_once 'lib/Application.php';
    $o_Application = new Application();
    $o_Application->dispatch();
    