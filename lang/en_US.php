<?php
    $lang = array(

        'friendlyname'       => 'Internationalization',
//        'postinstall'        => 'Module installed.',
        'postinstall'        => 'Be sure to set "internationalization" permissions to use this module!',
        'postuninstall'      => 'Module successfuly uninstalled',
        'really_uninstall'   => 'Really? Are you sure
you want to unsinstall this fine module?',
        'uninstalled'        => 'Module Uninstalled.',
        'installed'          => 'Module version %s installed.',
        'upgraded'           => 'Module upgraded to version %s.',
        'moddescription'     => 'This module helps to manage internationalized for translations, language selection, flags, etc.',

        'error'              => 'Error!',
        'admin_title'        => 'Internationalization Admin Panel',
        'admindescription'   => '',
        'accessdenied'       => 'Access Denied. Please check your permissions.',


        'title_translations' => 'Translations',
        'title_links'        => 'Links',
        'title_preferences'  => 'Preferences',
        'title_harvest'      => 'Harverst for texts',
        'form_harvest'      => 'Harverst for texts',
        'harvest_tips'       => 'Enable harvesting will create translations options in the database if they do not exists. This is required in the begining but may slowdown the website.',
        'title_cache'        => 'Use cache',
        'cache_label'        => 'Activate the cache for frontend translation (this reduces the DB load)',

        'translate_me'       => 'Translate me',
        'default_culture'    => 'Default culture',
        'form_default_culture'    => 'Default culture',
        'culture'            => 'Culture',
        'source'             => 'Source',
        'target'             => 'Target',

        'submit'             => 'Submit',
        'cancel'             => 'Cancel',

        'changelog'          => '<ul>
<li>Version 0.0.1 - 10 May 2010. Initial Release.</li>
</ul>',
        'help'               => '<h3>What Does This Do?</h3>
<p>This module helps to manage internationalized for translations, language selection, flags, etc.</p>
<h3>How Do I Use It</h3>
<p>To use the translation model: Put your pages under a main page like "en" or "fr". The alias of the page must be a valid language.</p>
<p><u>On the template, use:</u></p>
<p>{I18n text="My text to translate"}</p>
<p><u>or the capture function:</u></p>
<p>{I18nCapture}My text to translate{/I18nCapture}</p>
<p><u>or the super cool capture function:</u></p>
<p>{__}Text to translate{/__}</p>
<p>You can pass some replacements strings to the function using the __ prefix for the param.</p>
<p>{__ __number_of_donuts="10"}This belly contains __number_of_donuts donuts{/__}
<p>Activate the option "Harvest" in the admin panel.</p>
<p><u>Function for your own modules</u></p>
<p>I18n::__($content, $elements = array());</p>
<p>$content is the string you want to translate. $elements is an optional array where you can put some strings that you want to replace.<p>
<p>Example:</p>
<pre>
echo I18n::__(\'Welcom\');
will translate and echo "Welcom";
echo I18n::__(\'This belly contains %number_of_donuts% donuts\', array(\'%number_of_donuts%\' => $nb_donuts));
will translate and echo "This belly contains %number_of_donuts% donuts" and replace %number_of_donuts% with the value of $nb_donuts;
</pre>
<p></p>
<p>To initialize the strings, browse your pages.</p>
<p>You should now be able to set the translations in the admin panel.<p>
<p>For links</p>
<p>{I18n action=\'getlink\' selected_page=\'calendar\'} </p>
<p>This allows you to create a splash with a language selection and redirect the user to this page if language has been selected</p>
<h3>Browser detection</h3>
<p>You can use {I18n action="browserRedirect" en="alias-en" fr="alias-fr" default="alias-en"} where you can add all the languages redirections you want in the parameters. It will use the browser information to redirect to the right language.<p>
<p>The default will be fired if no language match. It is not mandatory. In this case, it will stay on the current page.</p>
<h3>Create links</h3>
<p>You could use the tag {I18n_link href="debug"} to create links. You can also use the param "page" instead of "href" to get a full link and the param "text" to replace the menu_text from the page and make it translated by I18n.</p>
<h3>get available languages</h3>
<p>Action languages returns var "languages_list".</p>
<h3>Support</h3>
<p>As per the GPL, this software is provided as-is. Please read the text of the license for the full disclaimer.</p>
<h3>Copyright and License</h3>
<p>Copyright &copy; 2010, Jean-Christophe Cuvelier <a href="mailto:jcc@morris-chapman.com">&lt;jcc@morris-chapman.com&gt;</a>. All Rights Are Reserved.</p>
<p>This module has been released under the <a href="http://www.gnu.org/licenses/licenses.html#GPL">GNU Public License</a>. You must agree to this license before using the module.</p>',
    );
?>
