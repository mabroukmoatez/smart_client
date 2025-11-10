# Contact Import Queue Fix Guide

## 🔴 CRITICAL ISSUES FOUND

### 1. **Queue Worker Not Running**
Your contact imports are stuck in "pending" because **no queue worker is running** to process them.

### 2. **Database Not Configured**
The `.env` file was missing, and the database connection needs to be properly configured.

### 3. **Tags Not Being Created**
Tags are handled correctly in the code, but jobs never run to create them.

---

## ✅ COMPLETE FIX STEPS

### Step 1: Configure Database Connection

Edit `/home/user/smart_client/.env` and update these lines:

```bash
# For MySQL (recommended for production)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

# For PostgreSQL (if you prefer)
# DB_CONNECTION=pgsql
# DB_HOST=127.0.0.1
# DB_PORT=5432
# DB_DATABASE=your_database_name
# DB_USERNAME=your_username
# DB_PASSWORD=your_password
```

**Make sure your database server is running!**

### Step 2: Run Migrations

```bash
cd /home/user/smart_client
php artisan migrate
```

This creates all necessary tables including:
- `jobs` (for queued jobs)
- `contact_import_jobs` (for import tracking)
- `contact_import_logs` (for individual contact results)

### Step 3: Verify Queue Configuration

Check that queue is set to database in `.env`:
```bash
QUEUE_CONNECTION=database
```

### Step 4: Start the Queue Worker

**This is the most important step!**

Run this command and **keep it running**:

```bash
php artisan queue:work database --verbose --tries=3 --timeout=3600
```

**Options explained:**
- `database` - Use database queue driver
- `--verbose` - Show detailed output for debugging
- `--tries=3` - Retry failed jobs up to 3 times
- `--timeout=3600` - Jobs can run for up to 1 hour

**✨ You should see output like:**
```
[2025-11-10 01:30:00][1] Processing: App\Jobs\ProcessContactImportJob
[2025-11-10 01:30:15][1] Processed:  App\Jobs\ProcessContactImportJob
```

---

## 🚀 FOR PRODUCTION: Run Queue Worker as Background Service

### Option A: Using Supervisor (Recommended)

1. Install supervisor:
```bash
sudo apt-get install supervisor
```

2. Create config file `/etc/supervisor/conf.d/smart_client-worker.conf`:
```ini
[program:smart_client-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /home/user/smart_client/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/home/user/smart_client/storage/logs/worker.log
stopwaitsecs=3600
```

3. Start supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start smart_client-worker:*
```

### Option B: Using systemd

1. Create `/etc/systemd/system/smart_client-queue.service`:
```ini
[Unit]
Description=Smart Client Queue Worker
After=network.target

[Service]
Type=simple
User=www-data
WorkingDirectory=/home/user/smart_client
ExecStart=/usr/bin/php /home/user/smart_client/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
Restart=always
RestartSec=5

[Install]
WantedBy=multi-user.target
```

2. Enable and start:
```bash
sudo systemctl enable smart_client-queue
sudo systemctl start smart_client-queue
sudo systemctl status smart_client-queue
```

---

## 🧪 TESTING

### 1. Check Queue Status
```bash
# See pending jobs
php artisan queue:monitor database

# Or check database directly
php artisan tinker --execute="echo 'Jobs in queue: ' . \DB::table('jobs')->count();"
```

### 2. Check Contact Import Jobs
```bash
php artisan tinker --execute="\App\Models\ContactImportJob::all(['id', 'name', 'status', 'total_contacts', 'total_imported'])->each(fn(\$j) => echo \$j->id . ': ' . \$j->name . ' - ' . \$j->status . ' (' . \$j->total_imported . '/' . \$j->total_contacts . ')' . PHP_EOL);"
```

### 3. Test Contact Import Flow

1. Upload a CSV file with contacts
2. Go to Contact Import page
3. Select file and tags
4. Create import
5. Watch queue worker terminal - you should see:
   ```
   Contact Import: Starting
   Contact Import: File read complete
   Contact Import: Processing row
   Contact Import: Contact processed
   Contact Import: Completed
   ```

---

## 🐛 TROUBLESHOOTING

### Jobs Stay in "Pending"
**Problem:** Queue worker not running
**Solution:** Run `php artisan queue:work database --verbose` and keep it running

### "Connection refused" Error
**Problem:** Database not running or wrong credentials
**Solution:**
1. Start your database server
2. Verify credentials in `.env` file
3. Test connection: `php artisan tinker --execute="DB::connection()->getPdo();"`

### Tags Not Created
**Problem:** Jobs weren't running to create tags
**Solution:** Once queue worker is running, tags will be sent to HighLevel API automatically

### Jobs Fail Immediately
**Problem:** HighLevel API credentials missing or invalid
**Solution:**
1. Go to Settings page
2. Enter valid HighLevel API credentials
3. Save and test connection

### "could not find driver" Error
**Problem:** PHP database extension not installed
**Solution:** Install required extension:
```bash
# For MySQL
sudo apt-get install php-mysql php-pdo

# For PostgreSQL
sudo apt-get install php-pgsql

# Restart PHP-FPM
sudo systemctl restart php-fpm
```

---

## 📋 QUICK CHECKLIST

- [ ] Database server is running
- [ ] `.env` file exists with correct database credentials
- [ ] Migrations have been run (`php artisan migrate`)
- [ ] Queue connection is set to `database` in `.env`
- [ ] Queue worker is running (`php artisan queue:work database --verbose`)
- [ ] HighLevel API credentials are configured in Settings
- [ ] File uploaded successfully
- [ ] Contact import job created
- [ ] Worker processes the job and updates status to "completed"

---

## 📊 MONITORING

### View Logs
```bash
# Application logs
tail -f /home/user/smart_client/storage/logs/laravel.log

# Queue worker logs (if using supervisor)
tail -f /home/user/smart_client/storage/logs/worker.log
```

### Check Failed Jobs
```bash
# View failed jobs table
php artisan queue:failed

# Retry failed job
php artisan queue:retry <job-id>

# Retry all failed jobs
php artisan queue:retry all
```

---

## 🎯 SUMMARY

The contact import system works like this:

1. **User creates import** → ContactImportJob record created with status="pending"
2. **Job dispatched** → ProcessContactImportJob added to `jobs` table
3. **Queue worker picks it up** → Changes status to "processing"
4. **Processes each contact** → Calls HighLevel API, creates tags, logs results
5. **Updates status** → Changes to "completed" when done

**The missing link was Step 3** - the queue worker wasn't running!

Now that you have the queue worker running, everything will work as expected.
