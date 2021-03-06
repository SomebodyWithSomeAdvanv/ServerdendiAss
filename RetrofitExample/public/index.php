<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require '../vendor/autoload.php';
require_once '../includes/DbOperation.php';
//Creating a new app with the config to show errors
$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);
$app->post('/register', function (Request $request, Response $response) {
    if (isTheseParametersAvailable(array('id'))) {
        $requestData = $request->getParsedBody();
        $id = $requestData['id'];
        $db = new DbOperation();
        $responseData = array();
        $result = $db->registerUser($id);
        if ($result!=true && $result == USER_CREATED) {
            $responseData['error'] = false;
            $responseData['message'] = 'Registered successfully';
            //  $responseData['user'] = $db->getUserByEmail($email);
        } elseif ($result == USER_CREATION_FAILED) {
            $responseData['error'] = true;
            $responseData['message'] = 'Some error occurred';
        } elseif ($result == USER_EXIST) {
            $responseData['error'] = true;
            $responseData['message'] = 'This email already exist, please login';
        }
        $response->getBody()->write(json_encode($responseData));
    }
});
$app->post('/uyingaKirish', function (Request $request, Response $response){
    if (isTheseParametersAvailable(array('data'))){
        $requestData = $request->getParsedBody();
        $data = $requestData['data'];
        $db = new DbOperation();
        $responseData = array();
        $result =$db->uyingaKirish($data);//gruppani chiqarish uchun
        if ($result == USER_CREATED) {
            $responseData['error'] = $result;
            $responseData['message'] = 'successfully';
        }elseif ($result == USER_CREATION_FAILED) {
            $responseData['error'] = true;
            $responseData['message'] = 'Some error occurred';
        }elseif ($result == USER_EXIST) {
            $responseData['error'] = true;
            $responseData['message'] = 'This email already exist, please login';
        }else{
            $responseData['group']=$result;
        }
        $response->getBody()->write(json_encode($responseData));
    }
});
$app->post('/UyinniDavomEtishi', function (Request $request, Response $response) {
    if (isTheseParametersAvailable(array('data'))) {
        $requestData = $request->getParsedBody();
        $data = $requestData['data'];
        $db = new DbOperation();
        $responseData = array();
        $result = $db->UyinniDAvomEttir($data);
        if ($result == USER_CREATED) {
            $responseData['error'] = false;
            $responseData['message'] = 'Registered successfully';
        } elseif ($result == USER_CREATION_FAILED) {
            $responseData['error'] = true;
            $responseData['message'] = 'Some error occurred';
        } elseif ($result == USER_EXIST) {
            $responseData['error'] = true;
            $responseData['message'] = 'This email already exist, please login';
        }else{
            $responseData['group']=$result;
        }
        $response->getBody()->write(json_encode($responseData));
    }
});
$app->post('/RRniKiritish', function (Request $request, Response $response) {
    if (isTheseParametersAvailable(array('data'))) {
        $requestData = $request->getParsedBody();
        $data = $requestData['data'];
        $db = new DbOperation();
        $responseData = array();
        $result = $db->RRniKiritish($data);
        if ($result == USER_CREATED) {
            $responseData['error'] = false;
            $responseData['message'] = 'Registered successfully';
        } elseif ($result == USER_CREATION_FAILED) {
            $responseData['error'] = true;
            $responseData['message'] = 'Some error occurred';
        } elseif ($result == USER_EXIST) {
            $responseData['error'] = true;
            $responseData['message'] = 'This email already exist, please login';
        }else{
            $responseData['group']=$result;
        }
        $response->getBody()->write(json_encode($responseData));
    }
});
$app->get('/messages/{id}/{group}', function (Request $request, Response $response) {
    $userid = $request->getAttribute('id');
    $userGropde= $request->getAttribute('group');
    $db = new DbOperation();
    $messages = $db->getMessages($userid,$userGropde);
    $response->getBody()->write(json_encode(array("messages" => $messages)));
});
//function to check parameters
function isTheseParametersAvailable($required_fields)
{
    $error = false;
    $error_fields = "";
    $request_params = $_REQUEST;
    foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }
    if ($error) {
        $response = array();
        $response["error"] = true;
        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        echo json_encode($response);
        return false;
    }
    return true;
}
$app->run();