<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Abraham\TwitterOAuth\TwitterOAuth;

class TestController extends AbstractActionController
{
    public function indexAction()
    {

    	$arr_request = [
    		"screen_name" => isset($_POST["account"]) ? $_POST["account"] : false
    	];

    	$result = false;
    	$url = false;
    	$geo = false;

    	if($arr_request["screen_name"]) {
	    	$connection = new TwitterOAuth(
	    		"znN1sWgX5Ik6CMXxhJ1WcKX7t", 
	    		"HgOkWS7ylr6gLR8y7xqCT6L3JJWD590wTHPN0vmnQ0qUX7GSj7", 
	    		"142377735-bS1lUi4Fgprhv3witT5xqGZsKee6bXz0CF6YTfsc",
	    		"5zZksjnKCLr0UrBM6B7dr5jzSHSJiLMzoV4dRr4A8rjCS");
	    	$statuses = $connection->get("users/lookup", $arr_request);

	    	

	    	if(strtolower($statuses[0]->screen_name) == strtolower($arr_request["screen_name"]))
			{

	    		$result = $statuses[0];

	    		$geo = $this->extractLL($result->location);

	    		if($geo["lat"] && $geo["long"]) {
		    		$url = "https://maps.google.com/maps?ll=".$geo["lat"].",".$geo["long"]."&amp;ie=UTF8&amp;t=&amp;z=14&amp;iwloc=A&amp;output=embed";
		    		echo $url;
			    } else {
		    		$url = "https://maps.google.com/maps?width=100%&amp;height=600&amp;hl=en&amp;q=".$result->location."+(Twitter%20Geo%20Location)&amp;ie=UTF8&amp;t=&amp;z=14&amp;iwloc=B&amp;output=embed";
		    	}
	    	}
	    }

    	// var_dump($result);
	    

        return new ViewModel(["result" => $result, "location_url" => $url]);
    }

    private function extractLL($str) {
    	$lat = false;
    	$long = false;

    	preg_match('/([0-9.-]+).+?([0-9.-]+)/', $str, $matches);

    	if(isset($matches[1]))
    		$lat=(float)$matches[1];

    	if(isset($matches[2]))
			$long=(float)$matches[2];

		return array("lat"=>$lat,"long"=>$long);
    }
}
