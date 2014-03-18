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
            $numbers[] = explode(';', $line.';0');
        }

        foreach ($numbers as $val)
        $tmp_pop[] = $val[2];

        array_multisort($tmp_pop, SORT_ASC, $numbers);

        $res_size = 0;
        $N = 5;
        $col_index = 0;

        if (!preg_match('/[^0-9]/', $searchString))
            $col_index = 0;
        else if (!preg_match('/[^A-Za-z]/', $searchString))
            $col_index = 1;
        else
            // What we're supposed to do in this case?
            return new JsonResponse($res);

        for ($i = 0; $i < count($lines); $i++) {
            if (strpos(strtolower($numbers[$i][$col_index]), strtolower($searchString)) === 0) {
                $el = array();
                $numbers[$i][3] = 1;

                for ($j = 0; $j < $N; $j++) {
                    if (isset($numbers[$i+$j-(($N-1)/2)]))
                        $el[$j] = $numbers[$i+$j-(($N-1)/2)];
                }
                $res[$res_size] = $el;
                $res_size = $res_size + 1;
            }
        }

        return new JsonResponse($res);
    }
}
