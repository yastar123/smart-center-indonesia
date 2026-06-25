# 3D Model Generation

Use this reference only when the user explicitly asks for a 3D model or asset.

## Available Function

### generate3DModel(description, outputPath, ...)

Generate a static 3D model game asset in GLB format from a text description.

**Parameters:**

- `description` (str, required): Text description of the desired 3D model. Describe only the object itself, not surrounding scenery.
- `outputPath` (str, required): Destination path for the generated model. Use an unused repl-relative `.glb` path.
- `quality` (str, default `"medium"`): `"medium"` is faster and should be used by default; `"high"` is slower and more detailed.
- `usageInstruction` (str, optional): Short sentence explaining how the generated model will be used.

**Returns:** Dict with `filePath`, `description`, and `thumbnailUrl` keys.

```javascript
const result = await generate3DModel({
    description: "low-poly treasure chest, stylized game asset, gold trim",
    outputPath: "attached_assets/generated_models/treasure_chest.glb",
    quality: "medium"
});
console.log(`3D model saved to: ${result.filePath}`);
```

## When to Use

- Static 3D game props, characters, and objects
- GLB assets for Three.js or React Three Fiber scenes
- Cases where a generated 3D mesh is needed instead of a flat image

## Best Practices

1. Describe only the object, not the surrounding scene.
2. Include style and target use, such as `low-poly`, `stylized`, `realistic`, or `game asset`.
3. Use `"medium"` by default. Use `"high"` only when the user explicitly asks for high detail or the medium result is insufficient.
4. Do not regenerate existing 3D assets unless you are sure it is necessary; 3D generation is slow and expensive.

## Output Locations

- Generated 3D models: `attached_assets/generated_models/`

## Limitations

- Generated 3D models are static; animation is not supported by this callback.
- Complex or highly specific prompts may not match exactly.
