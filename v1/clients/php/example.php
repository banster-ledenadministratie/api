<?php
/**
 *
 * Example usage of BANSTERApiClient
 *
 * This example demonstrates:
 * 1. Validating credentials
 * 2. Retrieving list entries
 * 3. Updating a list entry
 *
 * Adjust list IDs, entry IDs, login credentials and API key as needed.
 *
 * Run from CLI: php example.php
 *
 */

require_once 'BANSTERApiClient.php';

// ------------------------------------------------------------
// Configuration
// ------------------------------------------------------------

$baseUrl = 'https://your-subdomain.banster.nl/api/v1';
$apiKey  = 'YOUR_API_KEY';

// create client instance
$client = new BANSTERApiClient($baseUrl, $apiKey);

try {

    // ------------------------------------------------------------
    // 1. Validate credentials
    // ------------------------------------------------------------
    $authResponse = $client->validateCredentials(
        'login@example.com',
        'secret'
    );

    echo "=== Auth response ===\n";
    print_r($authResponse);
    echo "\n\n";


    // ------------------------------------------------------------
    // 2. Retrieve entries from list ID 1
    // ------------------------------------------------------------
    $entriesResponse = $client->getListEntries(1, [
        'limit'        => 3
    ]);

    echo "=== List entries response ===\n";
    print_r($entriesResponse);
    echo "\n\n";


    // ------------------------------------------------------------
    // 3. Update entry ID 9 in list ID 1
    // ------------------------------------------------------------
    $updateResponse = $client->updateListEntry(1, 9, [
        'email' => 'new@email.com'
    ]);

    echo "=== Update response ===\n";
    print_r($updateResponse);
    echo "\n\n";


} catch (RuntimeException $e) {

    echo "Error ({$e->getCode()}): " . $e->getMessage() . PHP_EOL;
}