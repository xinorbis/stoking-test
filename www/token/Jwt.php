<?php

namespace app\token;

use Ratchet\ConnectionInterface;

class Jwt
{
    private int $userId;
    private string $token;
    private string $secret;
    private string $header;
    private string $payload;
    private string $signature;
    private int $expire;

    public function __construct(ConnectionInterface $conn)
    {
        $this->secret = 'secret';
        $this->token = $this->getApiKey($conn);
        $this->parseToken();
        $this->setPayloadData();
    }

    private function getApiKey(ConnectionInterface $conn): string
    {
        $apiKey = '';
        $apiKeyHeader = $conn->WebSocket->request->getHeader('ApiKey');

        if ($apiKeyHeader) {
            $apiKey = $apiKeyHeader->toArray()[0];
        }

        return $apiKey;
    }

    private function parseToken(): void
    {
        $jwtArr = array_combine(['header', 'payload', 'signature'], explode('.', $this->token));
        $this->header = $jwtArr['header'];
        $this->payload = $jwtArr['payload'];
        $this->signature = $jwtArr['signature'];
    }

    private function setPayloadData(): void
    {
        $payload = base64_decode($this->payload);
        $payload = json_decode($payload, true);
        $this->userId = $payload['userId'];
        $this->expire = $payload['exp'];
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function isValid(): bool
    {
        $isValid = false;

        $calculatedSign = hash_hmac(
            'sha256',
            $this->header . '.' . $this->payload,
            $this->secret,
            true
        );

        if (base64_encode($calculatedSign) === $this->signature) {
            if ($this->expire > time()) {
                $isValid = true;
            }
        }

        return $isValid;
    }
}
