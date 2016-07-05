<?php

namespace Context;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Mink\Exception\ElementNotFoundException;
use Behat\Mink\Driver\GoutteDriver;
use Behat\Behat\Event\ScenarioEvent;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;

use Behat\Behat\Context\Step,
    Behat\Behat\Context\Step\Given,
    Behat\Behat\Context\Step\Then;
use Behat\Gherkin\Node\TableNode;

/**
 * Backend context.
 */
class BackendContext extends MinkContext implements KernelAwareInterface
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
     * @When /^I should see the row containing "([^"]*)"$/
     */
    public function iShouldSeeTheRowContaining($rowTexts)
    {
        $pieces = explode(';', $rowTexts);

        /**
         * @var $nodes \Behat\Mink\Element\NodeElement
         * @var $node \Behat\Mink\Element\NodeElement
         */
        $nodes = $this->getSession()->getPage()->findAll('css', sprintf('table tr > td:first-child:contains("%s")',
            $pieces[0]));
        if (!$nodes) {
            throw new \Exception(sprintf('Cannot find any row on the page containing the text "%s"', $rowTexts));
        }

        $findedNode = false;
        foreach ($nodes as $node) {
            $parent = $node->getParent();
            $rowId = explode(' ', $parent->getText())[0];

            if ($rowId == $pieces[0]) {
                $findedNode = $parent;

                break;
            }
        }

        if (!$findedNode) {
            throw new \Exception(sprintf('Cannot find any row on the page containing the text "%s"', $rowTexts));
        }
    }

    /**
     * @When /^I should not see the row containing "([^"]*)"$/
     */
    public function iShouldNotSeeTheRowContaining($rowTexts)
    {
        $pieces = explode(';', $rowTexts);

        /**
         * @var $nodes \Behat\Mink\Element\NodeElement
         * @var $node \Behat\Mink\Element\NodeElement
         */
        $nodes = $this->getSession()->getPage()->findAll('css', sprintf('table tr > td:first-child:contains("%s")',
            $pieces[0]));

        $findedNode = false;
        if ($nodes) {
            foreach ($nodes as $node) {
                $parent = $node->getParent();
                $rowId = explode(' ', $parent->getText())[0];

                if ($rowId == $pieces[0]) {
                    $findedNode = $parent;

                    break;
                }
            }
        }

        if ($findedNode) {
            throw new \Exception(sprintf('The row on the page containing the text "%s" was finded', $rowTexts));
        }
    }

    /**
     * @Given /^I delete the record with id "([^"]*)"$/
     */
    public function iDeleteRecordOf($index)
    {
        /**
         * @var $nodes \Behat\Mink\Element\NodeElement
         * @var $node \Behat\Mink\Element\NodeElement
         */
        $nodes = $this->getSession()->getPage()->findAll('css', sprintf('table tr > td:first-child:contains("%s")',
            $index));
        if (!$nodes) {
            throw new \Exception(sprintf('Cannot find any row with id "%s"', $index));
        }

        $findedNode = false;
        foreach ($nodes as $node) {
            $parent = $node->getParent();
            $rowId = explode(' ', $parent->getText())[0];

            if ($rowId == $index) {
                $findedNode = $parent;

                break;
            }
        }

        if (!$findedNode) {
            throw new \Exception(sprintf('Cannot find any row with id "%s"', $index));
        }

        $linksSelectors = $findedNode->findAll('css', 'ul.dropdown-menu li a');
        foreach ($linksSelectors as $link) {
            if (strpos($link->getHtml(), 'Delete')) {
                $this->getSession()->visit($link->getAttribute('href'));
            }
        }
    }

    /**
     * @Given /^I approve the call with id "([^"]*)"$/
     */
    public function iApproveCallOf($index)
    {
        /**
         * @var $nodes \Behat\Mink\Element\NodeElement
         * @var $node \Behat\Mink\Element\NodeElement
         */
        $nodes = $this->getSession()->getPage()->findAll('css', sprintf('table tr > td:first-child:contains("%s")', $index));
        if (!$nodes) {
            throw new \Exception(sprintf('Cannot find any row with id "%s"', $index));
        }

        $findedNode = false;
        foreach ($nodes as $node) {
            $parent = $node->getParent();
            $rowId = explode(' ', $parent->getText())[0];

            if ($rowId == $index) {
                $findedNode = $parent;

                break;
            }
        }

        if (!$findedNode) {
            throw new \Exception(sprintf('Cannot find any row with id "%s"', $index));
        }

        $linksSelectors = $findedNode->findAll('css', 'ul.dropdown-menu li a');
        foreach ($linksSelectors as $link) {
            if (strpos($link->getHtml(), 'Approve')) {
                $this->getSession()->visit($link->getAttribute('href'));
            }
        }
    }

    /**
     * Click on the element with the provided CSS Selector
     *
     * @When /^I click on the element with css selector "([^"]*)"$/
     */
    public function iClickOnTheElementWithCSSSelector($cssSelector)
    {
        $session = $this->getSession();
        $element = $session->getPage()->find(
            'xpath',
            $session->getSelectorsHandler()->selectorToXpath('css', $cssSelector) // just changed xpath to css
        );
        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Could not evaluate CSS Selector: "%s"', $cssSelector));
        }

        $element->click();
    }

    /**
     * @When /^I click "([^"]*)" on the row containing "([^"]*)"$/
     */
    public function iClickOnOnTheRowContaining($linkName, $rowTexts)
    {
        $pieces = explode(';', $rowTexts);

        /**
         * @var $nodes \Behat\Mink\Element\NodeElement
         * @var $node \Behat\Mink\Element\NodeElement
         */
        $nodes = $this->getSession()->getPage()->findAll('css', sprintf('table tr > td:first-child:contains("%s")',
            $pieces[0]));
        if (!$nodes) {
            throw new \Exception(sprintf('Cannot find any row on the page containing the text "%s"', $rowTexts));
        }

        $findedNode = false;
        foreach ($nodes as $node) {
            $parent = $node->getParent();
            $rowId = explode(' ', $parent->getText())[0];

            if ($rowId == $pieces[0]) {
                $findedNode = $parent;

                break;
            }
        }

        if (!$findedNode) {
            throw new \Exception(sprintf('Cannot find any row on the page containing the text "%s"', $rowTexts));
        }

        foreach ($pieces as $piece) {
            $pos = strpos($findedNode->getText(), $piece);
            if ($pos === false) {
                throw new \Exception(sprintf('Cannot find any row on the page containing the text "%s"', $rowTexts));
            }
        }

        $findedNode->clickLink($linkName);
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
     * @AfterScenario
     *
     * @param ScenarioEvent $scenarioEvent
     */
    public function printLastResponseOnError(ScenarioEvent $scenarioEvent)
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
     * Fills in form field with specified id|name|label|value.
     *
     * @When /^(?:|I )fills in "(?P<field>(?:[^"]|\\")*)" with "(?P<value>(?:[^"]|\\")*)" inside "(?P<locale>(?:[^"]|\\")*)" tab$/
     */
    public function fillsField($field, $value, $locale)
    {
        $field = $this->fixStepArgument($field);
        $value = $this->fixStepArgument($value);
        $page = $this->getSession()->getPage()->find('css', sprintf('div#%s:contains("%s")', $locale, $field));
        $page->fillField($field, $value);
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

                if (substr($upperFieldName, -2) == 'es') {
                    $upperFieldName = substr($upperFieldName, 0, -2);
                } elseif (substr($upperFieldName, -1) == 's') {
                    $upperFieldName = substr($upperFieldName, 0, -1);
                }

                if (method_exists($object, 'add'.$upperFieldName)) {
                    $relatedObjectsNames = explode(',', trim($row[$field]));
                    if (is_array($relatedObjectsNames) && count($relatedObjectsNames)) {
                        $relatedObjectName =  $metadata->getAssociationTargetClass($field);
                        foreach ($relatedObjectsNames as $name) {
                            $relatedObject = $this->findRelatedObject($relatedObjectName, $name);
                            if ($relatedObject !== null) {
                                $object->{'add'.$upperFieldName}($relatedObject);
                            }
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
        return $this->getContainer()->get('doctrine');
    }

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected function getContainer()
    {
        return $this->kernel->getContainer();
    }
}
