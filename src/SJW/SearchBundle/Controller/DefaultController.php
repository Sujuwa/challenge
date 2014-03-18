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

        foreach($lines as $line) {
            $numbers[] = explode(';', $line);
        }

        // TODO: Implement search based on query string.

        // Output content.
        return new JsonResponse($numbers);
	//$result_table = 'sfsdffsdsdfdssdfsdfdsf';
        //$this->get('session')->getFlashBag()->set('result_table', $result_table);

        //return $this->redirect($this->generateUrl(''), array('result_table' => $result_table));
	//return new RedirectResponse($this->generateUrl('_search'));
	//return $this->render('SJWSearchBundle:Default:search.html.twig', array('result_table' => $result_table));
	//return array('result_table' => $result_table);
    }
}
