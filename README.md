# PackageDev
Makes developing composer packages easier.



# About
This package comes with a set of commands that will support you in developing composer packages.  
It makes use of junctions to link your package from the developing directory `packages` to the vendor directory. This allows you to make modifications to your package and test it immediately.  
The package makes use of some [Composer Command Events](https://getcomposer.org/doc/articles/scripts.md#command-events) to secure the sourcecode of your package from `composer update` and `composer install`.



# Installation
Install this package with the following command:
```
composer global require devmcc/package-dev
```


## Bin
Add the global vendor binaries in your $PATH environment variable, more about that [here](https://getcomposer.org/doc/03-cli.md#global).


## Command Events
Assuming you know how to properly edit a json file and know something about [composer scripts](https://getcomposer.org/doc/articles/scripts.md), you have to make a few additions to your composer.json file.

### pre-install-cmd
```
"package-dev junction:drop"
```

### pre-update-cmd
```
"package-dev junction:drop"
```

### post-install-cmd
```
"package-dev junction:make"
```

### post-update-cmd
```
"package-dev junction:make"
```



## Verify installation
In order to verify the installation, you can run the following command:
```
package-dev
```
If everything has been installed correctly, you should receive the following message:
```
>> PackageDev is functioning properly.
```



# Usage
1. Initialize PackageDev;
2. Add your package into `packages`;
3. Link your package using the `Link` command;
4. Develop your package!



# Commands
The following is a documentation about all available commands:


## Init

### Syntax
```
package-dev init
```

### Description
Initializes the PackageDev environment in your current working directory.

### Requirements
* Your current working directory is a composer directory;
* Read/write permissions in your current working directory.

### Execution
1. Checks requirements (including arguments);
2. Attempts to create a `packages` directory in your current working directory;
3. Attempts to create `package-dev.json` file in the `packages` directory.


## Link

### Syntax
```
package-dev link {package}
```

### Description
Links {package} to the PackageDev environment and makes a junction for this package.

### Arguments
#### {package}
The package to be linked.
###### Requires
* {package} is in syntax of `vendor/package`;
* {package} is present in vendor;
* {package} is present in packages.

### Requirements
* Your current working directory is a composer directory;
* Your current working directory is a package-dev initialized directory;
* Read/write permissions in your current working directory;
* {package} is not already linked.

### Execution
1. Checks requirements (including arguments);
2. Triggers `JunctionMake` with {package};
3. Registers {package} to `package-dev.json`.


## Unlink

### Syntax
```
package-dev unlink {package}
```

### Description
Unlinks {package} from the PackageDev environment and drops the junction of this package.


### Arguments
#### {package}
The package to be unlinked.
###### Requires
* {package} is in syntax of `vendor/package`;
* {package} is present in vendor;
* {package} is present in packages.

### Notes
* Before you remove a package, you should unlink it.

### Requirements
* Your current working directory is a composer directory;
* Your current working directory is a package-dev initialized directory;
* Read/write permissions in your current working directory;
* {package} is linked.

### Execution
1. Checks requirements (including arguments);
2. Triggers `JunctionDrop` with {package};
3. Removes {package} from `package-dev.json`.


## JunctionMake

### Syntax
```
package-dev junction:make {package=linked-packages}
```

### Description
Makes a junction for {package}.

### Arguments
#### {package} (Optional-Default)
The package to make the junction for.
###### Default
linked-packages: All linked packages.
###### Requires
* {package} is in syntax of `vendor/package`;
* {package} is present in vendor;
* {package} is present in packages.

### Requirements
* Your current working directory is a composer directory;
* Your current working directory is a package-dev initialized directory;
* Read/write permissions in your current working directory;
* {package} is linked;
* {package} does not have a junction.

### Execution
1. Checks requirements (including arguments);
2. Makes a junction for {package}.

## JunctionDrop

### Syntax
```
package-dev junction:drop {package=linked-packages}
```

### Description
Drops a junction for {package}.

### Arguments
#### {package} (Optional-Default)
The package to drop its junction.
###### Default
linked-packages: All linked packages.
###### Requires
* {package} is in syntax of `vendor/package`;
* {package} is present in vendor;
* {package} is present in packages.

### Requirements
* Your current working directory is a composer directory;
* Your current working directory is a package-dev initialized directory;
* Read/write permissions in your current working directory;
* {package} is linked;
* {package} has a junction.

### Execution
1. Checks requirements (including arguments);
2. Drops the junction of {package}.
