# Contact Import System - Migration Guide

## Overview

The platform has been transformed from a **WhatsApp Automation System** to a **Contact Import & Tagging System** for HighLevel CRM.

## What Changed

### Old System (WhatsApp Automation)
- Upload files
- Map columns (phone, name)
- Select WhatsApp template
- Schedule campaign
- Send WhatsApp messages via HighLevel

### New System (Contact Import)
- Upload files ✅ (same)
- Map columns (phone, name, etc.) ✅ (enhanced)
- **Select/Create tags** 🆕
- **Import contacts to HighLevel** 🆕
- Track import progress

## Database Changes

### Tables Renamed

| Old Table Name | New Table Name |
|----------------|----------------|
| `automation_campaigns` | `contact_import_jobs` |
| `message_logs` | `contact_import_logs` |

### Schema Changes

**contact_import_jobs table:**
- ❌ Removed: `template_id`, `template_name`
- ✅ Added: `selected_tags` (JSON), `new_tags` (JSON)
- 🔄 Renamed: `total_recipients` → `total_contacts`
- 🔄 Renamed: `total_sent` → `total_imported`
- 🔄 Updated: `status` enum (removed 'scheduled', changed to 'pending')

**contact_import_logs table:**
- 🔄 Renamed: `campaign_id` → `import_job_id`
- 🔄 Renamed: `recipient_phone` → `contact_phone`
- 🔄 Renamed: `recipient_name` → `contact_name`
- 🔄 Renamed: `sent_at` → `imported_at`
- ❌ Removed: `template_id`, `message_content`, `highlevel_message_id`
- ✅ Added: `highlevel_contact_id`, `contact_data` (JSON), `assigned_tags` (JSON)

## New Features

### 1. Tag Management
- Fetch existing tags from HighLevel
- Create new tags on-the-fly
- Apply multiple tags to imported contacts
- Tags persist in HighLevel CRM

### 2. Contact Upsert
- Creates new contacts if they don't exist
- Updates existing contacts if found (by phone/email)
- Automatically adds selected tags
- Maintains data integrity

### 3. Bulk Import Processing
- Background job processing with queue system
- Retry logic for failed imports
- Detailed logging per contact
- Progress tracking

## New Models

### ContactImportJob
```php
// Relationships
$job->user;              // User who created the import
$job->contactLogs;       // Individual import logs
$job->uploadedFiles();   // Selected files for import

// Attributes
$job->all_tags;          // Combined selected + new tags
$job->completion_percentage;
$job->success_rate;

// Status Methods
$job->isPending();
$job->isProcessing();
$job->isCompleted();
$job->isFailed();
```

### ContactImportLog
```php
// Relationships
$log->importJob;         // Parent import job
$log->uploadedFile;      // Source file

// Data
$log->contact_phone;     // Normalized phone
$log->contact_name;      // Contact name
$log->highlevel_contact_id; // HighLevel CRM contact ID
$log->contact_data;      // Full contact info (JSON)
$log->assigned_tags;     // Tags applied (JSON)
$log->api_response;      // HighLevel API response (JSON)

// Status
$log->isImported();
$log->isFailed();
$log->canRetry();
```

## API Service Extensions

### New HighLevelApiService Methods

```php
// Tags
$api->getTags($apiToken, $locationId);
$api->createTag($tagName, $apiToken, $locationId);

// Contacts
$api->upsertContact($contactData, $tags, $apiToken, $locationId);
$api->addTagsToContact($contactId, $tags, $apiToken);
```

## Migration Steps

### Step 1: Run Database Migrations

```bash
php artisan migrate
```

This will:
1. Rename tables
2. Transform columns
3. Add new fields
4. Update indexes

### Step 2: Update Routes (Pending)

Routes will be updated from:
```php
/automation/* → /contact-imports/*
```

### Step 3: Update Navigation (Pending)

Navigation links will change from:
- "Campaigns" → "Contact Imports"
- "Create Campaign" → "Import Contacts"

### Step 4: Update UI Views (Pending)

Views will be transformed:
- `automation/index.blade.php` → `contact-imports/index.blade.php`
- `automation/create.blade.php` → `contact-imports/create.blade.php`
- `automation/show.blade.php` → `contact-imports/show.blade.php`

## New Workflow

### User Journey

1. **Upload File**
   - Navigate to "Files" → "Upload New File"
   - Upload Excel/CSV with contact data
   - Map columns (phone required, name optional)

2. **Create Import Job**
   - Navigate to "Contact Imports" → "Import Contacts"
   - Select uploaded file(s)
   - **Select existing tags** (multi-select dropdown)
   - **Add new tags** (comma-separated input)
   - Click "Import Contacts"

3. **Processing**
   - Background job processes each contact
   - Creates/updates contact in HighLevel
   - Applies selected tags
   - Logs success/failure

4. **Track Progress**
   - View import job status
   - See completion percentage
   - Review failed imports
   - Download error logs

## Required OAuth Scopes

Make sure your HighLevel Private Integration has these scopes:

- ✅ `contacts.readonly` - Read contact data
- ✅ `contacts.write` - Create/update contacts
- ✅ `locations.readonly` - Read location info
- ✅ `locations/tags.readonly` - Read tags
- ✅ `locations/tags.write` - Create tags (if creating new tags)

## Backward Compatibility

### Old Data
- Existing `automation_campaigns` will be migrated to `contact_import_jobs`
- Existing `message_logs` will be migrated to `contact_import_logs`
- WhatsApp-specific columns will be removed (data loss for template info)

### Old Code
- `AutomationCampaign` model is deprecated
- `MessageLog` model is deprecated
- Use `ContactImportJob` and `ContactImportLog` instead

## Next Implementation Steps

1. ✅ Database migrations
2. ✅ New models
3. ✅ API service extensions
4. ⏳ ContactImportController
5. ⏳ ImportContactsJob
6. ⏳ UI views
7. ⏳ Routes update
8. ⏳ Navigation update
9. ⏳ Testing

## Testing Checklist

- [ ] Upload file and map columns
- [ ] Fetch tags from HighLevel
- [ ] Create new tag in HighLevel
- [ ] Create import job with tags
- [ ] Process import in background
- [ ] Verify contacts created in HighLevel
- [ ] Verify tags applied correctly
- [ ] Test error handling
- [ ] Test retry logic
- [ ] View import job progress
- [ ] Export failed imports

## Support

For questions or issues:
1. Check HighLevel API documentation: https://highlevel.stoplight.io
2. Review logs in `storage/logs/laravel.log`
3. Check import job status in database
4. Verify HighLevel OAuth scopes in Settings
