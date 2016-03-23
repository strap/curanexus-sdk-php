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
// $strap->activity->get( array("date" => "YYYY-MM-DD", "guid" => "demo-strap") )
//URL resources can be passed as Strings or in the Array
// $strap->activity->get( "demo-strap" )

// Every endpoint has the get() method
// Get a record or set of records
$strap->activity->get( params ); 

// Most GET endpoints support the "page" value also exposes two additional methods and two detail values
// Get the next set of records
$set = $strap->month->next(); 
// Get All set of records until the max page count is reached
$strap->month->all( params ); 
// Get the page information for the request
$strap->month->pageData; // Contains the "page", "next", "pages", "per_page" information for the request
// Check to see if there is a next page
$strap->month->hasNext; // Contains BOOL true || false if there is more data that can be pulled

echo "Tests<hr><hr>";
echo "<b>Endpoints [GET]</b><br><pre>";
print var_dump( $strap->endpoints() );
// No Params
echo "</pre>";

echo "<hr><b>Activity [GET]</b><br><pre>";
print var_dump( $strap->activity->get("user-guid") );
// URL resource: "guid"
// Optional: "date", "count", "start", "end"

echo "<hr><b>Behavior [GET]</b><br><pre>";
print var_dump( $strap->behavior->get("user-guid") );
// URL resource: "guid"
// Optional: none

echo "<hr><b>Job [GET]</b><br><pre>";
print var_dump( $strap->job->get() );
// URL resource: "id"
// Optional: "id", "status"

echo "<hr><b>Job [POST]</b><br><pre>";
print var_dump( $strap->job->post( array(params) ) );
// URL resource: none
// Required: "name"
// Optional: "description", "guids", "startDate", "endDate", "notificationUrl"

echo "<hr><b>Job [PUT]</b><br><pre>";
print var_dump( $strap->job->put( array(params) ) );
// URL resource: "id"
// Optional: "name", "description"

echo "<hr><b>Job [DELETE]</b><br><pre>";
print var_dump( $strap->job->delete( "job-id" ) );
// URL resource: "id"
// Optional: none

echo "<hr><b>Job Data [GET]</b><br><pre>";
print var_dump( $strap->job_data->get() );
// URL resource: "id"
// Optional: none

echo "<hr><b>Month [GET]</b><br><pre>";
print var_dump( $strap->month->get() );
// URL resource: none
// Optional: "guid", "page", "per_page"

echo "<hr><b>Report [GET]</b><br><pre>";
print var_dump( $strap->report->get() );
// URL resource: "id"
// Optional: none

echo "<hr><b>Report Food [GET]</b><br><pre>";
print var_dump( $strap->report_food->get() );
// URL resource: "id"
// Optional: "food"

echo "<hr><b>Report Raw [GET]</b><br><pre>";
print var_dump( $strap->report_raw->get() );
// URL resource: "id"
// Optional: "type"

echo "<hr><b>Report Workout [GET]</b><br><pre>";
print var_dump( $strap->report_workout->get() );
// URL resource: "id"
// Optional: "workout"

echo "<hr><b>Segmentation [GET]</b><br><pre>";
print var_dump( $strap->segmentation->get() );
// URL resource: none
// Optional: "period", "date"

echo "<hr><b>Today [GET]</b><br><pre>";
print var_dump( $strap->today->get() );
// URL resource: none
// Optional: "guid", "page", "per_page"

echo "<hr><b>Trend [GET]</b><br><pre>";
print var_dump( $strap->trend->get() );
// URL resource:  none
// Optional: "guid"

echo "<hr><b>Trigger [GET]</b><br><pre>";
print var_dump( $strap->trigger->get() );
// URL resource: "id"
// Optional: "id", "key", "type", "actionType"

echo "<hr><b>Trigger [POST]</b><br><pre>";
print var_dump( $strap->trigger->post( array(params) ) );
// URL resource: none
// Required: "active", "name", "type", "range", "conditions"
// Optional: "actionType", "actionUrl" 

echo "<hr><b>Trigger [PUT]</b><br><pre>";
print var_dump( $strap->trigger->put( array(params) ) );
// URL resource: "id"
// Optional: "active", "name", "type", "range", "conditions", "actionType", "actionUrl" 

echo "<hr><b>Trigger [DELETE]</b><br><pre>";
print var_dump( $strap->trigger->delete( "trigger-id" ) );
// URL resource: "id"
// Optional: none

echo "<hr><b>Trigger Data[GET]</b><br><pre>";
print var_dump( $strap->trigger_data->get() );
// URL resource: "id"
// Optional: "count"

echo "<hr><b>User [GET]</b><br><pre>";
print var_dump( $strap->user->get("user-guid") );
// URL resource: "guid"
// Optional: none

echo "<hr><b>Users [GET]</b><br><pre>";
print var_dump( $strap->users->get() );
// URL resource: none
// Optional: "platform", "count"

echo "<hr><b>Week [GET]</b><br><pre>";
print var_dump( $strap->week->get() );
// URL resource: none
// Optional: "guid", "page", "per_page"

echo "<hr><b>WordCloud [GET]</b><br><pre>";
print var_dump( $strap->wordcloud->get() );
// URL resource: none
// Optional: "guid"

```
