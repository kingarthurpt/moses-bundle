# moses-bundle
Moses the prophet, is a symfony bundle that attempts to generate PHP unit test prophecies for a given PHP class that needs to be tested.
Moses went for an intensive training with God for 40 days and 40 nights in order to understand how to properly mock your PHP classes, so that you could just run a simple Symfony command to generate everything in less than 40 seconds.

> **NOTE**: This bundle is being developed with the intent to be used in lafourchette-rr, so it may only work on Symfony v2.0.

### How to add this bundle to your symfony project
Edit the *composer.json* file and add the following:
```json
    ...
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:arturzealves/moses-bundle.git"
        }
    ],
    ...
    "require-dev": {
        "arturzealves/moses": "dev-master"
    }
    ...
```

Execute the following command on your terminal
```shell
$ composer update "arturzealves/moses"
```

Edit the *app/AppKernel.php* file and add the following:
```php
if (in_array($this->getEnvironment(), array('dev', 'test'))) {
    ...
    $bundles[] = new ArturZeAlves\MosesBundle\ArturZeAlvesMosesBundle();
}
```

### How to invoke Moses the prophet
```shell
$ php app/console moses:generate-test-class {your_class_file_path}
```
