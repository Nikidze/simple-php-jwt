<?php

class JwtService implements JwtServiceContract
{

    protected $key = '';

    public function __construct()
    {
        $this->key = '123'; // load from .env
    }

    public function generate(int $user_id): string
    {
        $header = $this->getEncodedHeader();
        $payload = $this->encode(['id' => $user_id]);
        $signature = $this->getSignature($header, $payload);
        return "{$header}.{$payload}.{$signature}";
    }

    public function validate(string $token): bool
    {
        list($header, $payload, $signature) = explode('.', $token);
        if ($this->getSignature($header, $payload) == $signature)
            return true;
        return false;
    }

    public function getUserId(string $token): int
    {
        $payload = explode('.', $token)[1];
        $payload = $this->base64_decode($payload);
        $payload = json_decode($payload);
        return $payload->id;
    }

    protected function getSignature(string $header, string $payload): string
    {
        $hash = hash_hmac(
            'sha256',
            $header.'.'.$payload,
            $this->key
        );
        return $this->base64_encode($hash);
    }

    protected function getEncodedHeader(): string
    {
        return $this->encode($this->getHeader());
    }

    protected function getHeader(): array
    {
        return [
            'alg' => 'HS256',
            'typ' => 'JWT',
        ];
    }

    protected function encode(array $data): string
    {
        $json = json_encode($data);
        return $this->base64_encode($json);
    }

    protected function base64_encode(string $string): string
    {
        $base64 = base64_encode($string);
        return rtrim(strtr($base64, '+/', '-_'), '=');
    }

    protected function base64_decode(string $data): string
    {
        $string = base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
        return $string;
    }
}
