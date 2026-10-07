<?php
declare(strict_types=1);

namespace VeloxRouter\Rpc;

class JsonRpcRequest implements \JsonSerializable
{
    public function __construct(
        public readonly string $method,
        public readonly mixed $params = null,
        public readonly string|int|null $id = 1,
        public readonly string $jsonrpc = '2.0'
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'jsonrpc' => $this->jsonrpc,
            'method'  => $this->method,
            'params'  => $this->params,
            'id'      => $this->id,
        ];
    }
}
