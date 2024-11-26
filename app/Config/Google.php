<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Google extends BaseConfig
{
    public $clientId = '83785773954-g16k3biks40n3m3cc4gaeugo3d91fu87.apps.googleusercontent.com';
    public $clientSecret = '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';
    public $redirectUri = 'http://localhost:8080/user/login';
}
