Symfony (Sons of PHP Edition)
=============================

This is a modified version of Symfony. It is used to setup new projects and
has a few awesome features like:

- Supports "APP_NAME" so that multiple apps can use the same code base. This
  makes it easier to have "api", "admin", and "app" in the same code base.
- Docker files included so you don't need to add anything else
- Sons of PHP bundles and libraries are pre-installed and configured
- Includes example "Makefile" loaded with various helpful targets for you to
  use. To see what can be done, just run "make" to see the help screen.
- Includes [DAMADoctrineTestBundle](https://github.com/dmaicher/doctrine-test-bundle) pre-configured
- Includes GitHub actions for unit/functional tests using PHPUnit and static code analysis
  using psalm
- Includes `dependabot.yml` file
- Multiple Buses have been preconfigured for you
- Bootstrap CSS is preconfigured along with a few templates to get you started
- Ready to go with user & account registration, user login, and password reset
- Comes with a `UniqueDto` Symfony Constraint

## Setting it up

Just run `make install` to get started. This installs all the dependencies
(uses `composer` and `yarn`), installs various tools (`php-cs-fixer`, `psalm`,
etc.), and will build assets.

Once all the dependencies are installed, you can run `make start` to spin up the
Symfony server along with the docker containers.

Next, you will need to run the database migrations. This is quickly done via
`make db-migrate`.

To do all of this at once, just run `make install start db-migrate`.

To see a list of all the targets and descriptions, just run `make`. There are a
lot of different targets to help you manage your project.

## Apps

One of the goals of this project is to get a proof of concept out as fast as
possible. When it comes to adding apps, it's suggested you follow one of these
patterns: 1) A single app with a frontend (app), backend (admin), and API (api)
or 2) Multiple apps like "facebook" and "instagram".

You can, of course, use this however you want. These are just ideas and what
I've found to be useful.

NOTE: Both ways have pros & cons. Using this with a single app is pretty
straight forward as your entities, migrations, and data fixtures will be in the
Shared directory. However, having multiple apps requires more work since you
will have entities, migrations, and data fixtures that other apps will not know
anything about

### Directory Structure Examples

Single App, broken out
```
src/
    Admin/
    Api/
    App/
    Shared/
```

Multiple apps
```
src/
    Facebook/
    Instagram/
    Shared/
```

## Configuration

The "shared" config is always loaded. It's in the standard symfony config
location ("/config").

For each app you create you'll need to have a configuration folder in "/etc/{App
Name}" that mirrors the structure of the default configuration. Here you will be
able to override the shared config settings and add settings specific to the
app you want to setup. An example of this would be for "app" you want to load
users from the database. However for the "admin" app, you want to load users
from a LDAP server.

To change everything, just use your ".env" file and change "APP_NAME" to
whatever app you want to use.

## Adding/Removing Apps in the codebase

Take a look at the `composer.json` file. You will is in the "autoload" and
"autoload-dev" sections where you can add more apps or remove the existing ones.

NOTE: "Shared" should always remain and only the "psr-4" sections should be
modified.

## Doctrine Fixtures

Data Fixtures CAN be a pain in the ass if you aren't careful. The setup also
depends on if you have a single app broken up (app, admin, api) or multiple
apps. Every app has access to `Shared\DataFixtures` but they will not have
access to fixtures stored in other apps.

Loading the fixtures into a database can be tricky as well. I've found it best
to use Groups and name the groups after the app's name. Basically you will need
to load them in shared first and append the rest.

Example:
```
php bin/console doctrine:fixtures:load --group shared
php bin/console doctrine:fixtures:load --app app --group app --append
php bin/console doctrine:fixtures:load --app admin --group admin --append
php bin/console doctrine:fixtures:load --app api --group api --append
```

## Doctrine Migrations

Migrations are kind of a bitch right now. The `migrations/shared` migrations
will always run and the specific app migrations you want will run as well. The
`shared` migrations should ONLY contain things that are applicable for ALL apps.
This could be something like the `users` table.

Each app has no idea about the other migration files so if you have multiple
apps with different migrations, doctrine will bitch there are unknown migrations
that were executed when switching between apps.

### Creating a New Migration

`php bin/console doctrine:migrations:diff --namespace "DoctrineMigrations\\App"`

You'll need to make sure to include the namespace.

## Translations

* `templates/shared` includes common words and phrases used across all apps
* `templates/{app_name}` includes specific terms for the app being used


## Templates

Templates will work a little different. Out of the box, it will work like this:

1. Does the template exist in `templates/{app_name}`? If yes, load that template
2. Does the template exist in `templates/shared`? If yes, load that template
3. No template was found, throw error

```twig
{# templates/shared/base.html.twig #}
<html>
    <body>
        {% block _body %}{% endblock %}
    </body>
</html>
```

```twig
{# templates/shared/_header.html.twig #}
<h1>{% block _page_title %}{% endblock %}</h1>
```

```twig
{# templates/app/layout.html.twig #}
{% extends 'base.html.twig' %}
{# extends will use 'templates/shared/layout.html.twig' #}
{% block _body %}
    {# Unless you create the template `templates/app/_header.html.twig`, this
       will use the template `templates/shared/_header.html.twig` #}
    {{ include('_header.html.twig') }}
    <div class="container">
        {% block content %}{% endblock %}
    </div>
{% endblock %}
```

```twig
{# templates/app/homepage.html.twig #}
{% extends 'layout.html.twig' %}
{# extends will use 'templates/app/layout.html.twig' #}

{% block content %}
    Content!
{% endblock %}
```

## Testing

This template comes with it's own "WebTestCase" that you need to extend in order
for everything to work properly. You can see in the "ExampleTest" how to load
different configurations to test.

Running tests for all the apps can be a huge pain in the ass. If you have
multiple apps and each app has different database schemas, you will need to load
all those up, run migrations (or just execute SQL for everything), and load
fixtures in for each app.

## Getting Help

If you need help or have questions, please look at the following:

* https://github.com/orgs/SonsOfPHP/discussions
* https://discord.gg/sdVxNhFqND
