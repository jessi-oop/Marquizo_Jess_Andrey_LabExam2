# Be Money Wise — Login & Registration System

A simple login and registration system built with plain PHP, HTML, CSS, and vanilla JavaScript.
No frameworks, no Composer packages.

---

## Requirements

- **XAMPP** (PHP 8.0+, Apache)
- A modern browser (Chrome, Firefox, Edge)

---

## How to Run in XAMPP

**1. Copy the project folder into XAMPP's web root:**

```
C:\xampp\htdocs\login-system           ← Windows
/Applications/XAMPP/htdocs/login-system   ← macOS
/opt/lampp/htdocs/login-system         ← Linux
```

**2. Start Apache** from the XAMPP Control Panel.

**3. Open in your browser:**

```
http://localhost/login-system/
```

`index.php` redirects straight to the login page.

---

## Project Structure

```
login-system/
│
├── index.php           Redirects to login.php
├── login.php           Login page
├── register.php        Registration page
├── dashboard.php       Protected welcome page (requires session)
├── logout.php          Destroys session, redirects to login.php
│
├── includes/
│   └── functions.php   Helpers: clean(), loadUsers(), saveUsers(),
│                       emailExists(), validateRegister(), validateLogin()
│
├── data/
│   └── users.json      Flat-file user store (auto-populated on registration)
│
├── assets/
│   ├── css/
│   │   ├── base.css        Shared styles (variables, layout, card, form, etc.)
│   │   ├── login.css       Login-page overrides
│   │   ├── register.css    Register-page overrides
│   │   └── dashboard.css   Dashboard page styles
│   ├── js/
│   │   └── script.js       Lucide init, password toggle, live validation
│   └── images/
│       └── logo.png        Placeholder logo — replace with your own (see below)
│
└── README.md
```

---

## Replacing the Logo

`assets/images/logo.png` is a generated placeholder.

To use your own:
1. Save your logo as `logo.png` (recommended width: **100 px**).
2. Drop it into `assets/images/`, overwriting the placeholder.

No code changes needed — all three pages check `file_exists()` and fall back gracefully.

---

## Features

| Feature | Details |
|---|---|
| Registration | Fullname, Email, Password, Confirm Password — full server-side validation |
| Login | Email + Password, verified with `password_verify()` |
| Password storage | `password_hash()` / bcrypt, cost 12 |
| Session security | `session_regenerate_id(true)` on login, full teardown on logout |
| Input handling | `trim()` + `htmlspecialchars()` on all output |
| PRG pattern | Redirect after every successful POST |
| Session guards | Dashboard requires a session; login/register redirect if already logged in |
| Icons | Lucide icon set via CDN (`data-lucide` attributes) |
| Password toggle | Eye / eye-off icon swap (JS) |
| Live validation | Client-side feedback as you type — server side is authoritative |
| Responsive | Single-column layout on screens ≤ 800 px |

---

## Validation Rules

**Registration**

| Field | Rule |
|---|---|
| Fullname | Required, letters and spaces only, min 2 characters |
| Email | Required, valid format, not already registered |
| Password | Required, min 8 chars, ≥1 uppercase, ≥1 lowercase, ≥1 digit |
| Confirm Password | Required, must match Password |

**Login**

| Field | Rule |
|---|---|
| Email | Required, valid format |
| Password | Required |

A single generic error ("Invalid email or password") is shown on credential failure.

---

## Notes

- `data/users.json` must be writable by the web server (XAMPP allows this by default).
- For a real application, swap the JSON store for MySQL/MariaDB.
- The JSON flat-file approach is intentional for this local school lab exercise.

---

## Files Changed in Refactor

| File | Change |
|---|---|
| `assets/css/base.css` | **New** — shared styles extracted from the old `style.css` |
| `assets/css/login.css` | **New** — login-page slot (currently a placeholder) |
| `assets/css/register.css` | **New** — register-page override (`.card` gap) |
| `assets/css/dashboard.css` | **New** — all dashboard-specific styles |
| `assets/css/style.css` | **Deleted** — replaced by the four files above |
| `assets/js/script.js` | Rewritten — Lucide `createIcons()`, `data-lucide` toggle, simplified validation |
| `login.php` | Inline SVGs → `data-lucide`, new class names, split CSS links, Lucide CDN tag |
| `register.php` | Same as login.php |
| `dashboard.php` | Same as login.php |
| `includes/functions.php` | `sanitizeInput` → `clean()`, shorter comments, `elseif` chains |
