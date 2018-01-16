<?php

namespace SocialRocket\Api;

use SocialRocket\Exceptions\SocialRocketException;


/**
 * A request handler for SocialRocket authentication
 * and requests
 *
 * Class RequestHandler
 * @package SocialRocket\Api
 */

class RequestHandler
{

    /**
     * Key to access to the Api
     *
     * @var
     */
    private $apiKey;


    /**
     * Url of the api
     *
     * @var string
     */
    private $baseUrl = "https://socialrocket.io/api/";


    /**
     * Instantiate a new RequestHandler
     *
     * @param $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }


    /**
     * Get the key of the Api
     *
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }


    /**
     * Set the key of the Api for a new key
     *
     * @param mixed $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }


    /**
     * Create the headers for the authentication of the Api
     *
     * @return string
     */
    private function getHeaders()
    {
        return 'X-Authorization:' . $this->apiKey;

    }


    /**
     *  Make a request to the Api
     *
     * @param $endPoint the end point to send the request
     * @param $method   one of GET, POST
     * @param $params   the array of params
     *
     * @return Response
     * @throws SocialRocketException
     */
    public function sendRequest($endPoint, $method, $params)
    {
        $options = [
            CURLOPT_HEADER => true,
            CURLOPT_HTTPHEADER => ['Accept: application/json', $this->getHeaders()],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_URL => $this->baseUrl.$endPoint,
        ];

        $parameters = !empty($params) ? http_build_query($params) : "";
        if($method == 'POST')
        {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = $parameters;

        }else
        {
            $options[CURLOPT_URL] .= '?' . $parameters;
        }

        try {
            $channel = curl_init(); //get a channel
            curl_setopt_array($channel, $options); //set options
            $response = curl_exec($channel); //make the call

            //take out the header
            $headerSize = curl_getinfo($channel, CURLINFO_HEADER_SIZE);
            $header = substr($response, 0, $headerSize);
            $response = substr($response, $headerSize);

        }catch (\Exception $e){
            throw new SocialRocketException($e->getMessage(),$e->getCode());
        }

        return new Response($response);

    }
}