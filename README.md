# strapSDK

Strap SDK PHP provides an easy to use, chainable API for interacting with our
API services.  Its purpose is to abstract away resource information from
our primary API, i.e. not having to manually track API information for
your custom API endpoint.

Strap SDK PHP keys off of a global API discovery object using the read token for the API. 
The Strap SDK PHP extracts the need for developers to know, manage, and integrate the API endpoints.

The a Project API discovery can be found here:

HEADERS: "X-Auth-Token": 
GET [https://api2.straphq.com/discover]([https://api2.straphq.com/discover)

Once the above has been fetched, `strapSDK` will fetch the API discover
endpoint for the project and build its API.

### Installation

```
git clone git@github.com:strap/strap-sdk-php.git
```

### Usage

Below is a basic use case.

```php
// Require the StrapSDK
require_once './strap-sdk-php/strap.class.php';

// Setup Strap SDK
$strap = new StrapSDK("{Read Token for the Strap Project}");

echo "Tests<hr><hr>";
echo "<b>Get details of avilable Endpoints</b><br><pre>";
print var_dump( $strap->endpoints() );
echo "</pre>";

echo "<hr><b>Get all Users</b><br><pre>";
print var_dump( $strap->users->call() );

echo "<hr><b>Activity for a User</b><br><pre>";
print var_dump( $strap->activity->call(["guid" => "demo-strap"]) );

echo "<hr><b>Today information for today</b><br><pre>";
print var_dump( $strap->today->call() );

```
