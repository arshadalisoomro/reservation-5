CREATE TABLE boatSchedule (
id int IDENTITY (1,1) PRIMARY KEY,
departDate date NOT NULL,
departAnacortes time,
departDecatur time,
passengerCount int DEFAULT 0
)