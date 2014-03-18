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

	foreach ($numbers as $val)
	$tmp_pop[] = end($val);

	array_multisort($tmp_pop, SORT_DESC, $numbers);

	$res_size = 0;
	$N = 5;
	if (!preg_match('/[^0-9]/', $searchString)) {
	  // zip code
		for ($i = 0; $i < count($lines); $i++) {
			if (strpos($numbers[$i][0], $searchString) === 0) {
				$el = array();
				for ($j = 0; $j < $N; $j++) {
					$el[$j] = $numbers[$i+($N/2)-$j];
				}
				$res[$res_size] = $el;
				$res_size = $res_size + 1;
			}
		}
	} else if (!preg_match('/[^A-Za-z]/', $searchString)) {
	  // town
		for ($i = 0; $i < count($lines); $i++) {
			if (strpos($numbers[$i][1], $searchString) === 0) {
				$el = array();
				for ($j = 0; $j < $N; $j++) {
					$el[$j] = $numbers[$i+($N/2)-$j];
				}
				$res[$res_size] = $el;
				$res_size = $res_size + 1;
			}
		}
	}/* else {
	  // combination of them
		for ($i = 0; $i < count($lines); $i++) {
			if (strpos($numbers[$i][0].' '.$numbers[$i][1], $searchString) === 0) {
				$el = array();
				for ($j = 0; $j < $N; $j++) {
					$el[$j] = $numbers[$i+($N/2)-$j];
				}
				$res[$res_size] = $el;
				$res_size = $res_size + 1;
			}
		}
	}*/


        // TODO: Implement search based on query string.

        // Output content.
        return new JsonResponse($res);
    }
}
