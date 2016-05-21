# jot-edit

A simple web 2.0 application with a virtual file system that can be used as an interface to link to different storage, 
physical or cloud. Poorly designed so beware if you want to use it. I wouldn't...:)
IF you are looking through this code, this is a work in progress. It is not Tested and 
I'm not sure what version of code this is. Enjoy. 

** COMMENTS **
--------------------------------------------------------------------------------------------------------------------------------
There are comments in the code but they may be a little bit lacking 
at this point. You can build doxigen comment from the code it will be much easier to suss the class hierarchy.

** CONFIG**
--------------------------------------------------------------------------------------------------------------------------------
The system needs config files stored in your web root where they can not be accessed by malicious users.
config files are needed for things to run properly.
The 3 files can be found below

CDB_Config.php     //Database config files
FInputConfig.php   //form input config security and length
IconMap_Config.php // Icon map config files



This project was written in netbeans and developen using a XAMPP on windows 7
--------------------------------------------------------------------------------------------------------------------------------

It needs to have "rewrite rules" for hard coded links to work
Ths is what i use in my XAMPP development environment.

1. REWRITE
--------------------------------------------------------------------------------------------------------------------------------

RewriteEngine On 
RewriteBase /
AddDefaultCharset utf-8 
RewriteRule ^studio/search?$  scripts/php/studio/search.php?find=$1  [QSA,NC,L]

RewriteRule ^studio/([A-Za-z0-9]+)/?$ scripts/php/studio/studio.php?request=$1  [NC,L]
RewriteRule ^studio$ scripts/php/studio/studio.php  [NC,L]
RewriteRule ^studio/$ scripts/php/studio/studio.php [NC,L]
RewriteRule ^action/account/([A-Za-z_-]+)/?$  scripts/php/action/account.php?request=$1  [NC,L]
RewriteRule ^action/account$ scripts/php/action/account.php [NC,L]
RewriteRule ^action/([A-Za-z0-9_-]+)/([A-Za-z0-9]+)/([A-Za-z0-9]+)/?$ scripts/php/action/action.php?request=$1&ticket=$2&token=$3  [NC,L]
RewriteRule ^action/([A-Za-z0-9_-]+)/?$ scripts/php/action/action.php?request=$1 [NC,L]
//custom error
RewriteRule ^error/([A-Za-z0-9_-]+)/?$  scripts/php/error/error.php?request=$1  [NC,L]







PHP CONFIG FILES BELOW
--------------------------------------------------------------------------------------------------------------------------


#2. ICON MAP

IconMap_Config.php
--------------------------------------------------------------------------------------------------------------------------------

class IconMap_Config
{
    static $m_array;

    public static function Read($name)
    {
        return self::$m_array[$name];
    }
 
    public static function Write($name, $value)
    {
        self::$m_array[$name] = $value;
    }
}

    //Navigation
IconMap_Config::write('home', 'icon-home');   
IconMap_Config::write('favourites', 'icon-star'); 
IconMap_Config::write('trash', 'icon-trash-1'); 
IconMap_Config::write('shared', 'icon-share-1'); 
IconMap_Config::write('info', 'icon-info-circled'); 
    
    //File formats
IconMap_Config::write('sfolder', 'icon-folder-1');
IconMap_Config::write('folder', 'icon-folder-1');
IconMap_Config::write('jproject', 'icon-rocket-1');
IconMap_Config::write('jcomment', 'icon-comment-2');
IconMap_Config::write('jscript','icon-bug');
IconMap_Config::write('jgroup','icon-users-1');
IconMap_Config::write('jnote','icon-feather');
IconMap_Config::write('jnotebook','icon-book-3');
IconMap_Config::write('jcontact','icon-user');

    //Interactions
IconMap_Config::write('favoured','icon-star-1');
IconMap_Config::write('unfavoured','icon-star-empty-1');
IconMap_Config::write('edit tags','icon-tags');

IconMap_Config::write('ASC','icon-down-dir');
IconMap_Config::write('DESC','icon-up-dir');
IconMap_Config::write('removeTag','icon-cancel-circle');
IconMap_Config::write('addTag','icon-plus-circled-1');

    //Context menu
IconMap_Config::write('rename','icon-pencil-1');
IconMap_Config::write('open','icon-folder-open');
IconMap_Config::write('remove','icon-trash-1');
IconMap_Config::write('move','icon-share');
IconMap_Config::write('copy','icon-clipboard');
IconMap_Config::write('delete','icon-bomb');
IconMap_Config::write('restore','icon-stethoscope');
IconMap_Config::write('share','icon-share-1');

    //form popups menu
IconMap_Config::write('wait','icon-spin4');
IconMap_Config::write('close','icon-cancel-1');
IconMap_Config::write('back','icon-reply');
IconMap_Config::write('new_folder','icon-folder-add');
IconMap_Config::write('error','icon-attention');

    //Layers
IconMap_Config::write('newItem','icon-plus');
IconMap_Config::write('link','icon-link');




3. Input Configuration
--------------------------------------------------------------------------------------------------------------------------------
The input configuration file puts the form input variable values in one place.
So if there is a name field in a form you might have the following:

  FInputConfig::write('rEx_fname', '/^[\w\s@$\-\(\)\[\]\:!\+]+$/'); // valid name input
  FInputConfig::write('fname_min', 5);                              // min length
  FInputConfig::write('fname_max', 240);                            // max length
  




FInputConfig.php
--------------------------------------------------------------------------------------------------------------------------------
/**
 * @brief Stores all of the form validation parameters so they can be adjusted
 * without having to alter code. Parameters include 
 * length of textinputs, 
 * passwords, 
 * password strength
 * regEx for validating input.
 */
class FInputConfig
{
    static $m_array;

    public static function Read($name)
    {
        return self::$m_array[$name];
    }
 
    public static function Write($name, $value)
    {
        self::$m_array[$name] = $value;
    }
}



//Regular expresions for testing validity
FInputConfig::write('rEx_name', '/^[a-zA-z\s-]+$/');        //Regular expression for a valid name.
FInputConfig::write('rEx_unit', '/^[\w-]+$/');        //Regular expression for a valid name.
FInputConfig::write('rEx_addrLine', '/^[\w-,\.\s]+$/');        //Regular expression for a valid name.
FInputConfig::write('rEx_uname', '/^[\w$@*-]+$/');      //Regular expression for a valid username.
FInputConfig::write('rEx_phone', '/^[\d]+$/');      //Regular expression for a valid username.
FInputConfig::write('rEx_pcode', '/^[\d]+$/');      //Regular expression for a valid username.
FInputConfig::write('rEx_stnum', '/^[\d]+$/');      //Regular expression for a valid username.
FInputConfig::write('rEx_cname', '/^[\w \"@$-]+$/');    //Regular expression for a valid display/creator name.
FInputConfig::write('rEx_pword', '/^[\w@$%*&^%#!\-]+$/');    //Regular expression for a valid password name.
FInputConfig::write('rEx_email', '/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/');    //Regular expression for a valid email name.
FInputConfig::write('rEx_fname', '/^[\w\s@$\-\(\)\[\]\:!\+]+$/');    //Regular expression for a valid display/creator name.
FInputConfig::write('rEx_itemDesc', '/^[\w\s@$\-\(\)\[\]\:!\+]+$/');    //Regular expression for a valid display/creator name.

//Minimumn and maximum input lengths.
FInputConfig::write('uname_min', 4);    //Minimum length for a user name..
FInputConfig::write('uname_max', 55);   //Maximum length for a user name.

FInputConfig::write('addrLine_min', 4);    //Minimum length for a user name.
FInputConfig::write('addrLine_max', 128);   //Maximum length for a user name.

FInputConfig::write('unit_min', 1);    //Minimum length for a user name..
FInputConfig::write('unit_max', 6);   //Maximum length for a user name.

FInputConfig::write('stnum_min', 1);    //Minimum length for a user name..
FInputConfig::write('stnum_max', 6);   //Maximum length for a user name.

FInputConfig::write('pcode_min', 1);    //Minimum length for a user name..
FInputConfig::write('pcode_max', 6);    //Maximum length for a user name.

FInputConfig::write('cname_min', 1);     //Minimum length for a name.
FInputConfig::write('cname_max', 128);   //Maximum length for a name.

FInputConfig::write('name_min', 1);     //Minimum length for a name.
FInputConfig::write('name_max', 128);   //Maximum length for a name.

FInputConfig::write('email_min', 5);     //Minimum length for a email.
FInputConfig::write('email_max', 256);   //Maximum length for a email.

FInputConfig::write('phone_min', 5);     //Minimum length for a email.
FInputConfig::write('phone_max', 256);   //Maximum length for a email.

FInputConfig::write('fname_min', 5);     //Minimum length for a email.
FInputConfig::write('fname_max', 240);   //Maximum length for a email.

FInputConfig::write('itemDesc_min', 1);     //Minimum length for a email.
FInputConfig::write('itemDesc_max', 512);   //Maximum length for a email.

//Minimumn and maximum password lengths.
FInputConfig::write('weak_pword_min', 5);  //Minimum length for a weak password.
FInputConfig::write('weak_pword_max', 16); //Maximum length for a weak password.

FInputConfig::write('medium_pword_min', 8);  //Minimum length for a medium password.
FInputConfig::write('medium_pword_max', 32); //Maximum length for a medium password.

FInputConfig::write('strong_pword_min', 8);  //Minimum length for a strong password.
FInputConfig::write('strong_pword_max', 56); //Maximum length for a strong password.







4. Database config file
--------------------------------------------------------------------------------------------------------------------------------
This will vary dependant on you database.
My dev environment is uses pdo .

CDB_Config.php
--------------------------------------------------------------------------------------------------------------------------------
class DB_Config
{
    static $m_array;

    public static function Read($name)
    {
        return self::$m_array[$name];
    }
 
    public static function Write($name, $value)
    {
        self::$m_array[$name] = $value;
    }
}

// default database settings db for XAMPP dev environment
DB_Config::write('db.host', '127.0.0.1');
DB_Config::write('db.port', '5432');
DB_Config::write('db.basename', 'databaseName');
DB_Config::write('db.user', 'databaseUser'*/);
DB_Config::write('db.password','databasePassword');



Any more questions just let me know, but there are much better ways to do this.
I just did it this way cause it was built on a uni project where PHP was a requirement.







