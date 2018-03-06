# Search.gov Indexer for WordPress - this is an untested Alpha

> This Search.gov indexer allows WordPress administrators to index the posts and pages of their WordPress blog or website with the Search.gov hosted search service.

### Installation

Configuring the plugin is straightforward:

  - Install the plugin
  - Set your credentials
  - Manually run the indexer for the first time

Once the plugin has been properly configured, it will automatically update your search results as you create, update, or delete posts or pages.

##### Installing the Plugin
You can install the code via either git or by downloading a .zip of the plugin.

First, `cd` into the plugins directory
```sh
$ cd wp-content/plugins
```

Then, install the plugin via `git`
```sh
$ git clone https://github.com/GSA/wp-digitalgov-i14y-indexer.git digitalgov_search
```

Or by pulling down the .zip with `wget` and using `unzip` to unpack it.
```sh
$ wget -O dgsearch.zip https://github.com/GSA/wp-digitalgov-i14y-indexer/archive/master.zip
$ unzip dgsearch.zip -d digitalgov_search
$ rm -f dgsearch.zip # cleanup
```

### Credentials

To find the credentials for your website, log in to the search Admin Center ([https://search.usa.gov/sites](https://search.usa.gov/sites)) and navigate to `Content -> i14y Drawers`. Once you've created a new drawer, you will receive an API token which is used to authenticate your WordPress website with our service.

### Version

0.0.3

### Development

Want to contribute? Great! We're quite responsive to pull requests.
