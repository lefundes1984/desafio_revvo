# Repository Guidelines

This repository currently has no committed source code. Use this guide to establish consistent patterns as you add the initial implementation.

## Project Structure & Module Organization
- Place runtime code in `src/`; group by domain, not only by layer.
- Keep automated tests in `tests/`, mirroring module names (`src/auth/user.py` -> `tests/auth/test_user.py`; `src/components/Button.tsx` -> `tests/components/Button.spec.tsx`).
- Store developer scripts in `scripts/` with executable bits and short headers; keep assets in `assets/` or `public/` as needed.
- Document decisions in `docs/` (architecture notes, ADRs) and keep templates like `.env.example` at the root.

## Build, Test, and Development Commands
Add a Makefile or package scripts early; prefer consistent wrappers:
```sh
make setup     # install dependencies (pip/uv/npm/yarn/pnpm as chosen)
make lint      # run formatters/linters
make test      # run full test suite with coverage
make dev       # start local server or CLI loop
```
If a Makefile is not available, provide equivalent `npm run ...` or `python -m ...` entries and keep them documented in README and here.

## Coding Style & Naming Conventions
- Default to 2-space indent for JS/TS, 4-space for Python; keep trailing commas where supported.
- Filenames: kebab-case for configs/scripts (`data-loader.sh`), snake_case for Python modules, PascalCase for React components or classes.
- Enforce a single formatter (Prettier for JS/TS, Black for Python) and a linter (ESLint/Ruff). Run them via `make lint` before pushes.

## Testing Guidelines
- Choose one framework per language (e.g., Vitest/Jest for JS/TS, Pytest for Python) and keep coverage ≥80% once code exists.
- Name files `*.spec.ts[x]` or `test_*.py`; favor deterministic tests and factory/fixture helpers in `tests/fixtures/`.
- Add CI-friendly flags (`--runInBand`/`-q --maxfail=1 --cov=src`) to catch flakiness early.

## Commit & Pull Request Guidelines
- Use Conventional Commits (`feat:`, `fix:`, `chore:`) to keep history and changelog generation straightforward.
- PRs should describe intent, link issue or task IDs, note manual testing performed, and include screenshots for UI changes.
- Keep diffs small and focused; add or update tests alongside behavior changes; mention breaking changes explicitly.

## Security & Configuration Tips
- Never commit secrets; rely on `.env` loaded via tooling and track sample values in `.env.example`.
- Use `.gitignore` to exclude virtualenvs, `node_modules`, build artifacts, and editor state.
- Review dependencies for licenses and pin versions via lockfiles once a package manager is chosen.
