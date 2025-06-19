# 📢 Announcement Module – LCCD Integrated Information System

System administrators can publish short messages that appear on the dashboard. Each announcement includes a title, rich content, and an active flag. Inactive records remain in the database but are hidden from users.

Announcements are managed under **Settings → Announcements**. Admins may create, edit, or delete entries from this screen.

---

## Key Features
- CRUD interface within System Settings
- Only the latest five active announcements show on the dashboard
- Soft deletes preserve history via the `archived_at` column

---

## 🚀 Navigation
- Previous: [System Settings](system-settings.md)
- [Documentation Index](README.md)
