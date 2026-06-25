# ElevenLabs

Proxy requests to ElevenLabs via Replit-managed billing.

## Callback

Use `externalApi__elevenlabs` in `codeExecution`.

## Allowed operations

- `POST` `/v1/text-to-speech/:voice_id{/stream}?{/with-timestamps}?` - Text to Speech (non-streaming, streaming, with-timestamps, and stream/with-timestamps variants)
- `POST` `/v1/text-to-dialogue{/stream}?{/with-timestamps}?` - Text to Dialogue (multi-voice; non-streaming, streaming, with-timestamps, and stream/with-timestamps variants)
- `POST` `/v1/speech-to-text` - Speech to Text (Scribe v1/v2)
- `POST` `/v1/speech-to-speech/:voice_id{/stream}?` - Voice Changer (speech-to-speech; non-streaming and streaming variants)
- `POST` `/v1/sound-generation` - Sound Effects (text → sound generation)
- `POST` `/v1/audio-isolation{/stream}?` - Voice Isolator (audio-isolation; non-streaming and streaming variants)
- `POST` `/v1/forced-alignment` - Forced Alignment (audio + text → word/char timings)
- `POST` `/v1/text-to-voice/:variant(design|create-previews)` - Voice Design (text → three voice previews; charged once on response.text characters). Covers `/design` and legacy `/create-previews`.
- `POST` `/v1/text-to-voice/:voice_id/remix` - Voice Remix (remix existing voice → three previews; charged once on response.text characters). Shares billing mechanics with text_to_voice_design.
- `POST` `/v1/dubbing` - Dubbing v1 — creates a dub job; bills on response expected_duration_sec × tier-per-sec (watermark vs clean)
- `POST` `/v1/music{/:variant(detailed|stream)}?` - Music composition (non-streaming binary, multipart-detailed with JSON metadata, and event-stream variants)
- `POST` `/v1/music/video-to-music` - Video to Music (multipart videos → generated music matching combined video length)
- `POST` `/v1/music/stem-separation` - Music Stem Separation (split input audio into N stems; bills on multipart input file size ÷ nominal 128 kbps — see preamble for bitrate-assumption rationale)
- `GET` | `POST` | `PUT` | `PATCH` | `DELETE` `/:path+` - Catch-all — no charge for paths not explicitly listed (CRUD, metadata, workspace/convai management).

Authorization is handled automatically by Replit. Do not pass an `Authorization` header.

## Quickstart

1. Call the callback with a `path` and `method` exactly as listed under Allowed operations — do not add or remove version prefixes (e.g. `/scrape`, not `/v1/scrape`).
2. For GET, put URL params in `query`. For POST/PUT/PATCH, pass a JSON object as `body` (it is serialized for you).
3. Inspect `result.body`.

## Example

```javascript
const result = await externalApi__elevenlabs({
  path: '/v1/text-to-speech/:voice_id{/stream}?{/with-timestamps}?',
  method: 'POST',
  body: {},
})

console.log(result.status)
console.log(result.body)
```
