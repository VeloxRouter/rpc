declare(strict_types=1);

namespace VeloxRouter\Rpc;

use RuntimeException;

class JsonRpcClient
{
    public function __construct(
        private readonly string $endpoint,
        private readonly array $defaultHeaders = [],
        private readonly int $timeout = 30
    ) {}

    public function call(
        string $method,
        mixed $params = null,
        string|int|null $id = 1,
        ?string $correlationId = null,
        array $headers = []
    ): mixed {
        $request = new JsonRpcRequest($method, $params, $id);
        
        $correlationId ??= $this->generateCorrelationId();

        $mergedHeaders = array_merge([
            'Content-Type: application/json',
            'Accept: application/json',
            'X-Correlation-ID: ' . $correlationId,
        ], $this->defaultHeaders, $headers);

        $ch = curl_init($this->endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request, JSON_THROW_ON_ERROR));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $mergedHeaders);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);

        $responseBody = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError !== '') {
            throw new RuntimeException("JSON-RPC HTTP cURL error: " . $curlError);
        }

        if ($statusCode !== 200) {
            throw new RuntimeException("JSON-RPC unexpected HTTP status code: " . $statusCode);
        }

        $decoded = json_decode((string)$responseBody, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException("Failed to decode JSON-RPC response: " . json_last_error_msg());
        }

        $rpcResponse = JsonRpcResponse::fromArray($decoded);

        if ($rpcResponse->error !== null) {
            throw $rpcResponse->error;
        }

        return $rpcResponse->result;
    }

    private function generateCorrelationId(): string
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
