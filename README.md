#Magento Extension Quality Program Coding Standard

Magento EQP Coding Standard is a set of rules and sniffs for [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) tool.

It allows automatically check your code against some of the common Magento and PHP coding issues, like:
- raw SQL queries;
- SQL queries inside a loop;
- direct class instantiation;
- unnecessary collection loading;
- excessive code complexity;
- use of dangerous functions;
- use of PHP superglobals;
- code style issues

and many others.

**Magento Extension Quality Program Coding Standard** consists of two rulesets - MEQP1 for Magento and MEQP2 for Magento 2. Each of them contains the rules depending on the requirements of each version.

##Installation & Usage

Clone or download this repo somewhere on your computer.
```sh
$ git clone git@github.com:magento/marketplace-eqp.git
```
Install all required dependencies via [Composer](https://getcomposer.org):
```sh
$ composer install
```
It will add [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) to the `vendor` folder of the project. 
Go to `/vendor/squizlabs/php_codesniffer` and add MEQP standard's directory to PHP_CodeSniffer installed paths:
```sh
$ php scripts/phpcs --config-set installed_paths "/path/to/magento/marketplace-eqp"
```
Select the standard to run with PHP_CodeSniffer. To check Magento extension run:
```sh
$ php scripts/phpcs "/path/to/your/extension" --standard=MEQP1
```
To check Magento 2 extension run:
```sh
$ php scripts/phpcs "/path/to/your/extension" --standard=MEQP2
```
By default, PHP_CodeSniffer will check any file it finds with a `.inc`, .`php`, `.js` or `.css` extension. To check design templates, you can specify `.phtml` in the `--extensions` argument: `--extensions=php,phtml`.

To check syntax with specific PHP version set paths to php binary dir:
```sh
$ php scripts/phpcs --config-set php7.0_path "/dir/to/your/php7"
$ php scripts/phpcs --config-set php5.4_path "/dir/to/your/php5.4"
```
#Fixing Errors Automatically

PHP_CodeSniffer offers the PHP Code Beautifier and Fixer (`phpcbf`) tool. It can be used in place of `phpcs` to automatically generate and fix all fixable issues. We highly recommend run following command to fix as many sniff violations as possible:
```sh
$ php scripts/phpcbf "/path/to/your/extension" --standard=MEQP2
```
#Dynamic Sniffs

Sniffs with complex logic, like MEQP2.Classes.CollectionDependency and MEQP2.SQL.CoreTablesModification require path to installed Magento 2 instance. You can specify it using ```$ php scripts/phpcs --config-set m2-path <path-to-magento2>``` command.

>Notice: Dynamic sniffs will not work without specified ```m2-path``` configuration option.

>Notice: Don't forget to clear `cache` folder in project root directory if you are running sniffs for other Magento version

#Marketplace Technical Review
To make sure, your extension will pass CodeSniffer checks on Level 1 of Magento Marketplace Technical Review, you could run `phpcs` command with `--severity=10` option.
```sh
$ php scripts/phpcs "/path/to/your/extension" --standard=MEQP2 --severity=10
```
**All severity 10 errors must be fixed in order to successfully pass Level 1 CodeSniffer checks.**
 
##Requirements

* PHP >=5.6.0
* [Composer](https://getcomposer.org)
* [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) >= 2.6.2

>Notice: PHP and Composer should be accessible globally.

##Contribution

Please feel free to contribute new sniffs or any fixes or improvements for the existing ones.
