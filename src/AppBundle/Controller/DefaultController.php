<?php

namespace AppBundle\Controller;

use AppBundle\Repository\LocationRepository;
use AppBundle\Repository\RoomMealRepository;
use AppBundle\Repository\RoomRepository;
use AppBundle\Repository\VoteRepository;
use AppBundle\Service\VoteManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Form\Search;
use AppBundle\Entity\Meal;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation\Inject;
use Symfony\Component\Translation\IdentityTranslator;
use AppBundle\Service\MealManager;
use Doctrine\ORM\EntityManager;
use AppBundle\Service\PlaceManager;
use AppBundle\Service\AttachmentManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use AppBundle\Repository\MealRepository;
use AppBundle\Repository\PlaceRepository;
use Knp\Component\Pager\Paginator;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;

/**
 * @Route("")
 */
class DefaultController extends Controller
{
    /**
     * @var IdentityTranslator
     * @Inject("translator")
    */
    protected $identityTranslator = null;

    /**
     * @var MealManager
     * @Inject("service.mealManager")
    */
    protected $mealManager = null;

    /**
     * @var EntityManager
     * @Inject("doctrine.orm.default_entity_manager")
    */
    protected $entityManager = null;

    /**
     * @var PlaceManager
     * @Inject("service.placeManager")
    */
    protected $placeManager = null;

    /**
     * @var AttachmentManager
     * @Inject("service.attachmentManager")
    */
    protected $attachmentManager = null;

    /**
     * @var VoteManager
     * @Inject("service.voteManager")
     */
    protected $voteManager = null;

    /**
     * @var Translator
     * @Inject("translator.default")
     */
    protected $translator = null;

    /**
     * @var Paginator
     * @Inject("knp_paginator")
     */
	protected $paginator = null;
    
    /**
     * Short description
     *
     *
     * @Method({"GET"})
     *
     * @Route("", name="default_index", defaults = {})
     * @Template("AppBundle:Default:index.html.twig")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     *
    */
    public function indexAction(Request $request)
    {
        $roomMeals = is_object($this -> getUser()) ?
            $this -> _getRoomMealRepository() -> getMealsByUserAndDate(new \DateTime(), $this -> getUser()) :
            array()
        ;

        $rooms = is_object($this -> getUser()) ?
            $this -> _getRoomRepository() -> getMyRooms($this -> getUser()) :
            array()
        ;

        $todayVote = $this -> _getVoteRepository() -> getTodayVote(new \DateTime(),$request -> cookies -> get('vote',false));

        $form = $this -> createForm(new Search(), null, array('method' => 'GET'));
        $form->handleRequest($request);
        $form -> isSubmitted();

        $results = $this -> _getMealRepository() -> getList(
			$request -> query -> get('type', 'day'),
			new \DateTime(),
			$request -> query -> get('delivery') ? $request -> query -> get('delivery') : null, 
			$request -> query -> get('local') ? $request -> query -> get('local') : null, 
			$request -> query -> get('saturday') ? $request -> query -> get('saturday') : null, 
			$request -> query -> get('sunday') ? $request -> query -> get('sunday') : null, 
			$request -> query -> get('location') ? $request -> query -> get('location') : null
        );

        $results = $this -> paginator -> paginate($results, $request -> query -> get('page', 1), 200);
        $locations = $this -> _getLocationRepository() -> findAll();
        return array(
            'locations' => $locations,
            'form' => $form -> createView(),
            'results' => $results,
            'todayVote' => $todayVote,
            'rooms' => $rooms,
            'roomMeals' => $roomMeals
        );
	}


    /**
     * Short description
     *
     *
     * @Method({"GET"})
     *
     * @Route("/{id}-{slug}", name="view_meal", defaults = {})
     * @ParamConverter("id", class="AppBundle:Meal", options={"repository_method" = "getMeal"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \AppBundle\Entity\Meal $meal
     * @return array
     */
    public function viewAction(Request $request, Meal $meal)
    {
        $voted = false;
        $currentVoted = false;
        $todayVote = null;
        if($request -> cookies -> get('vote',false))
        {
            $todayVote = $this -> _getVoteRepository() -> getTodayVote(new \DateTime(),$request -> cookies -> get('vote',false));
            $voted = is_object($todayVote);
            $currentVoted = is_object($todayVote) && $todayVote -> getMeal() -> getId() == $meal -> getId();

        }

        $now = new \DateTime();

        $results = $this -> _getMealRepository() -> getMeals(
            $meal -> getId(),
			new \DateTime(),
			$meal -> getPlace(),
			$request -> query -> get('type') ? $request -> query -> get('type') : null
        );

        if($now -> format('Y-m-d') != $meal -> getActiveDate() -> format('Y-m-d'))
        {
            if(count($results))
            {
                return $this -> redirectToRoute('view_meal', array(
                    'id' => $results[0] -> getId(),
                    'slug' => $meal -> getPlace() -> getSeoSlug()
                ));
            } else {
                return $this -> redirectToRoute('default_index', array());
            }
        }

        if($request -> get('slug') != $meal -> getPlace() -> getSeoSlug())
        {
            return $this -> redirectToRoute('view_meal', array(
                'id' => $meal -> getId(),
                'slug' => $meal -> getPlace() -> getSeoSlug()
            ));
        }

        $response = $this -> render('AppBundle:Default:view.html.twig',array(
            'meal' => $meal,
            'results' => $results,
            'voted' => $voted,
            'currentVoted' => $currentVoted,
            'todayVote' => $todayVote
        ));

        if(!$request -> cookies -> get('vote', false))
            $response -> headers -> setCookie(new Cookie("vote", uniqid() . '_' . $now -> getTimestamp(), new \DateTime("+1 year")));

        return $response;
	}


    /**
     * Short description
     *
     * @ApiDoc(
     *    description = "Default:vote description",
     *    section = "Default",
     *    statusCodes = {
     *        200 = "Success",
     *        404 = "Resource was not found"
     *    }
     * )
     *
     * @Method({"POST"})
     *
     * @Route("/vote/{id}", name="default_vote", defaults = {"_format" = "json"})
     * @ParamConverter("id", class="AppBundle:Meal", options={})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \AppBundle\Entity\Meal $meal
     * @return JsonResponse
     */
    public function voteAction(Request $request, Meal $meal)
    {
        $ip = $request -> getClientIp();
        $cookie = $request -> cookies -> get('vote', 'notFound_0');


        $vote = $this -> voteManager -> vote($ip, $cookie, $meal);
        $this -> entityManager -> flush();

        return new JsonResponse(array(
            'status' => $vote,
            'msg' => $this -> translator -> trans('default.vote.success', [], 'messages')
        ), 200);
    }


    /**
     * Short description
     *
     *
     * @Method({"GET"})
     *
     * @Route("/team", name="default_team", defaults = {})
     * @Template("AppBundle:Default:team.html.twig")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     *
     */
    public function teamAction(Request $request)
    {
        return array(

        );
    }


    /**
     * Short description
     *
     * @ApiDoc(
     * 	description = "Default:me description",
     * 	section = "Default",
     * 	statusCodes = {
     * 		200 = "Success",
     * 		401 = "Not authorized",
     * 		403 = "No access"
     * 	}
     * )
     *
     * @Method({"GET"})
     * @Security("has_role('ROLE_USER')")
     * @Route("/me", name="default_me", defaults = {"_format" = "json"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return JsonResponse
     *
     */
    public function meAction(Request $request)
    {
        return new JsonResponse(array(
            'status' => 200,
            'user' => $this -> getUser() -> toArray()
        ), 200);
    }

    /**
     * @return MealRepository
     */
    protected function _getMealRepository()
    {
        return $this -> entityManager -> getRepository("AppBundle:Meal");
    }

    /**
     * @return PlaceRepository
     */
    protected function _getPlaceRepository()
    {
        return $this -> entityManager -> getRepository("AppBundle:Place");
    }

    /**
     * @return VoteRepository
     */
    protected function _getVoteRepository()
    {
        return $this -> entityManager -> getRepository("AppBundle:Vote");
    }

    /**
     * @return LocationRepository
     */
    protected function _getLocationRepository()
    {
        return $this -> entityManager -> getRepository("AppBundle:Location");
    }

    /**
     * @return RoomRepository
     */
    protected function _getRoomRepository()
    {
        return $this -> entityManager -> getRepository("AppBundle:Room");
    }

    /**
     * @return RoomMealRepository
     */
    protected function _getRoomMealRepository()
    {
        return $this -> entityManager -> getRepository("AppBundle:RoomMeal");
    }


}