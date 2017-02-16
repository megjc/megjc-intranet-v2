<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Api\Services\Authentication as Auth;
use \Api\Helpers\JSONWebToken as JOT;
use \Api\Models\User as User;

$app->group('/v2/auth', function(){
  /**
   * Authenticate a user based on credentials supplied.
   * If the user is found, a token in generated and issued.
   */
  $this->post('/user', function (Request $request, Response $response) {
      $req = $request->getParsedBody();
      if( $req['password'] != "" && $req['username'] != ""){
        $result = Auth::authUser($req);
        $role = "reg";
        if(!$result){
          return $response->withJson(array("message"=>"User not authenticated."), 400);
        }
        $token = JOT::encode($result);
        if($result->type_id == 1) $role = "admin";
        return $response->withJson(array("token" => $token, "role"=> "reg"), 200);
      }else{
        return $response->withJson(array("message"=>"User not authenticated. No username or password supplied"), 400);
      }
  });
  /**
   * Authenticates a token previously issued.
   * A valid token is supplied, decoded and if found valid
   * the endpoint returns a success message.
   */
  $this->post('/token', function(Request $request, Response $response){
      $status = 200;
      $result = array();
      if($request->getHeader('Authorization')){
        $auth_header = $request->getHeaderLine('Authorization');
        $token = explode(" ", $auth_header);
        $decoded = JOT::decode( $token[1] );
        if($decoded['status'] == 200){
          $result = array( "success"=> true,
                           "message" => "Authenicated",
                          "developer_message" => "The token supplied was successfully authenticated"
                        );
        }
      }else{
        $status = 400;
        $result = array("success"=> false,
                        "message"=> "The token was not included in the request",
                        "developer_message" => "Bad Request");
      }
      return $response->withJson($result, $status);
  });
  /**
   * Refreshes a token for a user.
   * A valid token (not expired) is supplied, decoded and a new token issued
   * once determined valid.
   */
  $this->post('/token/refresh', function(Request $request, Response $response){
    if($request->getHeader('Authorization')){
      $auth_header = $request->getHeaderLine('Authorization');
      $token = explode(" ", $auth_header);
      $decoded = JOT::decode( $token[1] );
      $result = User::getUserById( $decoded['data']->id );
      if(!$result) return $response->withJson(array("message"=>"User not authenticated"), 400);
      else{
        $token = JOT::encode($result);
      }
    }
    return $response->withJson(array("token"=> $token), 200);
  });
});
?>
