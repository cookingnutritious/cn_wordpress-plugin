<?php

/**
 * CookingNutritiousClient
 *
 * Http client for interacting with the cookingnutritious rest API
 *
 * @version 1.0
 * @author jgreathouse
 */
 
namespace cookingnutritious\CookingNutritiousClient;

use cookingnutritious\CookingNutritiousClient\CookingNutritiousObj;
 
class CookingNutritiousClient
{

    /**
     * Default connection and response timeout
     */
    const CN_API_TIMEOUT = 10;

    protected $token;
    
    protected $uri;

    public function __construct($token, $uri = null) {
        $this->token = $token;
        if (null !== $uri) {
            $this->uri = $uri;
        }
    } 
    
    public function requestGet($uri) {
        $this->uri = $uri;
        return $this->exec();
    }

    protected function exec()
    {
        $cn = new CookingNutritiousObj;
        $cn->setClient($this);

        $handle = @curl_init($this->uri);

        $timeout = CN_API_TIMEOUT;
        
        $header = array();
        $header[] = 'Content-length: 0';
        $header[] = 'Content-type: application/json';
        $header[] = 'Authorization: Token ' . $this->token;

        curl_setopt($handle, CURLOPT_HEADER, 0);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $header);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($handle, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, 1);

        $response = curl_exec($handle);
        $info = curl_getinfo($handle);
        $code = empty($info['http_code']) ? 'No HTTP code returned' : $info['http_code'];
        $cn->setCode($code);
        $error = curl_errno($handle);
        if ($error)
        {
            $cn->setStat(false)
               ->setError(curl_error($handle) . ' [' . $error . ']')
               ->setResponse($info);
        } else {
            $cn->setStat(true)
               ->setResponse(json_decode($response));
        }
        
        curl_close($handle);
        
        return $cn;
    }

}
