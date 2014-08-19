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
            return $this->requestGet($uri);
        }
    } 
    
    public function requestGet($uri) {
        $this->uri = $uri;
        return $this->exec();
    }

    protected function loadRequest()
    {
        return array(
            'token'    => $this->token
        );
    }

    protected function exec()
    {
        $cn = new CookingNutritiousObj;
        $cn->setClient($this);

        $handle = @curl_init($this->uri);

        $timeout = CN_API_TIMEOUT;

        curl_setopt($handle, CURLOPT_HEADER, 0);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($handle, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $this->loadRequest($this->request));

        $response = curl_exec($handle);

        $error = curl_errno($handle);
        if ($error)
        {
            $cn->setStat(false)
               ->setCode($error)
               ->setMsg(curl_error($handle) . ' [' . $error . ']')
                  ;
            curl_close($handle);
            return $cn;
        }

        curl_close($handle);
        
        return json_decode($response);
    }

}
