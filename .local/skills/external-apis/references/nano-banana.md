# Nano Banana

Proxy requests to Nano Banana via Replit-managed billing.

## Callback

Use `externalApi__nano_banana` in `codeExecution`.

## Allowed operations

- `POST` `/gemini-3.1-flash-image-preview:generateContent` - Generate image (Nano Banana 2 / Flash)
- `POST` `/gemini-3-pro-image-preview:generateContent` - Generate image (Nano Banana Pro)
- `POST` `/gemini-2.5-flash-image:generateContent` - Generate or edit image (Nano Banana v1)

Authorization is handled automatically by Replit. Do not pass an `Authorization` header.

## Quickstart

1. Call the callback with a `path` and `method` exactly as listed under Allowed operations — do not add or remove version prefixes (e.g. `/scrape`, not `/v1/scrape`).
2. For GET, put URL params in `query`. For POST/PUT/PATCH, pass a JSON object as `body` (it is serialized for you).
3. Inspect `result.body`.

## Example

```javascript
const result = await externalApi__nano_banana({
  path: '/gemini-3.1-flash-image-preview:generateContent',
  method: 'POST',
  body: {},
})

console.log(result.status)
console.log(result.body)
```
