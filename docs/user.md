# 👥 User Management Module – LCCD Integrated Information System

## 🎯 Purpose

The User Management Module is responsible for the secure **creation**, **classification**, **maintenance**, and **monitoring** of user accounts across all departments of LCCD. It ensures that all stakeholders — from students to department heads and administrators — have appropriate and timely access to system functions based on their roles and responsibilities.

See the [Access Control Module](Access_Control_Module.md) for details on permission enforcement.
Users should also review the [User Manual](user_manual.md) for day-to-day actions.

---

## 🧩 Core Features

- Admin users can create, edit, or remove accounts.
  Self-service registration is available for new users and requires only the **Name**, **Email**, **Password**, and **Confirm Password** fields. Department and contact details can be set by an admin after the account is created.
- Each account includes:
  - Full name
  - Email / Username
  - Department
  - Contact information (phone number or other details)
  - Account photo (optional, defaults to the value of `app.default_profile_photo`)
  - Set `APP_DEFAULT_PROFILE_PHOTO` in `.env` to customize the fallback image for users without a picture. The referenced file must exist or the app uses its bundled placeholder.
  - Passwords are hashed (e.g., bcrypt) and stored securely.
  - Optional: Bulk user registration (CSV import).

### 2. Role Assignments and Permission Settings
- Roles are predefined and linked to system modules. Allowed roles are:
  `admin`, `head`, and `staff`.
  Examples include:
  - **Staff**: Can create tickets, requisitions, and job orders.
  - **Department Heads**: Can review and approve within their scope.
- Each role grants access to specific views and actions (using RBAC).

### 3. Departmental Affiliations and Access Controls
- Users are linked to departments via a `department_id` foreign key (records are stored in the `departments` table, e.g., CCS, HR, Finance, ITRC, Library).
- Requests, approvals, and dashboard access are filtered by department.
- Cross-departmental access is managed only by Admins or Super Admins.

### 4. Account Status Management
- **Activation**: Active accounts can log in and access modules based on their role.
- **Deactivation**: Used when:
  - Staff/faculty leave LCCD.
  - Access needs to be temporarily suspended.
- Disabled users are locked out, but their data is retained in logs and history.

### 5. Audit Trails
- All user-related actions are recorded with timestamp, user ID, IP address, and description:
  - Logins, logouts, failed login attempts
  - Profile updates and password changes
  - Access to sensitive modules
  - Role or department modifications
- Logs are stored in `storage/logs` and feed into the **Audit & KPI dashboard**.
  Each entry also includes the user's IP address for accountability.

### 6. Profile Management
- Each user can update their **name**, **email**, **contact information**, and password from the *My Profile* page.
- Password changes require confirmation and log an audit entry.
- Access via the Profile link in the navigation menu when logged in.

---

## 🖼️ User Interface Design Notes

- Branded with **LCCD and CCS department logos** (located in `public/assets/images/LCCD.jpg` and `public/assets/images/CCS.jpg`).
- Uses Bootstrap 5 for responsive design.
- Consistent layout with navigation menu and profile dropdown.
- Accessible color scheme (dark navy/gold and cyan highlights).
- Status indicators: green (active), gray (inactive), red (locked).

---

## 🔒 Security Considerations

- Password hashing (e.g., bcrypt) and minimum complexity rules.
- Optional 2FA via email or SMS.
- Automatic logout on inactivity.
- Access checks at both **front-end** and **controller-level**.
- Logs are immutable and monitored by ITRC.
### Current Implementation
- Users can register and log in with hashed passwords.
- Admins manage roles, departments, activation status, and can create accounts directly from the Users page.
- Two-factor authentication, password expiry, and automatic logout remain planned features. Account lockout after repeated failed logins is already active.
- An optional `Remember me` checkbox keeps users logged in across browser restarts.

### Password Policy & Session Management
- Minimum 8 characters with a mix of letters, numbers, and symbols
- Planned: passwords will expire every 90 days and cannot be reused immediately
- Accounts lock for 15 minutes after 5 failed login attempts
- Sessions expire after 15 minutes of inactivity and IDs regenerate on login
- Changing a password invalidates all other active sessions

### Disabled Accounts
- Disabled users cannot authenticate or start new sessions
- Login attempts show an "Account disabled" message and are logged
- Administrators can reactivate accounts; historical data remains intact

---

## 📊 Integration with Other Modules

- Requisitions, Job Orders, Tickets — auto-fill department and user info.
- Approval routing is dynamically determined by user role and department.
- Reports filterable by user and role for KPI evaluation.

### Login Flow Example
1. User submits email and password on the login form
2. **User Management** verifies the account is active and not disabled
3. **Access Control** checks the password and records a login attempt
4. On success, a session is created and the session ID regenerates
5. User Management loads role and department data (via the `departments` table) to personalize the dashboard
6. The login event is written to the audit log

### Database Schema & Seeded Accounts
The `users` table stores account information with key fields:
- `name`, unique `email`, and hashed `password`
 - `department_id` referencing the `departments` table and `role`
- `is_active` plus lockout timestamps
- optional `designation`, `contact_info`, and `profile_photo_path`

Run `php artisan migrate --seed` to populate demo accounts via
`Database\Seeders\UserSeeder`. This seeder creates **admin**, **head**, and
**staff** examples so you can log in right away. Customize the credentials in
the seeder or provide your own users in production.


---

## ✨ Augustinian Integration

| Value                  | Implementation                                          |
|------------------------|----------------------------------------------------------|
| Unity                  | Departmental coherence and collaboration                 |
| Truth                  | Transparent audit trails and activity logs              |
| Competence             | Role-appropriate access and responsibility              |
| Charity                | Self-service and responsive admin support                |
| Stewardship            | Account lifecycle management and responsible access     |
| Committed Service      | Timely approvals and clarity of duties                   |
| Christ-Centeredness    | Trust-based, ethical digital community governance        |

For an overview of the entire system refer to the [project README](../README.md).

More guides are listed in the [documentation index](README.md).

---

## 🚀 Navigation
- Previous: [Access Control Module](Access_Control_Module.md)
- Next: [User Interface & Branding](user-interface-branding.md)
- [Documentation Index](README.md)
