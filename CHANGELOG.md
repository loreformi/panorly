# Changelog

## 0.1.0 - Initial scaffold

- Laravel backend with multi-user authentication (first registered user becomes admin)
- Per-user theme system: preset themes (midnight, daylight, forest, sunset), custom accent/background/text colors, layout density
- Custom background image upload with validation (mime-type, size limit)
- Theme export/import as portable JSON (versioned format)
- Docker-first deployment (Dockerfile, docker-compose.yml, nginx + supervisord)
- Minimal, modern dashboard UI built with Blade + Alpine.js + Tailwind CSS
- Drag-and-drop app reordering (SortableJS)
- Client-side app search
- No visual or code references to the original Heimdall project (MIT credit retained per license in THIRD_PARTY_NOTICES.md)
