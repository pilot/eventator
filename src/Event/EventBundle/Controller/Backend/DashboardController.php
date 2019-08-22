<?php

namespace Event\EventBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Event\EventBundle\Controller\Controller;

class DashboardController extends Controller
{
    public function indexAction()
    {
        return $this->render('EventEventBundle:Backend/Dashboard:index.html.twig', []);
    }

    public function settingAction(Request $request)
    {
        // @todo: add settings management

        return $this->render('EventEventBundle:Backend/Dashboard:setting.html.twig', []);
    }

    public function localeTabsAction($translations)
    {
        $configLocales = $this->container->getParameter('event.locales');

        $locales = [];
        foreach ($translations as $translation) {
            $locales[] = $configLocales[$translation->getLocale()];
        }

        return $this->render('EventEventBundle:Backend:_tabs.html.twig', [
            'locales' => $locales
        ]);
    }

    /**
     * Handle that new locales was added to the configuration, to init it
     */
    protected function addLocales()
    {
        // @todo: add locales when locales configuration is updated
    }
}
