<?php
/*
 * psx
 * A object oriented and modular based PHP framework for developing
 * dynamic web applications. For the current version and informations
 * visit <http://phpsx.org>
 *
 * Copyright (c) 2010-2014 Christoph Kappestein <k42b3.x@gmail.com>
 *
 * This file is part of psx. psx is free software: you can
 * redistribute it and/or modify it under the terms of the
 * GNU General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or any later version.
 *
 * psx is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with psx. If not, see <http://www.gnu.org/licenses/>.
 */

namespace PSX\Console\Generate;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * ControllerCommand
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl.html GPLv3
 * @link    http://phpsx.org
 */
class ControllerCommand extends GenerateCommandAbstract
{
	protected function configure()
	{
		$this
			->setName('generate:controller')
			->setDescription('Generates a new controller')
			->addArgument('namespace', InputArgument::REQUIRED, 'Absolute class name of the controller (i.e. Acme\News\Overview)')
			->addArgument('services', InputArgument::OPTIONAL, 'Comma seperated list of service ids (i.e. connection,template)')
			->addOption('dry-run', null, InputOption::VALUE_NONE, 'Executes no file operations if true');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$definition = $this->getServiceDefinition($input);

		$output->writeln('Generating controller');

		// create dir
		$path = $definition->getPath();

		if(!is_dir($path))
		{
			$output->writeln('Create dir ' . $path);

			if(!$definition->isDryRun())
			{
				mkdir($path, 0744, true);
			}
		}

		// generate controller
		$file = $path . DIRECTORY_SEPARATOR . $definition->getClassName() . '.php';

		if(!is_file($file))
		{
			$source = $this->getControllerSource($definition);

			$output->writeln('Write file ' . $file);

			if(!$definition->isDryRun())
			{
				file_put_contents($file, $source);
			}
		}
		else
		{
			throw new \RuntimeException('File ' . $file . ' already exists');
		}
	}

	protected function getControllerSource(ServiceDefinition $definition)
	{
		$namespace = $definition->getNamespace();
		$className = $definition->getClassName();
		$services  = '';

		foreach($definition->getServices() as $serviceName)
		{
			$services.= $this->getServiceSource($serviceName) . "\n\n";
		}

		$services = trim($services);

		return <<<PHP
<?php

namespace {$namespace};

use PSX\ControllerAbstract;

/**
 * {$className}
 *
 * @see http://phpsx.org/doc/design/controller.html
 */
class {$className} extends ControllerAbstract
{
	{$services}

	public function doIndex()
	{
		// @TODO controller action

		\$this->setBody(array(
			'message' => 'This is the default controller of PSX',
		));
	}
}

PHP;
	}
}
