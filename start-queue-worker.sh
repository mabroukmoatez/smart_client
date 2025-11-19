#!/bin/bash

# Smart Client Queue Worker Startup Script
# This script starts the Laravel queue worker for processing contact imports

echo "🚀 Starting Smart Client Queue Worker..."
echo ""
echo "📋 Configuration:"
echo "   - Queue Driver: database"
echo "   - Max Tries: 3"
echo "   - Timeout: 3600 seconds (1 hour)"
echo ""
echo "⚠️  IMPORTANT: Keep this terminal window open!"
echo "   Press Ctrl+C to stop the worker"
echo ""
echo "Starting in 3 seconds..."
sleep 3

cd laragon/www/abar_client

# Start the queue worker with optimal settings
php artisan queue:work database \
    --verbose \
    --tries=3 \
    --timeout=3600 \
    --sleep=3 \
    --max-jobs=1000 \
    --max-time=3600

echo ""
echo "❌ Queue worker stopped"
