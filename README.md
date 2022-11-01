Symfony (Sons of PHP Edition)
=============================

This is a modified version of Symfony LTS. It is used to setup new projects and
has a few awesome features like:

- Supports "APP_NAME" so that multiple apps can use the same code base. This
  makes it easier to have "api", "admin", and "app" in the same code base.
- Docker files included so you don't need to add anything else
- Sons of PHP bundles and libraries are pre-installed and configured
- Includes example "Makefile" loaded with various helpful targets for you to
  use. To see what can be done, just run "make" to see the help screen.

This template also comes some some example directory structures, but you are
free to change them or use them however you'd like.

## Apps

When you build an app, you will generally have a section for your users to login
and interact with (ie "app"). You may also want to have an admin section for you
to log in and manage the application (ie "admin"). Eventually, you may also want
an API for others to use (ie "api").

Each one of these apps will use a common code base (ie "shared").

The way this version of Symfony is laid out, allows you to separate each of
these apps. As an example, you could set this up on Heroku and have it share the
same database. Each Heroku App can be configure by just changed the env var
"APP_NAME" to whichever app you want to use.

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

## Testing

This template comes with it's own "WebTestCase" that you need to extend in order
for everything to work properly. You can see in the "ExampleTest" how to load
different configurations to test.

## Getting Help

If you need help or have questions, please look at the following:

* https://github.com/orgs/SonsOfPHP/discussions
* https://discord.gg/sdVxNhFqND