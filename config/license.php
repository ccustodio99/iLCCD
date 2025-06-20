<?php

return [
    'secret' => env('LICENSE_SECRET', env('APP_KEY')),
    'enabled' => env('LICENSE_ENABLED', true),
];
