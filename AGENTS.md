# AGENTS.md – LCCD Integrated Information System (Laravel Architecture)

Welcome to the LCCD Integrated Information System project!
This guide ensures **every contributor (human or AI)** follows best practices for maintainability, code quality, branding, and Augustinian values.

---

## 📁 Project Structure (Laravel Style)

```
/LCCD/
├── app/                 # Core application: Models, Controllers, Middleware, Providers
├── bootstrap/           # App bootstrap files
├── config/              # Config files (env, services, mail, etc.)
├── database/            # Migrations, factories, seeders, SQLite data
├── public/              # Assets (css, js, images, logos), index.php
├── resources/           # Blade templates, views, language files
├── routes/              # Web and API route files
├── storage/             # Logs, file uploads, cache, audit trails
├── tests/               # Test cases (unit, feature, integration)
├── docs/                # All documentation
├── AGENTS.md            # Contributor guidelines (this file)
└── .env                 # Environment config (never commit secrets)
```

---

## 🎨 Branding & UI Guidelines

- **Logos:**
  - Main: `public/assets/images/LCCD.jpg`
  - Department: `public/assets/images/CCS.jpg`
- **Colors:**
  - Navy Blue `#1B2660`, Gold `#FFCD38`, CCS Cyan `#20BFEA`, Red `#E5403A`, White, Gray
- **Fonts:**
  - Primary: Poppins
  - Secondary: Roboto, Montserrat
- **Layout:**
  - Responsive (Bootstrap 5)
  - Accessible (WCAG 2.1): Color contrast, keyboard navigation, alt text
- **Consistency:**
  - Use Blade components for repeated UI (header, nav, footer)
  - Stick to the established palette & type scale

---

## 🧑‍💻 Coding Conventions

- **PHP:**
  - PSR-12 standard (4 spaces, clear names, docblocks for functions/classes)
  - Use Laravel’s Eloquent models, validation, resource controllers, middleware
- **JS/CSS:**
  - Use [Laravel Mix](https://laravel.com/docs/11.x/mix) for asset pipeline
  - Structure JS and SASS/CSS in `resources/`
- **Naming:**
  - Files: snake_case for migration files, PascalCase for classes
  - Variables: meaningful, no abbreviations
- **Comments:**
  - Comment complex business logic, workflows, and any “why” behind key decisions
  - Run `composer pint` to automatically fix PHP formatting

---

## 🖥 Local Development

- Use `composer dev` to start the server, queue worker, and Vite in watch mode.
- This script runs `php artisan serve`, `php artisan queue:listen`, and `npm run dev` concurrently.

---

## 🧪 Testing Protocols

- **Manual:**
  - Test each module as all major user roles (admin, staff, finance, etc.)
  - Validate both happy path and error flows
- **Automated:**
  - Write unit and feature tests for controllers, models, and core logic in `/tests/`
  - Run: `php artisan test` before PR or merge (uses [Pest](https://pestphp.com/))
  - See [docs/testing.md](docs/testing.md) for detailed instructions

---

## 🚀 Git & Pull Request Workflow

1. **Branch:**
   - Create a feature branch: `git checkout -b [module]-[short-description]`
2. **Commit:**
   - Message format: `[Module] Short summary` (max 72 chars)
     - Example: `[Inventory] Fix stock deduction on requisition approval`
     - Optional Git hook: copy `githooks/commit-msg` to `.git/hooks/commit-msg` to enforce the format
3. **Pull Request:**
   - Describe what you changed and why
   - Reference related issues if any
   - Tag reviewers (`@ccustodio`, etc.)
   - Attach screenshots or videos for UI changes
   - Ensure all tests pass (manual & automated)

---

## 🛠️ Programmatic Checks

- **PHP Syntax:**
  - `php artisan test` (preferred) or `php -l file.php`
  - Run `composer pint` to fix PHP style issues
- **HTML Validation:**
  - [W3C Markup Validator](https://validator.w3.org/)
- **CSS Validation:**
  - [W3C CSS Validator](https://jigsaw.w3.org/css-validator/)
- **Accessibility:**
  - [WAVE Tool](https://wave.webaim.org/)
- **Security:**
  - CSRF and XSS checks (use Laravel’s built-in helpers)
  - Never hardcode passwords or API keys (use `.env`)

---

## 💬 Communication

- **Use Discussions/Issues:**
  - For proposals, problems, or design debates
- **Respectful, Open Collaboration:**
  - Suggest, don’t demand
  - Listen before pushing back

---

## ✨ Augustinian Value Alignment

- **Unity:**
  - Collaborate, avoid silos, keep everyone in the loop
- **Truth:**
  - Transparent commit history, open changelogs, honest bug reporting
- **Competence:**
  - Quality code, review before merging, continuous learning
- **Charity:**
  - Help new contributors, respond kindly, explain decisions
- **Stewardship:**
  - Mindful resource usage, no feature bloat, secure sensitive data
- **Service:**
  - Prioritize user needs, build for everyone (inclusion!)
- **Christ-Centeredness:**
  - Ethical, purpose-driven digital culture

---

## 📘 Resources

- [README.md](README.md) — System overview
- [User Manual](docs/user_manual.md) — End-user workflows
- [Branding Guide](docs/user-interface-branding.md)
- [Access Control](docs/Access_Control_Module.md)
- [Module Docs](docs/)
- [Codebase Overview](docs/codebase_overview.md)
- [Testing Guide](docs/testing.md)
- Email: `itrc@lccd.edu.ph` for help or onboarding

---

## 🙏 Commitment

> “Every line of code is a step toward servant leadership. Let’s build tech that uplifts, includes, and transforms.”

**Bless up, code boldly, and be a light for others.**
— LCCD CCS Dev Community

---
