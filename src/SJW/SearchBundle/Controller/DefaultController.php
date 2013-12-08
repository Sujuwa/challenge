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
        $searchString = trim($request->query->get('q'));

        // Read resource file.
        $kernel = $this->get('kernel');

        $filePath = $kernel->locateResource('@SJWSearchBundle/Resources/data/numbers.txt');

        // Split lines and comma delimited values.
        $lines = explode("\n", file_get_contents($filePath, true));

        $numbers = array();
        $response = array();

        foreach($lines as $k=>$line) {
            $numbers = explode(';', $line);

            if ($numbers[0]==$searchString
                || $numbers[1]==$searchString
                || strpos($numbers[1], $searchString)===(int)0
                || strpos($numbers[1], $searchString)!=false){
                $response[$k]['zip'] = (isset($numbers[0]) ? $numbers[0] : false);
                $response[$k]['city'] = (isset($numbers[1]) ? $numbers[1] : false);
                $response[$k]['population'] = (isset($numbers[2]) ? $numbers[2] : false);
            }
        }

        if (length($response)>20){
           //use infinite scroll
        }


        // TODO: Implement search based on query string.

        // Output content.
        return new JsonResponse($response);
    }
}
