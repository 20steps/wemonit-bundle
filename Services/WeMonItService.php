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
            try {
		        $request = $this->client->get($call,
		            array(), 
		            array(
		                'timeout'         => $this->timeout,
		                'connect_timeout' => $this->connectTimeout
		        ));
		    } catch (\Exception $e) {
		    	$this->logger->info($e->getMessage());
		    	return null;
		    } catch (Guzzle\Http\Exception\CurlException $e){
		    	$this->logger->info($e->getMessage());
		    	return null;
		    }
	        $response=$request->send()->json();
	        $service=$response;
	        if (array_key_exists('images',$service)) {
		        $service['images']['uptime4']='http://www.wemonit.de/'.$service['images']['uptime4'];
		        $service['images']['latency4']='http://www.wemonit.de/'.$service['images']['latency4'];	        	
	        }
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
		try {
	        $request = $this->client->get($call,
	            array(), 
	            array(
	                'timeout'         => $this->timeout,
	                'connect_timeout' => $this->connectTimeout
	        ));
	        $response=$request->send()->json();
        } catch (\Exception $e) {
	    	$this->logger->info($e->getMessage());
	    	return null;
	    } catch (Guzzle\Http\Exception\CurlException $e){
	    	$this->logger->info($e->getMessage());
	    	return null;
	    }
	    $this->logger->info("WeMonIt returned ".var_export($service,true));
    	if ($this->stopwatch) { $this->stopwatch->start('twentysteps\Bundle\WeMonItBundle\Services\WeMonItService.getServices','20steps'); };
        return $response;
    }

    public function getIPv4UptimePercent($id) {
    	$service=$this->getService($id);
    	if ($service && array_key_exists('stats',$service)) {
    		return $service['stats']['uptimePercent4'];
    	}
    	return null;
    }

    public function getIPv4DowntimePercent($id) {
    	$service=$this->getService($id);
    	if ($service && array_key_exists('stats',$service)) {
    		return $service['stats']['downtimePercent4'];
    	}
    	return null;
    }

    public function getIPv4Downtime($id) {
    	$service=$this->getService($id);
    	if ($service && array_key_exists('stats',$service)) {
    		return $service['stats']['downtime4'];
    	}
    	return null;
    }

    public function getAge($id) {
    	$service=$this->getService($id);
    	//var_dump($service);
    	if ($service && array_key_exists('stats',$service)) {
    		return $service['stats']['age'];
    	}
    	return null;
    }

    public function getIPv4ResponseDuration($id) {
    	$service=$this->getService($id);
    	if ($service && array_key_exists('stats',$service)) {
    		return $service['current'];
    	}
    	return null;
    }

    public function getIPv6ResponseDuration($id) {
    	$service=$this->getService($id);
    	if ($service && array_key_exists('stats',$service)) {
    		return $service['current6'];
    	}
    	return null;
    }

    public function getIPv4LastDowntime($id) {
    	$service=$this->getService($id);
    	if ($service && array_key_exists('stats',$service)) {
    		return $service['last_error'];
    	}
    	return null;
    }

    public function getIPv6LastDowntime($id) {
    	$service=$this->getService($id);
    	if ($service && array_key_exists('stats',$service)) {
    		return $service['last_error6'];
    	}
    	return null;
    }
    
 }