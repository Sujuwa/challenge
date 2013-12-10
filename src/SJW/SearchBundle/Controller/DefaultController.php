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
     * @Route("/", name="home")
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

        $session = $this->getRequest()->getSession();
        $limit = ($session->get('maximumRows') =='') ? 20 : $session->get('maximumRows');

        // Read resource file.
        $kernel = $this->get('kernel');

        $filePath = $kernel->locateResource('@SJWSearchBundle/Resources/data/numbers.txt');

        // Split lines and comma delimited values.
        $lines = explode("\n", file_get_contents($filePath, true));

        $numbers = array();
        $response = array();
        $allTowns = array();

        foreach($lines as $k=>$line) {
            $numbers = explode(';', $line);

            //first put everything in one big associative array
            $allTowns[$k]['zip'] = (isset($numbers[0]) ? $numbers[0] : false);
            $allTowns[$k]['name'] = (isset($numbers[1]) ? $numbers[1] : false);
            $allTowns[$k]['population'] = (isset($numbers[2]) ? $numbers[2] : false);
        }

        //sort array by population
        $this->aasort($allTowns,"population");
        $allTowns = array_reverse($allTowns);

        foreach($allTowns as $k=>$town){
            //be smart here and check %string%
            if ($town['zip']==$searchString
                || $town['name']==$searchString
                || strpos($town['name'], $searchString)===(int)0
                || strpos($town['name'], $searchString)!=false){

                //this one is correct result
                $town['correct'] = 'true';
                $response[] = $town;

                //unset it from all towns
                unset($allTowns[$k]);

                //use user set limit here to break out of the loop even with correct results
                if(count($response)>=$limit) break;
            }
        }

        if(count($response)>0 && count($response)<$limit){
            //if there is space for those with similar population
            foreach($allTowns as $k=>$town){
                if($response[0]['population']<$town['population']){
                    continue;
                }else{
                    $town['correct'] = 'false';
                    $response[] = $town;
                }
                if(count($response)>=$limit) break;
            }
        }

        // Output content.
        return new JsonResponse($response);
    }

    /**
     * @Route("/settings/", name="settings")
     *
     * @Template()
     */
    public function settingsAction(Request $request) {
        $session = $this->getRequest()->getSession();

        if($request->query->get('maximumRows')){
            $session->getFlashBag()->add('success', 'Maximum rows limit changed successfully!');
            $session->set('maximumRows', $request->query->get('maximumRows'));
            return $this->redirect($this->generateUrl('home'), 301);
        }


    }

    private function aasort (&$array, $key) {
        $sorter=array();
        $ret=array();
        reset($array);
        foreach ($array as $ii => $va) {
            $sorter[$ii]=$va[$key];
        }
        asort($sorter);
        foreach ($sorter as $ii => $va) {
            $ret[$ii]=$array[$ii];
        }
        $array=$ret;
    }
}
