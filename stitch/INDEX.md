# Stitch Design Files Index

Quick reference for all design mockups in the `stitch/` folder.

## Design System

| File | Lines | Description |
|------|-------|-------------|
| `cyber_sentinel/DESIGN.md` | ~160 | Design system specification - colors, typography, spacing |

## Page Designs

| # | Folder | HTML File | Lines | Screenshot | Status |
|---|--------|-----------|-------|------------|--------|
| 1 | `soc_platform_login_interactive_background` | code.html | ~320 | screen.png | ✅ Latest |
| 2 | `cyber_sentinel_dashboard_overview` | code.html | ~550 | screen.png | ✅ Latest |
| 3 | `cyber_sentinel_alerts_view_updated_nav` | code.html | ~450 | screen.png | ✅ Latest |
| 4 | `cyber_sentinel_dashboard_detailed_alerts` | code.html | ~550 | screen.png | ✅ Latest |
| 5 | `cyber_sentinel_incident_reports_updated_nav` | code.html | ~500 | screen.png | ✅ Latest |
| 6 | `cyber_sentinel_playbooks_view_updated_nav` | code.html | ~480 | screen.png | ✅ Latest |
| 7 | `cyber_sentinel_threat_intelligence_updated_nav` | code.html | ~560 | screen.png | ✅ Latest |
| 8 | `cyber_sentinel_virustotal_analysis` | code.html | ~540 | screen.png | ✅ Latest |
| 9 | `cyber_sentinel_user_management_updated_nav` | code.html | ~600 | screen.png | ✅ Latest |
| 10 | `cyber_sentinel_user_management_with_support_modal` | code.html | ~620 | screen.png | ✅ Latest |
| 11 | `cyber_sentinel_account_settings_updated_nav` | code.html | ~400 | screen.png | ✅ Latest |

## Legacy Versions (Pre-Updated Nav)

These are older versions before navigation updates. Use for reference only.

| Folder | Lines | Note |
|--------|-------|------|
| `cyber_sentinel_alerts_view` | ~450 | Pre-nav update |
| `cyber_sentinel_incident_reports` | ~500 | Pre-nav update |
| `cyber_sentinel_playbooks_view` | ~480 | Pre-nav update |
| `cyber_sentinel_user_management` | ~600 | Pre-nav update |
| `cyber_sentinel_account_settings` | ~400 | Pre-nav update |

## Component Extraction Guide

Common reusable patterns identified across designs:

### Navigation
- **Source**: All `*_updated_nav` folders
- **Components**: Sidebar, Topbar, Breadcrumb
- **Lines**: ~80-120 per component

### Data Table
- **Source**: Alerts, Incidents, User Management views
- **Features**: Sort, filter, pagination, inline actions
- **Lines**: ~150-200 for reusable component

### Cards
- **Stat Cards**: Dashboard overview (4 stat cards)
- **Alert Cards**: Dashboard alerts section
- **Incident Cards**: Incidents list
- **Lines**: ~60-80 per card type

### Modals
- **Source**: User Management (support modal)
- **Features**: Overlay, content, actions
- **Lines**: ~80-100

### Forms
- **Source**: Account settings, Create/Edit forms
- **Features**: Inputs, selects, checkboxes, validation
- **Lines**: ~100-150 per form type

## Quick Stats

- **Total unique pages**: 11
- **Total HTML lines**: ~5,500
- **Average per page**: ~500 lines
- **Largest page**: User Management with Support Modal (~620 lines)
- **Smallest page**: Login (~320 lines)

## Implementation Priority

1. **Phase 1**: Design System + Login
2. **Phase 2**: Dashboard Overview
3. **Phase 3**: Alerts + Incidents
4. **Phase 4**: Playbooks + Threat Intel
5. **Phase 5**: VirusTotal + User Management
6. **Phase 6**: Settings
