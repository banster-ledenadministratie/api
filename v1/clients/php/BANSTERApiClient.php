<?php
/**
 * Banster API v1 Client
 *
 * Usage: $client = new BANSTERApiClient('https://your-subdomain.banster.nl/api/v1', 'YOUR_API_KEY');
 *
 * Uses Bearer authentication
 * Returns decoded JSON responses as associative arrays
 * Throws RuntimeException on network errors, HTTP errors (status ≥ 400), or invalid JSON responses.
 *
 */
class BANSTERApiClient
{
    /**
     *
     * Base API URL (e.g. https://your-subdomain.banster.nl/api/v1)
     *
     */
    private string $baseUrl;

    /**
     *
     * API key used for Bearer authentication
     *
     */
    private string $apiKey;

    /**
     *
     * BANSTERApiClient constructor.
     *
     * @param string $baseUrl Base API URL (without trailing slash required)
     * @param string $apiKey  API key retrieved from the Banster admin interface
     *
     */
    public function __construct(string $baseUrl, string $apiKey)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->apiKey  = $apiKey;
    }

    /**
     *
     * Execute HTTP request against Banster API.
     *
     * @param string       $method   HTTP method (GET, POST, PUT, DELETE)
     * @param string       $endpoint Relative endpoint (e.g. "lists/1")
     * @param array|bool   $data     Query parameters (GET) or JSON body (POST/PUT/DELETE)
     *
     * @return array Decoded JSON response.
     * @throws RuntimeException On network errors, HTTP errors (status ≥ 400), or invalid JSON.
     *
     */
    protected function request(string $method, string $endpoint, array|bool $data = false): array
    {
        // build full request URL
        $url  = $this->baseUrl . '/' . $endpoint;

        // initialize cURL session
        $curl = curl_init();

        // default HTTP headers (Bearer authentication + JSON)
        $headers = [
            'Authorization: Bearer ' . $this->apiKey,
            'Accept: application/json',
            'Content-Type: application/json'
        ];

        // attach headers to request
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        // configure request based on HTTP method
        switch ($method)
        {
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, true);
                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                }
                break;

            case 'PUT':
            case 'DELETE':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                }
                break;

            case 'GET':
            default:
                if ($data) {
                    $url .= '?' . http_build_query($data);
                }
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
                break;
        }

        // common cURL options
        curl_setopt_array($curl, [
            CURLOPT_URL            => $url,     // final request URL
            CURLOPT_RETURNTRANSFER => true,     // return response as string
            CURLOPT_TIMEOUT        => 10,       // max execution time (seconds)
            CURLOPT_SSL_VERIFYPEER => true,     // verify SSL certificate
            CURLOPT_SSL_VERIFYHOST => 2,        // verify SSL host
        ]);

        // execute HTTP request
        $result = curl_exec($curl);

        // handle network-level errors
        if ($result === false) {
            throw new RuntimeException('Network error: ' . curl_error($curl));
        }

        // retrieve HTTP status code
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        // close cURL session
        curl_close($curl);

        // decode JSON response into associative array
        $decoded = json_decode($result, true);

        //echo "<pre>RAW RESPONSE:\n" . htmlspecialchars($result, ENT_QUOTES, 'UTF-8') . "</pre>";

        // HTTP-level error (4xx / 5xx)
        if ($httpCode >= 400) {
            $message = is_array($decoded) && isset($decoded['msg']) ? $decoded['msg'] : "HTTP $httpCode";
            throw new RuntimeException($message, $httpCode);
        }

        // ensure response is valid JSON
        if (!is_array($decoded)) {
            throw new RuntimeException('Invalid JSON response from API');
        }

        // return decoded API response
        return $decoded;
    }

    // ---- Lists ----

    public function searchLists(string $query, array $params = []): array
    {
        $params['searchQuery'] = $query;
        return $this->request('GET', 'lists/search', $params);
    }

    public function getLists(array $params = []): array
    {
        return $this->request('GET', 'lists', $params);
    }

    public function getList(int $listId, array $params = []): array
    {
        return $this->request('GET', "lists/$listId", $params);
    }

    public function getListEditableFields(int $listId): array
    {
        return $this->request('GET', "lists/$listId/editablefields");
    }

    public function getListSortableFields(int $listId): array
    {
        return $this->request('GET', "lists/$listId/sortablefields");
    }

    public function getListEntries(int $listId, array $params = []): array
    {
        return $this->request('GET', "lists/$listId/entries", $params);
    }

    public function getListEntry(int $listId, int $entryId, array $params = []): array
    {
        return $this->request('GET', "lists/$listId/entries/$entryId", $params);
    }

    public function updateListEntry(int $listId, int $entryId, array $variables): array
    {
        return $this->request('POST', "lists/$listId/entries/$entryId", [
            'variables' => $variables
        ]);
    }

    public function getListEntryInvoices(int $listId, int $entryId, array $params = []): array
    {
        return $this->request('GET', "lists/$listId/entries/$entryId/invoices", $params);
    }

    public function getListInvoices(int $listId, array $params = []): array
    {
        return $this->request('GET', "lists/$listId/invoices", $params);
    }

    // ---- Events ----

    public function searchEvents(string $query, array $params = []): array
    {
        $params['searchQuery'] = $query;
        return $this->request('GET', 'events/search', $params);
    }

    public function getEvents(array $params = []): array
    {
        return $this->request('GET', 'events', $params);
    }

    public function getEvent(int $eventId, array $params = []): array
    {
        return $this->request('GET', "events/$eventId", $params);
    }

    public function getEventSortableFields(int $eventId): array
    {
        return $this->request('GET', "events/$eventId/sortablefields");
    }

    public function getEventEntries(int $eventId, array $params = []): array
    {
        return $this->request('GET', "events/$eventId/entries", $params);
    }

    public function getEventEntry(int $eventId, int $entryId, array $params = []): array
    {
        return $this->request('GET', "events/$eventId/entries/$entryId", $params);
    }

    public function getEventEntryInvoices(int $eventId, int $entryId, array $params = []): array
    {
        return $this->request('GET', "events/$eventId/entries/$entryId/invoices", $params);
    }

    public function getEventInvoices(int $eventId, array $params = []): array
    {
        return $this->request('GET', "events/$eventId/invoices", $params);
    }

    // ---- Invoices ----

    public function getInvoiceSortableFields(): array
    {
        return $this->request('GET', 'invoices/sortablefields');
    }

    public function getInvoices(array $params = []): array
    {
        return $this->request('GET', 'invoices', $params);
    }

    public function getInvoice(int $invoiceId, array $params = []): array
    {
        return $this->request('GET', "invoices/$invoiceId", $params);
    }

    // ---- Invoice models ----

    public function getInvoiceModels(array $params = []): array
    {
        return $this->request('GET', 'invoicemodels', $params);
    }

    public function getInvoiceModel(int $invoiceModelId, array $params = []): array
    {
        return $this->request('GET', "invoicemodels/$invoiceModelId", $params);
    }

    // ---- Auth ----

    public function validateCredentials(string $login, string $password): array
    {
        return $this->request('POST', 'auth/validate', [
            'login'    => $login,
            'password' => $password
        ]);
    }
}
