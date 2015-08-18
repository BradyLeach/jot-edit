USE gngr;

/*Create the country table*/
CREATE 
table gcountry ( 
    name VARCHAR( 128 )NOT NULL COLLATE utf8_unicode_ci, 
    dialCode SMALLINT( 4 ) NULL ,
    abbrv  VARCHAR( 8 ) NULL COLLATE utf8_unicode_ci,
    PRIMARY KEY (name)
)
CHARACTER SET utf8 
COLLATE utf8_unicode_ci
; 




/*Create the state table*/
CREATE 
table gstate ( 
    id INT( 11 ) NOT NULL AUTO_INCREMENT,
    name VARCHAR( 50 ) NOT NULL COLLATE utf8_unicode_ci, 
    abbrv  VARCHAR( 8 ) NULL COLLATE utf8_unicode_ci,
    country VARCHAR( 128 ) NULL COLLATE utf8_unicode_ci,
    PRIMARY KEY (id),
    FOREIGN KEY(country) REFERENCES gcountry(name)
        ON UPDATE CASCADE ON DELETE RESTRICT 
)
CHARACTER SET utf8 
COLLATE utf8_unicode_ci
;  




/*Create the address type table*/
CREATE 
table gaddress_type (
    aType VARCHAR( 50 ) NOT NULL COLLATE utf8_unicode_ci,
    PRIMARY KEY(aType)
)
CHARACTER SET utf8 
COLLATE utf8_unicode_ci
;  



/*create the address base table
more complex version
CREATE 
table gaddress ( 
    id INT( 11 ) AUTO_INCREMENT,
    suburb  VARCHAR( 50 ) NULL COLLATE utf8_unicode_ci,
    city  VARCHAR( 50 ) NULL COLLATE utf8_unicode_ci,
    postCode  SMALLINT( 5 ) NOT NULL,
    stateID INT( 11 ) NOT NULL, 
    addressType  VARCHAR( 50 ) COLLATE utf8_unicode_ci,
    PRIMARY KEY (id),   
    FOREIGN KEY(stateID) REFERENCES gstate(id) ON UPDATE CASCADE ON DELETE RESTRICT , 
    FOREIGN KEY(addressType) REFERENCES gaddress_type(aType) ON UPDATE CASCADE ON DELETE RESTRICT   
)
CHARACTER SET utf8 
COLLATE utf8_unicode_ci
; 
*/

CREATE 
table gaddress ( 
    id INT( 11 ) AUTO_INCREMENT,
    line1  VARCHAR( 50 ) NULL COLLATE utf8_unicode_ci,
    line2  VARCHAR( 50 ) NULL COLLATE utf8_unicode_ci,
    suburb  VARCHAR( 50 ) NULL COLLATE utf8_unicode_ci,
    postCode  SMALLINT( 5 ) NOT NULL,
    stateID INT( 11 )  NULL,
    accountID INT( 11 )  NULL, 
    PRIMARY KEY (id),   
    FOREIGN KEY(stateID) REFERENCES gstate(id) ON UPDATE CASCADE ON DELETE RESTRICT,    
    FOREIGN KEY(accountID) REFERENCES gaccount(id) ON UPDATE CASCADE ON DELETE CASCADE    
)
CHARACTER SET utf8 
COLLATE utf8_unicode_ci
; 



/*Create street suffix table*/
CREATE 
table gstreet_suffix (
    suffix VARCHAR( 50 ) NOT NULL COLLATE utf8_unicode_ci,
    abbrv VARCHAR( 6 ) NOT NULL COLLATE utf8_unicode_ci,
    PRIMARY KEY(suffix)
)
CHARACTER SET utf8 
COLLATE utf8_unicode_ci
;  




/*Create the street address table*/
CREATE
table gstreet_address (
    streetName VARCHAR( 128 ) NOT NULL COLLATE utf8_unicode_ci, 
    streetSuffix VARCHAR( 50 ) NOT NULL COLLATE utf8_unicode_ci,
    streetNumber  SMALLINT( 5 ) NOT NULL,
    unit  VARCHAR( 50 ) NULL, 
    addressID INT( 11 ) NOT NULL, 
    PRIMARY KEY(addressID),
    FOREIGN KEY(addressID) REFERENCES gaddress(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(streetSuffix) REFERENCES gstreet_suffix(suffix)  ON UPDATE CASCADE ON DELETE RESTRICT
)
CHARACTER SET utf8 
COLLATE utf8_unicode_ci
; 




/*Create the post officebox address table*/
CREATE
table gpobox_address (
    poBox  SMALLINT( 8 ) NOT NULL,
    addressID INT( 11 ), 
    PRIMARY KEY(addressID), 
    FOREIGN KEY(addressID) REFERENCES gaddress(id) ON UPDATE CASCADE ON DELETE CASCADE
)
CHARACTER SET utf8 
COLLATE utf8_unicode_ci
;     















 