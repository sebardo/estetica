<?php


namespace AppBundle\Services;


use AppBundle\Entity\CreativityOrder;

class GoogleMapsApi
{
	const KEY = 'AIzaSyC7cVZcvx9RQMNMctC9egRB6XL4gsmGrno';

	public static function getGoogleApiLocation($mapAddress)
	{
		$url = "https://maps.googleapis.com/maps/api/geocode/json?key=" . self::KEY . "&sensor=false&address=" . urlencode($mapAddress);
		$latLong = get_object_vars(json_decode(file_get_contents($url)));

		$latitude = (count($latLong['results']) >= 1) ? $latLong['results'][0]->geometry->location->lat : CreativityOrder::DEFAULT_LAT;
		$longitude = (count($latLong['results']) >= 1) ? $latLong['results'][0]->geometry->location->lng : CreativityOrder::DEFAULT_LON;

		return array(
			'latitude' => $latitude,
			'longitude' => $longitude
		);
	}
}