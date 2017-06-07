<?php

namespace PaymentBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;

use PaymentBundle\Service\AppStripe;

class CreatePlanCommand extends ContainerAwareCommand
{
	const COMMAND_NAME = 'app:stripe:create-plan';
	private $options = [];

	public function configure()
	{
		$this
			->setName(self::COMMAND_NAME)
			->setDescription('This command help you generate Stripe Plan')
			->setHelp('No required arguments, just interact with the command')
		;
	}

	public function initialize(InputInterface $input, OutputInterface $output)
	{
		$output->writeln('Welcome to the app Stripe Plan command');
	}

	public function interact(InputInterface $input, OutputInterface $output)
	{
		$helper = $this->getHelper('question');
		$choices = [
			'interval' => ['week', 'month', 'year'],
			'currency' => ['usd', 'eur'],
		];
		$values = [];

		$params = ['name' => 'input', 'id' => 'input', 'interval' => 'choice', 'amount' => 'input', 'currency' => 'choice'];

		foreach ($params as $param => $type) {
			if ($type == 'choice') {
				$question = new ChoiceQuestion($param, $choices[$param], $choices[$param][0]);
			} else {
				$question = new Question($param . ' : ');
			}
			$this->options[$param] = $helper->ask($input, $output, $question);
		}
		$this->options['amount'] = (int) ($this->options['amount'] * 100);
	}

	public function execute(InputInterface $input, OutputInterface $output)
	{
		$output->writeln('You are about to create a Stripe Plan with the following values:');
		$output->writeln(serialize($this->options));
		foreach ($this->options as $option => $value) {
			$output->writeln('- ' . $option . ' : ' . $value);
		}

		if (!$this->getHelper('question')->ask($input, $output, new ConfirmationQuestion('Are you sure ?'))) {
			return ;
		}

		$stripePlan = $this->getContainer()->get(AppStripe::SERVICE_NAME);
		$stripePlan->createPlan($this->options);

		$output->writeln('Generating stripe plan...');		
		$output->writeln('Congratulations! your stripe plan have been created :)');
	}
}