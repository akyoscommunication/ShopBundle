<?php


namespace Akyos\ShopBundle\Service;

use Akyos\CoreBundle\Repository\CoreOptionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Swift_Mailer;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

class Mailer
{
    /** @var EntityManagerInterface */
    private $em;
    /** @var CoreOptionsRepository */
    private $coreOptionsRepository;
    /** @var RequestStack */
    private $request;
    /** @var Environment */
    private $twig;
    /** @var Swift_Mailer */
    private $mailer;

    public function __construct(EntityManagerInterface $em, CoreOptionsRepository $coreOptionsRepository, RequestStack $request, Environment $twig, Swift_Mailer $mailer)
    {
        $this->em = $em;
        $this->coreOptionsRepository = $coreOptionsRepository;
        $this->request = $request;
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendMessage($to, $subject, $template, $el, $body = NULL, $sender = NULL, $bcc = NULL, $attachment = NULL, array $options = NULL)
    {
        $coreOptions = $this->coreOptionsRepository->findAll();
        if($coreOptions) {
            $coreOptions = $coreOptions[0];
        }

        $host = $this->request->getCurrentRequest()->getHost();
        $host = explode('.', $host);
        if ((count($host) > 2) && ($host[0] === 'www')) {
            $host = $host[1].'.'.$host[2];
        } else {
            $host = implode('.', $host);
        }

        $message = (new \Swift_Message($subject))
            ->setFrom(['noreply@'.$host => ($coreOptions ? $coreOptions->getSiteTitle() : 'noreply')])
            ->setTo($to)
            ->setBody($this->twig->render($template, [
                'el' => $el,
                'body' => $body
            ]), 'text/html'
            );

        try {
            $this->mailer->send($message);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}