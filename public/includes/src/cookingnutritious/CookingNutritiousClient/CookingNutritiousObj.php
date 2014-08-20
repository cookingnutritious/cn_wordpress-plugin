<?php

/**
 * CookingNutritiousObj
 *
 * Object model for results from the cookingnutritious rest API
 *
 * @version 1.0
 * @author jgreathouse
 */

namespace cookingnutritious\CookingNutritiousClient;


class CookingNutritiousObj
{
    
    protected $stat = null;
    
    protected $error;

    protected $code;

    protected $client;
    
    protected $response = null;

    public function getClient()
    {
        return unserialize(base64_decode($this->client));
    }

    public function setClient($client)
    {
        $this->client = base64_encode(serialize($client));
        return $this;
    }

    public function getStat()
    {
        return $this->stat;
    }

    public function setStat($stat)
    {
        $this->stat = $stat;
        return $this;
    }

    public function getError()
    {
        return $this->error;
    }

    public function setError($error)
    {
        $this->error = $error;
        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }
 
    public function getResponse()
    {
        return $this->response;
    }
   
    public function setResponse($response)
    {
        $this->response = $response;
        return $this;
    }

}
