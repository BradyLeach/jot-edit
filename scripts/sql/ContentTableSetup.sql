USE gngr;

CREATE
table gcreator_type (
    cType varchar( 60 ) NOT NULL, 
    PRIMARY KEY(cType)
)
CHARACTER SET utf8 
COLLATE utf8_unicode_ci
; 

CREATE
table gcreator_status (
    status varchar( 60 ) NOT NULL, 
    PRIMARY KEY(status)
)
CHARACTER SET utf8 
COLLATE utf8_unicode_ci
; 



/*Create a link table that links billing addresses to a user account.*/
CREATE
table gcreator (
    id INT( 11 ) NOT NULL AUTO_INCREMENT,    
    name varchar( 60 ) NOT NULL, 
    ctype varchar( 60 ) NULL, 
    cStatus varchar( 60 ) NULL,
    url varchar( 128 ) NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(ctype) REFERENCES gcreator_type(cType) ON UPDATE CASCADE ON DELETE RESTRICT
    FOREIGN KEY(cStatus) REFERENCES gcreator_status(status) ON UPDATE CASCADE ON DELETE RESTRICT
)
CHARACTER SET utf8 
COLLATE utf8_unicode_ci
;  


/*Create a gallery table.*/
CREATE
table ggallery (
    id INT( 11 ) NOT NULL AUTO_INCREMENT,    
    name varchar( 60 ) NOT NULL,
    creatorID INT( 11 ),
    PRIMARY KEY(id),
    FOREIGN KEY(creatorID) REFERENCES gcreator(id)
)
CHARACTER SET utf8 
COLLATE utf8_unicode_ci
;



/*Create a format table.*/
CREATE
table gformat (   
    format varchar( 20 ) NOT NULL,
    PRIMARY KEY(format)
)
CHARACTER SET utf8 
COLLATE utf8_unicode_ci
; 



/*Create the content table*/
CREATE
table gcontent (
    id INT( 11 ) NOT NULL AUTO_INCREMENT,    
    name varchar( 60 ) NOT NULL,
    src varchar( 60 ) NOT NULL,
    contentFormat varchar( 20 ) NULL,
    galleryID INT( 11 ),
    PRIMARY KEY(id),
    FOREIGN KEY(galleryID) REFERENCES ggallery(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(contentFormat) REFERENCES gformat(format) ON UPDATE CASCADE ON DELETE RESTRICT
)
CHARACTER SET utf8 
COLLATE utf8_unicode_ci
; 



/*Create the content table*/
CREATE
table gpost (
    id INT( 11 ) NOT NULL AUTO_INCREMENT,     
    title varchar( 60 ) NOT NULL,
    postText TEXT NOT NULL,
    creatorID INT( 11 ),
    published TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY(id),
    FOREIGN KEY(creatorID) REFERENCES gcreator(id) ON UPDATE CASCADE ON DELETE CASCADE
)
CHARACTER SET utf8 
COLLATE utf8_unicode_ci
; 


/*Create the content -> post content link table*/
CREATE
table gpost_content (
    id INT( 11 ) NOT NULL AUTO_INCREMENT, 
    postID INT( 11 ) NOT NULL, 
    contentID INT( 11 ) NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(postID) REFERENCES gpost(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(contentID) REFERENCES gcontent(id) ON UPDATE CASCADE ON DELETE RESTRICT
); 



/*Create the album table*/
CREATE
table galbum (
    id INT( 11 ) NOT NULL AUTO_INCREMENT, 
    creatorID INT( 11 ) NOT NULL,
    title varchar( 60 ) NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(creatorID) REFERENCES gcreator(id) ON UPDATE CASCADE ON DELETE CASCADE
)
CHARACTER SET utf8 
COLLATE utf8_unicode_ci
; 




/*Create the track table*/
CREATE
table gtrack (
    id INT( 11 ) NOT NULL AUTO_INCREMENT, 
    title varchar( 60 ) NOT NULL,
    albumID INT( 11 ) NOT NULL,
    trackNO INT(4),
    duration varchar(7), 
    PRIMARY KEY(id),
    FOREIGN KEY(albumID) REFERENCES galbum(id) ON UPDATE CASCADE ON DELETE CASCADE
)
CHARACTER SET utf8 
COLLATE utf8_unicode_ci
;




/*Create the contribution table*/
CREATE
table gcontribution (
    contribution  varchar( 60 ) NOT NULL,
    PRIMARY KEY(contribution)
)
CHARACTER SET utf8 
COLLATE utf8_unicode_ci
;




/*Create the contributor table*/
CREATE
table gcontributor (
    id INT( 11 ) NOT NULL AUTO_INCREMENT, 
    creatorID INT( 11 ) NOT NULL,
    contribution varchar( 60 ) NULL,
    trackID INT( 11 ) NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(creatorID) REFERENCES gcreator(id),
    FOREIGN KEY(contribution) REFERENCES gcontribution(contribution) ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY(trackID) REFERENCES gtrack(id) ON UPDATE CASCADE ON DELETE CASCADE
)
CHARACTER SET utf8 
COLLATE utf8_unicode_ci
;