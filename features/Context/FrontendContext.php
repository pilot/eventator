<?php

namespace Context;

use Symfony\Component\HttpKernel\KernelInterface;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Behat\MinkExtension\Context\MinkContext;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Behat\Gherkin\Node\TableNode;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;

/**
 * Frontend context.
 */
class FrontendContext extends MinkContext implements KernelAwareInterface
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var $references
     */
    private $references = array();

    /**
     * Sets HttpKernel instance.
     * This method will be automatically called by Symfony2Extension ContextInitializer.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @BeforeScenario
     */
    public function restoreDatabase()
    {
        $context = $this->kernel->getContainer()->get('router')->getContext();
        $context->setBaseUrl($this->getMinkParameter('base_url'));

        $this->getEntityManager()->getConnection()->executeUpdate("SET foreign_key_checks = 0;");

        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $drop = array(
            'command' => 'doctrine:schema:drop',
            '--force' => true,
            '--no-interaction' => true,
            '--quiet' => true,
            '--env' => 'test',
        );

        $this->getEntityManager()->getConnection()->executeUpdate("SET foreign_key_checks = 1;");

        $application->run(new ArrayInput($drop));

        $create = array(
            'command' => 'doctrine:schema:create',
            '--no-interaction' => true,
            '--quiet' => true,
            '--env' => 'test',
        );

        $application->run(new ArrayInput($create));
    }

    /**
     * @Given /^following "([^"]*)":$/
     *
     * @param           $name
     * @param TableNode $table
     */
    public function areFollowingEntities($name, TableNode $table)
    {
        if (strpos($name, 'Translation')) {
            $objectName = 'Event\\EventBundle\\Entity\\Translation\\' . $name;
        } else {
            $objectName = 'Event\\EventBundle\\Entity\\' . $name;
        }

        $metadata = $this->getEntityManager()->getMetadataFactory()->getMetadataFor($objectName);
        $class = $metadata->getName();

        $entities = array();
        foreach ($table->getHash() as $row) {
            $object = new $class;
            $this->getEntityManager()->persist($object);

            $this->addFieldsDataFromRow($metadata, $object, $row);
            $this->addAssociationsFromRow($metadata, $object, $row);

            $entities[] = $object;
            if (isset($row['ref'])) {
                $this->references[$objectName][$row['ref']] = $object;
            }
        }

        $this->getEntityManager()->flush();
    }

    /**
     * @param ClassMetadata $metadata
     * @param object $object
     * @param array $row
     */
    protected function addFieldsDataFromRow(ClassMetadata $metadata, $object, array $row)
    {
        $names = $metadata->getAssociationNames();
        foreach ($row as $field => $value) {
            $upperFieldName = ucfirst($field);

            if (!in_array($field, $names) && method_exists($object, 'set'.$upperFieldName)) {
                //check if date field
                if ($date = \DateTime::createFromFormat('Y-m-d', $value)) {
                    $value = $date;
                } elseif ($date = \DateTime::createFromFormat('Y-m-d H:i', $value)) {
                    $value = $date;
                } elseif ($this->isDateModifier($value)) {
                    $date = new \DateTime();
                    $value = $date->modify($value);
                }
                $object->{'set'.$upperFieldName}($value);
            }
            if (!in_array($field, $names) && method_exists($object, 'add'.$upperFieldName)) {
                $values = explode(',', trim($row[$field]));
                foreach ($values as $val) {
                    $object->{'add'.$upperFieldName}($val);
                }
            }
        }
    }

    /**
     * @param ClassMetadata $metadata
     * @param object $object
     * @param array $row
     */
    protected function addAssociationsFromRow(ClassMetadata $metadata, $object, array $row)
    {
        $metas = $metadata->getAssociationNames();
        foreach ($row as $field => $value) {
            if (in_array($field, $metas)) {
                $upperFieldName = ucfirst($field);
                if (method_exists($object, 'set'.$upperFieldName)) {
                    $relatedObjectName = $metadata->getAssociationTargetClass($field);
                    $relatedObject = $this->findRelatedObject($relatedObjectName, $row[$field]);
                    if ($relatedObject !== null) {
                        $object->{'set'.$upperFieldName}($relatedObject);
                    }
                }
                if (method_exists($object, 'add'.$upperFieldName)) {
                    $relatedObjectsNames = explode(',', trim($row[$field]));
                    if ($relatedObjectsNames[0]) {
                        $relatedObjectName =  $metadata->getAssociationTargetClass($field);
                        foreach ($relatedObjectsNames as $name) {
                            $object->{'add'.$upperFieldName}($this->findRelatedObject($relatedObjectName, $name));
                        }
                    }
                }
            }
        }
    }

    /**
     * @param $name
     * @param $value
     */
    protected function findRelatedObject($name, $value)
    {
        if (!$value) {
            return;
        }

        if (isset($this->references[$name][$value])) {
            return $this->references[$name][$value];
        }

        return $this->getRepository($name)->findOneByName($value);
    }

    /**
     * @param $modifier
     *
     * @return int
     */
    protected function isDateModifier($modifier)
    {
        return preg_match('/^\+|-[0-9]+ (days?)|(months?)$/', $modifier);
    }

    /**
     * @When /^I click "([^"]+)"$/
     */
    public function iClick($link)
    {
        $this->clickLink($link);
    }

    /**
     * @Then /^I wait for a form$/
     */
    public function iWaitForAForm()
    {
        $this->getSession()->wait(10000, '(typeof(jQuery)=="undefined" || (0 === jQuery.active && 0 === jQuery(\':animated\').length))');
    }

    /**
     * @param $name
     *
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository($name)
    {
        return $this->getEntityManager()->getRepository($name);
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager|object
     */
    protected function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    protected function getDoctrine()
    {
        return $this->getService('doctrine');
    }

    /**
     * Get service by id.
     *
     * @param string $id
     *
     * @return object
     */
    protected function getService($id)
    {
        return $this->getContainer()->get($id);
    }

    /**
     * Returns Container instance.
     *
     * @return ContainerInterface
     */
    protected function getContainer()
    {
        return $this->kernel->getContainer();
    }
}
