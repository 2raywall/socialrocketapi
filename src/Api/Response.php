<?php

namespace SocialRocket\Api;

use SocialRocket\Exceptions\AuthenticationException;

/**
 * A response  object for the api response
 *
 * @package SocialRocket\Api
 */
class Response
{
    /**
     * Array with errors or 0 if is not error
     *
     * @var
     */
    private $errors;

    /**
     * Array with the data of the response
     *
     * @var
     */
    private $data;

    /**
     * Create a new Response
     * @param $response
     */
    public function __construct($response)
    {
        $this->init($response);

    }

    /**
     * Initialize a new Response
     *
     * @param $response
     * @throws AuthenticationException
     */
    private function init($response)
    {
        $response = json_decode($response,true);

        if(isset($response["errors"])){
            $this->errors = $response["errors"];
        }

        if(isset($response["data"])){
            $this->data = $response["data"];
        }

        if(isset($response["error"])){
            if($response["error"]["code"] == 401)
            {
                throw new AuthenticationException(
                    $response["error"]["message"],
                    $response["error"]["code"]
                );
            }
        }

    }


    /**
     * Get the errors of the response
     *
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }


    /**
     * Know if the response has error
     *
     * @return bool
     */
    public function hasError()
    {
        return $this->errors === 0;
    }


    /**
     * Get the data or a specific data
     *
     * @return mixed
     */
    public function getData($key = null)
    {
        if(is_null($key)) {
            return $this->data;
        }

        if(isset($this->data[$key])){
            return $this->data[$key];
        }

        return null;
    }

}