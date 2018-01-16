<?php

namespace SocialRocket\Api;

/**
 * A client to access the SocialRocket API
 *
 * @package SocialRocket\Api
 */
class SocialRocket
{
    /**
     * Params to send to the Api
     *
     * @var
     */
    private $params;

    /**
     * To handle the request
     *
     * @var RequestHandler
     */
    private $requestHandler;

    /**
     * Params that should be array
     *
     * @var array
     */
    private $arrayParams = [
        "images",
        "users_slug",
        "sites",
    ];

    /**
     * Create a new SocialRocket
     *
     * @param $apiKey
     */
    public function __construct($apiKey)
    {
        $this->requestHandler = new RequestHandler($apiKey);
    }

    /**
     * Reset the params
     */
    public function resetParams()
    {
        $this->params = [];
    }

    /**
     * Get the key of the Api
     *
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->requestHandler->getApiKey();
    }


    /**
     * Set the key of the api
     *
     * @param $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->requestHandler->setApiKey($apiKey);
    }


    /**
     *Prepare data to post
     *
     * @param $parameters  users to except to post
     * @return SocialRocket
     */
    public function except($parameters)
    {
        $this->params(['behavior'=>'except']);
        return $this->params(['users_slug'=>$parameters]);
    }


    /**
     * Prepare data to post
     *
     * @param $parameters users to only post
     * @return SocialRocket
     */
    public function only($parameters)
    {
        $this->params(['behavior'=>'only']);
        return $this->params(['users_slug'=>$parameters]);
    }


    /**
     * Prepare data to post, post all users
     *
     * @return SocialRocket
     */
    public function all()
    {
        return $this->params(['behavior'=>'all']);
    }


    /**
     * Function to call dinamically the methods in the class,
     * adding params by methods
     *
     * @param $method
     * @param $parameters
     *
     * @return SocialRocket
     */
    public function __call($method, $parameters)
    {
        $method = strtolower($method);
        $parameters = count($parameters)>0 ? $parameters[0] : null;
        return $this->params([$method=>$parameters]);
    }


    /**
     * Set the params of a request
     *
     * @param array $params
     *
     * @return SocialRocket
     */
    private function params($params = [])
    {
        foreach ($params as $key => $param)
        {
            if(!empty($param)) {
                if (in_array($key, $this->arrayParams)) {
                    $param = is_array($param) ? $param : [$param];
                }
                $this->params[$key] = $param;
            }

        }
        return $this;
    }


    /**
     *  Make a request to the Api
     *
     * @param $endPoint the end point to send the request
     * @param $method   one of GET, POST
     * @param $params   the array of params
     *
     * @return Response
     */
    public function sendRequest($endPoint, $method, $params = [])
    {
        $this->params($params);
        $response =  $this->requestHandler->sendRequest($endPoint, $method, $this->params);
        $this->resetParams();
        return $response;
    }


    /**
     * End point to send post
     *
     * @param string $appSlug
     * @param array $params
     * @return Response
     */
    public function post($appSlug = null, $params = [])
    {
        if($appSlug)
        {
            return $this->sendRequest("apps/{$appSlug}/post","POST",$params);
        }

        return $this->sendRequest("apps/post","POST",$params);
    }


    /**
     * End point to create a new User
     *
     * @param array $params
     *
     * @return Response
     */
    public function createUser($params = [])
    {
        return $this->sendRequest("users","POST",$params);
    }


    /**
     * End point to authenticate an User
     *
     * @param array $params
     *
     * @return Response
     */
    public function authenticateUser($params = [])
    {
        return $this->sendRequest("apps/authenticate/users","POST",$params);
    }


    /**
     * End point to get all the users of an App
     *
     * @return Response
     */
    public function getUsersApp()
    {
        $this->resetParams();
        return $this->sendRequest("apps/users","POST");
    }


    /**
     *  End point to create a new App
     *
     * @param array $params
     *
     * @return Response
     */
    public function createApp($params = [])
    {
        return $this->sendRequest("apps","POST", $params);
    }


    /**
     * End point to update an App
     *
     * @param array $params
     *
     * @return Response
     */
    public function updateApp($appKey,$params = [])
    {
        return $this->sendRequest("apps/".$appKey,"POST", $params);
    }


    /**
     *  End point to get the profile of an user
     *
     * @return Response
     */
    public function getUserProfile()
    {
        $this->resetParams();
        return $this->sendRequest("users/profile","GET");
    }


    /**
     *  End point to get all the Apps of an user
     *
     * @return Response
     */
    public function getApps()
    {
        $this->resetParams();
        return $this->sendRequest("apps","GET");
    }


    /**
     *  End point to get the profile of an App
     * @param $appKey
     * @return Response
     */
    public function getAppProfile($appKey)
    {
        $this->resetParams();
        return $this->sendRequest("apps/{$appKey}/profiles","GET");
    }

}