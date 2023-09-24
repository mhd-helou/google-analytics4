<?php
namespace App\Http\Controllers;
use Google\Analytics\Admin\V1alpha\DataStream\WebStreamData;
use Google\Analytics\Admin\V1alpha\UserLink;
use Illuminate\Http\Request;

use Google\Analytics\Admin\V1alpha\Property;
use Google\Analytics\Admin\V1alpha\DataStream;


use Google\Analytics\Admin\V1alpha\AnalyticsAdminServiceClient;
//use Google\Service\Analytics

use Google\Service\GoogleAnalyticsAdmin;
class GoogleAnalyticsController extends Controller
{
    private function createAuthenticatedAnalyticsClient()
    {
        // Replace with the path to your service account JSON key file.
        $credentialFile = storage_path('ga-4-399710-86fae8dd64b5.json');
        // Create and authenticate an instance of the Google Analytics Admin API client.
        return new AnalyticsAdminServiceClient([
            'credentials' => $credentialFile,
        ]);
    }

    public function createProperty(Request $request)
    {
        $client = $this->createAuthenticatedAnalyticsClient();
        $property = new Property();
        $property->setDisplayName($request->property_name);
        $property->setTimeZone($request->time_zone);
        $property->setParent($request->parent);
        try {
            $createdProperty = $client->createProperty($property);
            $propertyDisplayName = $createdProperty->getDisplayName();
            $propertyId = explode('/', $createdProperty->getName())[1];

            return response()->json(['property_display_name' => $propertyDisplayName, 'property_id' => $propertyId], 201);
        } catch (\Exception $e) {
            // Handle exceptions, and if necessary, return an error response.
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function createWebDatastreamForProperty(Request $request)
    {
        try {
            $client = $this->createAuthenticatedAnalyticsClient();
            $propertyId = $request->property_id;
            $webDataStream = new WebStreamData();
            $webDataStream->setDefaultUri($request->default_uri);
            $dataStream = new DataStream();
            $dataStream->setDisplayName($request->webstream_display_name);
            $dataStream->setWebStreamData($webDataStream);
            $dataStream->setType(1);
            $createdWebDataStream = $client->createDataStream("properties/" . $propertyId, $dataStream);
            $measurementId = $createdWebDataStream->getWebStreamData()->getMeasurementId();
            return response()->json(['MeasurementId' => $measurementId], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function createPropertyAndWebDataStream(Request $request)
    {
        try {
            $client = $this->createAuthenticatedAnalyticsClient();
            $responseData = json_decode($this->createProperty($request)->getContent(), true);
            $propertyId = $responseData['property_id'] ?? null;
            $webDataStream = new WebStreamData();
            $webDataStream->setDefaultUri($request->default_uri);
            $dataStream = new DataStream();
            $dataStream->setDisplayName($request->webstream_display_name);
            $dataStream->setWebStreamData($webDataStream);
            $dataStream->setType(1);
            $createdWebDataStream = $client->createDataStream("properties/" . $propertyId, $dataStream);
            $measurementId = $createdWebDataStream->getWebStreamData()->getMeasurementId();
            return response()->json(['PropertyId' => $propertyId, 'MeasurementId' => $measurementId], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    public function listAllProperties(Request $request)
    {
        try {
            $client = $this->createAuthenticatedAnalyticsClient();
            // Validate the request.
           /* if (!isset($request->parent) || empty($request->parent)) {
                throw new \Exception('The `parent` field is required.');
            }*/
            //$filter = "parent:accounts/" . (string)$request->parent;
            $filter = "parent:accounts/286104708";
            $properties = $client->listProperties($filter);
            $propertyList = [];
            foreach ($properties as $property) {
                $propertyId = explode('/', $property->getName())[1];
                $propertyList[] = [
                    'property_id' => $propertyId,
                    'display_name' => $property->getDisplayName(),
                    'time_zone' => $property->getTimeZone(),
                ];
            }
            return response()->json(['properties' => $propertyList], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function addUserAccessToProperty(Request $request){
        $client = $this->createAuthenticatedAnalyticsClient();
        $propertyId = $request->property_id;
        $userLink = new UserLink();
        $userLink->setEmailAddress($request->email);
        $userLink->setDirectRoles([$request->role]);
        $createdUserLink = $client->createUserLink($propertyId, $userLink);
        $responseContent = [
            'message' => 'User access added successfully',
            'userLink' => [
                'name' => $createdUserLink->getName(),
                'emailAddress' => $createdUserLink->getEmailAddress(),
            ],
        ];
        return response()->json($responseContent, 200);
    }

    public function listAccEmails(Request $request)
    {
        $client = $this->createAuthenticatedAnalyticsClient();
        $emails = [];
        $pagedResponse = $client->listUserLinks($request->parent);
        foreach ($pagedResponse->iteratePages() as $page) {
            foreach ($page as $element) {
                $emails[] = $element->getEmailAddress();
            }
        }
        return json_encode($emails);
    }
  /*  public function propertyAccessManagment(Request $request)
    {

        $client = $this->createAuthenticatedAnalyticsClient();
        $accounts = $client->listAccounts();

        $accountEmails = [];
        foreach ($accounts as $account) {
            $accountNames[] = $account->getName();
        }
        return response()->json(['accountNames' => $accountEmails], 200);


    }*/
}
