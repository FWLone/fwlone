<?php

namespace Fwlone\Core;

class Router
{

    public function __construct()
    {
        $this->template = $this->load();
    }

    public function load()
    {
        $root = $this->dir = $_SERVER['DOCUMENT_ROOT'] . '/';
        $request = isset($_GET['fw']) && $_GET['fw'] !== '' ? str_replace('..', '', $_GET['fw']) : 'main';

        if (substr($request, -1) == '/')
            $request = $request . 'index';

        if (!file_exists($root . '/app/modules/' . $request . '.php'))
            $request = '404';

        if (!is_file($root . '/app/modules/' . $request . '.php'))
            throw new \Exception('File not found: ' . $request);
        else
            return $request . '.php';

    }
}