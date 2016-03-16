<?php
use AspectMock\Test as test;
use Robo\Config;

class GulpTest extends \Codeception\TestCase\Test
{
    protected $container;

    /**
     * @var \AspectMock\Proxy\ClassProxy
     */
    protected $baseGulp;

    protected function _before()
    {
        $this->baseGulp = test::double('Robo\Task\Gulp\Base', [
            'getOutput' => new \Symfony\Component\Console\Output\NullOutput()
        ]);
        $this->container = Config::getContainer();
        $this->container->addServiceProvider(\Robo\Task\Gulp\ServiceProvider::class);
    }

    // tests
    public function testGulpRun()
    {
        $isWindows = defined('PHP_WINDOWS_VERSION_MAJOR');

        if ($isWindows) {
            verify(
                $this->container->get('taskGulpRun', ['default','gulp'])->getCommand()
            )->equals('gulp "default"');

            verify(
                $this->container->get('taskGulpRun', ['another','gulp'])->getCommand()
            )->equals('gulp "another"');

            verify(
                $this->container->get('taskGulpRun', ['anotherWith weired!("\') Chars','gulp'])->getCommand()
            )->equals('gulp "anotherWith weired!(\"\') Chars"');

            verify(
                $this->container->get('taskGulpRun', ['default','gulp'])->silent()->getCommand()
            )->equals('gulp "default" --silent');

            verify(
                $this->container->get('taskGulpRun', ['default','gulp'])->noColor()->getCommand()
            )->equals('gulp "default" --no-color');

            verify(
                $this->container->get('taskGulpRun', ['default','gulp'])->color()->getCommand()
            )->equals('gulp "default" --color');

            verify(
                $this->container->get('taskGulpRun', ['default','gulp'])->simple()->getCommand()
            )->equals('gulp "default" --tasks-simple');

        } else {

            verify(
                $this->container->get('taskGulpRun', ['default','gulp'])->getCommand()
            )->equals('gulp \'default\'');

            verify(
                $this->container->get('taskGulpRun', ['another','gulp'])->getCommand()
            )->equals('gulp \'another\'');

            verify(
                $this->container->get('taskGulpRun', ['anotherWith weired!("\') Chars','gulp'])->getCommand()
            )->equals("gulp 'anotherWith weired!(\"'\\'') Chars'");

            verify(
                $this->container->get('taskGulpRun', ['default','gulp'])->silent()->getCommand()
            )->equals('gulp \'default\' --silent');

            verify(
                $this->container->get('taskGulpRun', ['default','gulp'])->noColor()->getCommand()
            )->equals('gulp \'default\' --no-color');

            verify(
                $this->container->get('taskGulpRun', ['default','gulp'])->color()->getCommand()
            )->equals('gulp \'default\' --color');

            verify(
                $this->container->get('taskGulpRun', ['default','gulp'])->simple()->getCommand()
            )->equals('gulp \'default\' --tasks-simple');
        }
    }

}
