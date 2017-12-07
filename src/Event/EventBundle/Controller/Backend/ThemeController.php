<?php

namespace Event\EventBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Event\EventBundle\Controller\Controller;
use Event\EventBundle\Entity\Theme;
use Event\EventBundle\Form\Type\ThemeType;
use Guzzle\Http\Client;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class ThemeController extends Controller
{
    public function indexAction()
    {
        return $this->render('EventEventBundle:Backend/Theme:index.html.twig', array(
            'themes' => $this->getRepository('EventEventBundle:Theme')->findAll()
        ));
    }

    public function manageAction(Request $request, $id = null)
    {
        $css_url = '';
        if ($id === null) {
            $theme = new Theme();
            $theme->setChangedFile(true);
        } else {
            $theme = $this->findOr404('EventEventBundle:Theme', $id);
            $css_url = $this->getUploadUrl() . $theme->getTitle() . '/css/style.css';
        }

        $form = $this->createForm(ThemeType::class, $theme);

        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                if ($theme->getChangedFile()) {
                    $url = $request->request->get('file_url');
                    $path = $this->getUploadPath();
                    $file = $this->getFile($url);
                    $fs = new Filesystem();
                    try {
                        $fs->dumpFile($path . $theme->getTitle() . '/css/style.css', $file, 0777);
                    } catch (IOExceptionInterface $e) {
                        throw new Exception($e->getMessage());
                    }
                }

                $this->getManager()->persist($theme);
                $this->getManager()->flush();

                $successFlashText = sprintf('Theme %s updated.', $theme->getTitle());
                if (!$id) {
                    $successFlashText = sprintf('Theme %s added.', $theme->getTitle());
                }
                $this->setSuccessFlash($successFlashText);

                return $this->redirectToRoute('backend_theme');
            }
        }

        return $this->render('EventEventBundle:Backend/Theme:manage.html.twig', [
            'theme' => $theme,
            'form' => $form->createView(),
            'fs_api' => $this->container->getParameter('filestack.api_key'),
            'css_url' => $css_url,
        ]);
    }

    public function deleteAction($id)
    {
        $this->isGrantedAdmin();

        $entity = $this->findOr404('EventEventBundle:Theme', $id);
        $translator = $this->get('translator');
        try {
            $fs = new Filesystem();
            $fs->remove($this->getUploadPath() . DIRECTORY_SEPARATOR . $entity->getTitle());
        } catch (IOExceptionInterface $e) {
            throw new Exception($e->getMessage());
        }

        $this->getManager()->remove($entity);
        $this->getManager()->flush();


        $this->setSuccessFlash($translator->trans('Theme deleted.'));

        return $this->redirectToRoute('backend_theme');
    }

    public function getUploadPath(){
        return $this->container->getParameter('themes.uploadPath');
    }

    public function getUploadUrl(){
        return $this->container->getParameter('themes.uploadUrl');
    }

    protected function getFile($url)
    {
        $client = new Client();
        $file = $client->createRequest('GET', $url)->send()->getBody(true);
        return $file;
    }

}
