# 🚀 MySQL Configuration Guide

I've updated your `.env` file to use MySQL. Now follow these steps:

## Step 1: Create the Database

Run these commands in your MySQL terminal:

```sql
-- Connect to MySQL
mysql -u root -p

-- In MySQL console:
CREATE DATABASE IF NOT EXISTS smart_client CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Optional: Create dedicated user (recommended for production)
CREATE USER IF NOT EXISTS 'smart_client'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON smart_client.* TO 'smart_client'@'localhost';
FLUSH PRIVILEGES;

-- Verify database created
SHOW DATABASES LIKE 'smart_client';

EXIT;
```

## Step 2: Update .env Credentials

I've already updated your `.env` file to:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smart_client
DB_USERNAME=root
DB_PASSWORD=           ← ADD YOUR PASSWORD HERE!
```

**IMPORTANT:** Add your MySQL root password to `.env`:
```bash
# Edit .env file
nano /home/user/smart_client/.env

# Update this line:
DB_PASSWORD=your_mysql_password
```

If you created a dedicated user, use those credentials instead:
```bash
DB_USERNAME=smart_client
DB_PASSWORD=your_secure_password
```

## Step 3: Clear Config Cache

```bash
cd /home/user/smart_client
php artisan config:clear
php artisan cache:clear
```

## Step 4: Test Database Connection

```bash
php artisan tinker --execute="try { DB::connection()->getPdo(); echo '✓ MySQL Connected!' . PHP_EOL; } catch (Exception \$e) { echo '✗ Connection failed: ' . \$e->getMessage() . PHP_EOL; }"
```

You should see: `✓ MySQL Connected!`

## Step 5: Run Migrations

```bash
php artisan migrate
```

This creates all tables:
- `jobs` (for queue system)
- `contact_import_jobs`
- `contact_import_logs`
- `users`
- `uploaded_files`
- All other tables

## Step 6: Verify Tables Created

```bash
php artisan tinker --execute="echo 'Tables created: ' . count(Schema::getTableNames()) . PHP_EOL; echo implode(', ', Schema::getTableNames()) . PHP_EOL;"
```

## Step 7: Start Queue Worker

```bash
./start-queue-worker.sh
```

**Keep this terminal open!** This processes your contact imports.

## Step 8: Test Contact Import

1. Go to your application
2. Upload a CSV file
3. Create a new contact import with tags
4. Watch the queue worker terminal for real-time processing
5. Check `contact_import_jobs` table - status should go from "pending" → "processing" → "completed"
6. Check `contact_import_logs` table for individual contact results

---

## 🔍 Verify Setup

Check everything is working:

```bash
# 1. Database connection
php artisan tinker --execute="DB::connection()->getPdo(); echo '✓ DB Connected' . PHP_EOL;"

# 2. Tables exist
php artisan tinker --execute="echo 'Jobs table: ' . (Schema::hasTable('jobs') ? '✓' : '✗') . PHP_EOL;"

# 3. Queue configuration
php artisan tinker --execute="echo 'Queue driver: ' . config('queue.default') . PHP_EOL;"

# 4. Count existing imports
php artisan tinker --execute="echo 'Import jobs: ' . \App\Models\ContactImportJob::count() . PHP_EOL;"
```

---

## 🐛 Troubleshooting

### "Access denied for user"
**Problem:** Wrong password in `.env`
**Solution:** Update `DB_PASSWORD` in `.env` with correct MySQL password

### "Unknown database 'smart_client'"
**Problem:** Database not created
**Solution:** Run the CREATE DATABASE command from Step 1

### "SQLSTATE[HY000] [2002] Connection refused"
**Problem:** MySQL server not running
**Solution:**
```bash
# Start MySQL
sudo systemctl start mysql
# or
sudo service mysql start
```

### Jobs still not processing
**Problem:** Queue worker not running
**Solution:** Make sure `./start-queue-worker.sh` is running in a terminal

---

## 📊 Expected Results After Setup

When you create a contact import, you should see in the queue worker:

```
[2025-11-10 12:00:00][1] Processing: App\Jobs\ProcessContactImportJob
Contact Import: Starting
Contact Import: File read complete
Contact Import: Processing row
Contact Import: Contact processed
Contact Import: Contact processed
Contact Import: Contact processed
Contact Import: Completed
[2025-11-10 12:00:15][1] Processed:  App\Jobs\ProcessContactImportJob
```

And in the database:
```
contact_import_jobs:
- status: completed ✓
- total_imported: 3 ✓
- total_failed: 0 ✓
- total_pending: 0 ✓
- started_at: 12:00:00
- completed_at: 12:00:15 (15 seconds later)

jobs table:
- Empty (job completed and removed)

contact_import_logs:
- 3 rows with status='sent' and highlevel_contact_id
```

---

## 🎯 Summary

1. ✅ `.env` updated to use MySQL
2. ⏳ Create database: `CREATE DATABASE smart_client;`
3. ⏳ Add MySQL password to `.env`
4. ⏳ Run migrations: `php artisan migrate`
5. ⏳ Start queue worker: `./start-queue-worker.sh`
6. ⏳ Test contact import

After these steps, your contact imports will work perfectly!
