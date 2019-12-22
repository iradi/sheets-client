<?php

return [
    /**
     * Application Name
     */

    'app-name' => 'Radi Sheet',

    /**
     * Application Scope
     * can be set to
     * SPREADSHEETS_READONLY => readonly permissions granted
     * SPREADSHEETS => read, update, delete permissions granted
     * note: use as constant without any qotations
     */

    'scope' => Google_Service_Sheets::SPREADSHEETS_READONLY,

    /**
     * Realtive path to credentials.json file
     * path relative to current file's location
     * e.g. if file is in the same diectory as this file then
     * 'path-to-credentials' => __DIR__ . '/credentials.json'
     */

    'path-to-credentials' => __DIR__ . '/credentials.json',

    /**
     * Realtive path to token.json file
     * path relative to current file's location
     * e.g. if file is in the same as this file diectory then
     * 'path-to-token' => __DIR__ . '/token.json'
     */

    'path-to-token' => __DIR__ . '/token.json',

    /**
     * Realtive path to verification.json file
     * path relative to current file's location
     * e.g. if file is in the same diectory as this file then
     * 'path-to-verification' => __DIR__ . '/verification.json'
     */

    'path-to-verification' => __DIR__ . '/verification.json',
];
