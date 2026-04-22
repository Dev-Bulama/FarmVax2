# FarmVax — Testing Guide

> Run these commands first on a fresh install, then follow each role's walkthrough below.

```bash
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link
```

---

## Test Accounts

| Role | Email | Password | Notes |
|------|-------|----------|-------|
| **Admin** | admin@farmvax.com | admin123 | Full platform access |
| **Farmer 1** | farmer1@farmvax.com | farmer123 | Kano state, has livestock |
| **Farmer 2** | farmer2@farmvax.com | farmer123 | Lagos state |
| **Farmer 3** | farmer3@farmvax.com | farmer123 | Kano state |
| **Professional 1** | professional1@farmvax.com | professional123 | Approved vet – Dr. Ahmed Suleiman |
| **Professional 2** | professional2@farmvax.com | professional123 | Approved vet – Dr. Ngozi Okafor |
| **Professional 3** | professional3@farmvax.com | professional123 | **Pending** approval – Dr. Yusuf Garba |
| **Volunteer 1** | volunteer1@farmvax.com | volunteer123 | Active – Aisha Bello |
| **Volunteer 2** | volunteer2@farmvax.com | volunteer123 | Active – Chinedu Obi |

---

## 1. Admin Testing

### 1.1 Log in
1. Go to `/login`
2. Enter **admin@farmvax.com** / **admin123**
3. You land on `/admin/dashboard`

### 1.2 Dashboard overview
- Check the stats cards (users, livestock, vaccinations)
- Click **Users** in the sidebar → see all registered users

### 1.3 Bulk User Import
1. Navigate to **Admin → User Import** (`/admin/import`)
2. Click **New Import**
3. Select user type: **Farmer**
4. Download the CSV template → fill in some rows → upload
5. On the mapping screen, assign columns (Name, Email, Phone) → click **Process Import**
6. View the import result — see imported users and email status
7. Test re-send email button on any row

**What was fixed:** Directory creation before file move; cryptographically random passwords; correct livestock column names.

### 1.4 Professional Approval
1. Navigate to **Admin → Professionals → Approvals** (`/admin/professionals/approvals`)
2. Find **Dr. Yusuf Garba** (pending) → click **View**
3. Click **Approve** → professional can now log in and accept calls

### 1.5 Telemedicine Monitor
1. Navigate to **Admin → Telemedicine** (`/admin/telemedicine`)
2. View all pending, in-progress, and completed consultations
3. Open any request → assign a professional if unassigned
4. Add admin notes → save

### 1.6 AI Disease Detection Monitor
1. Navigate to **Admin → Disease Detection** (`/admin/disease-detection`)
2. View all scans with confidence scores, urgency levels, user names
3. Click **View** on any scan for full detail (conditions, recommendations)

### 1.7 AI Chatbot Admin Panel
1. Navigate to **Admin → Chatbot** (`/admin/chatbot`)
2. See active conversations from users
3. Click any conversation → click **Take Over** to manually chat with the user
4. Click **Release** to hand back to AI
5. To enable AI responses: go to **Admin → Settings → AI Settings** and set the OpenAI API key (`ai_api_key`) and enable the chatbot (`ai_enabled = 1`)

**What was fixed:** `is_active` column missing in DB; `openai_api_key` vs `ai_api_key` key mismatch; graceful "not configured" message instead of 500 error.

### 1.8 Outbreak Alerts
1. Navigate to **Admin → Outbreak Alerts** (`/admin/outbreak-alerts`)
2. Create a new alert for a disease/region
3. Check that farmers in that region would receive it

---

## 2. Animal Health Professional Testing

### 2.1 Log in
1. Go to `/login`
2. Enter **professional1@farmvax.com** / **professional123**
3. You land on `/professional/dashboard`

> **Note:** professional3@farmvax.com is pending approval and will be redirected to a waiting page until an admin approves them.

### 2.2 Telemedicine — Accept & Join a Call
> First create a request as a Farmer (see Section 3.2), then come back here.

1. Navigate to **Telemedicine** in the sidebar (`/professional/telemedicine`)
2. Under **Pending Requests**, find the farmer's request
3. Click **Accept** — status changes to "Assigned"
4. Click **Join Call** — the custom video room opens
5. Camera/microphone permission dialog appears — click **Allow**
6. Wait for the farmer to also join; both sides see each other via WebRTC
7. Use the **Mute**, **Camera off**, controls at the bottom
8. Fill in **Consultation Notes** in the right sidebar
9. Click **End & Complete Consultation** — status becomes "Completed"

### 2.3 View history
1. Navigate to Telemedicine → scroll to **Completed** section
2. Click any completed call to see notes

---

## 3. Farmer Testing

### 3.1 Log in
1. Go to `/login`
2. Enter **farmer1@farmvax.com** / **farmer123**
3. You land on `/farmer/dashboard`

### 3.2 Add Livestock
1. Navigate to **Livestock** (`/farmer/livestock`)
2. Click **+ Add Animal**
3. Fill in: Animal Type, Quantity, Health Status
4. Click **Save** → redirected back to livestock list

### 3.3 Request a Telemedicine Consultation
1. Navigate to **Telemedicine** (`/farmer/telemedicine`)
2. Click **+ Request Video Consultation**
3. Fill in:
   - **Reason** (e.g., "My cattle are limping")
   - Optionally link a livestock record
   - Set **Priority** (Normal / Urgent)
4. Click **Submit Request** — request is created with status "Pending"
5. Wait for a professional to accept it (or use another browser tab/incognito with professional account)

### 3.4 Join the Video Call
1. After the professional accepts, the request status shows **Assigned**
2. Click **Join Call** — custom video room opens
3. Allow camera/microphone access
4. Your **local video preview** appears immediately
5. When the professional joins, their video appears; your video moves to a picture-in-picture corner
6. The status badge turns 🟢 **Connected**
7. Use **Mute / Camera** buttons; click the red **End Call** button to leave

**How WebRTC works (in-house, no third-party):**
- Your browser creates a cryptographic "offer" (SDP)
- Offer is stored in the FarmVax database
- The professional's browser polls every 1.5 seconds, picks up the offer, creates an "answer"
- Both sides exchange ICE candidates (network paths) via the database
- Once matched, video/audio flows **directly peer-to-peer** — no video data touches the FarmVax server

### 3.5 AI Disease Detection Scan
1. Navigate to **Disease Detection** (`/disease-detection`)
2. Click **+ New Scan**
3. Upload a photo of an animal (JPEG/PNG, max 10 MB)
4. Select animal type, optionally link a livestock record
5. Describe symptoms (optional)
6. Click **Analyse Now** — AI analysis runs (takes 5–15 seconds)
7. View results: confidence score, detected conditions, severity, recommendations
8. Use **Book Vet Call** button to create a telemedicine request from the scan page

**Requires:** `ANTHROPIC_API_KEY` in `.env` for AI analysis. Without it, the scan is marked "failed" with a clear error message.

### 3.6 AI Chatbot
1. Click the chat bubble (bottom-right on any page)
2. Type a message — the AI responds about livestock care, vaccination schedules, etc.
3. Type "I need a human" or "speak to someone" → triggers admin notification
4. **Requires** `ai_api_key` configured in Admin → Settings → AI

### 3.7 Vaccination Records
1. Navigate to **Vaccinations** (`/farmer/vaccinations`)
2. Click **+ Add Record** → fill in animal, vaccine, date
3. Upcoming reminders appear on the dashboard

### 3.8 Farm Records (3-step form)
1. Navigate to **Farm Records** (`/farmer/farm-records`)
2. Click through Step 1 (farm details), Step 2 (location), Step 3 (submit)

---

## 4. Environment Variables Required

Add these to your `.env` before testing AI features:

```env
# AI Disease Detection (Anthropic Claude)
ANTHROPIC_API_KEY=sk-ant-...
ANTHROPIC_MODEL=claude-sonnet-4-6

# AI Chatbot (configure via Admin → Settings → AI after login)
# ai_api_key = your OpenAI key  (stored in Settings table, not .env)
```

---

## 5. Telemedicine Quick Test (Two Browsers)

1. **Browser A** – log in as `farmer1@farmvax.com`
2. **Browser B** – log in as `professional1@farmvax.com`
3. Browser A: create a telemedicine request
4. Browser B: accept the request → Join Call
5. Browser A: refresh telemedicine list → Join Call
6. Both browsers should show each other's video within a few seconds

> **Tip:** Use Incognito / Private mode for the second browser so both sessions are independent.

---

## 6. Migration Safety

All migrations added use `Schema::hasTable()` and `Schema::hasColumn()` guards, meaning:
- Running `php artisan migrate` on an **existing database** will not drop or alter existing data
- Only missing tables/columns are added
- Re-running migrations is safe

```bash
# Safe to run on existing production database:
php artisan migrate
```
