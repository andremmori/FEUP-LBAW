-----------------------------------------
-- Drop
-----------------------------------------

DROP TABLE IF EXISTS "user" CASCADE;
DROP TABLE IF EXISTS promotion CASCADE;
DROP TABLE IF EXISTS category CASCADE;
DROP TABLE IF EXISTS country CASCADE;
DROP TABLE IF EXISTS city CASCADE;
DROP TABLE IF EXISTS "event" CASCADE;
DROP TABLE IF EXISTS eventhost CASCADE;
DROP TABLE IF EXISTS invited CASCADE;
DROP TABLE IF EXISTS photo CASCADE;
DROP TABLE IF EXISTS ticket CASCADE;
DROP TABLE IF EXISTS comment CASCADE;
DROP TABLE IF EXISTS review CASCADE;
DROP TABLE IF EXISTS report CASCADE;

DROP TYPE IF EXISTS Genders;

DROP FUNCTION IF EXISTS ticket_creation() CASCADE;
DROP FUNCTION IF EXISTS ticket_deletion() CASCADE;
DROP FUNCTION IF EXISTS updateRating() CASCADE;
DROP FUNCTION IF EXISTS messages_trigger() CASCADE;

DROP TRIGGER IF EXISTS ticket_creation ON ticket;
DROP TRIGGER IF EXISTS ticket_deletion ON ticket;
DROP TRIGGER IF EXISTS updateRating ON review;
DROP TRIGGER IF EXISTS tsvectorupdate ON event;

-----------------------------------------
-- Types
-----------------------------------------

CREATE TYPE Genders AS ENUM ('M', 'F', 'O');

-----------------------------------------
-- Tables
-----------------------------------------

CREATE TABLE "user"(
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    gender Genders NOT NULL,
    birthdate DATE NOT NULL,
    profilePicture TEXT DEFAULT 'default.png',
    admin BOOLEAN DEFAULT FALSE,
    banned BOOLEAN DEFAULT FALSE
);

CREATE TABLE promotion(
    id SERIAL PRIMARY KEY,
    "date" DATE DEFAULT CURRENT_DATE,
    promoted BOOLEAN NOT NULL,
    idCreator INTEGER REFERENCES "user" (id) ON UPDATE CASCADE ON DELETE CASCADE,
    idReceiver INTEGER REFERENCES "user" (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE category(
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL
);

CREATE TABLE country (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL
);

CREATE TABLE city (
    id SERIAL PRIMARY KEY,
    "name" TEXT NOT NULL,
    idCountry INTEGER REFERENCES "country" (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "event"(
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    price FLOAT CHECK (price >= 0),
    "date" TIMESTAMP CHECK (date > CURRENT_TIMESTAMP),
    numberSpots INTEGER CHECK (numberSpots > 0),
    description TEXT NOT NULL,
    address TEXT NOT NULL,
    creationDate DATE DEFAULT CURRENT_DATE,
    rating INTEGER DEFAULT 0,
    idLocation INTEGER REFERENCES "city" (id) ON UPDATE CASCADE,
    idCategory INTEGER REFERENCES "category" (id) ON UPDATE CASCADE
);

CREATE TABLE eventhost(
    idUser INTEGER REFERENCES "user" (id) ON UPDATE CASCADE ON DELETE CASCADE,
    idEvent INTEGER REFERENCES "event" (id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (idUser, idEvent)
);

CREATE TABLE invited(
    id SERIAL PRIMARY KEY,
    status BOOLEAN DEFAULT FALSE,
    idUser INTEGER REFERENCES "user" (id) ON UPDATE CASCADE ON DELETE CASCADE,
    idEvent INTEGER REFERENCES "event" (id) ON UPDATE CASCADE ON DELETE CASCADE,
    email TEXT NOT NULL
);

CREATE TABLE photo(
    id SERIAL PRIMARY KEY,
    "name" TEXT NOT NULL,
    idEvent INTEGER REFERENCES "event" (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE ticket(
    qrcode BYTEA,
    entranceTime TIMESTAMP DEFAULT NULL,
    idUser INTEGER REFERENCES "user" (id) ON UPDATE CASCADE ON DELETE CASCADE,
    idEvent INTEGER REFERENCES "event" (id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (idUser, idEvent)
);

CREATE TABLE comment(
    id SERIAL PRIMARY KEY,
    "text" TEXT NOT NULL,
    "date" TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    idUser INTEGER REFERENCES "user" (id) ON UPDATE CASCADE ON DELETE CASCADE,
    idEvent INTEGER REFERENCES "event" (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE review(
    rating INTEGER CHECK (rating >= 0 AND rating <= 5),
    idUser INTEGER NOT NULL REFERENCES "user" (id) ON UPDATE CASCADE ON DELETE CASCADE,
    idEvent INTEGER NOT NULL REFERENCES "event" (id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (idUser, idEvent)
);

CREATE TABLE report(
    id SERIAL PRIMARY KEY,
    description TEXT NOT NULL,
    "date" TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    idUser INTEGER NOT NULL REFERENCES "user" (id) ON UPDATE CASCADE ON DELETE CASCADE,
    idEvent INTEGER REFERENCES "event" (id) ON UPDATE CASCADE ON DELETE CASCADE,
    idComment INTEGER REFERENCES comment (id) ON UPDATE CASCADE ON DELETE CASCADE,
    CHECK ((idEvent IS NOT NULL AND idComment IS NULL) OR (idEvent IS NULL AND idComment IS NOT NULL))
);

-----------------------------------------
-- INDEXES
-----------------------------------------

CREATE INDEX user_email ON "user" USING HASH(email);
CREATE INDEX event_date ON "event" USING btree(date);
CREATE INDEX search_idx ON "event" USING GIST (to_tsvector('english', name || ' ' || description));
CREATE INDEX city_search_idx ON city USING GIST (to_tsvector('english', name));

-----------------------------------------
-- TRIGGERS and UDFs
-----------------------------------------

CREATE FUNCTION ticket_creation() RETURNS TRIGGER AS
$BODY$
BEGIN
    UPDATE "event"
    SET numberSpots = numberSpots - 1
    WHERE "event".id = NEW.idEvent;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER ticket_creation
    AFTER INSERT ON ticket
    FOR EACH ROW
    EXECUTE PROCEDURE ticket_creation(); 

CREATE FUNCTION ticket_deletion() RETURNS TRIGGER AS
$BODY$
BEGIN
    UPDATE "event"
    SET numberSpots = numberSpots + 1
    WHERE "event".id = OLD.idEvent;
    RETURN OLD;
END
$BODY$
LANGUAGE plpgsql;
 
CREATE TRIGGER ticket_deletion
    AFTER DELETE ON ticket
    FOR EACH ROW
    EXECUTE PROCEDURE ticket_deletion(); 

CREATE FUNCTION updateRating() RETURNS TRIGGER AS
$BODY$
BEGIN
    UPDATE "event"
    SET rating = (SELECT SUM(rating)/COUNT(rating) FROM review WHERE review.idEvent = "event".id);
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER updateRating 
    AFTER INSERT ON review
    FOR EACH ROW
    EXECUTE PROCEDURE updateRating();

ALTER TABLE event ADD COLUMN tsv tsvector;
CREATE FUNCTION messages_trigger() RETURNS trigger AS $$
begin
  new.tsv :=
     setweight(to_tsvector('english', coalesce(new.name,'')), 'A') ||
     setweight(to_tsvector('english', coalesce(new.description,'')), 'B');
  return new;
end
$$ LANGUAGE plpgsql;

CREATE TRIGGER tsvectorupdate BEFORE INSERT OR UPDATE
    ON event FOR EACH ROW EXECUTE PROCEDURE messages_trigger();

insert into country (name) values ('China');
insert into country (name) values ('Australia');
insert into country (name) values ('Russia');
insert into country (name) values ('Canada');
insert into country (name) values ('Ukraine');
insert into country (name) values ('Greece');
insert into country (name) values ('Italy');
insert into country (name) values ('France');
insert into country (name) values ('Portugal');
insert into country (name) values ('United States');

insert into city (name, idCountry) values ('Guoduwan', 1);
insert into city (name, idCountry) values ('Shangjiangxu', 1);
insert into city (name, idCountry) values ('Laoxingchang', 1);
insert into city (name, idCountry) values ('Zhangfeng', 1);
insert into city (name, idCountry) values ('Nanmu', 1);
insert into city (name, idCountry) values ('Brisbane', 2);
insert into city (name, idCountry) values ('Strawberry Hills', 2);
insert into city (name, idCountry) values ('Eastern Suburbs Mc', 2);
insert into city (name, idCountry) values ('Perth', 2);
insert into city (name, idCountry) values ('Sydney', 2);
insert into city (name, idCountry) values ('Promyshlennaya', 3);
insert into city (name, idCountry) values ('Taldom', 3);
insert into city (name, idCountry) values ('Tsimlyansk', 3);
insert into city (name, idCountry) values ('Ust’-Dzheguta', 3);
insert into city (name, idCountry) values ('Novopodrezkovo', 3);
insert into city (name, idCountry) values ('Warwick', 4);
insert into city (name, idCountry) values ('Pilot Butte', 4);
insert into city (name, idCountry) values ('North Cowichan', 4);
insert into city (name, idCountry) values ('Alma', 4);
insert into city (name, idCountry) values ('Normandin', 4);
insert into city (name, idCountry) values ('Andrushivka', 5);
insert into city (name, idCountry) values ('Donetsk', 5);
insert into city (name, idCountry) values ('Veselynove', 5);
insert into city (name, idCountry) values ('Pavlivka', 5);
insert into city (name, idCountry) values ('Manyava', 5);
insert into city (name, idCountry) values ('Néa Palátia', 6);
insert into city (name, idCountry) values ('Kolindrós', 6);
insert into city (name, idCountry) values ('Káto Miliá', 6);
insert into city (name, idCountry) values ('Koilás', 6);
insert into city (name, idCountry) values ('Melíssia', 6);
insert into city (name, idCountry) values ('Messina',7);
insert into city (name, idCountry) values ('Napoli',7);
insert into city (name, idCountry) values ('Bologna',7);
insert into city (name, idCountry) values ('Milano',7);
insert into city (name, idCountry) values ('Roma',7);
insert into city (name, idCountry) values ('Quetigny', 8);
insert into city (name, idCountry) values ('Lucé', 8);
insert into city (name, idCountry) values ('Dijon', 8);
insert into city (name, idCountry) values ('Nanterre', 8);
insert into city (name, idCountry) values ('Vienne', 8);
insert into city (name, idCountry) values ('Charneca', 9);
insert into city (name, idCountry) values ('Faro', 9);
insert into city (name, idCountry) values ('Vermoim', 9);
insert into city (name, idCountry) values ('Lisboa', 9);
insert into city (name, idCountry) values ('Porto', 9);
insert into city (name, idCountry) values ('Mesa', 10);
insert into city (name, idCountry) values ('Birmingham', 10);
insert into city (name, idCountry) values ('Miami', 10);
insert into city (name, idCountry) values ('Minneapolis', 10);
insert into city (name, idCountry) values ('Washington', 10);

insert into "user" (name, password, email, gender, birthdate, profilePicture, admin) values ('Preston', 'y^68wbQFPD', 'risus@ipsumprimis.com', 'M', '1997-02-22', '1.png', FALSE);
insert into "user" (name, password, email, gender, birthdate, profilePicture, admin) values ('Magee', 'Ls69Ukgxun', 'diam.luctus.lobortis@musProin.net', 'F', '1995-05-10', '2.png', FALSE);
insert into "user" (name, password, email, gender, birthdate, profilePicture, admin) values ('Castor', 'HvHacDp2kV', 'luctus.lobortis@lobortis.co.uk', 'M', '1978-07-15', '3.png', FALSE);
insert into "user" (name, password, email, gender, birthdate, profilePicture, admin) values ('Jayme', 'Z2vzLBKtQx', 'enim.commodo.hendrerit@vel.edu', 'M', '1988-01-18', '4.png', FALSE);
insert into "user" (name, password, email, gender, birthdate, profilePicture, admin) values ('Wesley', 'w2kG3A33Rr', 'massa.lobortis@libero.edu', 'M', '1999-02-03', '5.png', FALSE);
insert into "user" (name, password, email, gender, birthdate, profilePicture, admin) values ('Alyssa', '6677zCsZtR', 'Praesent@dapibusquam.org', 'F', '1991-12-27', '6.png', TRUE);
insert into "user" (name, password, email, gender, birthdate, profilePicture, admin) values ('Gemma', '9KF3U9ZsNE', 'rhoncus@consectetueradipiscing.edu', 'F', '1978-12-06', '7.png', FALSE);
insert into "user" (name, password, email, gender, birthdate, profilePicture, admin) values ('Kitra', 'gpj67Rtqku', 'turpis@elita.edu', 'F', '1994-02-18', '8.png', FALSE);
insert into "user" (name, password, email, gender, birthdate, profilePicture, admin) values ('Ali', 'sG7DeNtAf8', 'vitae.aliquam.eros@nullaeuneque.co.uk', 'M', '1995-01-17', '9.png', FALSE);
insert into "user" (name, password, email, gender, birthdate, profilePicture, admin) values ('Amity', 'BQbn9929CZ', 'non.luctus@anteiaculis.edu', 'F', '1992-03-20', '10.png', FALSE);
insert into "user" (name, password, email, gender, birthdate, profilePicture, admin) values ('Jaime', 'A5qcY7zK2Z', 'dapibus.ligula.Aliquam@Aeneansedpede.org', 'M', '1982-05-25', '11.png', FALSE);
insert into "user" (name, password, email, gender, birthdate, profilePicture, admin) values ('Lamar', 'u9vuweHURE', 'consectetuer.cursus@nec.net', 'M', '1987-07-09', '12.png', FALSE);
insert into "user" (name, password, email, gender, birthdate, profilePicture, admin) values ('Shay', 'VDDwV3LPqc', 'id@Donec.com', 'F', '1996-08-29', '13.png', FALSE);
insert into "user" (name, password, email, gender, birthdate, profilePicture, admin) values ('Hamilton', 'wbe26P8dMP', 'ipsum.Suspendisse.non@maurisaliquam.co.uk', 'M', '1979-08-23', '14.png', FALSE);
insert into "user" (name, password, email, gender, birthdate, profilePicture, admin) values ('Hannah', 'Pr5a5weppp', 'egestas.Sed.pharetra@lorem.edu', 'O', '1997-09-14', '15.png', FALSE);
insert into "user" (name, password, email, gender, birthdate, profilePicture, admin) values ('Brenda', 'vP62DH2hzK', 'nonummy@aptenttacitisociosqu.ca', 'F', '1998-08-06', '16.png', FALSE);
insert into "user" (name, password, email, gender, birthdate, profilePicture, admin) values ('Maya', 'Cm9TgkdLEB', 'sed.orci@Naminterdumenim.edu', 'F', '1993-02-16', '17.png', FALSE);
insert into "user" (name, password, email, gender, birthdate, profilePicture, admin) values ('Victoria', 'Hc7unrNxCu', 'rutrum.non@risus.edu', 'F', '1977-06-12', '18.png', FALSE);
insert into "user" (name, password, email, gender, birthdate, profilePicture, admin) values ('Rahim', 'WgsegL3dvJ', 'dignissim.Maecenas@nuncQuisqueornare.co.uk', 'M', '1969-01-08', '19.png', FALSE);
insert into "user" (name, password, email, gender, birthdate, profilePicture, admin) values ('Madonna', '9aN4CYCu2L', 'lacus@ac.net', 'F', '1990-09-05', '20.png', TRUE);
insert into "user" (name, password, email, gender, birthdate, profilePicture, admin) values ('Admin', '$2y$10$ojUxY2vGPonIKAO9FXBP1.DZrNf2GGEfJ.ohAyOztjaMKBXd4iWMe', 'admin@meethology.com', 'O', '1990-09-05', '21.png', TRUE);
insert into "user" (name, password, email, gender, birthdate, profilePicture, admin) values ('Test', '$2y$10$UuJARQOKSKJqyDk0449xs.WU0mw65doVr1Y9gaklMMTHQ6a5j3Zre', 'test@meethology.com', 'M', '1990-09-05', '22.png', FALSE);

insert into promotion (date, promoted, idCreator, idReceiver) values ('2020-02-10', TRUE, 20, 19);

insert into category (name) values ('Sports');
insert into category (name) values ('Music');
insert into category (name) values ('Tech');
insert into category (name) values ('Health');
insert into category (name) values ('Food');
insert into category (name) values ('Film');
insert into category (name) values ('Dance');
insert into category (name) values ('Games');
insert into category (name) values ('Social');
insert into category (name) values ('Learning');

insert into "event" (name, price, date, numberSpots, description, address, creationDate, idLocation, idCategory) values ('Football match', NULL, '2020-08-20 15:12:01', 22, 'Friendly football match', 'Pavilhão X', '2020-01-05 15:12:01', 42, 1);
insert into "event" (name, price, date, numberSpots, description, address, creationDate, idLocation, idCategory) values ('Yoga for beginners', 5, '2020-08-22 10:00:00', 30, 'Come learn yoga', '2390  Driftwood Road', '2020-03-05 20:12:01', 3, 4);
insert into "event" (name, price, date, numberSpots, description, address, creationDate, idLocation, idCategory) values ('International Film Festival', 10, '2020-08-15 20:00:00', 150, 'Festival that celebrates cinema', '2771  Clair Street', '2020-08-04 20:12:01', 33, 6);
insert into "event" (name, price, date, numberSpots, description, address, creationDate, idLocation, idCategory) values ('Meetup to watch Oscar winning movies', NULL, '2020-10-06 20:00:00', 30, 'Come watch amazing movies with us!', '2705  Park Avenue', '2020-08-04 20:12:01', 40, 6);
insert into "event" (name, price, date, numberSpots, description, address, creationDate, idLocation, idCategory) values ('Friendly basketball match', NULL, '2020-08-06 20:00:00', 15, 'Basketball match for all those that love the sport', '2246  Private Lane', '2020-08-14 20:12:01', 18, 1);
insert into "event" (name, price, date, numberSpots, description, address, creationDate, idLocation, idCategory) values ('Cuban Salsa classes', 10, '2020-08-06 20:00:00', 25, 'Learn how to dance the cuban salsa', '921  Lincoln Street', '2020-08-14 20:12:01', 11, 7);
insert into "event" (name, price, date, numberSpots, description, address, creationDate, idLocation, idCategory) values ('Dancing workout', 10, '2020-10-22 20:00:00', 25, 'Come dance with us', '1088  Arthur Avenue', '2020-07-15 20:12:01', 6, 7);
insert into "event" (name, price, date, numberSpots, description, address, creationDate, idLocation, idCategory) values ('Rainbow Six Siege Tournament', NULL, '2020-12-10 20:00:00', 50, 'Do you think you have what it takes to win? Come participate in this tournament', '2660  Winifred Way', '2020-09-21 20:12:01', 33, 8);
insert into "event" (name, price, date, numberSpots, description, address, creationDate, idLocation, idCategory) values ('Dinner at Paradise Vegetarian Noodle House', 15, '2020-12-20 20:00:00', 30, 'By popular demand, we are returning to Paradise Vegetarian Noodle House.', '3791  Grim Avenue', '2020-11-21 20:12:01', 37, 5);
insert into "event" (name, price, date, numberSpots, description, address, creationDate, idLocation, idCategory) values ('Modern Java Clients with JavaFX', 5, '2020-08-20 20:00:00', 60, 'This session is for professionals building Java applications for desktop, mobile, and embedded devices in the Cloud age.', '3306  Everette Alley', '2020-08-17 20:12:01', 45, 3);
insert into "event" (name, price, date, numberSpots, description, address, creationDate, idLocation, idCategory) values ('Build your first website', 3, '2020-08-20 14:00:00', 100, 'In this workshop, you will discover the basics of programming with Python and will be quickly immersed in the daily life of a Data Analyst through concrete business cases using datasets from the real world.', '3968  Lunetta Street', '2020-03-07 20:12:01', 16, 3);
insert into "event" (name, price, date, numberSpots, description, address, creationDate, idLocation, idCategory) values ('Remote Pair Programming Session', NULL, '2020-07-13 20:00:00', 30, 'Pair programming is central to Codesmiths learning style. If you are looking to prepare for our programs, this session is a great chance to focus on the concepts we test for.', '3398  Ritter Street', '2020-08-21 20:12:01', 34, 3);
insert into "event" (name, price, date, numberSpots, description, address, creationDate, idLocation, idCategory) values ('Disney Movies for kids', NULL, '2020-10-06 21:00:00', 60, 'Lets all simultaneously watch this absolutely fabulous animated film', '3192  Fancher Drive', '2020-09-23 20:12:01', 45, 6);
insert into "event" (name, price, date, numberSpots, description, address, creationDate, idLocation, idCategory) values ('Movie Trivia Night', NULL, '2020-08-15 20:00:00', 20, 'Hey fellow trivia lovers! This time the theme is movies!', '3419  Broadway Avenue', '2020-07-14 20:12:01', 24, 6);
insert into "event" (name, price, date, numberSpots, description, address, creationDate, idLocation, idCategory) values ('Netflix movie: Nothing To Hide', NULL, '2020-07-06 16:00:00', 50, 'Several couples play a game over dinner, everyone has to share every text, phone call, and email with the others. The game soon becomes a nightmare.', '4349  Neville Street', '2020-08-01 20:12:01', 42, 6);
insert into "event" (name, price, date, numberSpots, description, address, creationDate, idLocation, idCategory) values ('Volleyball for All Levels', NULL, '2020-07-14 13:00:00', 50, 'This volleyball meetup is for players of all skill levels', '217  Pearlman Avenue', '2020-08-21 20:12:01', 15, 1);
insert into "event" (name, price, date, numberSpots, description, address, creationDate, idLocation, idCategory) values ('Yoga in the Park', NULL, '2020-08-21 17:00:00', 200, 'Join everyone at sunset for Yoga in the Park.', '4467  Platinum Drive', '2020-02-17 20:12:01', 39, 1);
insert into "event" (name, price, date, numberSpots, description, address, creationDate, idLocation, idCategory) values ('Tennis for Junior/Advanced Levels', NULL, '2020-10-02 14:00:00', 60, 'Come out for a fun night of recreational and competitive tennis. Many games are played at the friendly social level, while higher-level players may choose to split up and play a competitive match on a separate court.', '2742  Alfred Drive', '2020-01-20 20:12:01', 19, 1);
insert into "event" (name, price, date, numberSpots, description, address, creationDate, idLocation, idCategory) values ('Basketball for Intermediate Levels', NULL, '2021-01-10 21:00:00', 40, 'This event is for players at the intermediate level', '1525  Rubaiyat Road', '2020-11-17 20:12:01', 5, 1);
insert into "event" (name, price, date, numberSpots, description, address, creationDate, idLocation, idCategory) values ('Washington, DC History & Culture Guided Walking Tours', 15, '2021-02-07 17:00:00', 40, 'We look forward to seeing you - thanks!', '3153  Smithfield Avenue', '2021-01-21 20:12:01', 50, 10);
insert into "event" (name, price, date, numberSpots, description, address, creationDate, idLocation, idCategory) values ('European Union Embassy Open House', NULL, '2020-09-14 19:00:00', 60, 'European Union Embassy Open House - Visit Embassies for FREE!', '2280  Cambridge Court', '2020-08-14 20:12:01', 26, 10);
insert into "event" (name, price, date, numberSpots, description, address, creationDate, idLocation, idCategory) values ('Yosemite over Memorial Day. Gotta Go CAMPING!!', NULL, '2020-07-23 15:00:00', 30, 'That amazing place you want to go but have not yet. You have heard it is amazing but you did not want to go on your own and none of your friends were willing to join you or be adventurous.', '790  Rocky Road', '2020-08-16 20:12:01', 46, 9);
insert into "event" (name, price, date, numberSpots, description, address, creationDate, idLocation, idCategory) values ('Kenny Ceteras Chicago Experience', 10, '2020-07-22 10:00:00', 100, 'The Kenny Ceteras Chicago experience will take you back to a time that made you look forward to living your life to the fullest with inspirational songs that will make smile and feel stronger every day.', '796  Rocky Road', '2020-07-15 20:12:01', 25, 2);
insert into "event" (name, price, date, numberSpots, description, address, creationDate, idLocation, idCategory) values ('The Neil Diamond Experience', 15, '2020-10-04 12:00:00', 100, 'International tribute and recording artist David Sherry makes you "feel the Neil" in a live performance of Neil Diamonds Greatest hits.', '573  Millbrook Road', '2020-08-25 20:12:01', 37, 2);
insert into "event" (name, price, date, numberSpots, description, address, creationDate, idLocation, idCategory) values ('Free Tai Chi', NULL, '2020-11-26 16:00:00', 60, 'Yellow Dragon Tai Chi invites you to join our class to help stay physically and mentally healthy.', '2094  Fairway Drive', '2020-09-16 20:12:01', 48, 4);
insert into "event" (name, price, date, numberSpots, description, address, creationDate, idLocation, idCategory) values ('Dungeons & Dragons Campaign Session', NULL, '2020-10-19 20:00:00', 60, 'From first timers to veterans, drop in to any sessions you want. We will sort you into groups in the text below approx 24 hours before the session starts, and get you started on a one-shot adventure that fits into our campaign setting.', '634  Hickory Lane', '2020-10-13 20:12:01', 21, 8);
insert into "event" (name, price, date, numberSpots, description, address, creationDate, idLocation, idCategory) values ('Board Game Party', 2, '2020-07-20 21:00:00', 60, 'Come and play board games with us!', '4798  Benedum Drive', '2020-08-26 20:12:01', 14, 8);
insert into "event" (name, price, date, numberSpots, description, address, creationDate, idLocation, idCategory) values ('Pokemon Online Battle Day!', NULL, '2020-09-27 22:00:00', 60, 'For anyone looking to enjoy Pokemon Go with fellow Pokemon Go Trainers.', '1069  Wilmar Farm Road', '2020-07-28 20:12:01', 23, 8);
insert into "event" (name, price, date, numberSpots, description, address, creationDate, idLocation, idCategory) values ('Board Game Night', NULL, '2020-08-15 19:00:00', 60, 'Meeting of board game lovers.', '451  Wood Street', '2020-03-06 20:12:01', 6, 8);
insert into "event" (name, price, date, numberSpots, description, address, creationDate, idLocation, idCategory) values ('Open RPG / D&D Night', NULL, '2020-11-14 14:00:00', 60, 'Looking for a group to play with? Just come in and grab a table!', '4145  Vesta Drive', '2020-08-18 20:12:01', 4, 8);
insert into "event" (name, price, date, numberSpots, description, address, creationDate, idLocation, idCategory) values ('Managing Microsoft Teams', 5, '2020-08-16 17:00:00', 40, 'In this session you will learn how to manage Microsoft Teams in an Enterprise Environment, leveraging Microsoft Multi-Factor Authentication. You will also learn how to setup Teams and Channels.', '3285  Browning Lane', '2020-03-20 16:13:22', 36, 3);

insert into photo (name, idEvent) values ('1_0.jpg', 1);
insert into photo (name, idEvent) values ('1_1.jpg', 1);
insert into photo (name, idEvent) values ('2_0.jpg', 2);
insert into photo (name, idEvent) values ('2_1.jpeg', 2);
insert into photo (name, idEvent) values ('2_2.jpg', 2);
insert into photo (name, idEvent) values ('3_0.jpg', 3);
insert into photo (name, idEvent) values ('3_1.jpg', 3);
insert into photo (name, idEvent) values ('4_0.jpg', 4);
insert into photo (name, idEvent) values ('5_0.jpg', 5);
insert into photo (name, idEvent) values ('5_1.jpg', 5);
insert into photo (name, idEvent) values ('6_0.jpg', 6);
insert into photo (name, idEvent) values ('7_0.jpg', 7);
insert into photo (name, idEvent) values ('7_1.jpg', 7);
insert into photo (name, idEvent) values ('8_0.jpeg', 8);
insert into photo (name, idEvent) values ('8_1.jpeg', 8);
insert into photo (name, idEvent) values ('8_2.jpg', 8);
insert into photo (name, idEvent) values ('9_0.jpg', 9);
insert into photo (name, idEvent) values ('10_0.jpg', 10);
insert into photo (name, idEvent) values ('10_1.jpg', 10);
insert into photo (name, idEvent) values ('11_0.jpg', 11);
insert into photo (name, idEvent) values ('11_1.jpg', 11);
insert into photo (name, idEvent) values ('12_0.jpg', 12);
insert into photo (name, idEvent) values ('12_1.jpg', 12);
insert into photo (name, idEvent) values ('13_0.jpg', 13);
insert into photo (name, idEvent) values ('13_1.jpg', 13);
insert into photo (name, idEvent) values ('14_0.jpg', 14);
insert into photo (name, idEvent) values ('15_0.jpg', 15);
insert into photo (name, idEvent) values ('15_1.jpg', 15);
insert into photo (name, idEvent) values ('16_0.jpg', 16);
insert into photo (name, idEvent) values ('16_1.jpg', 16);
insert into photo (name, idEvent) values ('17_0.jpg', 17);
insert into photo (name, idEvent) values ('18_0.jpg', 18);
insert into photo (name, idEvent) values ('18_1.jpg', 18);
insert into photo (name, idEvent) values ('19_0.jpg', 19);
insert into photo (name, idEvent) values ('20_0.jpg', 20);
insert into photo (name, idEvent) values ('20_1.jpg', 20);
insert into photo (name, idEvent) values ('21_0.jpg', 21);
insert into photo (name, idEvent) values ('22_0.jpg', 22);
insert into photo (name, idEvent) values ('22_1.jpg', 22);
insert into photo (name, idEvent) values ('23_0.jpg', 23);
insert into photo (name, idEvent) values ('23_1.jpg', 23);
insert into photo (name, idEvent) values ('24_0.jpg', 24);
insert into photo (name, idEvent) values ('24_1.jpg', 24);
insert into photo (name, idEvent) values ('25_0.jpg', 25);
insert into photo (name, idEvent) values ('26_0.jpg', 26);
insert into photo (name, idEvent) values ('27_0.jpg', 27);
insert into photo (name, idEvent) values ('27_1.jpg', 27);
insert into photo (name, idEvent) values ('28_0.jpg', 28);
insert into photo (name, idEvent) values ('29_0.jpg', 29);
insert into photo (name, idEvent) values ('30_0.jpg', 30);
insert into photo (name, idEvent) values ('31_0.jpg', 31);

insert into eventhost (iduser, idEvent) values (1, 1);
insert into eventhost (iduser, idEvent) values (2, 2);
insert into eventhost (iduser, idEvent) values (7, 3);
insert into eventhost (iduser, idEvent) values (4, 4);
insert into eventhost (iduser, idEvent) values (5, 5);
insert into eventhost (iduser, idEvent) values (3, 6);
insert into eventhost (iduser, idEvent) values (1, 7);
insert into eventhost (iduser, idEvent) values (8, 8);
insert into eventhost (iduser, idEvent) values (7, 9);
insert into eventhost (iduser, idEvent) values (2, 10);
insert into eventhost (iduser, idEvent) values (15, 11);
insert into eventhost (iduser, idEvent) values (8, 12);
insert into eventhost (iduser, idEvent) values (20, 13);
insert into eventhost (iduser, idEvent) values (17, 14);
insert into eventhost (iduser, idEvent) values (13, 15);
insert into eventhost (iduser, idEvent) values (6, 16);
insert into eventhost (iduser, idEvent) values (4, 17);
insert into eventhost (iduser, idEvent) values (18, 18);
insert into eventhost (iduser, idEvent) values (11, 19);
insert into eventhost (iduser, idEvent) values (10, 20);
insert into eventhost (iduser, idEvent) values (8, 21);
insert into eventhost (iduser, idEvent) values (6, 22);
insert into eventhost (iduser, idEvent) values (4, 23);
insert into eventhost (iduser, idEvent) values (3, 24);
insert into eventhost (iduser, idEvent) values (8, 25);
insert into eventhost (iduser, idEvent) values (18, 26);
insert into eventhost (iduser, idEvent) values (17, 27);
insert into eventhost (iduser, idEvent) values (13, 28);
insert into eventhost (iduser, idEvent) values (12, 29);
insert into eventhost (iduser, idEvent) values (16, 30);

insert into invited (status, iduser, idEvent, email) values (FALSE, 5, 1, 'risus@ipsumprimis.com');
insert into invited (status, iduser, idEvent, email) values (FALSE, 8, 2, 'turpis@elita.edu');
insert into invited (status, iduser, idEvent, email) values (FALSE, 6, 3, 'zwellingtom.silvq@bercstuferca.tk');
insert into invited (status, iduser, idEvent, email) values (FALSE, 5, 2, 'iabdulla_mohmedv@lesslenett.cf');
insert into invited (status, iduser, idEvent, email) values (FALSE, 9, 1, 'imilan33333333o@cek-resi.website');
insert into invited (status, iduser, idEvent, email) values (FALSE, 6, 1, 'lfaizullahansari9@quifacto.ml');
insert into invited (status, iduser, idEvent, email) values (FALSE, 12, 1, 'lluviamango@roawintio.tk');
insert into invited (status, iduser, idEvent, email) values (FALSE, 1, 3, '0houcine.kauzac@67244de.xyz');
insert into invited (status, iduser, idEvent, email) values (TRUE, 3, 2, 'kiffaffocep-2670@yopmail.com');
insert into invited (status, iduser, idEvent, email) values (FALSE, 3, 1, 'uqinnuhi-5849@yopmail.com');
insert into invited (status, iduser, idEvent, email) values (FALSE, 6, 20, 'qhazouma_benabd@flowfthroughbrush.net');
insert into invited (status, iduser, idEvent, email) values (FALSE, 8, 20, 'qanaeldesha97x@plexamab.cf');
insert into invited (status, iduser, idEvent, email) values (TRUE, 12, 20, 'ralarygo-4469@yopmail.com');
insert into invited (status, iduser, idEvent, email) values (FALSE, 6, 13, 'risus@ipsumprimis.com');
insert into invited (status, iduser, idEvent, email) values (FALSE, 2, 13, 'qanaeldesha97x@plexamab.cf');
insert into invited (status, iduser, idEvent, email) values (FALSE, 18, 13, '0houcine.kauzac@67244de.xyz');
insert into invited (status, iduser, idEvent, email) values (TRUE, 11, 13, 'markie@signdastsaq.gq');
insert into invited (status, iduser, idEvent, email) values (FALSE, 3, 17 , 'turpis@elita.edu');
insert into invited (status, iduser, idEvent, email) values (FALSE, 7, 17 , 'markie@signdastsaq.gq');
insert into invited (status, iduser, idEvent, email) values (TRUE, 9, 17, 'lluviamango@roawintio.tk');
insert into invited (status, iduser, idEvent, email) values (FALSE, 13, 17 ,'dmotasim.1972h@geotamar.ga');
insert into invited (status, iduser, idEvent, email) values (FALSE, 18, 17, 'qhazouma_benabd@flowfthroughbrush.net');

insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 5, 1);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 7, 8);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 4, 3);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 6, 1);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 9, 3);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 3, 5);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 11, 8);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 3, 2);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 16, 22);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 4, 12);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 13, 15);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 9, 17);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 7, 20);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 12, 23);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 5, 26);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 17, 1);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 13, 5);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 19, 5);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 20, 5);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 15, 2);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 13, 2);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 15, 1);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 2, 25);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 5, 23);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 8, 23);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 9, 15);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 3, 17);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 13, 19);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 14, 20);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 20, 20);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 19, 21);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 13, 12);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 9, 13);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 12, 5);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 11, 5);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 5, 18);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 3, 12);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 8, 29);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 8, 27);
insert into ticket (qrcode, entranceTime, iduser, idEvent) values (NULL, NULL, 9, 22);

insert into comment (text, date, iduser, idEvent) values ('Cant wait', '2020-10-13 20:12:01', 5, 1);
insert into comment (text, date, iduser, idEvent) values ('love this event', '2020-02-10 15:22:00', 11, 8);
insert into comment (text, date, iduser, idEvent) values ('Do I need to bring something?', '2020-08-01 10:15:20', 3, 5);
insert into comment (text, date, iduser, idEvent) values ('Can I arrive 10 min later?', '2020-09-12 10:15:20', 4, 2);
insert into comment (text, date, iduser, idEvent) values ('So happy to be there!', '2020-08-23 10:15:20', 6, 7);
insert into comment (text, date, iduser, idEvent) values ('Amazing', '2020-11-08 06:44:18', 8, 2);
insert into comment (text, date, iduser, idEvent) values ('Is this legal?', '2020-03-16 15:47:34', 4, 6);
insert into comment (text, date, iduser, idEvent) values ('Is there a subway nearby?', '2019-05-11 03:48:52', 10, 13);
insert into comment (text, date, iduser, idEvent) values ('This is interesting', '2019-01-03 03:36:10', 4, 15);
insert into comment (text, date, iduser, idEvent) values ('Any bands coming?', '2020-02-16 18:04:58', 6, 26);
insert into comment (text, date, iduser, idEvent) values ('What about the weather?', '2020-03-24 05:02:12', 17, 10);
insert into comment (text, date, iduser, idEvent) values ('I want my money back', '2020-01-14 02:48:19', 1, 18);
insert into comment (text, date, iduser, idEvent) values ('love it', '2019-10-10 16:38:33', 13, 25);
insert into comment (text, date, iduser, idEvent) values ('Can I be late?', '2020-01-14 02:48:19', 14, 22);
insert into comment (text, date, iduser, idEvent) values ('Lets party', '2019-02-24 12:26:51', 16, 27);
insert into comment (text, date, iduser, idEvent) values ('Any place to store my belongings?', '2019-03-01 12:59:33', 5, 5);
insert into comment (text, date, iduser, idEvent) values ('Looks promising', '2020-03-02 14:55:04', 1, 18);
insert into comment (text, date, iduser, idEvent) values ('Sounds boring', '2020-02-08 15:55:08', 5, 25);
insert into comment (text, date, iduser, idEvent) values ('Why would someone create this?', '2019-09-13 01:21:06', 7, 21);
insert into comment (text, date, iduser, idEvent) values ('Isnt the place to small?', '2019-06-19 08:50:55', 16, 29);
insert into comment (text, date, iduser, idEvent) values ('I want it now', '2019-04-26 09:35:46', 17, 12);
insert into comment (text, date, iduser, idEvent) values ('love this event', '2019-08-21 00:41:53', 13, 18);
insert into comment (text, date, iduser, idEvent) values ('Do i need to bring something?', '2019-12-07 04:33:12', 13, 25);
insert into comment (text, date, iduser, idEvent) values ('How long will it take?', '2019-04-30 23:15:42', 4, 21);
insert into comment (text, date, iduser, idEvent) values ('Such a great location as well', '2019-01-14 01:23:17', 15, 7);
insert into comment (text, date, iduser, idEvent) values ('Is there food inside?', '2019-04-11 07:19:11', 5, 13);
insert into comment (text, date, iduser, idEvent) values ('Free food', '2019-03-01 12:22:02', 11, 18);
insert into comment (text, date, iduser, idEvent) values ('What a weird time to have it', '2019-01-16 20:50:24', 13, 15);
insert into comment (text, date, iduser, idEvent) values ('Lets go everybody', '2019-11-02 19:54:48', 14, 22);
insert into comment (text, date, iduser, idEvent) values ('So happy', '2019-08-14 13:01:22', 6, 13);

insert into review (rating, iduser, idEvent) values (4, 5, 1);
insert into review (rating, iduser, idEvent) values (2, 7, 8);
insert into review (rating, iduser, idEvent) values (3, 4, 3);
insert into review (rating, iduser, idEvent) values (3, 6, 1);
insert into review (rating, iduser, idEvent) values (2, 9, 3);
insert into review (rating, iduser, idEvent) values (4, 11, 13);
insert into review (rating, iduser, idEvent) values (2, 12, 22);
insert into review (rating, iduser, idEvent) values (1, 15, 13);
insert into review (rating, iduser, idEvent) values (1, 13, 14);
insert into review (rating, iduser, idEvent) values (5, 12, 5);
insert into review (rating, iduser, idEvent) values (5, 11, 9);
insert into review (rating, iduser, idEvent) values (4, 10, 21);
insert into review (rating, iduser, idEvent) values (4, 1, 22);
insert into review (rating, iduser, idEvent) values (2, 5, 4);
insert into review (rating, iduser, idEvent) values (3, 5, 19);
insert into review (rating, iduser, idEvent) values (3, 18, 20);
insert into review (rating, iduser, idEvent) values (4, 13, 22);
insert into review (rating, iduser, idEvent) values (4, 11, 1);
insert into review (rating, iduser, idEvent) values (4, 18, 15);
insert into review (rating, iduser, idEvent) values (5, 8, 18);
insert into review (rating, iduser, idEvent) values (5, 9, 13);
insert into review (rating, iduser, idEvent) values (5, 2, 5);
insert into review (rating, iduser, idEvent) values (5, 4, 19);
insert into review (rating, iduser, idEvent) values (2, 11, 29);
insert into review (rating, iduser, idEvent) values (2, 13, 27);
insert into review (rating, iduser, idEvent) values (2, 17, 27);
insert into review (rating, iduser, idEvent) values (1, 4, 25);
insert into review (rating, iduser, idEvent) values (4, 7, 22);
insert into review (rating, iduser, idEvent) values (3, 9, 12);
insert into review (rating, iduser, idEvent) values (3, 11, 15);

insert into report (description, iduser, idEvent, idComment) values ('Disrespectful event', 1, 2, NULL);
insert into report (description, iduser, idEvent, idComment) values ('Fake event', 2, 2, NULL);
insert into report (description, iduser, idEvent, idComment) values ('Disrespectful comment', 3, NULL, 3);
insert into report (description, iduser, idEvent, idComment) values ('Bad comment', 4, NULL, 1);
insert into report (description, iduser, idEvent, idComment) values ('Fake information in comment', 5, NULL, 4);
insert into report (description, iduser, idEvent, idComment) values ('Event does not exist', 5, 21, NULL);
