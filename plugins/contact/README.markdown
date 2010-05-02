# Contact form plugin for CakePHP

This plugin gives you a basic contact form that sends the contact info to you by email, and also stores it in the database in case the email gets lost.

After sending and storing the contact info it redirects to a thanks page on which you can put a Google Analytics tracker if you wish to follow AdWords conversion goals.

# Installation

### Clone
In your `plugins` folder type

    git clone git://github.com/msadouni/cakephp-contact-plugin.git contact

### Submodule
In your root folder type

    git submodule add git://github.com/msadouni/cakephp-contact-plugin.git plugins/contact`
    git submodule init
    git submodule update

### Archive
Download the archive from github and extract it in `plugins/contact`

# Usage

1. Import the sql in `plugins/contact/config/sql/contact.sql` in your database
2. In `config/bootstrap.php` or a config file, specify the email you wish to receive the contacts on : Configure::write('Contact.email', 'you@example.com');
3. To get the included french translation, add `Configure::write('Config.language', 'fre');` to `config/bootstrap.php` or a config file.
4. The contact form is by default located at `/contact/contacts/add`

# Customizing
- You can add a custom routes to your `config/routes.php` file if you wish :

        Router::connect('/contact', array(
            'plugin' => 'contact',
            'controller' => 'contacts',
            'action' => 'add'));
        Router::connect('/contact/thanks', array(
            'plugin' => 'contact',
            'controller' => 'contacts',
            'action' => 'thanks'));

- Basic views and css are provided. You can override them by creating custom views for the form and thanks pages in `views/plugins/contact/views/add.ctp` and `views/plugins/contact/views/thanks.ctp`. The thanks view is only suitable for development, you'll have to create your own for production use.

# Translating
The form view contains the string used by error messages so they can be extracted with `cake i18n` tool. Feel free to fork the code, translate it in your language and make a pull request or contact me so I can include it in the plugin.

# TODO
- Add an admin section to view and search the contacts, and remove the ones you don't want to keep