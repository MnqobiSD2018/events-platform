# 📋 Corporate Event Management & Analytics Platform – Handover Summary

This document provides a clear, concise overview of the application you need to build. Use this as a reference when handing off to a developer. It describes the **purpose, user roles, frontend pages, main features, and technology stack**.

---

## 🧾 Application Overview

A web‑based platform that allows companies to:

- **Plan** corporate events (marathons, wellness programs, trainings, etc.)
- **Manage participants** via bulk CSV uploads
- **Track attendance** using QR code scanning
- **Measure success** with simple attendance reports

---

## 👥 User Roles (MVP)

| Role | Description |
|------|-------------|
| **Admin (Organisation)** | Creates events, uploads participant lists, views reports, manages check‑ins. One organisation per registered user. |
| **Employee (Participant)** | Receives a unique QR code; scans it at the event to check in. No login required for participants. |
| **Scanner (Event Staff)** | Uses a dedicated page on a mobile/tablet to scan QR codes and mark attendance. Can be the same as Admin. |

> **Note:** In the MVP, only the “Admin” role is implemented with authentication. Participants do not log in.

---

## 🖥️ Frontend Pages (Blade Templates)

All pages are accessible after login (except the registration/login pages).

| Page | URL (example) | Description |
|------|---------------|-------------|
| **Login** | `/login` | Email + password login for admins. |
| **Register** | `/register` | New organisation sign‑up. |
| **Dashboard** | `/dashboard` | Welcome page with links to manage events. |
| **Event List** | `/events` | Shows all events created by the logged‑in admin. Buttons: *Create New Event*, *View*, *Edit*, *Delete*. |
| **Create Event** | `/events/create` | Form with fields: name, description, type (dropdown), event date/time. |
| **Event Details** | `/events/{id}` | Displays event info, list of participants (each with a QR code), an “Import CSV” button, a link to “Attendance Report”, and a link to “QR Scanner”. |
| **Attendance Report** | `/events/{id}/report` | Shows total participants, number checked in, attendance rate (%). Also a table listing each participant with check‑in time. |
| **QR Scanner** | `/scanner` | A simple page that uses the device camera to scan QR codes. After scanning, redirects to check‑in endpoint. |
| **Check‑in Endpoint** | `/checkin/{participant}` | Not a visible page – an API‑like endpoint that records attendance and returns a success/error message. |

---

## ⚙️ Key Features (MVP)

### 1. Event Management
- Create, edit, delete events.
- Each event has: name, description, type, date/time.
- Events belong to the admin who created them.

### 2. Bulk Participant Upload (CSV)
- Upload a CSV file with columns: `name`, `email`, `department` (optional).
- The system validates data (email unique, required fields).
- For each valid row, a participant record is created and assigned a **unique QR code** (generated automatically).
- The QR code contains a URL pointing to the check‑in endpoint.

### 3. QR Code Check‑in
- Each participant’s QR code is displayed next to their name on the Event Details page.
- Event staff open the **QR Scanner** page on a mobile device, point the camera at the code, and the participant is marked as “checked in”.
- The system prevents double check‑ins (only the first scan counts).
- Check‑in time is recorded.

### 4. Attendance Reporting
- After the event, the admin can view:
  - Total registered participants
  - Number of attendees (checked in)
  - Attendance rate = (attendees / total) * 100
- A detailed list shows who checked in and at what time.

---

## 🛠️ Technology Stack (as previously agreed)

| Layer | Technology |
|-------|-------------|
| Backend | Laravel 12 (PHP 8.2+) |
| Database | PostgreSQL |
| Frontend | Blade templates + Tailwind CSS (via Laravel Breeze) |
| Authentication | Laravel Breeze (simple email/password) |
| File Handling | Laravel Excel (maatwebsite/excel) for CSV imports |
| QR Codes | lara-zeus/simple-qrcode (Laravel 12 compatible) |
| Scanner | HTML5 QR Code library (browser‑based) |
| Real‑time (optional) | Laravel Reverb + Echo (for live check‑in updates) |
| Development OS | Windows (but final code is OS‑agnostic) |
| Version Control | Git |
| Deployment | DigitalOcean (or any VPS) |

---

## 🗄️ Database Schema Summary

| Table | Main Columns |
|-------|---------------|
| `users` | id, name, email, password (Laravel default) |
| `events` | id, name, description, type, event_date, user_id (FK to users) |
| `participants` | id, name, email, department, qr_code (unique), event_id (FK to events) |
| `attendances` | id, participant_id (FK), event_id (FK), checked_in_at (timestamp) |

**Relationships:**
- A user has many events.
- An event has many participants.
- A participant has one attendance (or zero if not checked in).

---

## ✅ Acceptance Criteria (for the developer)

- [ ] Admin can register, log in, and log out.
- [ ] Admin can create, edit, delete, and list events.
- [ ] On the event details page, admin can upload a CSV file and participants are saved with unique QR codes.
- [ ] The QR code is displayed as an image next to each participant.
- [ ] A separate “Scanner” page exists that uses the device camera to read QR codes.
- [ ] Scanning a valid QR code marks the participant as checked in (with timestamp) and prevents duplicate scans.
- [ ] The attendance report page shows total participants, checked‑in count, and percentage, plus a list of each participant’s status.
- [ ] All database migrations, seeders, and basic validation are implemented.
- [ ] The application runs on the specified stack (Laravel 12 + PostgreSQL) without errors.

---

## 📁 Recommended Project Structure (for the developer)

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── EventController.php
│   │   ├── CheckinController.php
│   │   └── ...
│   └── ...
├── Imports/
│   └── ParticipantsImport.php
├── Models/
│   ├── Event.php
│   ├── Participant.php
│   ├── Attendance.php
│   └── User.php
├── Policies/
│   └── EventPolicy.php
resources/
├── views/
│   ├── events/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── show.blade.php
│   │   ├── report.blade.php
│   ├── checkin/
│   │   └── scanner.blade.php
│   └── ...
routes/
└── web.php
database/
├── migrations/
└── seeders/
```

---

## 📝 Notes for the Developer

- Use Laravel’s **resource controllers** and **route model binding** where possible.
- **Authorization:** Implement a policy so users can only see/edit their own events.
- **Validation:** CSV import must validate email uniqueness across participants (within the same event). Provide clear error messages.
- **QR Code storage:** Generate the QR code on‑the‑fly (using the package) and embed as an SVG or data URI; no need to store image files for the MVP.
- **Scanner page:** It should be accessible from the event details page (e.g., a button “Open Scanner”). Use a lightweight JavaScript library – do not require a native app.
- **Testing:** Write basic feature tests for CSV import and check‑in flow.

---

This summary gives a complete picture of what needs to be built. The developer can use the previous detailed step‑by‑step guide for implementation. If any clarification is needed, refer to the original feature list and database design above.