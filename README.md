# Apereo CAS Authentication for Laravel

[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=NU3XK7VXYTYKY)
[![Latest Stable Version](https://poser.pugx.org/sentrasoft/laravel-cas/v/stable)](https://packagist.org/packages/sentrasoft/laravel-cas)
[![Total Downloads](https://poser.pugx.org/sentrasoft/laravel-cas/downloads)](https://packagist.org/packages/sentrasoft/laravel-cas)
[![Monthly Downloads](https://poser.pugx.org/sentrasoft/laravel-cas/d/monthly)](https://packagist.org/packages/sentrasoft/laravel-cas)
[![Latest Unstable Version](https://poser.pugx.org/sentrasoft/laravel-cas/v/unstable)](https://packagist.org/packages/sentrasoft/laravel-cas)
[![License](https://poser.pugx.org/sentrasoft/laravel-cas/license)](https://packagist.org/packages/sentrasoft/laravel-cas)

Easy Bring to CAS Authentication for Laravel

## Install

#### Via Composer

``` php
$ composer require sentrasoft/laravel-cas
```

#### Via edit `composer.json`

	"require": {
		"sentrasoft/laravel-cas": "dev-master"
	}

Next, update Composer from the Terminal:

``` bash
$ composer update
```

## Configuration

After updating composer, add the ServiceProvider to the providers array in `config/app.php`.

```php
'providers' => array(
    .....
    Sentrasoft\Cas\CasServiceProvider::class,
);
```

Now add the alias in `config/app.php`.

```php
'aliases' => array(
    ......
    'Cas' => Sentrasoft\Cas\Facades\Cas::class,
);
```

Add the middelware to your `Kernel.php` file or leverage your own:
```php
'cas.auth'  => \Sentrasoft\Cas\Middleware\Authenticate::class,
'cas.guest' => \Sentrasoft\Cas\Middleware\RedirectIfAuthenticated::class,
```

Now publish the configuration `cas.php` file:
``` php
$ php artisan vendor:publish --provider="Sentrasoft\Cas\CasServiceProvider" --tag="config"
```

Add new environment variables below to your `.env`
```
CAS_HOSTNAME=cas.example.com
CAS_VALIDATION=https://cas.example.com/cas/p3/serviceValidate
CAS_VERSION=3.0
CAS_LOGOUT_URL=https://cas.example.com/cas/logout
```

> To see further configuration, please see and read the description for each configuration item [config/cas.php](src/Config/cas.php)

## Route

#### Authentication
Redirect the user to the authentication page for the provider.
```php
Route::get('/cas/login', function() {
    return cas()->authenticate();
})->name('cas.login');
```

#### Controller and Callback Route
You can create a new controller named `Auth\CasController`.
```php
php artisan make:controller Auth\CasController
```

```php
class CasController extends Controller
{
    /**
     * Obtain the user information from CAS.
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function callback()
    {
        // $username = Cas::getUser();
        // Here you can store the returned information in a local User model on your database (or storage).

        // This is particularly usefull in case of profile construction with roles and other details
        // e.g. Auth::login($local_user);

        return redirect()->route('home');
    }
}
```

When the authentication is performed the callback url is invoked. In that callback you can process the User and create a local entry in the database.
```php
Route::get('/cas/callback', 'Auth\CasController@callback')->name('cas.callback');
```

#### Logout
Logout of the CAS Session and redirect users.

```php
Route::post('/cas/logout', [ 'middleware' => 'cas.auth', function() {
    cas()->logout();

    // You can also add @param string $url in param[0]
    cas()->logout(url('/'));

    // Or add @param string $service in param[1]
    cas()->logout('', url('/'));

}])->name('cas.logout');
```

>The `cas.auth` middleware is optional, but you will need to handle the error when a user tries to logout when they do not have a CAS Session.

> If the `CAS_LOGOUT_REDIRECT` configuration item in `.env` is added, the value is taken from that configuration. Or if nothing is configured, the value is taken based on the value you specified.


If you want to use *SLO (Single Logout)* (if the CAS server supports SLO), Your application must have a valid SSL and the CAS server must be able to send *HTTP POST* `/cas/logout` without having to verify `CsrfToken`. Therefore, you must change the `App\Http\Middleware\VerifyCsrfToken` file and exclude `/cas/logout` route.

```php
/**
 * The URIs that should be excluded from CSRF verification.
 *
 * @var array
 */
protected $except = [
    //

    '/cas/logout',
];
```

You can check that it works by trying to send an *HTTP POST* via [cURL](https://en.wikipedia.org/wiki/CURL).
```
curl -X POST https://yourapp.com/cas/logout
```

## Usage

#### Get User
To retrieve authenticated credentials.

> Not ID (integer), but given on the CAS login (username) form.

```php
$uid = Cas::user()->id;
```

#### Get User Attributes
Get the attributes for for the currently connected user.
```php
foreach (Cas::user()->getAttributes() as $key => $value) {
	...
}
```

#### Get User Attribute
Retrieve a specific attribute by key name. The attribute returned can be either a string or an array based on matches.
```php
$value = Cas::user()->getAttribute('key');
```

## Support Us
[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=NU3XK7VXYTYKY)

Help us to keep making awesome stuff. You don't have to be a developer to support our open source work. If you want to receive personal support, or just feel all warm and fuzzy inside from helping open source development, donations are very welcome. Thank you.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
