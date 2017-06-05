# Composer Build Plugin

One of the missing features of composer is to allow for build processes to occur on dependency projects, within the project installation directory.

This is meant to resolve that problem.

# Usage

The steps for a repository wanting build processes to take place when they are a dependency are as follows:

Require this repository:
```sh
composer require xylemical/composer-build-plugin
```

Add classes that implement `\Xylemical\Composer\Build\PluginInterface` to the build-plugins in build order within the extra of the composer.json.

```
{
    "extra": {
        "build-plugins": [
            "Xylemical\Composer\Build\Plugins\Npm",
            "Xylemical\Composer\Build\Plugins\Grunt"
        ]
    }
}
```

# Custom Plugins

These build steps are executed after all the packages have been installed into their locations, and are available via autoloading.

To implement a custom plugin, ensure that it implements `\Xylemical\Composer\Build\PluginInterface` and add it to the 'build-plugins' as normal.
