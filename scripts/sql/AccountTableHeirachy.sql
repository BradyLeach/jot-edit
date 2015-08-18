USE gngr;

/*Create the account table*/
CREATE 
table gaccount ( 
    id INT( 11 ) NOT NULL AUTO_INCREMENT,
    nameGiven VARCHAR( 50 ) NOT NULL COLLATE utf8_unicode_ci, 
    nameFamily  VARCHAR( 50 ) NOT NULL COLLATE utf8_unicode_ci,
    joined TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (id)
)
CHARACTER SET utf8 
COLLATE utf8_unicode_ci
; 

CREATE
table gaccount_state (
    aState varchar( 60 ) NOT NULL, 
    PRIMARY KEY(aState)
)
CHARACTER SET utf8 
COLLATE utf8_unicode_ci
; 


CREATE
table gaccount_status (
    status varchar( 60 )  NULL, 
    accountID INT( 11 ) NULL,
    PRIMARY KEY(accountID),
    FOREIGN KEY(accountID) REFERENCES gaccount(id),
    FOREIGN KEY(status) REFERENCES gaccount_state(aState)
)
CHARACTER SET utf8 
COLLATE utf8_unicode_ci
; 

/*Create the email contact table*/
CREATE 
table gcontact_email ( 
    id INT( 11 ) NOT NULL AUTO_INCREMENT ,
    email VARCHAR( 128 ) NOT NULL COLLATE utf8_unicode_ci, 
    accountID INT( 11 ) NOT NULL, 
    PRIMARY KEY(id),
    FOREIGN KEY(accountID) REFERENCES gaccount(id)
        ON UPDATE CASCADE ON DELETE RESTRICT 
)
CHARACTER SET utf8 
COLLATE utf8_unicode_ci
; 


/*Create the phone contact table*/
CREATE 
table gcontact_phone (
    id INT( 11 ) NOT NULL AUTO_INCREMENT ,
    phoneNumber VARCHAR( 128 ) NOT NULL COLLATE utf8_unicode_ci, 
    accountID INT( 11 ) NOT NULL, 
    PRIMARY KEY(id),
    FOREIGN KEY(accountID) REFERENCES gaccount(id)
        ON UPDATE CASCADE ON DELETE RESTRICT 
)
CHARACTER SET utf8 
COLLATE utf8_unicode_ci
; 



/*Create the email contact table*/
CREATE 
table glogin  (
    id INT( 11 ) NOT NULL AUTO_INCREMENT,
    username VARCHAR( 128 ) NOT NULL COLLATE utf8_unicode_ci,
    password VARCHAR( 60 ) NOT NULL COLLATE utf8_unicode_ci,
    accountID INT( 11 ) NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(accountID) REFERENCES gaccount(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
)
CHARACTER SET utf8 
COLLATE utf8_unicode_ci
; 


CREATE 
table gadmin  (
    id INT( 11 ) NOT NULL AUTO_INCREMENT,
    accountID INT( 11 ) NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(accountID) REFERENCES gaccount(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
)
CHARACTER SET utf8 
COLLATE utf8_unicode_ci
; 
/*ALTER TABLE gaccount CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;*/