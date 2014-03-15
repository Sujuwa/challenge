<?php

namespace SJW\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController
 * @package SJW\SearchBundle\Controller
 */
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
    public function searchAction(Request $request)
    {
        // Get the search string from the UI.
        $searchString = null;
        if ($request->getMethod() == 'POST') {
            $searchString = $request->get('q');

        }
        if (trim($searchString) == '') {
            // No criteria given
            return new JsonResponse();
        }

        // Match range is population of 5% in range.
        // Return 10 nearest results.
        $numbers = $this->searchExact($searchString, 10);

        // Output content.
        return new JsonResponse(array('numbers' => $numbers));
    }

    /**
     * @Route("/api/autocomplete")
     *
     * @Template()
     */
    public function autocompleteAction(Request $request)
    {
        // Get the search string from the UI.
        $searchString = null;
        if ($request->getMethod() == 'GET') {
            $searchString = $request->query->get('term');
        }
        if (trim($searchString) == '') {
            // No criteria given
            return new JsonResponse();
        }

        $numbers = $this->searchAutocomplete($searchString);

        // Output content.
        $out = array();
        foreach ($numbers as $numberData) {
            $obj = new \stdClass();
            $obj->id = $numberData[0]; // zip
            $obj->value = $numberData[1]; // name
            $obj->label = $numberData[1] . ' (' . $numberData[0] . ')'; // name (zip)
            $out[] = $obj;
        }
        return new JsonResponse($out);
    }

    /**
     * @param $searchString
     * @return array
     */
    protected function searchAutocomplete($searchString)
    {
        if ($lines = $this->get('cache')->fetch('lines')) {
            $lines = unserialize($lines);
        } else {
            // Read resource file.
            $kernel = $this->get('kernel');

            $filePath = $kernel->locateResource('@SJWSearchBundle/Resources/data/numbers.txt');

            // Split lines and comma delimited values.
            $lines = explode("\n", file_get_contents($filePath, true));
            foreach ($lines as $idx => $line) {
                $lines[$idx] = explode(';', $line);
            }

            $this->get('cache')->save('lines', serialize($lines));
        }

        $numbers = array();

        // Search based on query string.
        foreach ($lines as $data) {
            $match = false;
            if (preg_match('/^\d{5}$/', $searchString) && $searchString == $data[0]) {
                $match = true;
            }
            if (!$match && stristr($data[1], $searchString) !== false) {
                $match = true;
            }
            if ($match) {
                $data[2] = (int)$data[2];
                $numbers[] = $data;
            }
        }

        $compare = function ($a, $b) {
            return ($a[1] < $b[1]) ? -1 : 1;
        };
        usort($numbers, $compare);

        return $numbers;
    }

    /**
     * @param string $searchString
     * @param int $count
     * @return array
     */
    protected function searchExact($searchString, $count)
    {
        if ($lines = $this->get('cache')->fetch('linesBySize')) {
            $lines = unserialize($lines);
        } else {
            // Read resource file.
            $kernel = $this->get('kernel');

            $filePath = $kernel->locateResource('@SJWSearchBundle/Resources/data/numbers.txt');

            // Split lines and comma delimited values.
            $lines = explode("\n", file_get_contents($filePath, true));
            foreach ($lines as $idx => $line) {
                $lines[$idx] = explode(';', $line);
                if (!isset($lines[$idx][2])) {
                    unset($lines[$idx]);
                }
            }

            $sortClosure = function($a, $b) {
                return ((int)$a[2] > (int)$b[2]) ? -1 : 1;
            };
            usort($lines, $sortClosure);

            $this->get('cache')->save('linesBySize', serialize($lines));
        }

        // Search based on query string.
        // Match the input parameter
        $matchingIdx = null;
        $matchingData = null;
        foreach ($lines as $idx => $matchingData) {
            if (preg_match('/^\d{5}$/', $searchString) && $searchString == $matchingData[0]) {
                $matchingIdx = $idx;
                break;
            }
            if (mb_strtolower($matchingData[1], 'utf-8') == mb_strtolower($searchString, 'utf-8')) {
                $matchingIdx = $idx;
                break;
            }
        }
        if ($matchingIdx === null) {
            return false;
        }

        $startFrom = $matchingIdx - (int)floor($count / 2) + 1;
        if ($startFrom < 0) {
            $startFrom = 0;
        }
        $numbersOut = array_slice($lines, $startFrom, $count, true);
        $numbersOut[$matchingIdx][3] = true;    // We preserved keys on array_slice() to make this possible.

        return $numbersOut;
    }

}
