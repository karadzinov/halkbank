# Halkbank (NestPay) Laravel (ver3)

Laravel helper for NestPay/Payten 3D Pay Hosting with Hash Version 3.

Packagist: https://packagist.org/packages/karadzinov/halkbank

## Install

```bash
composer require karadzinov/halkbank
php artisan vendor:publish --tag=nestpay-config
```

## Usage

```php
use Nestpay\Laravel\Facades\Nestpay;

$params = [
  'clientid' => config('nestpay.client_id'),
  'amount' => '10.00',
  'oid' => 'OID'.time(),
  'okurl' => route('nestpay.ok'),
  'failUrl' => route('nestpay.fail'),
  'TranType' => 'Auth',
  'Instalment' => '',
  'currency' => config('nestpay.currency'),
  'rnd' => microtime(true),
  'storetype' => config('nestpay.store_type'),
  'hashAlgorithm' => 'ver3',
  'lang' => 'en',
];

$hash = Nestpay::buildRequestHash($params);
$fields = array_merge($params, ['HASH' => $hash]);
return view('nestpay::redirect', [
  'action' => Nestpay::threeDPostUrl(),
  'fields' => $fields,
]);
```

### Verify response

```php
if (!Nestpay::verifyResponseHash(request()->post())) {
    abort(400, 'Invalid HASH');
}
```

### Order inquiry

```php
$result = Nestpay::orderInquiry($orderId);
```

Configure credentials in `config/nestpay.php` or via `.env`.

## Required .env variables

```env
NESTPAY_CLIENT_ID=your_client_id
NESTPAY_STORE_KEY=your_store_key
NESTPAY_STORE_TYPE=3D_PAY_HOSTING
NESTPAY_CURRENCY=807
NESTPAY_LANG=en
NESTPAY_3D_POST_URL=https://your-bank-domain/fim/est3Dgate
NESTPAY_API_POST_URL=https://your-bank-domain/fim/api
NESTPAY_API_USERNAME=your_api_username
NESTPAY_API_PASSWORD=your_api_password

# Optional helpers for local dev behind a tunnel
# Set to your public https base (e.g., ngrok/Cloudflare Tunnel)
PUBLIC_BASE_URL=https://your-public-https-url
FORCE_HTTPS=true
```

The `PUBLIC_BASE_URL` ensures callback URLs (`okurl`/`failUrl`) are generated using your public HTTPS domain during development.


