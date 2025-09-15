# EAD Rio Theme Build System

## Overview

This theme uses SASS for styling with a build system that compiles SCSS files into optimized CSS. Widget styles are conditionally loaded only when the widgets are present on a page.

## Directory Structure

```
assets/
├── styles/
│   ├── main.scss          # Main theme styles entry point
│   ├── tokens.scss        # CSS custom properties/variables
│   └── overrides.scss     # Plugin overrides
├── css/
│   └── widgets/           # Compiled widget CSS (generated)
│       └── cards-module/
│           └── cards-module.css
└── widgets/
    └── cards-module/      # Colocated widget files
        ├── cards-module-widget.php    # Widget PHP class
        └── cards-module.scss          # Widget styles
```

## Build Commands

### Development
- `npm run dev` - **Hot reload development** (SASS watching + browser auto-refresh)
- `npm run build:dev` - Builds expanded CSS with source maps
- `npm run watch` - Watches main styles only
- `npm run serve` - Start browser-sync server only (no SASS watching)

### Production
- `npm run build` - Builds compressed CSS for production
- `./build.sh` - Runs all build commands

### Widget Styles
- `npm run build:widgets` - Compiles individual widget styles

## Adding New Widgets

1. Create widget directory: `widgets/new-widget/`
2. Create PHP file: `widgets/new-widget/new-widget-widget.php`
3. Create SCSS file: `widgets/new-widget/new-widget.scss`
4. Register in widget class: `get_style_depends() { return ['new-widget-widget']; }`
5. Register style in functions.php
6. Run build command

Note: Use `@use` instead of `@import` for modern Sass syntax without deprecation warnings.

## Development Workflow

### Setup Hot Reload
1. Update `bs-config.js` with your local WordPress URL (default: `localhost:8000`)
2. Run `pnpm dev` - This will:
   - Watch and compile SCSS files automatically
   - Start browser-sync server
   - Auto-refresh browser when CSS/PHP files change

### Manual Development
1. Make changes to SCSS files
2. Run `pnpm watch` for SCSS compilation only
3. For production: run `./build.sh`

### Hot Reload Features
- ✅ CSS injection (no full page reload)
- ✅ SCSS compilation on save
- ✅ PHP file change detection
- ✅ Multi-device sync

## Conditional Loading

Widget styles are only loaded when the specific widget is present on a page, reducing CSS bloat and improving performance.