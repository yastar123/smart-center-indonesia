# Firecrawl

Proxy requests to Firecrawl via Replit-managed billing.

## Callback

Use `externalApi__firecrawl` in `codeExecution`.

## Allowed operations

- `POST` `/scrape` - Scrape any URL and get its content in markdown, HTML, or other formats.
- `POST` `/batch/scrape` - Scrape multiple URLs and get their content in markdown, HTML, or other formats.
- `POST` `/map` - Discover all URLs on a website without scraping their content.
- `POST` `/crawl` - Scrape an entire website by following links from a starting URL. The same per-page scrape option costs apply to each page crawled.
- `POST` `/agent` - Autonomous web research agent. 5 daily runs free; usage-based pricing beyond that.
- `POST` `/search` - Search the web and optionally scrape the results. Rounded up per 10 results (e.g., 11 results = 4 credits). Additional per-page scrape costs apply to each result that is scraped. Enterprise ZDR search costs 10 credits / 10 results.
- `POST` | `GET` | `DELETE` `/*` - All other paths are no-charge.

Authorization is handled automatically by Replit. Do not pass an `Authorization` header.

## Quickstart

1. Call the callback with a `path` and `method` exactly as listed under Allowed operations — do not add or remove version prefixes (e.g. `/scrape`, not `/v1/scrape`).
2. For GET, put URL params in `query`. For POST/PUT/PATCH, pass a JSON object as `body` (it is serialized for you).
3. Inspect `result.body`.

## Example

```javascript
const result = await externalApi__firecrawl({
  path: '/scrape',
  method: 'POST',
  body: {},
})

console.log(result.status)
console.log(result.body)
```
