# LBPushCenter

### Step 1: Install DeepPermission

composer require libressltd/lbpushcenter

### Step 2: Add service provider to config/app.php

LIBRESSLtd\LBPushCenter\LBPushCenterServiceProvider::class,

and alias

'LBPushCenter' => LIBRESSLtd\LBPushCenter\Controllers\LBPushCenter::class,

### Step 3: Publish vendor

php artisan vendor:publish --tag=lbpushcenter --force

### Step 3: Using in master:

```php

use LBPushCenter;

LBPushCenter::push(array(
	array("type" => "appNameIOS", "token" => "a-device-token"),
	array("type" => "appNameAndroid", "token" => "a-device-token"),
), "Message to push")


```
