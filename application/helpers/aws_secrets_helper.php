<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Aws\SecretsManager\SecretsManagerClient;
use Aws\Ssm\SsmClient;
use Aws\Exception\AwsException;

if (!function_exists('get_secret')) {
    function get_secret($key = null)
    {
        // LOCAL → .env
        if (file_exists(FCPATH . '.env') && $_ENV['APP_ENV'] === 'local') {
            if($key === null) {
                return $_ENV;
            }
            return $_ENV[$key] ?? null;
        }
        
        // LIVE → AWS SSM
        static $cache = [];

        if (!empty($cache)) {
            if($key === null) {
                return $cache;
            }
            return $cache[$key] ?? null;
        }

        try {
            $client = new SsmClient([
                'region' => 'ap-south-1',
                'version' => 'latest'
                // IAM role will be used automatically on EC2
            ]);

            $result = $client->getParameter([
                'Name' => "/prod/act/rds/echo_rmt_user",
                'WithDecryption' => true
            ]);

            $cache = json_decode($result['Parameter']['Value'], true);

            if (!$cache) {
                log_message('error', 'AWS SSM Error: ');
                throw new Exception('Invalid JSON in SSM Parameter');
            }
            return $cache[$key] ?? null;

        } catch (AwsException $e) {
            log_message('error', 'AWS SSM Error: ' . $e->getMessage());
            return null;
        }
    }
}

