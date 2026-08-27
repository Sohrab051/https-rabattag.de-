<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Awin Publisher API credentials
    |--------------------------------------------------------------------------
    |
    | These are intentionally left blank until real Awin credentials are
    | available. AWIN_FEED_ENABLED must be explicitly set to true before any
    | sync command will attempt to call the live API.
    |
    */

    'publisher_id' => env('AWIN_PUBLISHER_ID'),

    'api_token' => env('AWIN_API_TOKEN'),

    'feed_enabled' => (bool) env('AWIN_FEED_ENABLED', false),

    'base_url' => env('AWIN_BASE_URL', 'https://api.awin.com'),

];
