/** 
    file that holds the sql statments for creating all the link tables.
*/    

USE gngr;


/*Create a link table that links billing addresses to a user account.*/
CREATE
table gbilling_address (
    id INT( 11 ) AUTO_INCREMENT,    
    addressID INT( 11 ), 
    accountID INT( 11 ),
    PRIMARY KEY(id),
    FOREIGN KEY(addressID) REFERENCES gaddress(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(accountID) REFERENCES gaccount(id) ON UPDATE CASCADE ON DELETE CASCADE
)
CHARACTER SET utf8 
COLLATE utf8_unicode_ci
;   



/*Create a link table that links shipping addresses to a user account.*/
CREATE
table gshipping_address (
    id INT( 11 ) AUTO_INCREMENT,
    addressID INT( 11 ), 
    accountID INT( 11 ), 
    PRIMARY KEY(id), 
    FOREIGN KEY(addressID) REFERENCES gaddress(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(accountID) REFERENCES gaccount(id) ON UPDATE CASCADE ON DELETE CASCADE
)
CHARACTER SET utf8 
COLLATE utf8_unicode_ci
;  



/*Create the creator -> account  link table*/
CREATE
table gcreator_account (
    id INT( 11 ) NOT NULL AUTO_INCREMENT, 
    accountID INT( 11 ) NULL, 
    creatorID INT( 11 ) NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(accountID) REFERENCES gaccount(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(creatorID) REFERENCES gcreator(id) ON UPDATE CASCADE ON DELETE CASCADE
); 



/*Create the comment -> post  link table*/
CREATE
table gcomment ( 
    id INT( 11 ) NOT NULL AUTO_INCREMENT,
    comment TEXT NOT NULL, 
    postID INT( 11 ) NOT NULL,
    creatorID INT ( 11 ) NOT NULL,
    authoredAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP(), 
    PRIMARY KEY(id),
    FOREIGN KEY(postID) REFERENCES gpost(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(creatorID) REFERENCES gcreator(id) ON UPDATE CASCADE ON DELETE CASCADE
);



/* interaction table (vote up, report etc etc) */
CREATE
table ginteraction_type (
    iType varchar( 60 ) NOT NULL, 
    PRIMARY KEY(iType)
)
CHARACTER SET utf8 
COLLATE utf8_unicode_ci
; 



/*Create the comment -> post  link table*/
CREATE
table ginteraction ( 
    id INT( 11 ) NOT NULL AUTO_INCREMENT,
    interactionType varchar( 60 ) NOT NULL,
    creatorID INT ( 11 ) NOT NULL,  
    postID INT( 11 ) NOT NULL,
    occuredAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP(), 
    PRIMARY KEY(id),
    FOREIGN KEY(interactionType) REFERENCES ginteraction_type(iType)  ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY(postID) REFERENCES gpost(id)  ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(creatorID) REFERENCES gcreator(id)  ON UPDATE CASCADE ON DELETE CASCADE
); 


/*Create the header image -> creator link table*/
CREATE
table gcreatorHeaderImg ( 
    creatorID INT ( 11 ) NOT NULL, 
    contentID INT ( 11 ) NOT NULL, 
    PRIMARY KEY(creatorID),
    FOREIGN KEY(creatorID) REFERENCES gcreator(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(contentID) REFERENCES gcontent(id) ON UPDATE CASCADE ON DELETE CASCADE  
);



/*Create the header image -> creator link table*/
CREATE
table gcreatorThumbImg ( 
    creatorID INT ( 11 ) NOT NULL, 
    contentID INT ( 11 ) NOT NULL, 
    PRIMARY KEY(creatorID),
    FOREIGN KEY(creatorID) REFERENCES gcreator(id)  ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(contentID) REFERENCES gcontent(id)  ON UPDATE CASCADE ON DELETE CASCADE    
);


USE gngr;

/*Create the header image -> creator link table*/
CREATE
table gcollaboration ( 
    id INT ( 11 ) NOT NULL, 
    groupID INT ( 11 ) NOT NULL, 
    memberID INT ( 11 ) NOT NULL, 
    PRIMARY KEY(id),
    FOREIGN KEY(groupID) REFERENCES gcreator(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(memberID) REFERENCES gcreator(id)  ON UPDATE CASCADE ON DELETE CASCADE   
);

/*Create a link table that links shipping addresses to a user account.*/
CREATE
table gtoken (
    id INT( 11 ) NOT NULL AUTO_INCREMENT,
    ticket varchar( 64 )NOT NULL, 
    token varchar( 64 ) NOT NULL, 
    born INT UNSIGNED NOT NULL,
    ttl INT UNSIGNED,
    PRIMARY KEY(id)
)
CHARACTER SET utf8 
COLLATE utf8_unicode_ci
; 

CREATE
table gtoken_account (
    tokenID INT( 11 ) NOT NULL,
    account INT( 11 ) NULL, 
    PRIMARY KEY(tokenID),
    FOREIGN KEY(tokenID) REFERENCES gtoken(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(account) REFERENCES gaccount(id)  ON UPDATE CASCADE ON DELETE CASCADE  
)
; 

CREATE
table ginvite (
    tokenID INT( 11 ) NOT NULL,
    recipient VARCHAR( 64 ) NOT NULL, 
    email VARCHAR( 255 ) NOT NULL, 
    PRIMARY KEY(tokenID),
    FOREIGN KEY(tokenID) REFERENCES gtoken(id) ON UPDATE CASCADE ON DELETE CASCADE 
)
CHARACTER SET utf8 
COLLATE utf8_unicode_ci
; 


CREATE
table gsecure_session (
    id char( 128 ) NOT NULL,
    set_time CHAR(10) NOT NULL,
    data text NOT NULL,
    session_key CHAR(128)NOT NULL,
    PRIMARY KEY(id)
)
ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE
table gaccess_attempt (
    id INT(11) AUTO_INCREMENT NOT NULL,
    userID  INT(11) NOT NULL,
    time INT(30) NOT NULL,
    PRIMARY KEY(id)
);