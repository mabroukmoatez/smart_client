# WhatsApp Automation Platform with HighLevel Integration

A comprehensive Laravel 11 web application for uploading spreadsheet files and automating WhatsApp template messages via HighLevel (LeadConnector) API.

## Features

### 1. Authentication
- User registration and login using Laravel Breeze
- Email verification
- Password reset functionality
- Secure dashboard access

### 2. File Upload & Processing
- Upload Excel (.xlsx, .xls) and CSV files
- Automatic Excel to CSV conversion
- Column mapping interface (phone and name columns)
- Phone number normalization to UAE format (+971)
- File metadata storage with row counts
- File preview functionality

### 3. Phone Normalization
- Automatic conversion to UAE format (+971)
- Handles various input formats:
  - `0501234567` → `+971501234567`
  - `501234567` → `+971501234567`
  - `971501234567` → `+971501234567`
  - `+971501234567` → `+971501234567` (no change)
- Validates phone number format
- Supports both mobile and landline numbers

### 4. WhatsApp Automation
- Select multiple uploaded files for campaigns
- Fetch WhatsApp templates from HighLevel API
- Schedule message sending (date/time picker)
- Real-time statistics:
  - Total phone numbers
  - Valid/invalid phone counts
  - Messages sent/failed/pending
  - Campaign progress and success rate
- Automatic retry mechanism for failed messages
- Detailed message logs

### 5. Queue System
- Laravel queue jobs for message scheduling
- Rate limiting (10 messages per minute by default)
- Retry logic with exponential backoff
- Background processing for scalability

### 6. Security & Validation
- File size limits (10MB default)
- Row count limits (50,000 default)
- Rate limiting on uploads and automation
- CSRF protection
- Input validation and sanitization
- User authorization checks

## Installation

### Requirements
- PHP 8.2+
- Composer
- MySQL/PostgreSQL
- Node.js & NPM (for asset compilation)
- Redis (optional, for queue driver)

### Step 1: Clone and Install Dependencies

```bash
# Clone the repository
git clone <repository-url>
cd whatsapp-automation

# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### Step 2: Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 3: Configure Database

Edit `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=whatsapp_automation
DB_USERNAME=root
DB_PASSWORD=your_password
```

Create the database:

```bash
mysql -u root -p
CREATE DATABASE whatsapp_automation;
exit;
```

### Step 4: Configure HighLevel API

Edit `.env` file with your HighLevel credentials:

```env
HIGHLEVEL_API_URL=https://services.leadconnectorhq.com
HIGHLEVEL_API_TOKEN=your_private_integration_token_here
HIGHLEVEL_API_VERSION=2021-07-28
HIGHLEVEL_LOCATION_ID=your_location_id_here
```

#### How to Get HighLevel API Token:

1. Log in to your HighLevel account
2. Go to **Settings** → **Private Integrations** (under "Other Settings")
3. Click **"Create new Integration"**
4. Give it a name (e.g., "WhatsApp Automation")
5. Select required scopes:
   - `contacts.readonly`
   - `contacts.write`
   - `conversations.readonly`
   - `conversations.write`
   - `conversations/message.readonly`
   - `conversations/message.write`
6. Copy the generated token and paste it in your `.env` file

### Step 5: Configure Queue Driver

For production, use Redis or database queue:

```env
QUEUE_CONNECTION=database
# OR
QUEUE_CONNECTION=redis
```

For Redis:
```env
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### Step 6: Run Migrations

```bash
php artisan migrate
```

### Step 7: Configure File Upload Limits

Edit `.env`:

```env
# Maximum file size in KB (10MB = 10240KB)
MAX_UPLOAD_SIZE=10240

# Maximum CSV rows
MAX_CSV_ROWS=50000
```

Also update `php.ini` if needed:

```ini
upload_max_filesize = 10M
post_max_size = 10M
```

### Step 8: Create Storage Symlink

```bash
php artisan storage:link
```

### Step 9: Compile Assets

```bash
npm run build
# OR for development
npm run dev
```

### Step 10: Start Queue Worker

In a separate terminal, start the queue worker:

```bash
php artisan queue:work --tries=3 --timeout=120
```

For production, use Supervisor to manage queue workers.

### Step 11: Start the Application

```bash
php artisan serve
```

Visit http://localhost:8000

## Usage

### 1. Register/Login
- Navigate to `/register` to create an account
- Or login at `/login` if you already have an account

### 2. Upload Files

1. Go to **Files** → **Upload File**
2. Select your Excel or CSV file
3. Click **Next: Map Columns**
4. Select which column contains phone numbers
5. Optionally select which column contains names
6. Add notes if needed
7. Click **Save File**

### 3. Create Campaign

1. Go to **Automation** → **Create Campaign**
2. Enter campaign name and description
3. Select one or more uploaded files
4. Choose a WhatsApp template from the dropdown
5. Schedule the date and time
6. Review statistics (total recipients, valid/invalid phones)
7. Click **Create Campaign**

### 4. Monitor Campaign

1. Go to **Automation** to see all campaigns
2. Click on a campaign to view details:
   - Current status
   - Statistics (sent, failed, pending)
   - Progress bar
   - Success rate
   - Recent message logs

## API Integration

### HighLevel API Example

#### Send WhatsApp Template Message

```php
use App\Services\HighLevelApiService;

$highLevelApi = app(HighLevelApiService::class);

// Send message
$response = $highLevelApi->sendTemplateMessage(
    phone: '+971501234567',
    templateId: 'template_id_here',
    templateData: [
        'name' => 'John Doe',
        // Add other template variables as needed
    ]
);

// Response structure
[
    'messageId' => 'msg_xxxxx',
    'conversationId' => 'conv_xxxxx',
    'contactId' => 'contact_xxxxx',
    // ... other fields
]
```

#### Get WhatsApp Templates

```php
$templates = $highLevelApi->getWhatsAppTemplates();

foreach ($templates as $template) {
    echo $template['id'];
    echo $template['name'];
    echo $template['status'];
}
```

### Phone Normalization Example

```php
use App\Services\PhoneNormalizationService;

$phoneService = app(PhoneNormalizationService::class);

// Normalize single phone
$normalized = $phoneService->normalize('0501234567');
// Result: +971501234567

// Normalize array of phones
$phones = ['0501234567', '971502345678', '+971503456789'];
$normalized = $phoneService->normalizeArray($phones);
// Result: ['+971501234567', '+971502345678', '+971503456789']

// Validate phone
$isValid = $phoneService->isValid('0501234567');
// Result: true

// Format for display
$formatted = $phoneService->formatForDisplay('+971501234567');
// Result: +971 50 123 4567
```

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AutomationController.php
│   │   ├── DashboardController.php
│   │   └── FileUploadController.php
│   ├── Middleware/
│   │   └── RateLimitMiddleware.php
│   └── Requests/
│       ├── StoreAutomationCampaignRequest.php
│       └── StoreUploadedFileRequest.php
├── Jobs/
│   ├── ProcessCampaignJob.php
│   └── SendWhatsAppMessageJob.php
├── Models/
│   ├── AutomationCampaign.php
│   ├── MessageLog.php
│   ├── UploadedFile.php
│   └── User.php
└── Services/
    ├── FileProcessingService.php
    ├── HighLevelApiService.php
    └── PhoneNormalizationService.php

database/
└── migrations/
    ├── 2024_01_01_000001_create_uploaded_files_table.php
    ├── 2024_01_01_000002_create_automation_campaigns_table.php
    ├── 2024_01_01_000003_create_message_logs_table.php
    └── 2024_01_01_000004_create_jobs_table.php

resources/
└── views/
    ├── automation/
    │   ├── create.blade.php
    │   ├── index.blade.php
    │   └── show.blade.php
    ├── files/
    │   ├── index.blade.php
    │   ├── map-columns.blade.php
    │   ├── preview.blade.php
    │   └── upload.blade.php
    └── dashboard.blade.php

routes/
├── auth.php
└── web.php
```

## Database Schema

### uploaded_files
- `id`: Primary key
- `user_id`: Foreign key to users
- `original_filename`: Original file name
- `original_file_path`: Path to original file
- `converted_csv_path`: Path to converted CSV
- `row_count`: Number of rows
- `column_mapping`: JSON (phone_column, name_column)
- Timestamps and soft deletes

### automation_campaigns
- `id`: Primary key
- `user_id`: Foreign key to users
- `name`: Campaign name
- `template_id`: WhatsApp template ID
- `selected_file_ids`: JSON array of file IDs
- `scheduled_at`: When to send
- `status`: draft|scheduled|processing|completed|failed|cancelled
- `total_recipients`, `total_sent`, `total_failed`, `total_pending`
- Timestamps and soft deletes

### message_logs
- `id`: Primary key
- `campaign_id`: Foreign key to campaigns
- `uploaded_file_id`: Foreign key to files
- `recipient_phone`: Normalized phone number
- `recipient_name`: Contact name (optional)
- `template_id`: Template used
- `highlevel_message_id`: HighLevel message ID
- `status`: pending|sent|failed
- `error_message`: Error if failed
- `retry_count`: Number of retries
- Timestamps

## Queue Jobs

### ProcessCampaignJob
- Triggered when campaign is scheduled
- Dispatches individual SendWhatsAppMessageJob for each recipient
- Implements rate limiting (6 seconds between messages)

### SendWhatsAppMessageJob
- Sends individual WhatsApp message via HighLevel API
- Retries up to 3 times with 60-second backoff
- Updates message log and campaign statistics
- Handles errors gracefully

## Rate Limiting

- **File Uploads**: 50 uploads per hour per user
- **Automation Endpoints**: 60 requests per minute per user
- **Message Sending**: 10 messages per minute (configurable)

## Testing HighLevel Connection

```php
php artisan tinker

$api = app(\App\Services\HighLevelApiService::class);
$api->testConnection(); // Should return true if configured correctly
```

## Production Deployment

### 1. Supervisor Configuration

Create `/etc/supervisor/conf.d/whatsapp-automation.conf`:

```ini
[program:whatsapp-automation-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/your/app/artisan queue:work --tries=3 --timeout=120
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=3
redirect_stderr=true
stdout_logfile=/path/to/your/app/storage/logs/worker.log
stopwaitsecs=3600
```

Start supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start whatsapp-automation-worker:*
```

### 2. Cron Job (Optional)

Add to crontab for scheduled tasks:

```bash
* * * * * cd /path/to/your/app && php artisan schedule:run >> /dev/null 2>&1
```

### 3. Optimize for Production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

## Troubleshooting

### Issue: Queue jobs not processing
**Solution**: Make sure queue worker is running: `php artisan queue:work`

### Issue: File upload fails
**Solution**:
- Check file size limits in `php.ini`
- Verify storage directory permissions: `chmod -R 775 storage`
- Check `.env` MAX_UPLOAD_SIZE setting

### Issue: HighLevel API errors
**Solution**:
- Verify API token is correct
- Check token has required scopes
- Ensure Location ID is correct
- Test connection: `$api->testConnection()`

### Issue: Phone normalization not working
**Solution**:
- Ensure phone numbers in CSV are in a valid format
- Check PhoneNormalizationService validation rules
- Preview file to see normalized phones

## Support

For issues, please check:
1. Laravel logs: `storage/logs/laravel.log`
2. Queue failed jobs: `php artisan queue:failed`
3. HighLevel API documentation: https://highlevel.stoplight.io/

## License

MIT License

## Credits

Built with:
- Laravel 11
- Laravel Breeze
- Maatwebsite Excel
- Tailwind CSS
- HighLevel API (LeadConnector)
