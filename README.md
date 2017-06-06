# OpenInbound for WordPress

Connector plugin for OpenInbound.com.

## Development

Development of this plugin happens on GitHub, while final releases will be published on WordPress.org.

### Grunt Tasks

Use `grunt build` to create a build for the plugin without any unneeded files. That build can be used for uploading the plugin to any WordPress install, or for submitting the plugin to WordPress.org

Besides `grunt build`, there's currently also a `checktextdomain` task that can be invoked using `grunt checktextdomain` or just `grunt`. This task makes sure all the i18n functions use the correct text domain so that the translation on WordPress.org works flawlessly.

**Note:** To be able to use Grunt, you have to install the needed scripts using `npm install` first.

### Release Day

To prepare a new release, you need to do a few things in advance:

1. Install all dependencies using `composer install`.
   This will create a new `vendor` folder containing the dependencies needed by the plugin. There are currently only two of them, so the folder is really small.
2. Run `grunt checktextdomain` to make sure al the text domains are OK.

After that, it basically comes down to this:

1. Update version numbers in `composer.json` and `package.json`.
2. Update the `Changelog`, `Upgrade Notice` and the `Stable Tag` in `readme.txt`.
3. Build the plugin using `grunt build`.
   This will create a new folder inside `release` containing the final build. The folder name is based on the version set in step 1.

After that, you can use the files inside `release/<version>` to commit the changes to the WordPress.org repository. The [Plugin Handbook](https://developer.wordpress.org/plugins/wordpress-org/how-to-use-subversion/) helps you with that.