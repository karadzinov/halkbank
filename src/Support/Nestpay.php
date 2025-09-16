<?php

namespace Nestpay\Laravel\Support;

use Nestpay\Laravel\Contracts\NestpayContract;

class Nestpay implements NestpayContract
{
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function buildRequestHash(array $params): string
    {
        return $this->computeHash($params, $this->config['store_key'] ?? '');
    }

    public function verifyResponseHash(array $params): bool
    {
        $calculated = $this->computeHash($params, $this->config['store_key'] ?? '');
        $received = $params['HASH'] ?? '';
        return hash_equals($calculated, $received);
    }

    public function threeDPostUrl(): string
    {
        return $this->config['three_d_post_url'] ?? '';
    }

    protected function computeHash(array $params, string $storeKey): string
    {
        $keys = array_keys($params);
        natcasesort($keys);
        $hashval = '';
        foreach ($keys as $key) {
            $lower = strtolower($key);
            if ($lower === 'hash' || $lower === 'encoding') {
                continue;
            }
            $value = (string)($params[$key] ?? '');
            $escaped = str_replace('|', '\\|', str_replace('\\', '\\\\', $value));
            $hashval .= $escaped . '|';
        }
        $escapedStoreKey = str_replace('|', '\\|', str_replace('\\', '\\\\', $storeKey));
        $hashval .= $escapedStoreKey;
        $calculated = hash('sha512', $hashval);
        return base64_encode(pack('H*', $calculated));
    }

    public function orderInquiry(string $orderId): array
    {
        $xml = new \DOMDocument('1.0', 'UTF-8');
        $order = $xml->createElement('CC5Request');
        $order->appendChild($xml->createElement('Name', $this->config['api_username'] ?? ''));
        $order->appendChild($xml->createElement('Password', $this->config['api_password'] ?? ''));
        $order->appendChild($xml->createElement('ClientId', $this->config['client_id'] ?? ''));
        $order->appendChild($xml->createElement('OrderId', $orderId));
        $order->appendChild($xml->createElement('Type', 'OrderInquiry'));
        $xml->appendChild($order);

        $ch = curl_init($this->config['api_post_url'] ?? '');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/xml; charset=UTF-8'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml->saveXML());
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $resp = curl_exec($ch);
        if ($resp === false) {
            $err = curl_error($ch);
            curl_close($ch);
            return ['error' => 'cURL error', 'detail' => $err];
        }
        $status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);

        $out = ['http_status' => $status, 'raw' => $resp];
        $respXml = @simplexml_load_string($resp);
        if ($respXml) {
            $out['Response'] = (string)($respXml->Response ?? '');
            $out['OrderId'] = (string)($respXml->OrderId ?? '');
            $out['ProcReturnCode'] = (string)($respXml->ProcReturnCode ?? '');
            $out['ErrMsg'] = (string)($respXml->ErrMsg ?? '');
            $out['AuthCode'] = (string)($respXml->AuthCode ?? '');
            $out['TransId'] = (string)($respXml->TransId ?? '');
        }
        return $out;
    }
}


