# Laravel 5 package to redirect to last visited url on login

[![Latest Version on Packagist](https://img.shields.io/packagist/v/waavi/save-url.svg?style=flat-square)](https://packagist.org/packages/waavi/save-url)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/Waavi/save-url/master.svg?style=flat-square)](https://travis-ci.org/Waavi/save-url)
[![Total Downloads](https://img.shields.io/packagist/dt/waavi/save-url.svg?style=flat-square)](https://packagist.org/packages/waavi/save-url)

## Introduction

This package allows you to easily redirect users to the last visited page on login.

WAAVI is a web development studio based in Madrid, Spain. You can learn more about us at [waavi.com](http://waavi.com)

## Installation

Require through composer

	composer require waavi/save-url 1.0.x

Or manually edit your composer.json file:

	"require": {
		"waavi/save-url": "1.0.x"
	}

In config/app.php, add the following entry to the end of the providers array:

	\Waavi\Mailman\SaveUrlServiceProvider::class,

Publish the configuration file:

	php artisan vendor:publish --provider="Waavi\SaveUrl\SaveUrlServiceProvider"

## Usage

### Cached urls
By default, the last visited URL visited by a user is saved in Session. URLs must follow these criteria to be saved:

	- Only GET requests are saved.
	- AJAX requests are not saved.
	- If the user is logged in, no urls are saved.

### Excluding urls from the cache
If you want to exclude certain urls from the url cache, like for example the login and signup pages, you may use the provided "doNotSave" middleware:

```php
// app/Http/routes.php

Route::get('/login', ['middleware' => 'doNotSave', 'uses' => 'AuthController@login']);
```

### Redirecting after login
To redirect the user to the last saved url, such as after authentication, you may use:

```php
public function login() {
	/** Auth user **/
	if ($success) {
		redirect()->toSavedUrl();
	}
}
```