<?php

namespace spec\MaddHatter\MarkdownTable;

use MaddHatter\MarkdownTable\Builder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BuilderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Builder::class);
    }

    function it_should_make_the_example_table()
    {
    	// PHP_EOL is required so this test passes on Windows and Linux
    	$table = '| Tables   |      Are      |  Cool |' . PHP_EOL . 
				 '|----------|:-------------:|------:|' . PHP_EOL .
				 '| col 1 is | left-aligned  | $1600 |' . PHP_EOL .
				 '| col 2 is |   centered    |   $12 |' . PHP_EOL .
				 '| col 3 is | right-aligned |    $1 |' . PHP_EOL;

    	$this->headers(['Tables','Are','Cool']) //headers
			->align(['L','C','R']) // set column alignment
			->rows([ // add multiple rows at once
				['col 1 is', 'left-aligned', '$1600'],
				['col 2 is', 'centered', '$12'],
			])
			->row(['col 3 is', 'right-aligned', '$1'])
			->render()
			->shouldReturn($table);
    }
}
