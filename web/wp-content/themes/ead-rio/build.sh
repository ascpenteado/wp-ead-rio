#!/bin/bash

echo "Building EAD Rio theme assets..."

# Build main theme styles
echo "Compiling main styles..."
npm run build

# Build widget styles
echo "Compiling widget styles..."
npm run build:widgets

echo "Build complete!"
echo "Generated files:"
echo "- style.css (main theme styles)"
echo "- assets/css/widgets/*.css (widget styles)"