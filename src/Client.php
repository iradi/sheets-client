<?php

namespace Iradi\SheetsClient;

use Exception;

class Client
{
    /**
     * @var \Google_Client
     */
    protected $client;

    /**
     * @param StdObject $settings
     */
    public function __construct($settings)
    {
        $settings = $this->validateSettings($settings);
        $client = new \Google_Client();
        $client->setApplicationName($settings['app-name']);
        $client->setScopes($settings['scope']);
        $client->setAuthConfig($settings['path-to-credentials']);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        // Load previously authorized token from a file, if it exists.
        // The file token.json stores the user's access and refresh tokens, and is
        // created automatically when the authorization flow completes for the first
        // time.
        $tokenPath = $settings['path-to-token'];
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                if (file_exists($settings['path-to-verification'])) {
                    // Exchange authorization code for an access token.
                    $authCode = trim(file_get_contents($settings['path-to-verification']));
                    if (empty($authCode)) {
                        $authUrl = $client->createAuthUrl();
                        throw new Exception("Invalid Auth Code.\r\nOpen the following link in your browser: {$authUrl}\r\n Paste the new verification code as is in the verifiation.json file");
                    }
                    $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

                    // Check to see if there was an error.
                    if (array_key_exists('error', $accessToken)) {
                        throw new Exception(join(', ', $accessToken));
                    } else {
                        $client->setAccessToken($accessToken);
                    }
                } else {
                    $authUrl = $client->createAuthUrl();
                    throw new Exception("Open the following link in your browser:{$authUrl}\r\n Paste the new verification code as is in the verifiation.json file");
                }
            }
            // Save the token to a file.
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }
        $this->client = $client;
    }

    /**
     * @param array $settings
     * @throws Exception
     * @return array $settings
     */
    protected function validateSettings($settings)
    {
        if (empty($settings['app-name'])) {
            $settings['app-name'] = 'Sheets App';
        }
        if (empty($settings['scope'])) {
            $settings['scope'] = \Google_Service_Sheets::SPREADSHEETS;
        }
        if (empty($settings['path-to-credentials'])) {
            throw new Exception('Invalid path to credentials.json file');
        }
        if (empty($settings['path-to-token'])) {
            throw new Exception('Invalid path to token.json file');
        }
        if (empty($settings['path-to-verification'])) {
            throw new Exception('Invalid path to verification.json file');
        }
        return $settings;
    }
    
    /**
     * @return Google_client
     */
    public function getClient(): \Google_Client
    {
        return $this->client;
    }
};
