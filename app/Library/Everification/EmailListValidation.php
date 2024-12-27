<?php

namespace Acelle\Library\Everification;

use Exception;
use Acelle\Library\Contracts\VerifyInterface;

class EmailListValidation implements VerifyInterface
{
    protected $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function isBulkVerifySupported(): bool
    {
        return false;
    }

    public function verify($email)
    {
        $url = "https://app.emaillistvalidation.com/api/verifEmailv2?secret={$this->apiKey}&email={$email}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $rawResponse = curl_exec($ch);

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($httpcode != 200) {
            throw new Exception('Error working with emailvaliation.com server: '.$rawResponse);
        }

        $response = json_decode($rawResponse, true);

        if (array_key_exists('error', $response)) {
            throw new Exception('Failed connecting with emailvaliation.com server: '.$rawResponse);
        }

        // See: https://help.emaillistvalidation.com/article/7-result-codes-and-terminology

        $statusMap = [
            'valid' => 'deliverable',
            'invalid' => 'undeliverable',
            'ok' => 'deliverable',
            'ok_for_all' => 'deliverable',
            'ok_for_all | ok_for_all' => 'deliverable',
            'ok_for_all|ok_for_all' => 'deliverable',
            'email_disabled' => 'undeliverable',
            'risky' => 'risky',
            'unknown' => 'unknown',
            'ok' => 'deliverable',
            'unknown_email' => 'undeliverable',
            'email_disabled' => 'undeliverable',
            'domain_error' => 'undeliverable',
            'Spam Traps' => 'deliverable',
            'Disposable' => 'deliverable',
            'Accept all or Ok for all' => 'deliverable',
            'Accept all' => 'deliverable',
            'Ok for all' => 'deliverable',
            'syntax_error' => 'undeliverable',
            'Invalid vendor response' => 'unknown',
            'dead_server' => 'unknown',
            'antispam_system' => 'unknown',
            'relay_error' => 'unknown',
            'attempt_rejected' => 'unknown',
            'smtp_protocol' => 'unknown',
            'smtp_error' => 'unknown',
            'error' => 'unknown',
            'ok or valid' => 'deliverable',
        ];

        $result = strtolower($response['Result']);

        if (!array_key_exists($result, $statusMap)) {
            throw new Exception('Unknown status code returned from ZeroBounce: '.$result);
        }

        $verificationStatus = $statusMap[$result];

        return [$verificationStatus, $rawResponse];
    }
}
