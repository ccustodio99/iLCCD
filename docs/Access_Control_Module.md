# 🔐 Access Control Module – LCCD Integrated Information System

## 🎯 Purpose

The Access Control module is the core of security and data integrity in the system, ensuring that only authorized users gain access to appropriate modules and data. It guarantees that all user actions are logged and traceable, aligning with the institution's standards of transparency, stewardship, and digital safety.

---

## 🧩 Core Features

### 1. Role-Based Access Control (RBAC) Implementation
- Every user is assigned a **role** (e.g., Admin, Department Head, or Staff).
- Each role maps to a specific set of permissions, such as:
  - View/Create/Approve/Modify/Delete requests
  - Manage users or departments
  - Access to sensitive modules or audit logs
- **Permissions** are enforced both at the UI level (what buttons/menus are visible) and at the controller level (what the backend will allow).
- Route middleware (`role:admin`, etc.) checks the authenticated user's role before allowing access to sensitive endpoints.
- **Departmental scoping**: Data access is filtered by department unless the role has cross-department permissions (e.g., Admin, President).

### 2. Secure Login Mechanisms
- **Login page** is accessible over HTTPS (recommended).
- Passwords are hashed with a secure algorithm (bcrypt or Argon2).
- **Account lockout** after five failed login attempts for 15 minutes (brute-force prevention).
- **Optional Two-Factor Authentication (2FA):**
  - Can be enabled for privileged users/roles (e.g., President, Finance, Admin).
  - Sends a time-based OTP (One Time Password) to email or SMS upon login.
- **Session regeneration** upon successful login to prevent session fixation attacks.

### 3. Session Management and Timeout
- Each login creates a secure PHP session.
- **Automatic logout** after 15 minutes of inactivity (configurable via `SESSION_LIFETIME`).
- **Manual logout** option on every page.
- Session tokens are regenerated on privilege change and logout.
- Session storage and handling prevent session hijacking (store session ID in HTTP-only, secure cookies).

### 4. Data Encryption In Transit and At Rest
- **In Transit:** All communication between client and server should use SSL/TLS (HTTPS).
- **At Rest:**
  - Sensitive data in the database (passwords, audit logs) are encrypted or hashed.
  - Database and backup access is restricted to authorized personnel only.

### 5. Audit Logging
- Every authentication, authorization, and access event is logged.
  - Successful and failed logins
  - Session timeouts
  - Role or permission changes
  - Unauthorized access attempts
- Logs are immutable and regularly reviewed by the ITRC/admin.
### Current Implementation
- Role-based middleware restricts access to routes.
- Passwords are hashed and sessions regenerate on login/logout.
- Accounts are locked for 15 minutes after five failed login attempts and audit logs capture these events.
- Two-factor auth is not yet implemented. Automatic session timeout logs users out after 15 minutes of inactivity.
- Role checks rely on `app/Http/Middleware/RoleMiddleware.php`, which compares the `role` field on the `users` table with the allowed roles configured on each route.

### Planned Two-Factor Authentication
1. Integrate [Laravel Fortify](https://laravel.com/docs/11.x/fortify) to handle time-based OTP verification.
2. Store a per-user secret key and allow enabling or disabling 2FA from the *My Profile* page.
3. Require the OTP during login when 2FA is active and record failures in the audit trail.

---

## 🖼️ User Interface Design Notes

- Login screens and all sensitive operations display school (`public/assets/images/LCCD.jpg`) and department (`public/assets/images/CCS.jpg`) logos.
- Branding uses Navy Blue, Gold, CCS Cyan, and White for clarity and trust.
- Error messages (e.g., "Access Denied", "Session Expired") use Bootstrap alert components for clarity.

---

## 🔒 Security Considerations

- **Password complexity** enforced (min. 8 characters, mix of upper/lowercase, digit, symbol).
- **2FA** optional toggle per user or required for critical roles.
- **CSRF tokens** on all sensitive forms and state-changing requests.
- **Regular security audits** and vulnerability scans.

---

## 📊 Integration with Other Modules

- All modules (Tickets, Requisitions, Job Orders, etc.) **check user role and department** before displaying or processing data.
- Approvals, modifications, and deletions are allowed only for users with proper permissions.
- KPIs and audit logs can be filtered by user/role/department for compliance monitoring.

See the [User Management Module](user.md) for how roles are created and assigned.

For an overview of all system modules consult the [project README](../README.md)

---

## ✨ Augustinian Integration

| Value           | Implementation Example                                             |
|-----------------|-------------------------------------------------------------------|
| Unity           | Respectful access across departments, fostering collaboration      |
| Truth           | Transparent logging of all access attempts and changes             |
| Competence      | Strong technical safeguards and access policy enforcement          |
| Charity         | Support for users needing access help and clear error feedback     |
| Stewardship     | Regular review and responsible handling of all access privileges   |
| Service         | Fast, reliable access for all roles, supporting workflow needs     |
| Christ-Centeredness | Ethical digital conduct, fairness in all access policies       |

For more guides visit the [documentation index](README.md).

---

## 🚀 Navigation

- Previous: [User Manual](user_manual.md)
- Next: [User Management Module](user.md)
- [Documentation Index](README.md)
