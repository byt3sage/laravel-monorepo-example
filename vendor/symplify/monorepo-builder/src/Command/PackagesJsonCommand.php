<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Command;

use MonorepoBuilder202211\Nette\Utils\Json;
use MonorepoBuilder202211\Symfony\Component\Console\Input\InputInterface;
use MonorepoBuilder202211\Symfony\Component\Console\Input\InputOption;
use MonorepoBuilder202211\Symfony\Component\Console\Output\OutputInterface;
use Symplify\MonorepoBuilder\Json\PackageJsonProvider;
use Symplify\MonorepoBuilder\ValueObject\Option;
use MonorepoBuilder202211\Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
final class PackagesJsonCommand extends AbstractSymplifyCommand
{
    /**
     * @var \Symplify\MonorepoBuilder\Json\PackageJsonProvider
     */
    private $packageJsonProvider;
    public function __construct(PackageJsonProvider $packageJsonProvider)
    {
        $this->packageJsonProvider = $packageJsonProvider;
        parent::__construct();
    }
    protected function configure() : void
    {
        $this->setName('packages-json');
        $this->setDescription('Provides package paths in json format. Useful for GitHub Actions Workflow');
        $this->addOption(Option::TESTS, null, InputOption::VALUE_NONE, 'Only with /tests directory');
        $this->addOption(Option::EXCLUDE_PACKAGE, null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Exclude one or more package from the list, useful e.g. when scoping one package instead of bare split');
    }
    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $onlyTests = (bool) $input->getOption(Option::TESTS);
        if ($onlyTests) {
            $packagePaths = $this->packageJsonProvider->providePackagesWithTests();
        } else {
            $packagePaths = $this->packageJsonProvider->providePackages();
        }
        $excludedPackages = (array) $input->getOption(Option::EXCLUDE_PACKAGE);
        $allowedPackagePaths = \array_diff($packagePaths, $excludedPackages);
        // re-index from 0
        $allowedPackagePaths = \array_values($allowedPackagePaths);
        // must be without spaces, otherwise it breaks GitHub Actions json
        $json = Json::encode($allowedPackagePaths);
        echo $json;
        return self::SUCCESS;
    }
}
