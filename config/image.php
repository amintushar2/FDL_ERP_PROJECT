<?php
 
/*
|--------------------------------------------------------------------------
| Intervention Image v4 — Driver Configuration
|--------------------------------------------------------------------------
| Place this file at:  config/image.php
|
| Available drivers:
|   'gd'      — requires PHP GD extension
|   'imagick' — requires PHP Imagick extension  ← use this if GD is missing
|
| Install Imagick on Ubuntu/Debian:
|   sudo apt install php-imagick
|   sudo systemctl restart php-fpm   (or apache2)
|
| Install via Composer (already done if you ran intervention/image:^4):
|   composer require intervention/image:^4.0
|
*/
 
return [
    'driver' => 'imagick',
];