#!/bin/bash

FILE="`dirname $0`/outputExample.md"

echo "## Example
#### vendor/symfony/symfony/src/Symfony/Component/HttpKernel/Event/FilterControllerEvent.php
\`\`\`php
`cat vendor/symfony/symfony/src/Symfony/Component/HttpKernel/Event/FilterControllerEvent.php`
\`\`\`" > $FILE

php app/console moses:generate-test-class --no-confirmation vendor/symfony/symfony/src/Symfony/Component/HttpKernel/Event/FilterControllerEvent.php

echo "## Generated test class
#### vendor/symfony/symfony/src/Symfony/Component/HttpKernel/Tests/Event/FilterControllerEventTest.php
\`\`\`php
`cat vendor/symfony/symfony/src/Symfony/Component/HttpKernel/Tests/Event/FilterControllerEventTest.php`
\`\`\`" >> $FILE
