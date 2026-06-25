---
name: media-generation
description: "Generate AI images and retrieve stock images. Use this skill for visual content creation. For AI video clips, read `media-generation/video-generation.md`; for 3D model assets, read `media-generation/reference/3d-model-generation.md`; for music, sound effects, and text-to-speech audio, read `media-generation/audio-generation.md`"
---

# Media Generation Skill

Generate custom images, videos, or retrieve stock images for real-photography use cases. The TypeScript runtime currently registers `generateImage`, `generateVideo`, `stockImage`, `generateMusic`, `generateSoundEffect`, `searchVoices`, and `textToSpeech`.

## Available Functions

### generateImage({prompt, ...})

Generate one custom image from a text description. Await the returned promise before reading the generated file. If you need multiple images, start multiple `generateImage` calls and await them together.

**Parameters:**

- `prompt` (required): Text description of the desired image
- `outputPath` (required): File path **must end in `.png`** -- this is the only accepted format. `.jpg`, `.jpeg`, `.webp`, and other extensions will cause an error. Use an unused workspace-relative path.
- `summary`: Optional, short 4-5 word description for the return description
- `removeBackground`: Optional boolean. Set to `true` when the result should be a transparent PNG with the background removed, such as logos, icons, stickers, product cutouts, or subject images that will be composited over another background.

**Returns:** A job that resolves to a dict with `filePath` and `description`

**Examples:**

```javascript
// Start early, await before consuming the file
const heroImage = generateImage({
  prompt: 'A serene mountain landscape at sunset with snow-capped peaks',
  outputPath: 'src/assets/images/hero.png',
});

// Do unrelated file/code work here.

const result = await heroImage;
console.log(`Image saved to: ${result.filePath}`);

// Single image
const result = await generateImage({
  prompt: 'A serene mountain landscape at sunset with snow-capped peaks',
  outputPath: 'src/assets/images/hero.png',
});
console.log(`Image saved to: ${result.filePath}`);

// Logo or icon with a transparent background
const logo = await generateImage({
  prompt: 'A simple friendly robot mascot icon, no text, no words, no letters',
  outputPath: 'src/assets/images/robot-logo.png',
  summary: 'robot logo',
  removeBackground: true,
});
console.log(`Transparent image saved to: ${logo.filePath}`);

// Multiple images in parallel
const imageJobs = [
  generateImage({ prompt: 'A red apple', outputPath: 'assets/apple.png' }),
  generateImage({
    prompt: 'A yellow banana',
    outputPath: 'assets/banana.png',
  }),
  generateImage({ prompt: 'An orange', outputPath: 'assets/orange.png' }),
];
const images = await Promise.all(imageJobs);
for (const img of images) {
  console.log(`Generated: ${img.filePath}`);
}
```

### stockImage({description, ...})

Retrieve stock photos matching a description and save them to the repl.

**Parameters:**

- `description` (string, required): Text description of the desired stock image or images
- `outputPath` (string, required): Destination path including filename and extension, for example `attached_assets/stock_images/office.jpg`
- `limit` (number, optional): Number of images to retrieve, from 1 to 10. Defaults to 1
- `orientation` (string, optional): `"horizontal"`, `"vertical"`, or `"all"`. Defaults to `"horizontal"`

**Returns:** Dict with `filePaths` and `query`. If a multi-image save partially fails, the result also includes `failures`.

Note: Unlike `generateImage` and `generateVideo`, `stockImage` is not a job and should be directly awaited.

For multiple images, the callback appends numeric suffixes before the extension, for example `office_1.jpg`.
The callback does not overwrite existing files. Choose a new `outputPath` when rerunning a similar request.

**Example:**

```javascript
const result = await stockImage({
  description: 'modern office with natural lighting',
  outputPath: 'attached_assets/stock_images/office_background.jpg',
  limit: 3,
  orientation: 'horizontal',
});

for (const path of result.filePaths) {
  console.log(`Stock image saved to: ${path}`);
}
```

## When to Use Each Function

### generateImage

- Custom illustrations or graphics not available elsewhere
- Specific visual concepts or designs
- Placeholder images for development
- Creative or artistic content

### stockImage

- Professional photography
- Real-world scenes and people
- Business and corporate imagery
- Cases where authenticity matters more than customization

## Aspect Ratio Guidelines

### Images

`generateImage` does not accept an `aspectRatio` argument in this runtime. Describe the desired composition in the prompt, such as "wide 16:9 hero image" or "square icon on transparent-style background."

## Best Practices

1. **Write detailed prompts**: Include style, mood, lighting, colors, and composition
2. **Use negative prompts**: Exclude unwanted elements like "blurry", "watermark", "text"
3. **Start slow generations early**: Store each `generateImage` promise, do unrelated work, then `await` before using the file path.
4. **Choose appropriate formats**: Match aspect ratio and media type to intended use
5. **Consider stock for realism**: Use stock images when you need authentic photography
6. **Do not over generate**: Only generate multiple images when the user explicitly asks.

## Output Locations

- Generated images: `attached_assets/generated_images/`
- Stock images: `attached_assets/stock_images/`

## Limitations

- Stock image availability depends on the search query
- Complex or highly specific prompts may not match exactly
- Text in generated media is not reliably rendered

## Copyright

- Use this skill to create media assets instead of copying from websites
- Generated images are created for your use
- Stock images come from Pixabay. Use them responsibly and keep the downloaded file paths in the project
