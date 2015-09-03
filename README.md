# vktoken
Simple class on php, created for makind long-life token on vk.com.

## usage

* create object:
```php 
$token = vktoken::create(VK_APP_ID, VK_APP_SECRET);
```

* using vktoken to get user info on exists token.
```php
$user = vktoken::getUserInfo($token);

if ($user){
	$user['first_name'];
	$user['last_name'];
	$user['photo_100'];
	$user['status'];
	$user['last_seen'];
}
```

* create auth link:
```php
$link = $token->link('wall,friends,photos');
```

* get token by url:
```php
$res = $token->getToken($code_url);
//$code_url is string like: http://api.vk.com/blank.html#code=5a38b024efa465fb51
if ($res['token']) {
    //save $res['token'] into db.
    //$res['user'] - it is field of token user
    $user = $res['user'];
}
```