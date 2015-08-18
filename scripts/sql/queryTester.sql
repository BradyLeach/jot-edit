USE gngr;

SELECT creator.name, content.src
FROM gcreator creator
JOIN gcreatorheaderimg cheader
ON creator.id = cheader.creatorID
JOIN gcontent content
ON cheader.contentID = content.id
WHERE creator.name =:name;


/*clean test database*/
DELETE FROM gcontact_email;
DELETE FROM glogin;
DELETE FROM gaccount_status;
DELETE FROM gpending_account;
DELETE FROM `gcreator_account`;
DELETE FROM `gcreator`;
DELETE FROM `gaccount`;


INSERT INTO gstreet_suffix(suffix, abbrv) VALUES('access','Accs');
INSERT INTO gstreet_suffix(suffix, abbrv) VALUES('alley','Ally');
INSERT INTO gstreet_suffix(suffix, abbrv) VALUES('alleyway','Alwy');
INSERT INTO gstreet_suffix(suffix, abbrv) VALUES('amble','Ambl');
INSERT INTO gstreet_suffix(suffix, abbrv) VALUES('anchorage','Ancg');
INSERT INTO gstreet_suffix(suffix, abbrv) VALUES('approach','App');
INSERT INTO gstreet_suffix(suffix, abbrv) VALUES('arcade','Arc');
INSERT INTO gstreet_suffix(suffix, abbrv) VALUES('artery','Art');
INSERT INTO gstreet_suffix(suffix, abbrv) VALUES('avenue','Ave');




SELECT  id  FROM gaddress
WHERE line1= '2 new street' AND
line2 = '' AND
suburb = 'town' AND
postCode= 4144 AND
stateID =  2 AND
accountID = 131;