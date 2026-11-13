# UNILAK Student Academic Forum System

An organized, centralized web forum built for the students and faculty of the **University of Lay Adventists of Kigali (UNILAK)** across its Kigali, Nyanza, and Rwamagana campuses. 

Designed to replace disorganized WhatsApp group chats with a structured academic repository where students can ask coursework questions, share lecture notes and past exam papers, vote on helpful answers, and receive official university communications.

---

## 🚀 Key Features

### 1. Authentication & Role-Based Access Control
- Student registration with **Full Name**, **UNILAK Student ID**, **Email**, **Department/Faculty**, and **Password**.
- Roles: `student`, `moderator`, `admin`.
- Role-based authorization via dedicated **Laravel Policies** (`PostPolicy`, `ReplyPolicy`, `ResourcePolicy`, `AnnouncementPolicy`).
- Automatic account suspension / ban enforcement via middleware.

### 2. Academic Categories & Boards
- Categorized by:
  - **Faculties & Departments** (Computing & Information Sciences only, for the current faculty-focused forum experience).
  - **Course Modules** (Web Development, Data Structures, Database Systems, Corporate Finance, Constitutional Law).
  - **Student Interests** (Internships & Careers, Campus Life & Student Union, Final Year Projects).
- Sorting tabs: **Latest Activity**, **Most Discussed**, **Top Voted**, **Unanswered**.

### 3. Discussions, Questions & Accepted Solutions
- Markdown/text questions with categorized tagging (`#Laravel`, `#PastPapers`, etc.).
- Threaded replies with inline editing and deletion for authors.
- **Accepted "Best Answer"** feature: Post owners can mark the definitive solution, highlighting it in green with a verified solution badge.
- Pinned topics (pinned by administrators/moderators to top of category feeds).
- Discussion locking (closes inactive or resolved threads to new replies).

### 4. Polymorphic Voting & Reputation System
- Upvote (+1) and downvote (-1) on both discussions and individual replies with auto-toggling and flipping.
- **Student Reputation Karma** computed dynamically:
  - Discussion upvote: **+5 points**
  - Reply upvote: **+10 points**
  - Accepted Best Answer: **+25 points**
- Dynamic reputation badges: *Freshman Member*, *Active Student*, *Top Contributor*, *Academic Scholar*.

### 5. Academic Resources Module
- Upload lecture slides, course outlines, past exam papers, assignments, and revision notes.
- Supported file types: `PDF`, `DOC/DOCX`, `PPT/PPTX`, `Images (PNG, JPG)`, `ZIP`, `TXT` (Max 10MB).
- Files securely hashed and stored in public disk storage.
- Download tracking counter on each resource.

### 6. Official Announcements Module
- Broadcast university-wide or department-specific communications.
- Pinned notices highlighted at the top of the feed and prominently on student dashboards.

### 7. Universal Campus Search & Bookmarks
- Instant search across **Discussions**, **Study Materials**, and **Students/Faculty**.
- One-click bookmarking / saved discussions list.

### 8. Moderation & Flagging System
- Students can report inappropriate content with violation reasons (*Cheating*, *Spam*, *Harassment*, *Misinformation*).
- Prevents duplicate reports from the same student.
- Full administration reports panel with one-click dismiss or permanent deletion.

### 9. Administration Suite (`/admin`)
- Platform statistics: Total Users, Discussions, Replies, Uploaded Materials, Pending Reports.
- **User Management**: Search, filter, promote/demote roles, ban/unban students, delete accounts.
- **Discussions Management**: Pin, lock, delete threads.
- **Category Management**: Create, edit, and color-code academic categories.
- **Reports Moderation**: Review reported discussions/replies and take action.

### 10. In-App Notifications
- Real-time in-app notifications sent when another student replies to your discussion.
- Notification bell in the navbar with unread count badge.
- Notifications center with "Mark All as Read" action.

---

## 🛠️ Technology Stack

- **Framework**: Laravel 13 (PHP 8.3+)
- **Database**: MySQL (via Laragon)
- **Styling**: Tailwind CSS v4 via `@tailwindcss/vite`
- **Design System**: 
  - Primary: UNILAK Deep Blue (`#1E3A5F`)
  - Accent / Highlights: Soft Orange (`#F97316`)
  - Font: Inter (Google Fonts)
  - Rounded cards, clean modern UI, fully mobile-responsive
- **JavaScript**: Alpine.js for lightweight modals, dropdowns, and inline editing

---

## 🔑 Default Seeded Accounts

| Role | Email | Password | Permissions |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin@unilak.ac.rw` | `Admin@1234` | Full access to Admin Suite, category management, announcements, pinning, moderation, banning |
| **Sample Student** | `student@unilak.ac.rw` | `Student@1234` | Full student forum access, asking questions, replying, voting, uploading materials, bookmarking |

---

## 💻 Installation & Setup Guide

### 1. Requirements
- PHP 8.3 or higher (with PDO, OpenSSL, Mbstring, Fileinfo extensions enabled)
- MySQL 8.0+ or MariaDB (via Laragon, XAMPP, or standalone)
- Composer 2.x
- Node.js 18+ and npm

### 2. Database Setup in Laragon
1. Start **Laragon** and click **"Start All"** (ensure MySQL is running on port `3306`).
2. Open HeidiSQL or MySQL terminal and create the database:
   ```sql
   CREATE DATABASE IF NOT EXISTS unilak_forum;
   ```
3. Ensure your `.env` has:
   ```ini
   APP_ENV=local
   APP_DEBUG=true
   APP_URL=http://unilak-forum.test

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=unilak_forum
   DB_USERNAME=root
   DB_PASSWORD=
   ```

### 3. Run Migrations & Seeders
Inside `c:\laragon\www\unilak-forum`:
```powershell
php artisan migrate:fresh --seed
```

### 4. Create Public Storage Symlink
Creates the link for uploaded lecture materials and student profile photos:
```powershell
php artisan storage:link
```

### 5. Build Frontend Assets (Tailwind & Vite)
```powershell
npm install
npm run build
```
*(For active frontend development with hot-reload, run `npm run dev`)*

---

## 📂 Project Structure Highlights

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── AdminDashboardController.php
│   │   │   ├── AdminUserController.php
│   │   │   ├── AdminPostController.php
│   │   │   ├── AdminCategoryController.php
│   │   │   └── AdminReportController.php
│   │   ├── Auth/
│   │   ├── CategoryController.php
│   │   ├── PostController.php
│   │   ├── ReplyController.php
│   │   ├── ResourceController.php
│   │   ├── AnnouncementController.php
│   │   ├── SearchController.php
│   │   ├── ProfileController.php
│   │   ├── VoteController.php
│   │   ├── ReportController.php
│   │   ├── SavedPostController.php
│   │   └── NotificationController.php
│   └── Middleware/
│       ├── EnsureUserIsAdmin.php
│       └── EnsureUserIsNotBanned.php
├── Models/
│   ├── User.php
│   ├── Category.php
│   ├── Post.php
│   ├── Reply.php
│   ├── Resource.php
│   ├── Announcement.php
│   ├── Vote.php
│   ├── Report.php
│   └── Tag.php
└── Policies/
    ├── PostPolicy.php
    ├── ReplyPolicy.php
    ├── ResourcePolicy.php
    └── AnnouncementPolicy.php
```

---

## 🛡️ Security Measures
- **Banned User Interception**: Middleware logs out and rejects suspended accounts immediately.
- **CSRF Token Verification**: Every form action includes `@csrf`.
- **Escaped Output**: All dynamic data rendered using `{{ }}` to prevent XSS.
- **Form Request Validation**: Whitelist validation with file type restrictions (`pdf, doc, docx, ppt, pptx, png, jpg, jpeg, zip`).
- **Cryptographic File Storage**: Uploaded files renamed with random 32-character hashes.
- **Brute Force Protection**: Rate-limiting on login (5 attempts per minute).
- **Authorization Policies**: Role and ownership checks on all updates and deletions.

---

*Developed for the academic community of the University of Lay Adventists of Kigali (UNILAK).*
