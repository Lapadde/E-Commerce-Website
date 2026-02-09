<?php

if (!function_exists('getMidtransConfig')) {
    /**
     * Get Midtrans configuration
     */
    function getMidtransConfig()
    {
        return [
            'server_key' => getenv('MIDTRANS_SERVER_KEY') ?: env('MIDTRANS_SERVER_KEY', ''),
            'client_key' => getenv('MIDTRANS_CLIENT_KEY') ?: env('MIDTRANS_CLIENT_KEY', ''),
            'is_production' => getenv('MIDTRANS_IS_PRODUCTION') ?: env('MIDTRANS_IS_PRODUCTION', false),
        ];
    }
}

if (!function_exists('initMidtrans')) {
    /**
     * Initialize Midtrans
     */
    function initMidtrans()
    {
        // Check if Midtrans library is installed
        if (!class_exists('Midtrans\Config')) {
            throw new \RuntimeException(
                'Midtrans library tidak ditemukan. Silakan install dengan menjalankan: composer require midtrans/midtrans-php'
            );
        }
        
        $config = getMidtransConfig();
        
        // Validate configuration
        if (empty($config['server_key'])) {
            throw new \RuntimeException(
                'MIDTRANS_SERVER_KEY tidak dikonfigurasi. Silakan set di file .env'
            );
        }
        
        // Set Midtrans configuration
        \Midtrans\Config::$serverKey = $config['server_key'];
        \Midtrans\Config::$isProduction = filter_var($config['is_production'], FILTER_VALIDATE_BOOLEAN);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    }
}

