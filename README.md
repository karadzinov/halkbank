# Halkbank (NestPay) Laravel (ver3)

Laravel helper for NestPay/Payten 3D Pay Hosting with Hash Version 3.

## Install (path repository)

Add to your app's composer.json:

```json
{
  "repositories": [
    { "type": "path", "url": "packages/nestpay-laravel" }
  ],
  "require": {
    "karadzinov/halkbank": "*"
  }
}
```

Then:

```bash
composer update
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


