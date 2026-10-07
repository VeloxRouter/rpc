<?php
declare(strict_types=1);

namespace VeloxRouter\Rpc;

class JsonRpcError extends \Exception implements \JsonSerializable
{
    public function __construct(
        int $code,
        string $message,
        public readonly mixed $data = null
    ) {
        parent::__construct($message, $code);
    }

    public function jsonSerialize(): array
    {
        $error = [
            'code'    => $this->code,
            'message' => $this->message,
        ];

        if ($this->data !== null) {
            $error['data'] = $this->data;
        }

        return $error;
    }
}
