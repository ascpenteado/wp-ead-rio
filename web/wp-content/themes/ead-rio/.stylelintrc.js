module.exports = {
  extends: [
    'stylelint-config-standard-scss'
  ],
  rules: {
    // Allow CSS custom properties (CSS variables)
    'custom-property-pattern': null,

    // WordPress/Elementor specific class patterns
    'selector-class-pattern': null,

    // Allow vendor prefixes for WordPress compatibility
    'property-no-vendor-prefix': null,
    'selector-no-vendor-prefix': null,
    'value-no-vendor-prefix': null,

    // SCSS specific rules
    'scss/at-rule-no-unknown': true,
    'scss/dollar-variable-pattern': '^[a-z][a-zA-Z0-9]*$',

    // Allow WordPress/Elementor specific functions
    'function-no-unknown': [
      true,
      {
        ignoreFunctions: [
          'get_template_directory_uri',
          'wp_get_theme',
          'esc_attr',
          'esc_url'
        ]
      }
    ],

    // Disable some overly strict rules for WordPress development
    'no-descending-specificity': null,
    'selector-max-id': null,

    // Ensure consistent formatting
    'indentation': 2,
    'string-quotes': 'single',
    'color-hex-case': 'lower',
    'color-hex-length': 'short',
  },
  ignoreFiles: [
    'dist/**/*',
    'node_modules/**/*'
  ]
};