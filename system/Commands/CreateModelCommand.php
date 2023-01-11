<?php

namespace System\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Str;

//TODO: Add service tag
#[AsCommand(
    name: 'app:create-model'
)]
class CreateModelCommand extends Command
{
    private string $stubPath = __DIR__ . '/../../stubs/model.stub';
    private string $modelPath = __DIR__ . '/../../app/';

    public function makeModelFile(string $rawModelName, array $params): bool
    {
        $fileName = Str::ucfirst(Str::replaceLast('s', '', $rawModelName));

        $outputFile = $this->modelPath . $fileName . '.php';

        if (file_exists($outputFile)) {
            return false;
        }

        $stubFileContent = file_get_contents($this->stubPath);
        $modelFileContent = preg_replace_callback(
            '/{{ (.*?) }}/',
            function ($match) use ($params) {
                return $params[$match[1]] ?? '';
            },
            $stubFileContent
        );

        file_put_contents($outputFile, $modelFileContent);
        return true;
    }

    public function getParams(string $rawModelName): array
    {
        $trimName = Str::replaceLast('s', '', $rawModelName);

        $class = Str::ucfirst($trimName);
        $clas2 = Str::lower($rawModelName);

        return [
            'namespace' => 'App',
            'class' => $class,
            'class2' => $clas2
        ];
    }

    protected function configure(): void
    {
        $this->addArgument('model_name', InputArgument::REQUIRED, 'model raw name');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {

        $rawModelName = $input->getArgument('model_name');

        if ($this->makeModelFile($rawModelName, $this->getParams($rawModelName))) {
            $output->write('successfully created:)');
            return Command::SUCCESS;
        }

        $output->write('successfully failed:)');
        return Command::FAILURE;
    }
}