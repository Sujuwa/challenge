<?php

namespace SJW\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="_index")
     *
     * @Template()
     */
    public function indexAction()
    {
	return array();
    }

    /**
     * @Route("/api/search", name="_search")
     *
     * @Template()
     */
    public function searchAction(Request $request) {
        // Get the search string from the UI.
        $searchString = $request->query->get('q');

        // Read resource file.
        $kernel = $this->get('kernel');

        $filePath = $kernel->locateResource('@SJWSearchBundle/Resources/data/numbers.txt');

        // Split lines and comma delimited values.
        $lines = explode("\n", file_get_contents($filePath, true));

        $numbers = array();
	$res = array();

        foreach($lines as $line) {
            $numbers[] = explode(';', $line);
        }
/*
	usort($numbers, function($a, $b) {
	    return $a[2] - $b[2];
	});
*/

	$res_size = 0;
	if (!preg_match('/[^0-9]/', $searchString)) {
	  // zip code
		for ($i = 0; $i < count($lines); $i++) {
			if (strpos($numbers[$i][0], $searchString) === 0) {
				$el = array();
				for ($j = 0; $j < 5; $j++) {
					$el[$j] = $numbers[$i+2-$j];
				}
				$res[$res_size] = $el;
				$res_size = $res_size + 1;
			}
		}
	} else if (!preg_match('/[^A-Za-z]/', $searchString)) {
	  // town
	} else {
	  // combination of them
	}


        // TODO: Implement search based on query string.

        // Output content.
        return new JsonResponse($res);
    }
}
