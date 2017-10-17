<?php

namespace Event\EventBundle\Controller\Backend;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Event\EventBundle\Controller\Controller;
use Event\EventBundle\Entity\Media;
use Event\EventBundle\Form\Type\MediaType;

class MediaController extends Controller
{

    public function getUploadPath()
    {
        return $this->get('kernel')->getRootDir() . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .
            'web' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR;
    }
    
    public function getUploadUrl(){
        return '/uploads/media/';
    }

    public function indexAction()
    {
        return $this->render('EventEventBundle:Backend/Media:index.html.twig', array(
            'media' => $this->getRepository('EventEventBundle:Media')->findAll(),
            'uploadPath' => $this->getUploadPath(),
            'uploadUrl' => $this->getUploadUrl()
        ));
    }

    public function manageAction(Request $request, $id = null)
    {
        $oldFileName = '';
        if ($id === null) {
            $entity = new Media();
        } else {
            $entity = $this->findOr404('EventEventBundle:Media', $id);
            $oldFileName = $entity->getFilename();
        }

        $form = $this->createForm(MediaType::class, $entity);

        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                if(is_null($entity->getId())){
                    $entity->setCreatedDate(new \DateTime());
                }

                if($oldFileName !== $entity->getFilename()) {
                    $url = $request->request->get('file_url');
                    $path = $this->getUploadPath();
                    $entity->setFilename( time() . '_' . $entity->getFilename());
                    $file = $this->getFile($url);
                    file_put_contents($path . $entity->getFilename(), $file);
                    chmod($path . $entity->getFilename(), 0777);
                    if(is_file($path . $oldFileName)) {
                        unlink($path . $oldFileName);
                    }
                }

                $entity->setUpdatedDate(new \DateTime());
                $this->getManager()->persist($entity);
                $this->getManager()->flush();

                $successFlashText = sprintf('Media %s updated.', $entity->getTitle());
                if (!$id) {
                    $successFlashText = sprintf('Media %s added.', $entity->getTitle());
                }
                $this->setSuccessFlash($successFlashText);

                return $this->redirectToRoute('backend_media');
            }
        }

        return $this->render('EventEventBundle:Backend/Media:manage.html.twig', [
            'media' => $entity,
            'form' => $form->createView(),
//            'configLocales' => $this->container->getParameter('media.locales'),
        ]);
    }

    protected function getFile($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $file = curl_exec($ch);
        curl_close($ch);
        return $file;
    }

    public function deleteAction($id)
    {
        $this->isGrantedAdmin();

        $entity = $this->findOr404('EventEventBundle:Media', $id);
        $file = $this->getUploadPath() . $entity->getFilename();
        $this->getManager()->remove($entity);
        $this->getManager()->flush();
        if(is_file($file)) {
            unlink($file);
        }

        $this->setSuccessFlash('Media deleted.');

        return $this->redirectToRoute('backend_media');
    }


}
