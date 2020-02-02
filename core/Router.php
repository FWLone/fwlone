<?php

namespace FwCore;

class Router
{

    public function __construct()
    {
        $this->template = $this->load();
    }

    public function load()
    {
        $request = isset($_GET['fw']) && $_GET['fw'] !== '' ? str_replace('..', '', $_GET['fw']) : 'main';

        if (substr($request, -1) == '/')
            $request = $request . 'index.php';

        if (!file_exists(H . '/app/modules/' . $request . '.php'))
            $request = 'main.php';

        if (!is_file(H . '/app/modules/' . $request . '.php'))
            throw new \Exception('File not found: ' . $request);
        else
            return $request;

    }
}