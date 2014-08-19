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

    protected $msg;

    protected $code;

    protected $client;

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

    public function getMsg()
    {
        return $this->msg;
    }

    public function setMsg($msg)
    {
        $this->msg = $msg;
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

}
