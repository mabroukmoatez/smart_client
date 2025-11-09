# Complete File Structure

This document outlines the complete file structure of the WhatsApp Automation Platform.

## Root Files

```
.
├── composer.json                 # PHP dependencies (Laravel 11, Maatwebsite Excel, etc.)
├── .env.example                  # Environment configuration template
├── README.md                     # Main documentation
├── HIGHLEVEL_API_EXAMPLE.md      # HighLevel API integration examples
├── PHONE_NORMALIZATION_EXAMPLES.md  # Phone normalization guide
└── FILE_STRUCTURE.md             # This file
```

## Application Directory (`app/`)

### Controllers (`app/Http/Controllers/`)
```
app/Http/Controllers/
├── AutomationController.php      # Campaign creation and management
├── DashboardController.php       # Dashboard statistics
└── FileUploadController.php      # File upload and column mapping
```

### Middleware (`app/Http/Middleware/`)
```
app/Http/Middleware/
└── RateLimitMiddleware.php       # Rate limiting for automation endpoints
```

### Form Requests (`app/Http/Requests/`)
```
app/Http/Requests/
├── StoreAutomationCampaignRequest.php  # Campaign validation
└── StoreUploadedFileRequest.php        # File upload validation
```

### Jobs (`app/Jobs/`)
```
app/Jobs/
├── ProcessCampaignJob.php        # Main campaign processing job
└── SendWhatsAppMessageJob.php    # Individual message sending job
```

### Models (`app/Models/`)
```
app/Models/
├── AutomationCampaign.php        # Campaign model with statistics
├── MessageLog.php                # Message sending logs
├── UploadedFile.php              # Uploaded files metadata
└── User.php                      # User authentication model
```

### Services (`app/Services/`)
```
app/Services/
├── FileProcessingService.php     # Excel/CSV processing and conversion
├── HighLevelApiService.php       # HighLevel API integration
└── PhoneNormalizationService.php # UAE phone number normalization
```

### Providers (`app/Providers/`)
```
app/Providers/
└── AppServiceProvider.php        # Rate limiting configuration
```

## Configuration (`config/`)

```
config/
└── services.php                  # HighLevel API configuration
```

## Database (`database/`)

### Migrations (`database/migrations/`)
```
database/migrations/
├── 2024_01_01_000001_create_uploaded_files_table.php
├── 2024_01_01_000002_create_automation_campaigns_table.php
├── 2024_01_01_000003_create_message_logs_table.php
└── 2024_01_01_000004_create_jobs_table.php
```

## Routes (`routes/`)

```
routes/
├── web.php                       # Main web routes
└── auth.php                      # Authentication routes (Laravel Breeze)
```

## Resources (`resources/`)

### Views (`resources/views/`)

```
resources/views/
├── dashboard.blade.php           # Main dashboard with statistics
├── automation/
│   ├── create.blade.php          # Create new campaign form
│   ├── index.blade.php           # List all campaigns
│   └── show.blade.php            # Campaign details and progress
└── files/
    ├── index.blade.php           # List uploaded files
    ├── map-columns.blade.php     # Column mapping interface
    ├── preview.blade.php         # File preview (not created yet, optional)
    └── upload.blade.php          # File upload form
```

## Key Features by File

### 1. File Upload Flow

**Upload File** → **Map Columns** → **Save to Database**

- `FileUploadController@create` - Show upload form
- `FileUploadController@upload` - Process file upload
- `FileUploadController@mapColumns` - Show column mapping
- `FileUploadController@store` - Save file metadata

Files:
- `resources/views/files/upload.blade.php`
- `resources/views/files/map-columns.blade.php`
- `app/Services/FileProcessingService.php`

### 2. Phone Normalization

**Read CSV** → **Normalize Phones** → **Validate**

- `PhoneNormalizationService@normalize` - Convert to +971 format
- `PhoneNormalizationService@isValid` - Validate format
- `PhoneNormalizationService@normalizeArray` - Bulk normalization

File:
- `app/Services/PhoneNormalizationService.php`

### 3. Campaign Creation

**Select Files** → **Choose Template** → **Schedule** → **Create**

- `AutomationController@create` - Show form
- `AutomationController@calculateStats` - Calculate statistics
- `AutomationController@store` - Create campaign and message logs

Files:
- `resources/views/automation/create.blade.php`
- `app/Http/Controllers/AutomationController.php`

### 4. Message Sending

**Campaign Scheduled** → **Process Job** → **Send Messages** → **Update Stats**

- `ProcessCampaignJob` - Main campaign processor
- `SendWhatsAppMessageJob` - Individual message sender
- `HighLevelApiService@sendTemplateMessage` - API integration

Files:
- `app/Jobs/ProcessCampaignJob.php`
- `app/Jobs/SendWhatsAppMessageJob.php`
- `app/Services/HighLevelApiService.php`

### 5. HighLevel API Integration

**Get Templates** → **Create Contact** → **Send Message** → **Check Status**

- `HighLevelApiService@getWhatsAppTemplates`
- `HighLevelApiService@getOrCreateContact`
- `HighLevelApiService@sendTemplateMessage`
- `HighLevelApiService@getMessageStatus`

File:
- `app/Services/HighLevelApiService.php`

## Database Schema Overview

### Tables

1. **users** (Laravel default)
   - User authentication

2. **uploaded_files**
   - File metadata
   - Column mapping
   - Row counts

3. **automation_campaigns**
   - Campaign details
   - Template information
   - Statistics (sent, failed, pending)
   - Status tracking

4. **message_logs**
   - Individual message records
   - Send status
   - Retry information
   - API responses

5. **jobs**
   - Queue jobs
   - Failed jobs tracking

## Environment Variables

### Required Configuration

```env
# Database
DB_CONNECTION=mysql
DB_DATABASE=whatsapp_automation

# HighLevel API
HIGHLEVEL_API_URL=https://services.leadconnectorhq.com
HIGHLEVEL_API_TOKEN=your_token_here
HIGHLEVEL_LOCATION_ID=your_location_id

# Queue
QUEUE_CONNECTION=database

# Upload Limits
MAX_UPLOAD_SIZE=10240
MAX_CSV_ROWS=50000
```

## Installation Order

1. ✅ Install dependencies (`composer install`)
2. ✅ Configure environment (`.env`)
3. ✅ Generate app key (`php artisan key:generate`)
4. ✅ Run migrations (`php artisan migrate`)
5. ✅ Link storage (`php artisan storage:link`)
6. ✅ Compile assets (`npm run build`)
7. ✅ Start queue worker (`php artisan queue:work`)
8. ✅ Start application (`php artisan serve`)

## Production Checklist

- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure Redis for queues
- [ ] Set up Supervisor for queue workers
- [ ] Configure proper database
- [ ] Set up cron for scheduler
- [ ] Run optimization commands
- [ ] Configure SSL/HTTPS
- [ ] Set up backups
- [ ] Monitor queue health
- [ ] Set up logging and error tracking

## Code Statistics

- **Controllers**: 3
- **Models**: 4
- **Services**: 3
- **Jobs**: 2
- **Migrations**: 4
- **Blade Views**: 7
- **Routes**: ~15
- **Middleware**: 1
- **Form Requests**: 2

## Technologies Used

- **Backend**: Laravel 11
- **Authentication**: Laravel Breeze
- **Database**: MySQL/PostgreSQL
- **Queue**: Database/Redis
- **Excel Processing**: Maatwebsite/Excel
- **API Client**: Guzzle HTTP
- **Frontend**: Blade Templates + Tailwind CSS
- **External API**: HighLevel (LeadConnector)

## Development Workflow

1. User registers/logs in
2. User uploads Excel/CSV file
3. System converts to CSV and extracts headers
4. User maps columns (phone, name)
5. System normalizes phone numbers
6. User creates campaign
7. User selects files and template
8. User schedules send time
9. System creates message logs
10. Queue job processes campaign
11. Individual jobs send messages
12. System tracks statistics
13. User monitors progress

## Security Features

- ✅ CSRF protection on all forms
- ✅ User authentication required
- ✅ Authorization checks on all resources
- ✅ File upload validation
- ✅ Rate limiting on API endpoints
- ✅ Input sanitization
- ✅ SQL injection prevention (Eloquent ORM)
- ✅ XSS protection (Blade escaping)

## Performance Optimizations

- Queue jobs for async processing
- Rate limiting to prevent abuse
- Batch message sending
- Database indexes on foreign keys
- Eager loading relationships
- Pagination on listings
- Storage symlink for efficient file serving

## Future Enhancements (Not Implemented)

- [ ] Multi-language support
- [ ] Advanced scheduling (recurring campaigns)
- [ ] A/B testing for templates
- [ ] Analytics dashboard
- [ ] Webhook support for message status
- [ ] Contact management
- [ ] Tag and segment management
- [ ] Export campaign reports
- [ ] Template preview before sending
- [ ] Bulk campaign actions

## Support Files

- `README.md` - Complete setup and usage guide
- `HIGHLEVEL_API_EXAMPLE.md` - API integration examples
- `PHONE_NORMALIZATION_EXAMPLES.md` - Phone normalization guide
- `FILE_STRUCTURE.md` - This file
