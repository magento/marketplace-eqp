# Magento 1

If you run PHP_CodeSniffer without specifying a coding standard, PHP_CodeSniffer will look for a file called either `phpcs.xml` or `phpcs.xml.dist`. If found, configuration information will be read from this file, including the files to check, the coding standard to use, and any command line arguments to apply.

## Default Configuration

1. Add the `phpcs.xml` configuration file, e.g:

    ```
    <?xml version="1.0"?>
    <ruleset name="<Ruleset Name>">
        <description>Code Sniffer Configuration</description>
        <file>app/</file>
        <arg name="extensions" value="php"/>
        <rule ref="MEQP1" />
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
                "vendor/bin/phpcs --config-set default_standard MEQP1",
                "vendor/bin/phpcs --config-set installed_paths \"$(pwd)/vendor/magento/marketplace-eqp\"",
                "vendor/bin/phpcs --config-set php5.5_path \"$(which php)\""
            ]
        },
        "extra": {
            "exclude": [
                "phpcs.xml"
            ]
        },
        "repositories": [
            {
                "type": "vcs",
                "url": "https://github.com/magento/marketplace-eqp.git"
            }
        ]
    }
    ```
1. Updates the dependencies in `composer.lock`:

    ```
    composer update
    ```
