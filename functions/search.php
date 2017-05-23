<?PHP
/*
** Determine whether ZIP, Postal, or City was Entered
*/

/*
** If string is all alpha, it's city - if it's all numeric it's a zipcode, if it's alphanum then it's a Postal.
*/

function new_search($query){
	global $db;
	
	if(ctype_alpha($query)){
		//Query is a City.
		$state = 'city';
	}else{
		//check if it's a zip(easier to identify than a postal)
		if(is_numeric($query)){
			//Query is a ZIP Code
			$state = 'zip';
		}else{
			//By default we're left with a Postal Code.
			//Still do a check though
			$query = trim($query);
			
			$query = str_replace(' ', '', $query);
			$query = str_replace('-', '', $query);
			if(strlen($query)==6){
				//Not a string.
				$state = 'post';
			}else{
				$error = 'Invalid Input - search by City, Postal Code, or Zip Code.';
			}
		}
	}
	
	if(!isset($error)){
		
		switch($state){
			case 'city':
				//Already a city - pull all local trucks, and return the results.
				$stmt = $db->prepare("SELECT truckname,longitude,latitude,description FROM truck_users WHERE city= :city AND onoff='1' AND active='1'");
				$stmt->bindParam(':city', $query);
				$stmt->execute();
				
				$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
				
				$city = $query;
				$country = '';
				break;
			
			case 'zip':
				//Determine city.
				$zip = new PDO('sqlite:zipcodes.db');
				$stmt = $zip->prepare("SELECT city FROM zipcodes WHERE zipcode= :zip LIMIT 1");
				$stmt->bindParam(':zip', $query);
				$stmt->execute();
				$city = $stmt->fetchAll(PDO::FETCH_ASSOC);
				
				$city = strtolower(str_replace(' ','+',$city[0]['city']));
				$country = 'usa';
				$zip = null;
				
				//get trucks from city.
				$stmt = $db->prepare("SELECT truckname,longitude,latitude,description FROM truck_users WHERE city= :city AND onoff='1' AND active='1'");
				$stmt->bindParam(':city', $city);
				$stmt->execute();
				
				$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
				break;
				
			case 'post':
				//Determine city - only need First 3 characters.
				$postal = substr($query,0,3);
				
				$city = json_decode(file_get_contents('http://api.zippopotam.us/ca/'.$postal), true);
				
				$country = $city['country'];
				$city = $city['places'][0]['place name'];
				$city = explode('(', $city);
				$city = trim($city[0]);
				
				//Got city - get the trucks from city.
				$stmt = $db->prepare("SELECT truckname,longitude,latitude,description FROM truck_users WHERE city= :city AND onoff='1' AND active='1'");
				$stmt->bindParam(':city', $city);
				$stmt->execute();
				
				$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
				break;
			
			default:
				break;
		}
		$return = array($city,$country,$results);
		return $return;
	}else{
		return $error;
	}
}


function geocode_city($city, $country){
	global $geocode_api;
	
	if(empty($country)){
		$country = '';
	}
	
	$location = $city.'+'.$country;
	$url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$location.'&key='.$geocode_api;
	
	$response = json_decode(file_get_contents($url),true);
	
	
	$citylat = $response['results'][0]['geometry']['location']['lat'];
	$citylong = $response['results'][0]['geometry']['location']['lng'];
	
	$center = array($citylat,$citylong);
	
	return $center;
}
?>