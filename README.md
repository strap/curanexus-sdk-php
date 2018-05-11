# PHP > CuraNEXUS Server-Side SDK

CuraNEXUS SDK PHP provides an easy to use, chainable API for interacting with our
API services.  Its purpose is to abstract away resource information from
our primary API, i.e. not having to manually track API information for
your custom API endpoint.

CuraNEXUS SDK PHP keys off of a global API discovery object using the read token for the API. 
The CuraNEXUS SDK PHP extracts the need for developers to know, manage, and integrate the API endpoints.

The a Project API discovery can be found here:

HEADERS: "X-Auth-Token": 
GET [https://api.curanexus.io/discover]([https://api.curanexus.io/discover)

Once the above has been fetched, `curaNEXUSSDK` will fetch the API discover
endpoint for the project and build its API.

### Installation

```
git clone git@github.com:strap/curanexus-sdk-php.git
```

### Usage

Below is a basic use case.

```php
// Require the curaNEXUSSDK
require_once './curanexus-sdk-php/curanexus.class.php';

// Setup CuraNEXUS SDK
$curanexus = new CuraNEXUS("{Read Token for the CuraNEXUS Project}");

//Optional Param can be passed in as an array
// $curanexus->activity->get( array("date" => "YYYY-MM-DD", "guid" => "demo-curanexus") )
//URL resources can be passed as Strings or in the Array
// $curanexus->activity->get( "demo-curanexus" )

// Every endpoint has the get() method
// Get a record or set of records
$curanexus->activity->get( params ); 

// Most GET endpoints support the "page" value also exposes two additional methods and two detail values
// Get the next set of records
$set = $curanexus->month->next(); 
// Get All set of records until the max page count is reached
$curanexus->month->all( params ); 
// Get the page information for the request
$curanexus->month->pageData; // Contains the "page", "next", "pages", "per_page" information for the request
// Check to see if there is a next page
$curanexus->month->hasNext; // Contains BOOL true || false if there is more data that can be pulled

echo "Tests<hr><hr>";
echo "<b>Endpoints [GET]</b><br><pre>";
print var_dump( $curanexus->endpoints() );
// No Params
echo "</pre>";

echo "<hr><b>Activity [GET]</b><br><pre>";
print var_dump( $curanexus->activity->get("user-guid") );
// URL resource: "guid"
// Optional: "date", "count", "start", "end"

echo "<hr><b>Behavior [GET]</b><br><pre>";
print var_dump( $curanexus->behavior->get("user-guid") );
// URL resource: "guid"
// Optional: none

echo "<hr><b>Job [GET]</b><br><pre>";
print var_dump( $curanexus->job->get() );
// URL resource: "id"
// Optional: "id", "status"

echo "<hr><b>Job [POST]</b><br><pre>";
print var_dump( $curanexus->job->post( array(params) ) );
// URL resource: none
// Required: "name"
// Optional: "description", "guids", "startDate", "endDate", "notificationUrl"

echo "<hr><b>Job [PUT]</b><br><pre>";
print var_dump( $curanexus->job->put( array(params) ) );
// URL resource: "id"
// Optional: "name", "description"

echo "<hr><b>Job [DELETE]</b><br><pre>";
print var_dump( $curanexus->job->delete( "job-id" ) );
// URL resource: "id"
// Optional: none

echo "<hr><b>Job Data [GET]</b><br><pre>";
print var_dump( $curanexus->job_data->get() );
// URL resource: "id"
// Optional: none

echo "<hr><b>Month [GET]</b><br><pre>";
print var_dump( $curanexus->month->get() );
// URL resource: none
// Optional: "guid", "page", "per_page"

echo "<hr><b>Report [GET]</b><br><pre>";
print var_dump( $curanexus->report->get() );
// URL resource: "id"
// Optional: none

echo "<hr><b>Report Food [GET]</b><br><pre>";
print var_dump( $curanexus->report_food->get() );
// URL resource: "id"
// Optional: "food"

echo "<hr><b>Report Raw [GET]</b><br><pre>";
print var_dump( $curanexus->report_raw->get() );
// URL resource: "id"
// Optional: "type"

echo "<hr><b>Report Workout [GET]</b><br><pre>";
print var_dump( $curanexus->report_workout->get() );
// URL resource: "id"
// Optional: "workout"

echo "<hr><b>Segmentation [GET]</b><br><pre>";
print var_dump( $curanexus->segmentation->get() );
// URL resource: none
// Optional: "period", "date"

echo "<hr><b>Today [GET]</b><br><pre>";
print var_dump( $curanexus->today->get() );
// URL resource: none
// Optional: "guid", "page", "per_page"

echo "<hr><b>Trend [GET]</b><br><pre>";
print var_dump( $curanexus->trend->get() );
// URL resource:  none
// Optional: "guid"

echo "<hr><b>Trigger [GET]</b><br><pre>";
print var_dump( $curanexus->trigger->get() );
// URL resource: "id"
// Optional: "id", "key", "type", "actionType"

echo "<hr><b>Trigger [POST]</b><br><pre>";
print var_dump( $curanexus->trigger->post( array(params) ) );
// URL resource: none
// Required: "active", "name", "type", "range", "conditions"
// Optional: "actionType", "actionUrl" 

echo "<hr><b>Trigger [PUT]</b><br><pre>";
print var_dump( $curanexus->trigger->put( array(params) ) );
// URL resource: "id"
// Optional: "active", "name", "type", "range", "conditions", "actionType", "actionUrl" 

echo "<hr><b>Trigger [DELETE]</b><br><pre>";
print var_dump( $curanexus->trigger->delete( "trigger-id" ) );
// URL resource: "id"
// Optional: none

echo "<hr><b>Trigger Data[GET]</b><br><pre>";
print var_dump( $curanexus->trigger_data->get() );
// URL resource: "id"
// Optional: "count"

echo "<hr><b>User [GET]</b><br><pre>";
print var_dump( $curanexus->user->get("user-guid") );
// URL resource: "guid"
// Optional: none

echo "<hr><b>Users [GET]</b><br><pre>";
print var_dump( $curanexus->users->get() );
// URL resource: none
// Optional: "platform", "count"

echo "<hr><b>Week [GET]</b><br><pre>";
print var_dump( $curanexus->week->get() );
// URL resource: none
// Optional: "guid", "page", "per_page"

echo "<hr><b>WordCloud [GET]</b><br><pre>";
print var_dump( $curanexus->wordcloud->get() );
// URL resource: none
// Optional: "guid"

```
