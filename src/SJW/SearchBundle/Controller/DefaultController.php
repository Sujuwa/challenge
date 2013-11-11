<?php

namespace SJW\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;

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
     * @Route("/api/numbers")
     *
     * @Template()
     */
    public function numbersAction() {
        // Read resource file.
        $kernel = $this->get('kernel');

        $filePath = $kernel->locateResource('@SJWSearchBundle/Resources/data/numbers.txt');

        // Split lines and comma delimited values.
        $lines = explode("\n", file_get_contents($filePath, true));

        $numbers = array();

        foreach($lines as $line) {
            $numbers[] = explode(';', $line);
        }

        // Output content.
        return new JsonResponse($numbers);
    }
}
