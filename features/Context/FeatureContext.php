<?php

namespace Context;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Behat\MinkExtension\Context\MinkContext;

use Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException,
    Behat\Behat\Context\Step,
    Behat\Behat\Context\Step\Given,
    Behat\Behat\Context\Step\Then;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Doctrine\Common\Util\Inflector;

/**
 * Feature context.
 */
class FeatureContext extends MinkContext implements KernelAwareInterface
{
    private $kernel;
    private $parameters;
    private $references = array();

    /**
     * Initializes context with parameters from behat.yml.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @Given /^I am on the "([\w\s]+)"( page)?$/
     * @When /^I go to the "([\w\s]+)"( page)?$/
     */
    public function iAmOnThePage($page)
    {
        $this->getSession()->visit($this->generatePageUrl($page, array()));
    }

    /**
     * @Given /^I am sign in as admin$/
     */
    public function iAmSignInAsAdmin()
    {
        return array(
            new Given('I am on "/event/admin/login"'),
            new Given('I fill in "_username" with "admin"'),
            new Given('I fill in "_password" with "admin"'),
            new Given('I press "Login"'),
            new Given('I should not see "Bad credentials"'),
            new Given('I should see "Dashboard"')
        );
    }

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
     * @Given /^pause "([^"]*)"$/
     */
    public function pause($pause)
    {
        $this->getMink()->getSession()->wait($pause);
    }

    /**
     * @BeforeScenario
     */
    public function restoreDatabase()
    {
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
     * @AfterScenario
     */
    public function printLastResponseOnError($scenarioEvent)
    {
        if (!$this->getContainer()->getParameter('behat_print_err')) {
            return;
        }

        if ($scenarioEvent->getResult() != 0) {
            // try to prevent it from dying if we error out before we have a request
            $driver = $this->getSession()->getDriver();
            $hasLastResponse = (!$driver instanceof GoutteDriver || $driver->getClient()->getRequest());

            if ($hasLastResponse) {
                $this->printLastResponse();
            }
        }
    }

    /**
     * @Given /^are following "([^"]*)":$/
     * @Given /^following "([^"]*)":$/
     */
    public function areFollowingEntities($name, TableNode $table)
    {
        $objectName = 'Event\\EventBundle\\Entity\\' . $name;

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
     * @Given /^I delete "([^"]*)" record of "([^"]*)"$/
     */
    public function iDeleteRecordOf($arg1, $arg2)
    {
        throw new PendingException();
    }

    public function getRepository($name)
    {
        return $this->getEntityManager()->getRepository($name);
    }

    /**
     * Generates url with Router.
     *
     * @param string  $route
     * @param array   $parameters
     * @param Boolean $absolute
     *
     * @return string
     */
    private function generateUrl($route, array $parameters = array(), $absolute = false)
    {
        return $this->getContainer()->get('router')->generate($route, $parameters, $absolute);
    }

    /**
    * Generate page url from name and parameters.
    *
    * @param string $page
    * @param array  $parameters
    *
    * @return string
    */
    protected function generatePageUrl($page, array $parameters = array())
    {
        $parts = explode(' ', trim($page), 2);
        if (2 === count($parts)) {
            $parts[1] = Inflector::camelize($parts[1]);
        }

        $route  = implode('_', $parts);
        $routes = $this->getContainer()->get('router')->getRouteCollection();

        return $this->getMinkParameter('base_url').$this->generateUrl($route, $parameters);
    }

    protected function addFieldsDataFromRow($metadata, $object, $row)
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
                foreach ($values as $value) {
                    $object->{'add'.$upperFieldName}($value);
                }
            }
        }
    }

    protected function addAssociationsFromRow($metadata, $object, $row)
    {
        $metas = $metadata->getAssociationNames();
        foreach ($row as $field => $value) {
            if (in_array($field, $metas)) {
                $upperFieldName = ucfirst($field);
                if (method_exists($object, 'set'.$upperFieldName)) {
                    $relatedObjectName =  $metadata->getAssociationTargetClass($field);
                    $relatedObject = $this->findRelatedObject($relatedObjectName, $row[$field]);
                    if ($relatedObject !== null) {
                        $object->{'set'.$upperFieldName}($relatedObject);
                    }
                }
                $upperFieldName = ucfirst($field);
                if (method_exists($object, 'add'.$upperFieldName)) {
                    $relatedObjectsNames = explode(',', trim($row[$field]));
                    $relatedObjectName =  $metadata->getAssociationTargetClass($field);
                    foreach ($relatedObjectsNames as $name) {
                        $object->{'add'.$upperFieldName}($this->findRelatedObject($relatedObjectName, $name));
                    }
                }
            }
        }
    }

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

    protected function isDateModifier($modifier)
    {
        return preg_match('/^\+|-[0-9]+ (days?)|(months?)$/', $modifier);
    }

    protected function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }

    protected function getDoctrine()
    {
        return $this->getContainer()->get('doctrine');
    }

    protected function getContainer()
    {
        return $this->kernel->getContainer();
    }
}
