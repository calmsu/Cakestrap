Cakestrap - Twitter Bootstrap CakePHP 2.0 Plugin
================================================

Cakestrap is designed to simplify integrating [Twitter Bootstrap](http://twitter.github.com/bootstrap/ ) 
into CakePHP 2.0.  It includes CakestrapFormHelper and CakestrapHtmlHelper which
are designed to replace the built-in CakePHP Html and Form helpers.  Both of 
these helpers extend the built-in helpers so that all functionality provided by
the built-in helpers is still available.

Usage
=====

Cakestrap doesn't include the Twitter Bootstrap libraries (css files, less 
files, etc) so you'll need to include those on your own and link to them in your
layouts.  Cakestrap does include a helper method in the CakestrapHtmlHelper for 
hot-linking to the Twitter Bootstrap css files:

    //In your layout file's head section
    <?php echo $this->Html->bootstrapCss(); ?>

Including the helpers
---------------------
###Option 1 - Include the component
When using the included component, Cakestrap looks to see if the default Html 
and Form helpers have been requested and replaces them with Cakestrap's 
versions. 
_Note:_ This will only work if the Html and Form helpers are included in
your AppController.  If they are requested in an action, Cakestrap will never
see them and won't include them.

The component also replaces the default flash message template with it's own 
element.  To use any of the other flash elements included with Cakestrap 
(bs\_error, bs\_success, bs\_warning) provide their name when calling 
Session::setFlash().  Cakestrap will replace any element beginning with 'bs\_' 
with the appropriate file from Cakestrap's elements.

    //In AppController.php
    public $components = array('Session', 'Cakestrap.Cakestrap' => array(
        'flash' => true,
        'helpers' => true,
    );

The settings array in the above example is optional and indicates the default 
values.  Use these options to disable either feature of the Component.

###Option 2 - Include the Helpers yourself
CakePHP 2.0 allows for aliasing of helpers.  This functionality is used to allow
Cakestrap to override the default helpers.  The example below overrides 
HtmlHelper with Cakestrap's html helper.  Replace Html with Form in this example
to include the Form helper.

_Note:_ Including the helpers directly means that Cakestrap won't override the 
default flash messages, but you can still access Cakestrap's flash elements 
using the plugin array of Session::setFlash().

    //In AppController.php
    public $helpers = array('Html' => array('className' => 'Cakestrap.CakestrapHtml'));

Methods
-------

###CakestrapHtmlHelper
* label - output inline label
* button - output anchor link styled button
* copyLink - not really a Bootstrap function, create a 'Copy to Clipboard' link
* mediaGridStart - outputs starting tag for a media grid
* mediaGridImage - outputs image wrapped for a media grid
* rowStart - outputs starting tag for a layout grid row
* columnStart - outputs starting tag for layout grid column with specified width
* alertMessage - outputs one-liner alert message
* tabs - outputs tabs or pills
* bootstrapCss - includes hotlinked Twitter Bootstrap CSS
* less - wrapper for css method, sets rel for less stylesheets

###CakestrapFormHelper
* input - Reformats inputs
* error - Reformats error messages 
* postButton - outputs postLink styled as an anchor button.

Note: Check the helper files for a complete list of functions.
