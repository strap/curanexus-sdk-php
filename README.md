# PHP > Strap Server-Side SDK

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
$strap = new Strap("{Read Token for the Strap Project}");

//Optional Param can be passed in as an array
// $strap->getActivity( ["day" => "YYYY-MM-DD", "guid" => "demo-strap"] )
//URL resources can be passed as Strings or in the Array
// $strap->getActivity( "demo-strap" )

echo "Tests<hr><hr>";
echo "<b>Endpoints</b><br><pre>";
print var_dump( $strap->endpoints() );
// No Params
echo "</pre>";

echo "<hr><b>Activity</b><br><pre>";
print var_dump( $strap->getActivity("demo-strap") );
// URL resource: "guid"
// Optional: "day", "count"

echo "<hr><b>Report</b><br><pre>";
print var_dump( $strap->getReport() );
// URL resource: "id"
// Optional: none

echo "<hr><b>Today</b><br><pre>";
print var_dump( $strap->getToday() );
// URL resource: none
// Optional: "guid", "page"

echo "<hr><b>Trigger</b><br><pre>";
print var_dump( $strap->getTrigger() );
// URL resource: "id"
// Optional: "count"

echo "<hr><b>Users</b><br><pre>";
print var_dump( $strap->getUsers() );
// URL resource: none
// Optional: "platform", "count"

```
