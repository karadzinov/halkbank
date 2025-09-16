<?php

namespace Nestpay\Laravel\Contracts;

interface NestpayContract
{
    public function buildRequestHash(array $params): string;
    public function verifyResponseHash(array $params): bool;
    public function threeDPostUrl(): string;
    public function getConfig(): array;
    public function orderInquiry(string $orderId): array;
}


