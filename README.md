# Banster API

The Banster API provides structured, read-only and limited write access to Banster data such as lists, entries, events, and invoices.

## Base URL

`https://your-subdomain.banster.nl/api/v1`

## IP whitelisting

All requests must originate from a whitelisted IP address.

- IP addresses can be configured in the Banster admin interface.
- Requests from non-whitelisted IPs are rejected with code `4010` (ip_error).

## Authentication

All requests must be authenticated using an API key sent via the `Authorization` header.

```
Authorization: Bearer YOUR_API_KEY
```

- API keys are linked to a specific Banster environment
- Requests without a valid API key are rejected with code `4020` (api_key_error)

## Rate & Concurrency Limits

API access is subject to usage limits:

- Maximum 120 requests per 60-second window.
- Maximum 3 concurrent requests.

If a limit is exceeded, the API returns one of the following return codes:

- `4030` (api_rate_limit_exceeded)
- `4040` (api_concurrency_limit_exceeded)

Clients must handle these conditions and implement appropriate retry or backoff logic.

## Requests & Responses

- All requests must be made over HTTPS. Requests over HTTP will be rejected.
- All responses are JSON (`Content-Type: application/json`).
- GET endpoints use query parameters.
- POST endpoints require JSON request bodies.
- On success: a standardized success object is returned.
- On error: a standardized error object is returned.

Clients must evaluate the `status` and `code` fields in the JSON response to determine the outcome of a request.

## Primitive Types

The API uses standard JSON data types:

- string
- integer
- float
- boolean (`true` / `false`)
- array
- object

Numeric values are returned as JSON numbers.
Boolean values must be sent and are returned as JSON booleans.
Arrays must be sent and are returned as JSON arrays.

### Example request

```bash
curl "https://your-subdomain.banster.nl/api/v1/lists" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_API_KEY"
```

### Error format

```json
{
  "request": "/api/v1/lists/99999",
  "status": "error",
  "code": 4090,
  "description": "Invalid argument",
  "msg": "List not found"
}
```

### Success format

```json
{
  "request": "/api/v1/lists/9",
  "status": "success",
  "code": 1000,
  "description": "Success",
  "msg": "1 list retrieved",
  "data": {
    "id": 9,
    "info": {
      "name": "Donateurs",
      "type": "relation"
    },
    "...": "...",
    "links": {
      "self": "/api/v1/lists/9"
    }
  }
}
```

## Return codes

| Code | Name | Description |
|:-----|:-----|:------------|
| 1000 | success | Success |
| 4010 | ip_error | Unauthorized IP |
| 4020 | api_key_error | API key error |
| 4030 | api_rate_limit_exceeded | Too many requests |
| 4040 | api_concurrency_limit_exceeded | Too many concurrent requests |
| 4050 | module_inactive | Module inactive |
| 4060 | invalid_method | Invalid HTTP method |
| 4070 | invalid_endpoint | Invalid endpoint |
| 4080 | invalid_path | Invalid path |
| 4090 | invalid_argument | Invalid argument |
| 4100 | invalid_parameter | Invalid parameter |
| 4110 | update_failed | Update rejected |

## Pagination, sorting & filtering

Most collection endpoints support the following optional parameters:

- `start` – zero-based offset
- `limit` – maximum number of records returned
- `orderBy` – field to sort on (see sortable fields endpoints)
- `sortDirection` – `asc` or `desc`
- `searchQuery` – free-text search (where supported)

## Versioning

- The API is versioned via the URL (`/api/v1`)
- Backward-incompatible changes will result in a new version

## Reference documentation

The complete, auto-generated endpoint reference is available here:

- [API v1 Reference](v1/API.md)

