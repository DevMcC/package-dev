# PackageDev
A simple tool for developing Composer packages.

PackageDev makes use of symlinks to make it seem as if the package you are developing is run in vendor.
Thanks to this, you can make changes to your package and immediately check the results.

# Installation
It is recommended to install PackageDev globally:
```
composer global require devmcc/package-dev
```

After you have installed PackageDev, go to the root directory of the project that needs to use your package and run:
```
package-dev init
```

Next, open up your project's `composer.json` file and add the following:
```json
"scripts": {
    "pre-install-cmd": [
        "package-dev symlink-remove"
    ],
    "pre-update-cmd": [
        "package-dev symlink-remove"
    ],
    "post-install-cmd": [
        "package-dev symlink-create"
    ],
    "post-update-cmd": [
        "package-dev symlink-create"
    ]
}
```

## Linking packages
After running `init`, a new directory called `packages` was created, you need to add your packages to this directory.

If your package is called `devmcc/testing`, you need to add your package in the following folder structure:
```
./packages/devmcc/testing
```

Link your package with the following command:
```
package-dev link devmcc/testing
```

Unlink your package with the following command:
```
package-dev unlink devmcc/testing
```

## Using a phar archive
PackageDev can be run through a phar archive.
This can be very usefull for when you are using things like Docker containers.

You can create an archive with:
```
package-dev phar
```

Next, make the following changes to your project's `composer.json` file:
```diff
"pre-install-cmd": [
-    "package-dev symlink-remove"
+    "@php package-dev.phar symlink-remove"
],
"pre-update-cmd": [
-    "package-dev symlink-remove"
+    "@php package-dev.phar symlink-remove"
],
"post-install-cmd": [
-    "package-dev symlink-create"
+    "@php package-dev.phar symlink-create"
],
"post-update-cmd": [
-    "package-dev symlink-create"
+    "@php package-dev.phar symlink-create"
]
```

**NOTE**: In order to be able to create phar archives, you need to add the following to your `php.ini` file:
```
phar.readonly = 0
```

# Testing
After you have cloned the repo and installed all dependencies you can do the following:
* Run unit tests with - `vendor/bin/phpunit`
* Run code analysis with - `vendor/bin/phpstan analyse src tests --level max`
* Run code coverage analysis with - `vendor/bin/phpunit --coverage-html tmp/code-coverage`
    * Then open tmp/code-coverage/index.html in your browser
* Run phpcs with - `vendor/bin/phpcs --standard=PSR12 src tests`
