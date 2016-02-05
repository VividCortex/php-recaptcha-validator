VividCortex reCaptcha Validator
===============================

Deprecation notice
------------------

This package has been deprecated in favor of [Google's own recaptcha validator](https://github.com/google/recaptcha).

---


A simple validator for [Google's reCaptcha](https://developers.google.com/recaptcha/) responses written in PHP.

[![Build status](https://circleci.com/gh/VividCortex/php-recaptcha-validator.png)](https://circleci.com/gh/VividCortex/php-recaptcha-validator)


Installation
------------

Add your project requirement using composer.

```
composer require vividcortex/recaptcha-validator
```

How it works
------------

First, you need to instantiate a new _validator_ with your [secret key](http://www.google.com/recaptcha/admin) provided by Google.

```php
use VividCortex\RecaptchaValidator\Validator;

// ...

$validator = new Validator($secret);
```

Then, you can move on to validating responses. The response and the client's IP are required.

```php
// Returns TRUE or FALSE
$result = $validator->validate($response, $clientIp);
```
