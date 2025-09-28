# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Development Environment Setup

This is a WordPress theme running in DDEV. Always check that DDEV is running before development:
```bash
ddev describe  # Check DDEV status
ddev start     # Start if not running
```

## Essential Build Commands

### Primary Development Workflow
```bash
npm run dev          # Start development with hot reload (SASS watching + browser-sync)
npm run build        # Production build (includes prebuild validation and postbuild fixes)
npm run build:dev    # Development build with expanded CSS and source maps
```

### Code Quality
```bash
npm run lint         # Run all linters (TypeScript + SCSS)
npm run lint:fix     # Auto-fix linting issues
npm run format       # Format all code with Prettier
```

### Specific Build Tasks
```bash
npm run build:ts     # Compile TypeScript only
npm run build:css    # Compile SCSS only
npm run fix:imports  # Fix ES6 module imports (adds .js extensions)
```

## Architecture Overview

### Hybrid TypeScript/PHP Theme
This is a WordPress theme with modern TypeScript/SCSS build system. The theme uses a configuration-driven approach for Elementor widgets and follows a component-based architecture.

### Key Architectural Patterns

**1. Configuration-Driven Widgets**
- Elementor widgets extend `Base_Widget` (abstract class)
- Widget configuration is defined in separate `-config.php` files
- Controls are built dynamically from configuration using `Control_Builder`
- Templates are separate PHP files referenced in configuration

**2. Conditional Style Loading**
- Widget styles are registered but only enqueued when widgets are present on page
- Each widget declares its style dependencies in configuration: `'style_dependencies' => ['widget-handle']`
- Styles are built separately per widget and loaded conditionally

**3. TypeScript Module System**
- Uses ES6 modules compiled to `dist/js/`
- Import paths require `.js` extensions for browser compatibility (handled by `fix:imports` script)
- Base component system with `BaseComponent` abstract class for consistent widget initialization

### Directory Structure Logic

```
src/                    # TypeScript source files
├── theme.ts           # Main theme entry point (singleton pattern)
├── components/        # TypeScript component classes
├── utils/             # Utility functions (DOM manipulation, etc.)
└── types/             # TypeScript type definitions

components/            # Co-located component files (PHP + SCSS + TS)
├── widgets/          # Elementor widgets
│   └── widget-name/
│       ├── widget-name-widget.php      # PHP widget class
│       ├── widget-name-config.php      # Widget configuration
│       ├── widget-name.template.php    # Template file
│       ├── widget-name.scss           # Widget styles
│       └── widget-name.ts             # Widget TypeScript (optional)
└── molecules/        # Reusable PHP/SCSS components

includes/             # WordPress/PHP includes
├── widgets/          # Widget infrastructure
│   ├── abstracts/    # Base widget class
│   └── controls/     # Control builder system
└── component-loader.php  # Auto-loads widget styles

assets/styles/        # Global SCSS
├── main.scss         # Entry point (imports other files)
├── tokens.scss       # CSS custom properties/variables
├── site-header.scss  # Header component styles
└── pages/           # Page-specific styles
```

## WordPress Integration Specifics

### Theme Configuration
- Set as standalone theme (not child theme): template option = 'ead-rio'
- Registers custom post type: 'curso' for course content
- Uses Elementor integration for widgets

### Script Loading
- Main theme script loaded as ES6 module: `type="module"`
- Scripts are enqueued from `dist/js/src/theme.js`
- TypeScript compilation outputs to `dist/js/` preserving src structure

## Critical Build System Notes

### ES6 Module Import Fix
The `postbuild` script automatically fixes TypeScript's ES6 imports by adding `.js` extensions. This is essential because:
- TypeScript compiles imports without file extensions: `import { ready } from './utils/dom-utils'`
- Browsers require explicit extensions: `import { ready } from './utils/dom-utils.js'`
- The `fix:imports` script handles this transformation automatically

### SCSS Architecture
- Uses modern `@use` syntax instead of deprecated `@import`
- Main entry point includes all component styles
- Widget styles compile separately for conditional loading
- CSS variables defined in `tokens.scss` for consistency

## Widget Development Pattern

1. Create widget directory: `components/widgets/new-widget/`
2. Create configuration file: `new-widget-config.php` with style dependencies
3. Create widget class extending `Base_Widget`
4. Create template file for rendering
5. Create SCSS file for styling
6. Register style handle in `functions.php`
7. Run build to compile styles

The widget system automatically handles Elementor registration, control building, and conditional style loading based on configuration.