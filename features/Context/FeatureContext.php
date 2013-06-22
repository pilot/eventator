<?php

namespace Context;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Behat\MinkExtension\Context\MinkContext;

use Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException,
    Behat\Behat\Context\Step;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Feature context.
 */
class FeatureContext extends MinkContext implements KernelAwareInterface
{
    private $kernel;
    private $parameters;

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
     * @Given /^restore database$/
     */
    public function restoreDatabase()
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $optionsLoad = array(
            'command' => 'doctrine:fixtures:load',
            '--no-interaction' => true,
            '--quiet' => true,
            '--env' => 'test',
        );

        $application->run(new ArrayInput($optionsLoad));
    }

    /**
     * @Given /^pause "([^"]*)"$/
     */
    public function pause($pause)
    {
        $this->getMink()->getSession()->wait($pause);
    }

    /**
     * @When /^I click xpath "([^"]*)"$/
     */
    public function iClickXpath($xpath)
    {
        $this->getMink()->getSession()->getDriver()->click($xpath);
    }
}
