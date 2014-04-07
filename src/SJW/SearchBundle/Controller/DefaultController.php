<?php

namespace SJW\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     *
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/api/search")
     *
     * @Template()
     */
    public function searchAction(Request $request) {
        // Get the search string from the UI.
        $searchString = $request->query->get('q');
		
		//Get request session and if session = null then declare a 'number_range' to be 10
		$session = $this->get('request');
		$session = $session->getSession();
		if($session->get('number_range')==null)
			$session->set('number_range', 10);

		$how_many = $session->get('number_range');
		
        // Read resource file.
        $kernel = $this->get('kernel');

        $filePath = $kernel->locateResource('@SJWSearchBundle/Resources/data/numbers.txt');

        // Split lines and comma delimited values.
        $lines = explode("\n", file_get_contents($filePath, true));

        $numbers = array();

        foreach($lines as $line) {
            $numbers[] = explode(';', $line);
        }

        // TODO: Implement search based on query string.
		
		//explode the search string because the input is preformatted with autocomplete
		$searchArr = explode(',', $searchString);
		
		//compare function to compare multidimensional arrays
		$compare = function ($a, $b) {
           return (intval($a[2]) > intval($b[2])) ? -1 : 1;
        };
		
		//Sort numbers at first to ease the closest areas range
		usort($numbers, $compare);
		
		$helperArray = array();
		foreach($numbers as $key => $number){
			if($number[0] == $searchArr[0] || $number[1] == $searchArr[1]){//If the zipcode [0] or the name [1] matches the search,  the autocomplete has preformatted the strings
				$helperArray[] = $numbers[$key];
				array_push($helperArray[0], "true");//The right answer is the first
				for($i = 1; $i <= $how_many; $i++){
					$helperArray[] = $numbers[$key+$i];//Get n numbers of bigger areas
					$helperArray[] = $numbers[$key-$i];//Get n numbers of smaller areas
				}
				usort($helperArray, $compare);//sort after that because we didnt take care of the indexes while pushing
			}
		}
		
        // Output content.
        return new JsonResponse($helperArray);
    }
	/**
     * @Route("/api/autocomplete")
     *
     * @Template()
     */
    public function autocompleteAction(Request $request) {//autocomplete action just to give an json to jquery ui
        $searchString = $request->query->get('q');
        $kernel = $this->get('kernel');
        $filePath = $kernel->locateResource('@SJWSearchBundle/Resources/data/numbers.txt');
        $lines = explode("\n", file_get_contents($filePath, true));
        $numbers = array();
        foreach($lines as $line) {
           $numbers[] = explode(';', $line);
        }
        return new JsonResponse($numbers);
    }
	
	/**
	* @Route("/settings", name="_settings")
	*
	* @Template()
	*/
    public function settingsAction() {
        return array();
    }
	
		/**
     * @Route("/settings/settingsSet", name="_settingsSet")
     *
     * @Template()
     */
	 public function settingsSetAction(Request $request){
		
		//create session and redirect back to the base index
		$session = $this->get('request');
		$session = $session->getSession();

		$session->set('number_range', $request->request->get('number_range'));
		$session->get('number_range');
		return $this->redirect($request->getBaseUrl());
	 }

}
///challenge/web/app_dev.php/api/search?q=00520
