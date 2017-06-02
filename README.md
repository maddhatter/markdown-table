# Markdown Table
[![Latest Stable Version](https://poser.pugx.org/maddhatter/markdown-table/v/stable)](https://packagist.org/packages/maddhatter/markdown-table) [![Total Downloads](https://poser.pugx.org/maddhatter/markdown-table/downloads)](https://packagist.org/packages/maddhatter/markdown-table) [![Latest Unstable Version](https://poser.pugx.org/maddhatter/markdown-table/v/unstable)](https://packagist.org/packages/maddhatter/markdown-table) [![License](https://poser.pugx.org/maddhatter/markdown-table/license)](https://packagist.org/packages/maddhatter/markdown-table)


A small package to dynamically generate Markdown tables, as described [here](http://www.tablesgenerator.com/markdown_tables).

## Install

Install using composer:
```
composer require maddhatter/markdown-table
```

## Usage

```php
// create instance of the table builder
$tableBuilder = new \MaddHatter\MarkdownTable\Builder();

// add some data
$tableBuilder
	->headers(['Tables','Are','Cool']) //headers
	->align(['L','C','R']) // set column alignment
	->rows([ // add multiple rows at once
		['col 1 is', 'left-aligned', '$1600'],
		['col 2 is', 'centered', '$12'],
	])
	->row(['col 3 is', 'right-aligned', '$1']); // add a single row

// display the result
echo $tableBuilder->render();
```

### Result

```
| Tables   |      Are      |  Cool |
|----------|:-------------:|------:|
| col 1 is | left-aligned  | $1600 |
| col 2 is |   centered    |   $12 |
| col 3 is | right-aligned |    $1 |
```