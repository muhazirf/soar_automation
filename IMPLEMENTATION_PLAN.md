# Cyber Sentinel - Implementation Plan

## 📋 Table of Contents
1. [View Index](#view-index)
2. [Component Structure](#component-structure)
3. [Controller Architecture](#controller-architecture)
4. [File Organization](#file-organization)
5. [Implementation Phases](#implementation-phases)

---

## 1. View Index

### Core Pages (from `stitch/` folder)

| # | View Name | Route | Controller | Method | Description |
|---|-----------|-------|------------|--------|-------------|
| 1 | Login | `/login` | `AuthController` | `showLogin` | Interactive background login |
| 2 | Dashboard Overview | `/dashboard` | `DashboardController` | `index` | Main dashboard with alerts overview |
| 3 | Alerts View | `/alerts` | `AlertController` | `index` | Detailed alerts list |
| 4 | Detailed Alerts | `/alerts/{id}` | `AlertController` | `show` | Single alert detail view |
| 5 | Incident Reports | `/incidents` | `IncidentController` | `index` | Incident management |
| 6 | Playbooks View | `/playbooks` | `PlaybookController` | `index` | Security playbooks |
| 7 | Threat Intelligence | `/threat-intel` | `ThreatController` | `index` | Threat intelligence data |
| 8 | VirusTotal Analysis | `/virustotal` | `VirusTotalController` | `index` | VirusTotal integration |
| 9 | User Management | `/users` | `UserController` | `index` | User management |
| 10 | Account Settings | `/settings` | `SettingsController` | `index` | User account settings |

---

## 2. Component Structure

### Layout Components (`resources/views/components/`)

```
components/
├── layouts/
│   ├── app.blade.php          # Main layout (~150 lines)
│   ├── auth.blade.php          # Auth layout (~100 lines)
│   └── dashboard.blade.php     # Dashboard layout with sidebar (~200 lines)
│
├── navigation/
│   ├── sidebar.blade.php       # Main sidebar navigation (~150 lines)
│   ├── topbar.blade.php        # Top navigation bar (~120 lines)
│   └── breadcrumb.blade.php    # Breadcrumb component (~50 lines)
│
├── ui/
│   ├── card.blade.php          # Reusable card component (~80 lines)
│   ├── button.blade.php        # Button variants (~60 lines)
│   ├── badge.blade.php         # Status badges (~50 lines)
│   ├── input.blade.php         # Form input (~70 lines)
│   ├── select.blade.php        # Dropdown select (~60 lines)
│   ├── modal.blade.php         # Modal dialog (~100 lines)
│   ├── dropdown.blade.php      # Dropdown menu (~80 lines)
│   └── tabs.blade.php          # Tab navigation (~70 lines)
│
├── data/
│   ├── table.blade.php         # Reusable data table (~180 lines)
│   ├── table-header.blade.php  # Table header with sort (~80 lines)
│   ├── table-row.blade.php     # Dynamic table row (~60 lines)
│   ├── pagination.blade.php    # Pagination controls (~100 lines)
│   ├── sparkline.blade.php     # Mini charts (~80 lines)
│   └── status-pip.blade.php    # Pulsing status indicator (~40 lines)
│
├── dashboard/
│   ├── stat-card.blade.php     # Dashboard stat card (~80 lines)
│   ├── alert-card.blade.php    # Alert preview card (~100 lines)
│   ├── chart-card.blade.php    # Chart container (~90 lines)
│   └── activity-feed.blade.php # Activity timeline (~120 lines)
│
├── forms/
│   ├── form-group.blade.php    # Form wrapper (~60 lines)
│   ├── checkbox.blade.php      # Checkbox input (~40 lines)
│   ├── radio.blade.php         # Radio input (~40 lines)
│   ├── textarea.blade.php      # Text area input (~50 lines)
│   └── file-upload.blade.php   # File upload zone (~80 lines)
│
└── feedback/
    ├── loading.blade.php       # Loading spinner (~30 lines)
    ├── empty-state.blade.php   # Empty state display (~60 lines)
    ├── error-state.blade.php   # Error message display (~50 lines)
    └── toast.blade.php         # Toast notification (~70 lines)
```

### Page Views (`resources/views/`)

```
views/
├── auth/
│   ├── login.blade.php         # Login page (~150 lines)
│   └── register.blade.php      # Registration page (~180 lines)
│
├── dashboard/
│   ├── index.blade.php         # Dashboard overview (~200 lines)
│   └── partials/
│       ├── stats-grid.blade.php    # Stats section (~80 lines)
│       ├── recent-alerts.blade.php # Recent alerts table (~100 lines)
│       └── activity-log.blade.php  # Activity feed (~80 lines)
│
├── alerts/
│   ├── index.blade.php         # Alerts list (~180 lines)
│   ├── show.blade.php          # Alert detail (~220 lines)
│   └── partials/
│       ├── filters.blade.php        # Alert filters (~100 lines)
│       ├── alert-row.blade.php      # Single alert row (~60 lines)
│       └── timeline.blade.php       # Alert timeline (~80 lines)
│
├── incidents/
│   ├── index.blade.php         # Incidents list (~180 lines)
│   ├── create.blade.php        # Create incident (~200 lines)
│   ├── edit.blade.php          # Edit incident (~200 lines)
│   └── partials/
│       ├── incident-card.blade.php   # Incident card (~100 lines)
│       └── severity-badge.blade.php  # Severity indicator (~50 lines)
│
├── playbooks/
│   ├── index.blade.php         # Playbooks list (~180 lines)
│   ├── show.blade.php          # Playbook detail (~220 lines)
│   └── partials/
│       ├── playbook-card.blade.php   # Playbook card (~100 lines)
│       └── step-list.blade.php       # Execution steps (~80 lines)
│
├── threats/
│   ├── index.blade.php         # Threat intelligence (~200 lines)
│   └── partials/
│       ├── threat-card.blade.php     # Threat card (~120 lines)
│       └── indicator-list.blade.php  # IOC list (~80 lines)
│
├── virustotal/
│   ├── index.blade.php         # VT Analysis dashboard (~200 lines)
│   ├── scan.blade.php          # Scan form (~150 lines)
│   └── partials/
│       ├── result-card.blade.php     # Scan result (~120 lines)
│       └── score-badge.blade.php     # VT Score badge (~50 lines)
│
├── users/
│   ├── index.blade.php         # User list (~180 lines)
│   ├── create.blade.php        # Create user (~200 lines)
│   ├── edit.blade.php          # Edit user (~200 lines)
│   └── partials/
│       ├── user-row.blade.php        # User table row (~80 lines)
│       └── role-badge.blade.php      # Role indicator (~50 lines)
│
└── settings/
    ├── index.blade.php         # Account settings (~200 lines)
    └── partials/
        ├── profile-form.blade.php     # Profile update (~120 lines)
        ├── security-form.blade.php    # Password/2FA (~100 lines)
        └── notification-form.blade.php # Notifications (~80 lines)
```

---

## 3. Controller Architecture

### Controller Structure (`app/Http/Controllers/`)

```
Controllers/
├── DashboardController.php      # Dashboard main (~150 lines)
├── AlertController.php          # Alerts CRUD (~200 lines)
├── IncidentController.php       # Incidents CRUD (~220 lines)
├── PlaybookController.php       # Playbooks CRUD (~180 lines)
├── ThreatController.php         # Threat intel (~150 lines)
├── VirusTotalController.php     # VT integration (~180 lines)
├── UserController.php           # User management (~200 lines)
├── SettingsController.php       # Settings (~150 lines)
└── Api/
    ├── AlertApiController.php      # API endpoints (~180 lines)
    └── IncidentApiController.php   # API endpoints (~180 lines)
```

### Base Controller Pattern

```php
// app/Http/Controllers/BaseController.php
abstract class BaseController extends Controller
{
    protected function paginate($query, $perPage = 15)
    protected function jsonSuccess($data, $message = null)
    protected function jsonError($message, $code = 400)
}
```

---

## 4. File Organization

### Asset Structure (`resources/`)

```
resources/
├── css/
│   ├── app.css                # Main styles (~200 lines)
│   ├── components.css         # Component styles (~300 lines)
│   └── themes.css             # Theme variables (~100 lines)
│
├── js/
│   ├── app.js                 # Main JS (~100 lines)
│   ├── components/
│   │   ├── table.js           # Table functionality (~150 lines)
│   │   ├── modal.js           # Modal handlers (~100 lines)
│   │   ├── charts.js          # Chart configurations (~150 lines)
│   │   └── notifications.js   # Toast system (~100 lines)
│   └── pages/
│       ├── dashboard.js       # Dashboard logic (~150 lines)
│       └── alerts.js          # Alerts page logic (~120 lines)
│
└── views/
    └── [structure as above]
```

### Configuration (`config/`)

```
config/
└── cyber_sentinel.php         # Platform configuration (~100 lines)
    - themes
    - pagination limits
    - alert severity levels
    - incident statuses
```

---

## 5. Implementation Phases

### Phase 1: Foundation (Week 1)

**Priority: HIGH**

1. **Setup Design System**
   - Create theme configuration
   - Setup Tailwind with custom config
   - Create base layout components
   - Build navigation components

2. **Core UI Components**
   - Button, Badge, Card, Input
   - Table, Modal, Dropdown
   - Status indicators

**Files to Create:**
- `resources/css/themes.css`
- `tailwind.config.js` (update)
- `resources/views/components/layouts/app.blade.php`
- `resources/views/components/layouts/dashboard.blade.php`
- `resources/views/components/navigation/sidebar.blade.php`
- All `components/ui/*.blade.php`

### Phase 2: Authentication (Week 1-2)

**Priority: HIGH**

1. **Login Page**
   - Interactive background
   - Form validation
   - Remember me

2. **Registration**
   - Multi-step form
   - Email verification
   - Password strength

**Files to Create:**
- `resources/views/auth/login.blade.php`
- `resources/views/auth/register.blade.php`
- `app/Http/Controllers/AuthController.php`

### Phase 3: Dashboard (Week 2)

**Priority: HIGH**

1. **Main Dashboard**
   - Stats cards
   - Recent alerts
   - Activity feed
   - Charts

**Files to Create:**
- `resources/views/dashboard/index.blade.php`
- `resources/views/dashboard/partials/*.blade.php`
- `resources/js/pages/dashboard.js`
- `app/Http/Controllers/DashboardController.php`

### Phase 4: Core Features (Week 3-4)

**Priority: MEDIUM**

1. **Alerts Module**
   - Alerts list with filters
   - Alert detail view
   - Status updates

2. **Incidents Module**
   - Incident list
   - Create/Edit forms
   - Severity management

**Files to Create:**
- `resources/views/alerts/*.blade.php`
- `resources/views/incidents/*.blade.php`
- `app/Http/Controllers/AlertController.php`
- `app/Http/Controllers/IncidentController.php`

### Phase 5: Advanced Features (Week 5)

**Priority: MEDIUM**

1. **Playbooks**
   - Playbook library
   - Execution interface
   - Step tracking

2. **Threat Intelligence**
   - IOC management
   - Threat feeds
   - Analysis tools

3. **VirusTotal Integration**
   - Scan interface
   - Result display
   - History

**Files to Create:**
- `resources/views/playbooks/*.blade.php`
- `resources/views/threats/*.blade.php`
- `resources/views/virustotal/*.blade.php`
- Corresponding controllers

### Phase 6: User Management (Week 5-6)

**Priority: LOW**

1. **User Management**
   - User list
   - Create/Edit users
   - Role management
   - Support modal

2. **Settings**
   - Account settings
   - Security settings
   - Notification preferences

**Files to Create:**
- `resources/views/users/*.blade.php`
- `resources/views/settings/*.blade.php`
- `app/Http/Controllers/UserController.php`
- `app/Http/Controllers/SettingsController.php`

### Phase 7: Polish & Testing (Week 6)

**Priority: LOW**

1. **Performance Optimization**
   - Lazy loading
   - Caching
   - Asset optimization

2. **Testing**
   - Component testing
   - Integration testing
   - E2E testing

3. **Documentation**
   - Component docs
   - API documentation
   - Deployment guide

---

## Design System Reference

### Colors (from `stitch/cyber_sentinel/DESIGN.md`)

```css
/* Primary Palette */
--surface: #131315
--surface-dim: #131315
--surface-bright: #39393b
--surface-container: #1f1f21
--primary: #b9c7e4
--primary-container: #0a192f
--secondary: #b8c8da
--tertiary: #e7bf99

/* Functional */
--error: #ffb4ab
--error-container: #93000a
--success: #64ffda  /* Signal Cyan */
--warning: #ffd700
```

### Typography

```css
/* Font Families */
--font-display: 'Inter', sans-serif
--font-mono: 'JetBrains Mono', monospace

/* Type Scale */
--display-lg: 48px / 700 / -0.02em
--headline-md: 24px / 600 / -0.01em
--body-md: 16px / 400 / 1.5
--label-sm: 12px / 500 / 0.05em (mono)
```

### Spacing

```css
--unit: 4px
--gutter: 16px
--container-max: 1440px
```

### Border Radius

```css
--radius-sm: 0.125rem   /* 2px */
--radius-md: 0.25rem     /* 4px */
--radius-lg: 0.5rem      /* 8px */
--radius-xl: 0.75rem     /* 12px */
```

---

## Notes

1. **Max 400 lines per file** - Each view/controller/component is designed to stay under 400 lines
2. **Reusable components** - All common UI elements are componentized
3. **Partials** - Complex pages use partials to keep main files small
4. **API separation** - API controllers in separate folder for future API versioning
5. **Theme system** - CSS variables for easy theming
