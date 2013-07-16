<?php

namespace Event\EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class Controller extends BaseController
{
    protected function getRepository($name)
    {
        return $this->getManager()->getRepository($name);
    }

    protected function getManager()
    {
        return $this->getDoctrine()->getManager();
    }

    protected function dispatchEvent($name, $event)
    {
        $this->get('event_dispatcher')->dispatch($name, $event);
    }

    protected function redirectToRoute($route, $params = array())
    {
        return $this->redirect($this->generateUrl($route, $params));
    }

    /**
     * @return SecurityContextInterface
     */
    protected function getSecurityContext()
    {
        return $this->get('security.context');
    }

    protected function isGranted($role, $object = null)
    {
        return $this->getSecurityContext()->isGranted($role, $object);
    }

    protected function isGrantedAdmin()
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }
    }

    /**
     * @param string $roleName
     * @param User   $user
     *
     * @return boolean
     */
    protected function hasUserAccess($roleName, User $user)
    {
        $roleHierarchy  = new RoleHierarchy($this->container->getParameter('security.role_hierarchy.roles'));
        $reachableRoles = $roleHierarchy->getReachableRoles(array_map(function($item) { return new Role($item); }, $user->getRoles()));
        /* @var \Symfony\Component\Security\Core\Role\RoleInterface $role */
        foreach ($reachableRoles as $role) {
            if ($roleName === $role->getRole()) {
                return true;
            }
        }

        return false;
    }

    protected function throw404Unless($condition)
    {
        if (!$condition) {
            throw $this->createNotFoundException('Resource not found');
        }
    }

    protected function findOr404($entity, $id)
    {
        $entity = $this->getRepository($entity)->findOneBy(is_array($id) ? $id : array('id' => $id));
        if (!$entity) {
            throw $this->createNotFoundException(sprintf('Resource %s for %s not found.', $id, $entity->getName()));
        }

        return $entity;
    }

    protected function getSession()
    {
        return $this->getRequest()->getSession();
    }

    protected function initObjectLocales($entity, $translation)
    {
        $locales = $this->container->getParameter('event.locales');
        $translation = get_class($translation);

        if ($locales) {
            foreach ($locales as $locale => $title) {
                $translation = new $translation();
                $translation->setlocale($locale);

                $this->getManager()->persist($translation);

                $entity->addTranslation($translation, $this->getClassName($entity));
            }
        }

        return $entity;
    }

    protected function getClassName($class)
    {
        $ref = new \ReflectionClass(get_class($class));

        return $ref->getShortName();
    }

    protected function getFlashBag()
    {
        return $this->getSession()->getFlashBag();
    }

    protected function setSuccessFlash($message)
    {
        $this->getFlashBag()->add('success', $message);
    }

    protected function setErrorFlash($message)
    {
        $this->getFlashBag()->add('error', $message);
    }

    protected function setNoticeFlash($message)
    {
        $this->getFlashBag()->add('notice', $message);
    }

    protected function setCustomFlash($type, $message)
    {
        $this->getFlashBag()->add($type, $message);
    }
}
