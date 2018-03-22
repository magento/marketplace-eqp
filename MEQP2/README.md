# Magento 2

If you run PHP_CodeSniffer without specifying a coding standard, PHP_CodeSniffer will look for a file called either `phpcs.xml` or `phpcs.xml.dist`. If found, configuration information will be read from this file, including the files to check, the coding standard to use, and any command line arguments to apply.

## Default Configuration

1. Add the `phpcs.xml` configuration file, e.g:

    ```
    <?xml version="1.0"?>
    <ruleset name="<Ruleset Name>">
        <description>Code Sniffer Configuration</description>
        <file>.</file>
        <exclude-pattern>vendor/*</exclude-pattern>
        <arg name="extensions" value="php"/>
        <rule ref="MEQP2" />
    </ruleset>
    ```
1. Update `composer.json` with:

    ```
    {
        [...]
        "require-dev": {
            "magento/marketplace-eqp": "dev-master"
        },
        "scripts": {
            "post-install-cmd": [
                "vendor/bin/phpcs --config-set default_standard MEQP2",
                "vendor/bin/phpcs --config-set installed_paths \"$(pwd)/vendor/magento/marketplace-eqp\"",
                "vendor/bin/phpcs --config-set php7.0_path \"$(which php)\""
            ]
        },
        "extra": {
            "exclude": [
                "phpcs.xml"
            ]
        },
        "repositories": [
            {
                "type": "composer",
                "url": "https://repo.magento.com/"
            }
        ]
    }
    ```
1. Updates the dependencies in `composer.lock`:

    ```
    composer update
    ```
