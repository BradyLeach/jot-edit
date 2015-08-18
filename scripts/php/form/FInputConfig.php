<?php

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
FInputConfig::write('pcode_max', 6);   //Maximum length for a user name.

FInputConfig::write('cname_min', 1);     //Minimum length for a name.
FInputConfig::write('cname_max', 128);   //Maximum length for a name.

FInputConfig::write('name_min', 1);     //Minimum length for a name.
FInputConfig::write('name_max', 128);   //Maximum length for a name.

FInputConfig::write('email_min', 5);     //Minimum length for a email.
FInputConfig::write('email_max', 256);   //Maximum length for a email.

FInputConfig::write('phone_min', 5);     //Minimum length for a email.
FInputConfig::write('phone_max', 256);   //Maximum length for a email.


//Minimumn and maximum password lengths.
FInputConfig::write('weak_pword_min', 5);  //Minimum length for a weak password.
FInputConfig::write('weak_pword_max', 16); //Maximum length for a weak password.

FInputConfig::write('medium_pword_min', 8);  //Minimum length for a medium password.
FInputConfig::write('medium_pword_max', 32); //Maximum length for a medium password.

FInputConfig::write('strong_pword_min', 8);  //Minimum length for a strong password.
FInputConfig::write('strong_pword_max', 56); //Maximum length for a strong password.