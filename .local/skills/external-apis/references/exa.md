# Exa

Proxy requests to Exa via Replit-managed billing.

## Callback

Use `externalApi__exa` in `codeExecution`.

## Allowed operations

- `POST` `/search` - Web search (auto, neural, fast, deep, deep-reasoning).
- `POST` `/contents` - Page contents retrieval (text, highlights, summary, subpages, livecrawl).
- `POST` `/findSimilar` - Find similar links by URL similarity (optional contents pulldown).
- `POST` `/answer` - Web-grounded answer (with optional SSE streaming).

Authorization is handled automatically by Replit. Do not pass an `Authorization` header.

## Quickstart

1. Call the callback with a `path` and `method` exactly as listed under Allowed operations — do not add or remove version prefixes (e.g. `/scrape`, not `/v1/scrape`).
2. For GET, put URL params in `query`. For POST/PUT/PATCH, pass a JSON object as `body` (it is serialized for you).
3. Inspect `result.body`.

## Example

```javascript
const result = await externalApi__exa({
  path: '/search',
  method: 'POST',
  body: {},
})

console.log(result.status)
console.log(result.body)
```
