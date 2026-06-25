---
name: external-apis
description: "Access external APIs through Replit-managed billing"
---

# External APIs

This skill provides access to external APIs through Replit-managed
passthrough billing. Requests are proxied through OpenInt with
managed credentials.

## Recommended workflow

1. Open the connector reference for request and response details.
2. Call `externalApi__<connector_name>` from `codeExecution`.
3. Use `query` for URL parameters and read `result.body`.
4. For media, save files under `attached_assets/` and present them.

## Response bodies

The callback decodes the response by Content-Type:

- JSON → `result.body` is the parsed object.
- `text/*` → `result.body` is a string.
- Anything else (binary, e.g. audio/image) → `result.body` is a base64 string and `result.encoding === 'base64'`. Decode it with `Buffer.from(result.body, 'base64')` before writing the file.

Responses are capped (~1 MB); larger media cannot be returned in-band yet. Prefer operations that return a hosted URL, or request smaller media until object-storage handoff is available.

## Available APIs

- [Brave](references/brave.md) - Search real web image results through Brave passthrough billing.
- [ElevenLabs](references/elevenlabs.md)
- [Exa](references/exa.md)
- [Firecrawl](references/firecrawl.md)
- [Nano Banana](references/nano-banana.md)
