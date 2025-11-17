# Repository Guidelines

## Project Structure & Module Organization
- Root Docker/Compose setup (`docker-compose.dev.yml`, `docker-compose.prod.yml`) orchestrates WordPress and MySQL; start from here for environment lifecycle.
- Application code lives in `web/` (standard WordPress tree). Custom work is in `web/wp-content/themes/ead-rio/`.
- Theme source divides into `src/assets/styles` (global SCSS), `src/components` (component-level SCSS), and `src/**/*.ts` (TypeScript for front-end behavior). Built assets emit to `dist/css` and `dist/js`.
- PHP theme entry points (`functions.php`, `header.php`, templates like `single-curso.php`, `page.php`) wire WordPress hooks and layouts. Avoid editing core WordPress files outside the theme.

## Build, Test, and Development Commands
- `pnpm run dev` — boot local Docker stack (uses `.env.local`) and serves WordPress; `dev:logs`, `dev:logs:wp`, `dev:logs:db` follow container logs.
- `pnpm run dev:down | dev:stop | dev:restart` — stop, halt, or restart the dev stack; `dev:clean` removes volumes/orphans when you need a fresh DB.
- Inside the theme (`web/wp-content/themes/ead-rio`):
  - `pnpm run dev` — TS/SCSS watchers + BrowserSync (expects Docker stack running).
  - `pnpm run build` — production TypeScript + compressed CSS; ensures dependencies present and validates outputs.
  - `pnpm run lint`, `lint:fix`, `format`, `format:check`, `type-check` — enforce code health before committing.

## Coding Style & Naming Conventions
- Use TypeScript ES2020 modules with strict compiler options (see `tsconfig.json`); prefer `const`, avoid `any`, and return types explicitly where practical.
- SCSS follows Stylelint Standard SCSS rules; nest sparingly and keep BEM-like class names for reusable components.
- Prettier governs TS/SCSS formatting; keep 2-space indentation and single quotes per lint defaults. Generated assets in `dist/` should not be edited manually.
- WordPress PHP templates: favor WordPress functions/APIs over raw SQL; keep escaping (`esc_html`, `wp_kses`) close to outputs.

## Testing Guidelines
- No dedicated test suite exists; rely on:
  - `pnpm run type-check` and lint tasks to gate regressions.
  - BrowserSync manual verification for theme pages; sanity-check key templates (course pages, archives, headers/footers) after changes.
- When adding new scripts/styles, ensure `build:validate` still passes and BrowserSync reflects live updates.

## Commit & Pull Request Guidelines
- Follow existing Conventional Commit style seen in history (`fix:`, `ci:`, `feat:`, etc.). Scope commits to a single concern; avoid committing `dist/` if CI/build can regenerate it.
- PRs should include: brief summary of intent, linked issue/ticket, notes on environment changes (`.env`/Docker), screenshots for UI changes, and validation evidence (commands run).
- If changes touch deployment, mention any required updates to GitHub Secrets or droplet config (`README-DEPLOYMENT.md`).

## Security & Configuration Tips
- Store local secrets in `.env.local` (dev) and `.env` (prod droplet); never commit them.
- Keep Docker stack up before running theme dev commands; use `pnpm run dev:shell` for wp-cli or troubleshooting inside the WordPress container.
- Before deployment, run `pnpm run build` at the theme level to bake assets into the Docker image used by production.
