The user interface follows a mobile‑first approach using **Bootstrap 5**. All
module tables are wrapped in a `table-responsive` container so they scroll
horizontally on small screens. Navigation uses a hamburger button on mobile
while a persistent sidebar remains visible on larger screens. The menu lists
links to **Tickets**, **Job Orders**, **Requisitions**, **Inventory**,
**Purchase Orders**, and **Documents** so moving between modules is quick and
intuitive. Each sidebar link now displays a small Material icon for faster
visual recognition.

### UX Design Principles

The interface embraces seven core UX principles:

1. **User-centricity** – forms preserve input and highlight required fields while
   confirmation messages guide each step.
2. **Consistency** – all modules share the same palette and fonts. Administrators
  customize these in the [Appearance Settings](system-settings.md#theme-branding--institution) screen.
3. **Hierarchy** – the active navigation menu link and clear headings reveal where you
   are within the system.
4. **Context** – breadcrumbs and page titles maintain orientation in multi-step
   workflows.
5. **User control** – cancel buttons and a collapsible menu let you easily back
   out or explore.
6. **Accessibility** – alt text, ARIA labels, skip links and keyboard-friendly
   toggles support every user.
7. **Usability** – responsive layouts and quick AJAX updates keep tasks simple
   and fast.

### Improved Navigation

- A **Skip to main content** link and a new **Back to Top** button help with
  keyboard navigation and screen readers.
- Each standalone form page now includes a **Cancel** button that returns users
  to the list view without submitting changes.

### Form Guidelines

- Labels are always explicitly tied to form controls.
- Required fields use the `required` attribute and display validation errors.
- Submit buttons appear before the new Cancel link for better tab order.

### System Flow

- Tickets convert to Job Orders or Requisitions when needed.
- Requisitions automatically check Inventory and may create Purchase Orders.
- Documents and audit logs track every approval step.

### Dashboard Performance

Data on the dashboard is now requested via AJAX from the `dashboard.data`
endpoint. This minimizes initial load time and keeps the interface responsive,
supporting the project's emphasis on user-centric feedback.

### Theme & Branding Settings

Interface colors and typography come from the database so the same scheme applies across all screens. `layouts/app.blade.php` defines CSS variables pulled from settings:

```html
<style>
    :root {
        --color-primary: {{ setting('color_primary', '#1B2660') }};
        --color-accent: {{ setting('color_accent', '#FFCD38') }};
        --font-primary: '{{ setting('font_primary', 'Poppins') }}';
        --font-secondary: '{{ setting('font_secondary', 'Roboto') }}';
    }
</style>
```

`SettingController` loads these values via `appearanceData()` and saves updates from the **Settings → Appearance** form. A live preview powered by `resources/js/theme-preview.js` lets administrators test colors and fonts before saving. Uploaded logos and favicons are stored in `storage/branding/`, while header and footer text are managed through the same screen with the `updateInstitution()` action.
---

## 🚀 Navigation
- Previous: [User Management Module](user.md)
- Next: [Ticketing System Module](Ticketing_System_Module.md)
- [Documentation Index](README.md)
- [Appearance Settings](system-settings.md#theme-branding--institution)
