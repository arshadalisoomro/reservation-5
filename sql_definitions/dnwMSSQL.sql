#dnw MSSQL tables by Jonathan Flessner 15-Mar-2015

#phone is stored as 10 digit numeric string
#boolean values will be stored as a bit with 0 as false, 1 as true
CREATE TABLE reservationsDNW (
id int IDENTITY PRIMARY KEY,
confirmationCode varchar(6) NOT NULL UNIQUE,
homeownerName varchar(255) NOT NULL,
guestName varchar(255),
isGuest bit DEFAULT 0 NOT NULL, 
email varchar(255) NOT NULL,
phone varchar(10) NOT NULL, 
numAdultHomeowners tinyint NOT NULL DEFAULT 0,
numChildHomeowners tinyint NOT NULL DEFAULT 0,
numAdultGuests tinyint NOT NULL DEFAULT 0,
numChildGuests tinyint NOT NULL DEFAULT 0,
dateToDecatur date,
dateToAnacortes date,
timeToDecatur time,
timeToAnacortes time,
comments text,
paypal varchar(1) NOT NULL DEFAULT 'N',
cost smallmoney NOT NULL DEFAULT 0,
timestamp smalldatetime NOT NULL
);

CREATE TABLE boatsDNW (
id int IDENTITY PRIMARY KEY,
departDate date NOT NULL,
departAnacortes time,
fromAnacortesCount tinyint DEFAULT 0 NOT NULL,
departDecatur time,
fromDecaturCount tinyint DEFAULT 0 NOT NULL,
isCancelled bit DEFAULT 0 NOT NULL
);

#used for login into admin tools
#entries are manually added
#same INSERT INTO adminLogin (username, password) VALUES ('admin', 'pass');
CREATE TABLE adminLogin (
id int IDENTITY PRIMARY KEY,
username varchar(255) NOT NULL UNIQUE,
password varchar(255) NOT NULL
);