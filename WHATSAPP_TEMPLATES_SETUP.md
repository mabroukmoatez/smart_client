# WhatsApp Templates API Setup Guide

## Problem Discovered

The debug endpoint revealed that `/locations/{locationId}/templates/whatsapp` **exists** but returns:
```json
{
    "status": 401,
    "success": false,
    "body": {
        "statusCode": 401,
        "message": "This route is not yet supported by the IAM Service. Please update your IAM config."
    }
}
```

This means your Private Integration Token needs additional OAuth scopes to access WhatsApp templates.

## Solution: Update Private Integration Scopes

### Step 1: Access Private Integrations Settings

1. Log into your **HighLevel account**
2. Go to **Settings** (gear icon)
3. Navigate to **Integrations** → **Private Integrations**
4. Find your existing integration or click **"Create New Integration"**

### Step 2: Enable Required Scopes

Look for and enable these scopes (check all that apply):

**Essential Scopes:**
- ✅ `conversations.readonly` - Required to read WhatsApp data
- ✅ `conversations.write` - Required to send WhatsApp messages
- ✅ `conversations/message.readonly` - Read message data
- ✅ `conversations/message.write` - Send messages
- ✅ `locations/templates.readonly` - **Critical for WhatsApp templates**

**Additional Recommended Scopes:**
- ✅ `contacts.readonly` - Read contact information
- ✅ `contacts.write` - Update contact data

### Step 3: Generate New Token

1. After selecting scopes, click **"Create"** or **"Update"**
2. **Copy the new API token** (you'll only see it once!)
3. Also note your **Location ID** from Settings → Business Profile

### Step 4: Update Your Application

1. Go to your Laravel application's settings page: `http://localhost:8000/settings`
2. Click **"Disconnect"** to remove old credentials
3. Enter your **new API token** (with updated scopes)
4. Enter your **Location ID**
5. Click **"Test Connection"**

### Step 5: Verify Templates Endpoint

After updating credentials, test the WhatsApp templates endpoint:

1. Visit: `http://localhost:8000/debug/templates`
2. Look for `/locations/{locationId}/templates/whatsapp`
3. It should now return **200 status** with your templates instead of 401

Expected response:
```json
{
    "GET /locations/JdANelPsVVN0qv1rMSX1/templates/whatsapp": {
        "status": 200,
        "success": true,
        "body": {
            "templates": [
                {
                    "id": "...",
                    "name": "hello",
                    "status": "APPROVED",
                    "language": "en"
                },
                {
                    "id": "...",
                    "name": "gulfa_offer",
                    "status": "APPROVED",
                    "language": "ar"
                }
                // ... your 18 templates
            ]
        }
    }
}
```

## Important Notes

### About Private Integration Tokens

- **Private Integration Tokens** are static OAuth2 access tokens with restricted scopes
- Each token has specific permissions based on selected scopes
- You can edit scopes anytime by updating the Private Integration
- **Security Best Practice:** Only enable the minimum scopes your application needs

### Scope Documentation

- Full list of scopes: https://highlevel.stoplight.io/docs/integrations/vcctp9t1w8hja-scopes
- Private Integrations Guide: https://help.gohighlevel.com/support/solutions/articles/155000003054

### If Templates Still Don't Appear

If you've updated scopes but templates still don't show:

1. **Verify WhatsApp Setup:**
   - Go to Settings → WhatsApp in HighLevel
   - Confirm your WhatsApp Business Account is connected
   - Verify templates are **Approved** (not Pending/Rejected)

2. **Check Template Sync:**
   - Templates created in Meta Business Manager can take time to sync
   - Try clicking "Sync Templates" if available in HighLevel

3. **Contact HighLevel Support:**
   - If the issue persists, contact HighLevel support
   - Reference the 401 IAM error and WhatsApp templates endpoint
   - Provide your Location ID and integration details

## After Templates Work

Once the API returns templates successfully:

1. **Update the API Service** to use the correct endpoint
2. **Remove manual template input** fallback (or keep as backup)
3. **Test campaign creation** with API-loaded templates
4. **Remove debug route** before production deployment

## Current Workaround

Until you update the scopes, the application has a **manual template input fallback**:
- When creating campaigns, you can manually type template names
- Find template names in HighLevel at: Settings → WhatsApp → Templates
- Enter exact names (case-sensitive): `hello`, `gulfa_offer`, `after_calling`, etc.
