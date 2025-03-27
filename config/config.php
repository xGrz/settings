<?php

return [
    /*
     * Database table name. Configure this before migration is fired
     */
    'database_table' => 'app_settings',

    /*
     * Cache configuration
     */
    'cache' => [
        /*
         * Set cache timeout for settings (in seconds)
         * You can set false to disable cache
         */
        'timeout' => 86400,

        /* Cache key to store data. Change only when you have a conflict with other modules */
        'key' => 'settings'
    ],

];
