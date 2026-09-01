# Banster API v1

Authentication and general API behavior are described in the root README.md.

<a id="top"></a>

## Endpoints

### Lists
- [GET /api/v1/lists/search](#get-apiv1listssearch)
- [GET /api/v1/lists](#get-apiv1lists)
- [GET /api/v1/lists/{listid}](#get-apiv1listslistid)
- [GET /api/v1/lists/{listid}/editablefields](#get-apiv1listslistideditablefields)
- [GET /api/v1/lists/{listid}/sortablefields](#get-apiv1listslistidsortablefields)
- [GET /api/v1/lists/{listid}/entries](#get-apiv1listslistidentries)
- [GET /api/v1/lists/{listid}/entries/{entryid}](#get-apiv1listslistidentriesentryid)
- [POST /api/v1/lists/{listid}/entries/{entryid}](#post-apiv1listslistidentriesentryid)
- [GET /api/v1/lists/{listid}/entries/{entryid}/invoices](#get-apiv1listslistidentriesentryidinvoices)
- [GET /api/v1/lists/{listid}/invoices](#get-apiv1listslistidinvoices)

### Events
- [GET /api/v1/events/search](#get-apiv1eventssearch)
- [GET /api/v1/events](#get-apiv1events)
- [GET /api/v1/events/{eventid}](#get-apiv1eventseventid)
- [GET /api/v1/events/{eventid}/sortablefields](#get-apiv1eventseventidsortablefields)
- [GET /api/v1/events/{eventid}/entries](#get-apiv1eventseventidentries)
- [GET /api/v1/events/{eventid}/entries/{entryid}](#get-apiv1eventseventidentriesentryid)
- [GET /api/v1/events/{eventid}/entries/{entryid}/invoices](#get-apiv1eventseventidentriesentryidinvoices)
- [GET /api/v1/events/{eventid}/invoices](#get-apiv1eventseventidinvoices)

### Invoices
- [GET /api/v1/invoices/sortablefields](#get-apiv1invoicessortablefields)
- [GET /api/v1/invoices](#get-apiv1invoices)
- [GET /api/v1/invoices/{invoiceid}](#get-apiv1invoicesinvoiceid)

### Invoicemodels
- [GET /api/v1/invoicemodels](#get-apiv1invoicemodels)
- [GET /api/v1/invoicemodels/{invoicemodelid}](#get-apiv1invoicemodelsinvoicemodelid)

### Auth
- [POST /api/v1/auth/validate](#post-apiv1authvalidate)

## Lists

<a id="get-apiv1listssearch"></a>
### GET /api/v1/lists/search [⇧](#top)

Search for entries across all lists

### Parameters

| Name | Type | Description | Values | Default |
|:-----|:-----|:------------|:-------|:--------|
| searchQuery | string | search query (minimum length 3) | — | — |

<a id="get-apiv1lists"></a>
### GET /api/v1/lists [⇧](#top)

All lists

### Parameters

| Name | Type | Description | Values | Default |
|:-----|:-----|:------------|:-------|:--------|
| start | integer | zero-based starting position within the total result set | 0-999999 | 0 |
| limit | integer | maximum number of results returned | 0-100 | 100 |
| orderBy | string | field to sort by | id \| name | id |
| sortDirection | string | sort direction | asc \| desc | desc |
| searchQuery | string | search query | — | — |

<a id="get-apiv1listslistid"></a>
### GET /api/v1/lists/{listid} [⇧](#top)

List `$listid`

<a id="get-apiv1listslistideditablefields"></a>
### GET /api/v1/lists/{listid}/editablefields [⇧](#top)

Editable fields for list `$listid` for use with [POST /api/v1/lists/{listid}/entries/{entryid}](#post-apiv1listslistidentriesentryid)

> Each editable field includes its name, type, and validation constraints (e.g. required, max length, allowed values).

**Example request**

```bash
curl -X GET "https://your-subdomain.banster.nl/api/v1/lists/1/editablefields" \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json"
```

**Example response**

```json
{
  "request": "/api/v1/lists/1/editablefields",
  "status": "success",
  "code": 1000,
  "description": "Success",
  "msg": "<count> fields retrieved",
  "data": [
    {
      "name": "email",
      "display": "E-mail",
      "type": "text",
      "datatype": "string",
      "format": "email",
      "values": [],
      "editable": true,
      "obligatory": false,
      "tab": "details",
      "group": "contact",
      "alias": "r"
    },
    {
      "name": "gender",
      "display": "Aanhef",
      "type": "radio",
      "datatype": "string",
      "format": null,
      "values": ["heer", "mevrouw"],
      "editable": true,
      "obligatory": true,
      "tab": "details",
      "group": "name",
      "alias": "r"
    },
    ...,
    {
      "name": "flex_18",
      "display": "Interesses",
      "type": "checkbox",
      "datatype": "string",
      "format": null,
      "values": ["Nieuwsbrief", "Evenementen", "Vrijwilliger"],
      "editable": true,
      "obligatory": false,
      "tab": "flexible",
      "group": "Voorkeuren",
      "alias": "ffv"
    },
    ...
  ]
}
```

<a id="get-apiv1listslistidsortablefields"></a>
### GET /api/v1/lists/{listid}/sortablefields [⇧](#top)

Sortable fields for list `$listid` for use with [GET /api/v1/lists/{listid}/entries](#get-apiv1listslistidentries)

**Example request**

```bash
curl -X GET "https://your-subdomain.banster.nl/api/v1/lists/1/sortablefields" \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json"
```

**Example response**

```json
{
  "request": "/api/v1/lists/1/sortablefields",
  "status": "success",
  "code": 1000,
  "description": "Success",
  "msg": "<count> fields retrieved",
  "data": [
    "id",
    "gender",
    "initials",
    "firstname",
    "betweenpart",
    "lastname",
    "..."
  ]
}
```

<a id="get-apiv1listslistidentries"></a>
### GET /api/v1/lists/{listid}/entries [⇧](#top)

All entries on list `$listid`

### Parameters

| Name | Type | Description | Values | Default |
|:-----|:-----|:------------|:-------|:--------|
| start | integer | zero-based starting position within the total result set | 0-999999 | 0 |
| limit | integer | maximum number of results returned | 0-100 | 100 |
| orderBy | string | field to sort by | [GET /api/v1/lists/{listid}/sortablefields](#get-apiv1listslistidsortablefields) | id |
| sortDirection | string | sort direction | asc \| desc | desc |
| status | string | entry status | active \| inactive \| pending \| refused | active |
| searchQuery | string | search query | — | — |
| tsCreatedStart | date | date (dd-mm-yyyy) or datetime (dd-mm-yyyy hh:mm:ss) | — | — |
| tsCreatedEnd | date | date (dd-mm-yyyy) or datetime (dd-mm-yyyy hh:mm:ss) | — | — |
| tsModifiedStart | date | date (dd-mm-yyyy) or datetime (dd-mm-yyyy hh:mm:ss) | — | — |
| tsModifiedEnd | date | date (dd-mm-yyyy) or datetime (dd-mm-yyyy hh:mm:ss) | — | — |

> Note: sortable fields may represent internal or derived fields and do not necessarily correspond 1-to-1 with keys in the returned entries payload.

<a id="get-apiv1listslistidentriesentryid"></a>
### GET /api/v1/lists/{listid}/entries/{entryid} [⇧](#top)

Entry `$entryid` on list `$listid`

<a id="post-apiv1listslistidentriesentryid"></a>
### POST /api/v1/lists/{listid}/entries/{entryid} [⇧](#top)

Edit entry `$entryid` on list `$listid`

### Parameters

| Name | Type | Description | Values | Default |
|:-----|:-----|:------------|:-------|:--------|
| variables | object | key value pairs of variables to edit | [GET /api/v1/lists/{listid}/editablefields](#get-apiv1listslistideditablefields) | — |

> The variables parameter must be an object where keys correspond to editable field names and values contain the new value. Values must conform to the datatype and format returned by [GET /api/v1/lists/{listid}/editablefields](#get-apiv1listslistideditablefields).

**Example request**

```bash
curl -X POST "https://your-subdomain.banster.nl/api/v1/lists/1/entries/9" \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"variables":{"email":"new@email.com","notification_email_general":true,"discount":"10.5","start_date":"01-01-2026","flex_18":["Evenementen","Vrijwilliger"]}}'
```

**Example response**

```json
{
  "request": "/api/v1/lists/1/entries/12345",
  "status": "success",
  "code": 1000,
  "description": "Success",
  "msg": "entry updated",
  "data": {
    "id": 12345
  }
}
```

<a id="get-apiv1listslistidentriesentryidinvoices"></a>
### GET /api/v1/lists/{listid}/entries/{entryid}/invoices [⇧](#top)

All invoices belonging to entry `$entryid` on list `$listid`

> Supports the same query parameters as [GET /api/v1/invoices](#get-apiv1invoices).

<a id="get-apiv1listslistidinvoices"></a>
### GET /api/v1/lists/{listid}/invoices [⇧](#top)

All invoices belonging to entries on list `$listid`

> Supports the same query parameters as [GET /api/v1/invoices](#get-apiv1invoices).

## Events

<a id="get-apiv1eventssearch"></a>
### GET /api/v1/events/search [⇧](#top)

Search for entries across all events

### Parameters

| Name | Type | Description | Values | Default |
|:-----|:-----|:------------|:-------|:--------|
| searchQuery | string | search query (minimum length 3) | — | — |

<a id="get-apiv1events"></a>
### GET /api/v1/events [⇧](#top)

All events

### Parameters

| Name | Type | Description | Values | Default |
|:-----|:-----|:------------|:-------|:--------|
| start | integer | zero-based starting position within the total result set | 0-999999 | 0 |
| limit | integer | maximum number of results returned | 0-100 | 100 |
| orderBy | string | field to sort by | id \| name | id |
| sortDirection | string | sort direction | asc \| desc | desc |
| searchQuery | string | search query | — | — |

<a id="get-apiv1eventseventid"></a>
### GET /api/v1/events/{eventid} [⇧](#top)

Event `$eventid`

<a id="get-apiv1eventseventidsortablefields"></a>
### GET /api/v1/events/{eventid}/sortablefields [⇧](#top)

Sortable fields for event `$eventid` for use with [GET /api/v1/events/{eventid}/entries](#get-apiv1eventseventidentries)

<a id="get-apiv1eventseventidentries"></a>
### GET /api/v1/events/{eventid}/entries [⇧](#top)

All entries for event `$eventid`

### Parameters

| Name | Type | Description | Values | Default |
|:-----|:-----|:------------|:-------|:--------|
| start | integer | zero-based starting position within the total result set | 0-999999 | 0 |
| limit | integer | maximum number of results returned | 0-100 | 100 |
| orderBy | string | field to sort by | [GET /api/v1/events/{eventid}/sortablefields](#get-apiv1eventseventidsortablefields) | id |
| sortDirection | string | sort direction | asc \| desc | desc |
| status | string | entry status | active \| inactive \| pending \| refused | active |
| searchQuery | string | search query | — | — |
| tsCreatedStart | date | date (dd-mm-yyyy) or datetime (dd-mm-yyyy hh:mm:ss) | — | — |
| tsCreatedEnd | date | date (dd-mm-yyyy) or datetime (dd-mm-yyyy hh:mm:ss) | — | — |
| tsModifiedStart | date | date (dd-mm-yyyy) or datetime (dd-mm-yyyy hh:mm:ss) | — | — |
| tsModifiedEnd | date | date (dd-mm-yyyy) or datetime (dd-mm-yyyy hh:mm:ss) | — | — |

> Note: sortable fields may represent internal or derived fields and do not necessarily correspond 1-to-1 with keys in the returned entries payload.

<a id="get-apiv1eventseventidentriesentryid"></a>
### GET /api/v1/events/{eventid}/entries/{entryid} [⇧](#top)

Entry `$entryid` for event `$eventid`

<a id="get-apiv1eventseventidentriesentryidinvoices"></a>
### GET /api/v1/events/{eventid}/entries/{entryid}/invoices [⇧](#top)

Invoice (if any) belonging to event entry `$entryid` for event `$eventid`

> Supports the same query parameters as [GET /api/v1/invoices](#get-apiv1invoices).

<a id="get-apiv1eventseventidinvoices"></a>
### GET /api/v1/events/{eventid}/invoices [⇧](#top)

All invoices belonging to event `$eventid`

> Supports the same query parameters as [GET /api/v1/invoices](#get-apiv1invoices).

## Invoices

<a id="get-apiv1invoicessortablefields"></a>
### GET /api/v1/invoices/sortablefields [⇧](#top)

Sortable fields for `invoices` for use with [GET /api/v1/invoices](#get-apiv1invoices)

<a id="get-apiv1invoices"></a>
### GET /api/v1/invoices [⇧](#top)

All invoices

### Parameters

| Name | Type | Description | Values | Default |
|:-----|:-----|:------------|:-------|:--------|
| start | integer | zero-based starting position within the total result set | 0-999999 | 0 |
| limit | integer | maximum number of results returned | 0-100 | 100 |
| orderBy | string | field to sort by | [GET /api/v1/invoices/sortablefields](#get-apiv1invoicessortablefields) | number |
| sortDirection | string | sort direction | asc \| desc | asc |
| status | string | invoice status | all \| open \| sent \| paid \| unpaid \| removed | all |
| type | string | invoice type | all \| contribution \| donation \| event | all |
| incasso | string | direct debit? | all \| yes \| no | all |
| emailAndOptin | string | email and optin? | all \| yes \| no | all |
| numberStart | string | invoice number (yyyynnnnnn) | — | — |
| numberEnd | string | invoice number (yyyynnnnnn) | — | — |
| dateStart | date | date (dd-mm-yyyy) | — | — |
| dateEnd | date | date (dd-mm-yyyy) | — | — |
| openStart | date | date (dd-mm-yyyy) | — | — |
| openEnd | date | date (dd-mm-yyyy) | — | — |
| sentStart | date | date (dd-mm-yyyy) | — | — |
| sentEnd | date | date (dd-mm-yyyy) | — | — |
| paidStart | date | date (dd-mm-yyyy) | — | — |
| paidEnd | date | date (dd-mm-yyyy) | — | — |
| unpaidStart | date | date (dd-mm-yyyy) | — | — |
| unpaidEnd | date | date (dd-mm-yyyy) | — | — |
| removedStart | date | date (dd-mm-yyyy) | — | — |
| removedEnd | date | date (dd-mm-yyyy) | — | — |
| paidThroughMollie | string | paid through Mollie? | all \| yes \| no | all |
| searchQuery | string | search query | — | — |

<a id="get-apiv1invoicesinvoiceid"></a>
### GET /api/v1/invoices/{invoiceid} [⇧](#top)

Invoice `$invoiceid`

## Invoicemodels

<a id="get-apiv1invoicemodels"></a>
### GET /api/v1/invoicemodels [⇧](#top)

All invoice models. An invoice model id can be used to update the field `invoicemodel` when using [POST /api/v1/lists/{listid}/entries/{entryid}](#post-apiv1listslistidentriesentryid)

### Parameters

| Name | Type | Description | Values | Default |
|:-----|:-----|:------------|:-------|:--------|
| type | string | type of invoice model | contribution \| donation | contribution |
| start | integer | zero-based starting position within the total result set | 0-999999 | 0 |
| limit | integer | maximum number of results returned | 0-100 | 100 |
| orderBy | string | field to sort by | id \| name \| sort | sort |
| sortDirection | string | sort direction | asc \| desc | asc |
| searchQuery | string | search query | — | — |

<a id="get-apiv1invoicemodelsinvoicemodelid"></a>
### GET /api/v1/invoicemodels/{invoicemodelid} [⇧](#top)

Invoice model `$invoicemodelid`

## Auth

<a id="post-apiv1authvalidate"></a>
### POST /api/v1/auth/validate [⇧](#top)

Validate credentials and return whether this is an active member allowed to use the member portal https://your-subdomain.banster.nl. This endpoint does not create a session.

### Parameters

| Name | Type | Description | Values | Default |
|:-----|:-----|:------------|:-------|:--------|
| login | string | member login | — | — |
| password | string | member password | — | — |

**Example request**

```bash
curl -X POST "https://your-subdomain.banster.nl/api/v1/auth/validate" \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"login":"login@example.com","password":"secret"}'
```

**Example response – invalid credentials**

```json
{
  "request": "/api/v1/auth/validate",
  "status": "success",
  "code": 1000,
  "description": "Success",
  "msg": "Credentials checked",
  "data": {
    "valid": false,
    "reason": "password_invalid"
  }
}
```

**Example response – valid credentials**

```json
{
  "request": "/api/v1/auth/validate",
  "status": "success",
  "code": 1000,
  "description": "Success",
  "msg": "Credentials checked",
  "data": {
    "valid": true,
    "detailurl": "/api/v1/lists/{listid}/entries/{entryid}"
  }
}
```

