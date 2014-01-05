<?php

namespace twentysteps\Bundle\WeMonItBundle\Services;

/**
 * Class WeMonItService
 *
 * This service fetches data from the WeMonIt API
 */
class WeMonItService {

	protected $logger=null;
    protected $cache=null;
    protected $client=null;
    protected $stopwatch=null;

    protected $apiKey=null;
    protected $timeout=null;
    protected $connectTimeout=null;
    protected $cacheTTL=null;

	public function __construct($logger,$cache,$client,$apiKey,$timeout,$connectTimeout,$cacheTTL,$stopwatch=null)
    {
        $this->logger=$logger;
        $this->cache=$cache;
        $this->client=$client;
        $this->stopwatch=$stopwatch;
        $this->apiKey=$apiKey;
        $this->client=$client;
        $this->timeout=$timeout;
        $this->connectTimeout=$connectTimeout;
    }

    public function getService($id) {
    	if ($this->stopwatch) { $this->stopwatch->start('twentysteps\Bundle\WeMonItBundle\Services\WeMonItService.getService','20steps'); };
    	$call="service/view?apikey=".$this->apiKey.'&id='.$id;
    	$cacheKey='ts_wm:'.$call;
        $service=null;
        if (false === ($service = $this->cache->fetch($cacheKey))) {
            $this->logger->info('Calling WeMonIt API: '.$call);
	        $request = $this->client->get($call,
	            array(), 
	            array(
	                'timeout'         => $this->timeout,
	                'connect_timeout' => $this->connectTimeout
	        ));
	        $response=$request->send()->json();
	        $service=$response;
	        $service['images']['uptime4']='http://www.wemonit.de/'.$service['images']['uptime4'];
	        $service['images']['latency4']='http://www.wemonit.de/'.$service['images']['latency4'];
	        $this->logger->info("WeMonIt returned ".var_export($service,true));
            $this->cache->save($cacheKey, $service, $this->cacheTTL);
        }
    	if ($this->stopwatch) { $this->stopwatch->stop('twentysteps\Bundle\WeMonItBundle\Services\WeMonItService.getService','20steps'); };
        return $service;
    }

    public function getServices() {
    	if ($this->stopwatch) { $this->stopwatch->start('twentysteps\Bundle\WeMonItBundle\Services\WeMonItService.getServices','20steps'); };
        $call="service/list?apikey=".$this->apiKey;
		$this->logger->info('Calling WeMonItAPI: '.$call);
        $request = $this->client->get($call,
            array(), 
            array(
                'timeout'         => $this->timeout,
                'connect_timeout' => $this->connectTimeout
        ));
        $response=$request->send()->json();
	    $this->logger->info("WeMonIt returned ".var_export($service,true));
    	if ($this->stopwatch) { $this->stopwatch->start('twentysteps\Bundle\WeMonItBundle\Services\WeMonItService.getServices','20steps'); };
        return $response;
    }
 }