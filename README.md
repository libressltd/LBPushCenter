# LBPushCenter

### Step 1: Install LBPushCenter

composer require libressltd/lbpushcenter

### Step 2: Add service provider to config/app.php

LIBRESSLtd\LBPushCenter\LBPushCenterServiceProvider::class,

and alias

'LBPushCenter' => LIBRESSLtd\LBPushCenter\Controllers\LBPushCenter::class,

### Step 3: Publish vendor

php artisan vendor:publish --tag=lbpushcenter --force

### Step 3: Using in master:

```php

// Add a new device 

Push_device::add($token, $app_name);

// Send message

$device = Push_device::findOrFail($device_id); // not device token

$device->send($title, $desc); // sync push

$device->send_in_queue($title, $desc) // must run in queue

```

### Remove badge

POST: <host>/lbpushcenter/api/device/<device_id>/clear_badge


### Migration:

Remove and re-migrate the device table;


Using link: (should be added by lbsidemenu)

manage application (for example: ios-dev, ios-production, ios, etc ...): /lbpushcenter/application

manage application type (ios / fcm, need added manual): /lbpushcenter/application_type

manage all device: /lbpushcenter/device

manage all notification: /lbpushcenter/notification


