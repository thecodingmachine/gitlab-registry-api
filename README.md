[![Latest Stable Version](https://poser.pugx.org/thecodingmachine/gitlab-registry-api/v/stable.svg)](https://packagist.org/packages/thecodingmachine/gitlab-registry-api)
[![Total Downloads](https://poser.pugx.org/thecodingmachine/gitlab-registry-api/downloads.svg)](https://packagist.org/packages/thecodingmachine/gitlab-registry-api)
[![Latest Unstable Version](https://poser.pugx.org/thecodingmachine/gitlab-registry-api/v/unstable.svg)](https://packagist.org/packages/thecodingmachine/gitlab-registry-api)
[![License](https://poser.pugx.org/thecodingmachine/gitlab-registry-api/license.svg)](https://packagist.org/packages/thecodingmachine/gitlab-registry-api)
[![Build Status](https://travis-ci.org/thecodingmachine/gitlab-registry-api.svg?branch=master)](https://travis-ci.org/thecodingmachine/gitlab-registry-api)


Gitlab registry api
===================
This is a package to use Docker registry from GitLab. I wrote it because I couldn't found an usefull library with fully
implementation (read and destroy). To simplify the use, all element returned are objects (and it's pretty cool in your IDE).

Private registry
----------------
If you have a private registry, you'll need to create a Personal Access Token in order to access it.
This is in your profile, settings and access tokens. If you want to read and delete element, you must check "api" and "read_registry".

Installation
------------
Use Composer to install it, this repo is on Packagist, so something like this should be fine:
```json
{
    "require": {
        "thecodingmachine/gitlab-registry-api": "^1.0"
    }
}
```

Then do composer install or composer update in the usual way.

To make use of it, load the autoloader:
```php
require_once __DIR__ . '/vendor/autoload.php';
```

How to it work
--------------
With your personnal registry information (domain, token, gorup and project), I use Guzzle 6 to call the registry api.
So in all this package you could catch a Guzzle Exception if there is an error with an api call.

How to use it
-------------
I tried to simplify the use as much as possible with object manipulation. But is always possible to get the original array result,
with the function getPayload() on Image or Tag.

### Get data
There is a getter for each attribut of each element.

Example:
```php
// Create client with access
$client = new Client('https://git.yourdomain.com/', 'myPrivateAccessToken');
// Declare your registry and retrieve a Registry object
$registry = $client->getRegistry('myGroup', 'myProject');
foreach ($registry->getImages() as $image) {
    echo $image->getId();
    foreach ($image->getTags() as $tag) {
        echo $tag->geTotalSize();
    }
}
```

If your group and project is already concatenated (like your registry comes from GitLab api), you could set it in only the group parameter :
```php
$client = new Client('https://git.yourdomain.com/', 'myPrivateAccessToken');
$registry = $client->getRegistry('myGroup/myProject');
foreach ($registry->getImages() as $image) {
    echo $image->getId();
    foreach ($image->getTags() as $tag) {
        echo $tag->geTotalSize();
    }
}
```

#### Pagination
In the tag list, it's possible to add paginate parameter to filter result:
```php
$client = new Client('https://git.yourdomain.com/', 'myPrivateAccessToken');
$registry = $client->getRegistry('myGroup/myProject');
foreach ($registry->getImages() as $image) {
    echo $image->getId();
    // Pagination, get second page with 10 elements
    foreach ($image->getTags(2, 10) as $tag) {
        echo $tag->geTotalSize();
    }
}
```

### Destroy
You could destroy an image or only a tag with a simple method destroy.

Example for image:
```php
$client = new Client('https://git.yourdomain.com/', 'myPrivateAccessToken');
$registry = $client->getRegistry('myGroup/myProject');
foreach ($registry->getImages() as $image) {
    // This return true, false or guzzle exception
    var_dump($image->destroy());
}
```

Example for tag:
```php
$client = new Client('https://git.yourdomain.com/', 'myPrivateAccessToken');
$registry = $client->getRegistry('myGroup/myProject');
foreach ($registry->getImages() as $image) {
    foreach ($image->getTags() as $tag) {
        // This return true, false or guzzle exception
        var_dump($tag->destroy());
    }
}
```

### Payload
You could retrieve the original array result.

Example for image:
```php
$client = new Client('https://git.yourdomain.com/', 'myPrivateAccessToken');
$registry = $client->getRegistry('myGroup/myProject');
foreach ($registry->getImages() as $image) {
    // This return original array
    var_dump($image->getPayload());
}
```

Example for tag:
```php
$client = new Client('https://git.yourdomain.com/', 'myPrivateAccessToken');
$registry = $client->getRegistry('myGroup/myProject');
foreach ($registry->getImages() as $image) {
    foreach ($image->getTags() as $tag) {
        // This return original array
        var_dump($tag->getPayload());
    }
}
```
