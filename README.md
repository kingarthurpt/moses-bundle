# moses-bundle
![very nice image of Moses](http://217.218.67.237:83/thumbnail/20160428/1054597_xl.jpg "Moses")

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
            "url": "git@github.com:kingarthurpt/moses-bundle.git"
        }
    ],
    ...
    "require-dev": {
        "kingarthurpt/moses": "dev-master"
    }
    ...
```

Execute the following command on your terminal
```shell
$ composer update "kingarthurpt/moses"
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

### How to contribute
1. Pick an [open issue](https://github.com/kingarthurpt/moses-bundle/issues) and assign it to yourself
2. Develop your code on some different branch (other than master)
3. Push the code to this repository and open a pull request
