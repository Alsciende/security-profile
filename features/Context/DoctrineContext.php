<?php

declare(strict_types=1);

namespace App\Features\Context;

use Assert\Assert;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\Environment\InitializedContextEnvironment;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Doctrine\Common\Persistence\ConnectionRegistry;

class DoctrineContext implements Context
{
    /**
     * @var ConnectionRegistry
     */
    private $doctrine;

    /**
     * @var \ArrayAccess|null
     */
    private $apiContext;

    public function __construct(ConnectionRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $environment = $scope->getEnvironment();

        if ($environment instanceof InitializedContextEnvironment) {
            if ($environment->hasContextClass('App\Features\Context\ApiContext')) {
                $this->apiContext = $environment->getContext('App\Features\Context\ApiContext');
            }
        }
    }

    /**
     * @When /^I load Doctrine data from "(?P<tableName>.*)"$/
     * @When /^I load Doctrine data from "(?P<tableName>.*)" using "(?P<connectionName>.*)"$/
     */
    public function iLoadDoctrineData($tableName, $connectionName = null)
    {
        $this->apiContext[$tableName] = $this
            ->doctrine
            ->getConnection($connectionName)
            ->fetchAll('SELECT * FROM ' . $tableName);
    }

    /**
     * @Then /^The table "(?P<tableName>.*)" is not empty$/
     */
    public function TheTableIsNotEmpty($tableName)
    {
        $row = $this
            ->doctrine
            ->getConnection()
            ->fetchArray('SELECT COUNT(*) FROM ' . $tableName);

        Assert::that($row[0])->greaterThan(0);
    }
}
