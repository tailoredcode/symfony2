<?php

namespace AppBundle\Service;

use JMS\DiExtraBundle\Annotation\Service as JMSService;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Inject;
use Swift_Message;
use Doctrine\ORM\EntityManager;
use Twig_Environment;
use AppBundle\Service\MealManager;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Swift_Mailer;

use AppBundle\Entity\Place;

/**
 * @JMSService("service.mailerManager")
 */
class MailerManager
{
            
    /**
    * @var EntityManager
    */
    protected $entityManager = null;

    /**
    * @var Twig_Environment
    */
    protected $twigEnvironment = null;

    /**
    * @var MealManager
    */
    protected $mealManager = null;

    /**
    * @var Translator
    */
    protected $translator = null;

    /**
    * @var Swift_Mailer
    */
    protected $swiftMailer = null;

                
    /**
     * @InjectParams({
     *    "entityManager" = @Inject("doctrine.orm.default_entity_manager"),
     *    "twigEnvironment" = @Inject("twig"),
     *    "mealManager" = @Inject("service.mealManager"),
     *    "translator" = @Inject("translator.default"),
     *    "swiftMailer" = @Inject("mailer")
     * })
    */
    public function __construct(EntityManager $entityManager, Twig_Environment $twigEnvironment, MealManager $mealManager, Translator $translator, Swift_Mailer $swiftMailer)
    {
        $this -> entityManager = $entityManager;
        $this -> twigEnvironment = $twigEnvironment;
        $this -> mealManager = $mealManager;
        $this -> translator = $translator;
        $this -> swiftMailer = $swiftMailer;
    }
    
                
    public function remind(Place $place)
    {
        $message = Swift_Message::newInstance()
            -> setSubject($this -> translator -> trans('email.remind.subject',[], 'messages'))
            -> setTo($place -> getEmail())
            -> setFrom(array( 'email@example.com' => $this -> translator -> trans('email.remind.senderName',[], 'messages') ))
            -> setBody($this -> twigEnvironment -> render(
                "AppBundle:Emails:remind.html.twig",
                array(
					'place' => $place
                )
            ), 'text/html')
        ;
        $this -> swiftMailer -> send($message);
    }
    
            
    
}
