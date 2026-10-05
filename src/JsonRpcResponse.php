declare(strict_types=1);

namespace VeloxRouter\Rpc;

class JsonRpcResponse
{
    public function __construct(
        public readonly string $jsonrpc,
        public readonly mixed $result,
        public readonly ?JsonRpcError $error,
        public readonly string|int|null $id
    ) {}

    public static function fromArray(array $data): self
    {
        $errorData = $data['error'] ?? null;
        $error = null;

        if ($errorData !== null) {
            $error = new JsonRpcError(
                code: $errorData['code'] ?? -32603,
                message: $errorData['message'] ?? 'Internal JSON-RPC error',
                data: $errorData['data'] ?? null
            );
        }

        return new self(
            jsonrpc: $data['jsonrpc'] ?? '2.0',
            result: $data['result'] ?? null,
            error: $error,
            id: $data['id'] ?? null
        );
    }
}
