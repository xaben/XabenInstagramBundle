XabenInstagramBundle
======================
Provides a sonata block to display latest Instagram feed on your website

Installation
------------

Download XabenInstagramBundle and its dependencies to the ``vendor`` directory. You
can use Composer for the automated process:

```console
php composer.phar require xaben/instagram-bundle --no-update

php composer.phar update
```
Next, be sure to enable the bundle in your AppKernel.php file:
```php
// app/AppKernel.php
public function registerBundles()
{
    return array(
        // ...
        new Xaben\InstagramBundle\XabenInstagramBundle(),
        // ...
    );
}
```
Now, install the assets from the bundle:

```console
php app/console assets:install web
```

Configuration
------------
Provide the necesarry configuration in `config.yml` add:
```yml
xaben_instagram:
    api_key: %instagram_api_id%
    user_id: {Your Instagram numeric user ID}
    user_name: {Your Instagram username}
    cache_service: doctrine_cache.providers.{Your cache service name}
```
Configure api key in `parameters.yml` add:
```yml
    instagram_api_id: {YOUR API ID}
```
For cache service use the Doctrine Cacher, to configure it:
```yml
doctrine_cache:
    providers:
        {Your cache service name}:
            type: {Your preffered cache type}
            namespace: {Your namespace}
```
Usage
-----
To display the feed drop the following block in the coresponding place (usually your layout), and the optional runtime configuration, by default the User ID from the configuration and 6 photos are diplayed with the supplied template:
```twig
{{ sonata_block_render(
    { 'type': 'xaben.instagram.block.instagram' }, 
    {
    'userId': 'the user ID to fetch the feed for',
    'limit': 12,
    'template': 'your custom template'
    }
  )
}}
```
