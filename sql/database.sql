-- Création de la base de données
CREATE DATABASE IF NOT EXISTS festival_db;
USE festival_db;

-- Table des utilisateurs
CREATE TABLE users (
    id INT(11) NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) DEFAULT NULL,
    name VARCHAR(100) NOT NULL,
    firstname VARCHAR(100) NOT NULL,
    age INT(11) DEFAULT NULL,
    google_id VARCHAR(255) DEFAULT NULL UNIQUE,
    email VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (id)
);

-- Table des festivals
CREATE TABLE festivals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    location VARCHAR(100) NOT NULL,
    date DATE NOT NULL,
    description TEXT,
    image VARCHAR(255) -- Lien vers une image
);

-- Insertion de quelques données de test
INSERT INTO users (username, password) VALUES 
('admin', SHA2('lili', Ipodtutch@12369!));

INSERT INTO festivals (name, location, date, description, image) VALUES 
('Rock en Seine', 'Paris, France', '2025-08-23', 'Festival de rock annuel.', 'rock_seine.jpg'),
('Tomorrowland', 'Boom, Belgique', '2025-07-21', 'Le plus grand festival électro.', 'tomorrowland.jpg');

INSERT INTO festivals (name, location, date, description) VALUES
('Festival du Soleil', 'Nice, France', '2025-06-15', 'Un festival musical en plein air célébrant l’été.'),
('Rock en Seine', 'Paris, France', '2025-08-23', 'Festival de musique rock avec des artistes internationaux.'),
('Electrobeach Festival', 'Le Barcarès, France', '2025-07-12', 'Le plus grand festival de musique électronique en France.'),
('Hellfest', 'Clisson, France', '2025-06-21', 'Festival de métal incontournable en Europe.'),
('Les Vieilles Charrues', 'Carhaix, France', '2025-07-18', 'Le plus grand festival de musique français.'),
('Tomorrowland', 'Boom, Belgique', '2025-07-19', 'L’un des plus grands festivals de musique électronique au monde.'),
('Glastonbury Festival', 'Pilton, Royaume-Uni', '2025-06-26', 'Festival emblématique de musique et d’arts de la scène.'),
('Coachella', 'Indio, USA', '2025-04-12', 'Le festival le plus célèbre de la scène musicale et mode.'),
('Burning Man', 'Nevada, USA', '2025-08-25', 'Festival artistique et expérimental dans le désert du Nevada.'),
('Lollapalooza', 'Chicago, USA', '2025-07-30', 'Festival multiculturel de musique et d’art.'),
('Sziget Festival', 'Budapest, Hongrie', '2025-08-07', 'Un des plus grands festivals européens, sur une île du Danube.'),
('Primavera Sound', 'Barcelone, Espagne', '2025-05-30', 'Festival branché mélangeant indie, électro et rock.'),
('Roskilde Festival', 'Roskilde, Danemark', '2025-07-01', 'Festival historique alliant musique et engagement social.'),
('Ultra Music Festival', 'Miami, USA', '2025-03-28', 'Référence mondiale de la musique électro.'),
('Fuji Rock Festival', 'Niigata, Japon', '2025-07-26', 'Le plus grand festival de musique du Japon.'),
('Montreux Jazz Festival', 'Montreux, Suisse', '2025-07-05', 'L’un des festivals de jazz les plus prestigieux au monde.'),
('Mad Cool Festival', 'Madrid, Espagne', '2025-07-11', 'Festival de rock et musique alternative en Espagne.'),
('Open’er Festival', 'Gdynia, Pologne', '2025-07-04', 'Festival éclectique en Pologne, mélangeant rock et électro.'),
('Dour Festival', 'Dour, Belgique', '2025-07-10', 'Festival belge emblématique mêlant plusieurs genres musicaux.'),
('Balaton Sound', 'Lac Balaton, Hongrie', '2025-07-03', 'Festival électro organisé sur les rives du lac Balaton.'),
('Exit Festival', 'Novi Sad, Serbie', '2025-07-10', 'Festival dans la forteresse de Petrovaradin, mélangeant techno et rock.'),
('Outlook Festival', 'Pula, Croatie', '2025-09-04', 'Festival emblématique des musiques bass et reggae.'),
('Boom Festival', 'Idanha-a-Nova, Portugal', '2025-07-28', 'Festival psychédélique et spirituel au Portugal.'),
('Sonar Festival', 'Barcelone, Espagne', '2025-06-14', 'Festival électro et d’arts numériques en Espagne.'),
('Dekmantel Festival', 'Amsterdam, Pays-Bas', '2025-08-01', 'Festival techno et électro pointu aux Pays-Bas.'),
('Mysteryland', 'Haarlemmermeer, Pays-Bas', '2025-08-24', 'Le plus ancien festival de musique électronique.'),
('Defqon.1', 'Biddinghuizen, Pays-Bas', '2025-06-20', 'Le plus grand festival de Hardstyle au monde.'),
('Awakenings Festival', 'Spaarnwoude, Pays-Bas', '2025-06-29', 'Festival techno légendaire aux Pays-Bas.'),
('We Love Green', 'Paris, France', '2025-06-01', 'Festival éco-responsable mêlant musique et engagement écologique.'),
('Solidays', 'Paris, France', '2025-06-21', 'Festival associatif de musique engagé contre le SIDA.'),
('Main Square Festival', 'Arras, France', '2025-07-05', 'Festival éclectique dans la citadelle d’Arras.'),
('Jazz à Vienne', 'Vienne, France', '2025-06-28', 'Grand festival de jazz dans un théâtre antique.'),
('La Route du Rock', 'Saint-Malo, France', '2025-08-14', 'Festival de musique indie rock en Bretagne.'),
('Calvi on the Rocks', 'Calvi, France', '2025-07-05', 'Festival électro et plage en Corse.'),
('Garorock', 'Marmande, France', '2025-06-27', 'Festival de rock et électro dans le sud-ouest.'),
('Reggae Sun Ska', 'Bordeaux, France', '2025-08-02', 'Grand festival de reggae en France.'),
('Festival Interceltique', 'Lorient, France', '2025-08-02', 'Festival de musique celtique rassemblant des artistes du monde entier.'),
('Printemps de Bourges', 'Bourges, France', '2025-04-16', 'Festival mettant en avant la scène musicale émergente.'),
('Eurockéennes', 'Belfort, France', '2025-07-04', 'Grand festival de rock en France.'),
('Festival de Nîmes', 'Nîmes, France', '2025-06-25', 'Concerts dans les arènes romaines de Nîmes.'),
('Les Francofolies', 'La Rochelle, France', '2025-07-10', 'Festival de la chanson francophone.'),
('Festival de Cannes', 'Cannes, France', '2025-05-14', 'Festival international du film.'),
('Chorégies d’Orange', 'Orange, France', '2025-07-12', 'Festival d’opéra dans un théâtre antique.'),
('Festival d’Avignon', 'Avignon, France', '2025-07-05', 'Grand festival de théâtre en France.'),
('Nuits Sonores', 'Lyon, France', '2025-05-21', 'Festival de musique électronique et arts numériques.'),
('Festival de Carcassonne', 'Carcassonne, France', '2025-07-01', 'Festival multidisciplinaire dans une cité médiévale.'),
('Les Escales', 'Saint-Nazaire, France', '2025-07-26', 'Festival international de musiques actuelles.'),
('Jazz in Marciac', 'Marciac, France', '2025-07-29', 'Festival de jazz mondialement reconnu.'),
('Festival du Bout du Monde', 'Crozon, France', '2025-08-02', 'Festival de musiques du monde en Bretagne.');



ALTER TABLE festivals
ADD COLUMN official_website VARCHAR(255) DEFAULT NULL;


UPDATE festivals
SET official_website = 'https://www.rockenseine.com'
WHERE name = 'Rock en Seine';

UPDATE festivals
SET official_website = 'https://www.tomorrowland.com'
WHERE name = 'Tomorrowland';

UPDATE festivals
SET official_website = 'https://www.festivaldusoleil.com'
WHERE name = 'Festival du Soleil';

UPDATE festivals
SET official_website = 'https://www.electrobeach.com'
WHERE name = 'Electrobeach Festival';

UPDATE festivals
SET official_website = 'https://www.hellfest.fr'
WHERE name = 'Hellfest';

UPDATE festivals
SET official_website = 'https://www.vieillescharrues.asso.fr'
WHERE name = 'Les Vieilles Charrues';

UPDATE festivals
SET official_website = 'https://www.glastonburyfestivals.co.uk'
WHERE name = 'Glastonbury Festival';

UPDATE festivals
SET official_website = 'https://www.coachella.com'
WHERE name = 'Coachella';

UPDATE festivals
SET official_website = 'https://www.burningman.org'
WHERE name = 'Burning Man';

UPDATE festivals
SET official_website = 'https://www.lollapalooza.com'
WHERE name = 'Lollapalooza';

UPDATE festivals
SET official_website = 'https://www.szigetfestival.com'
WHERE name = 'Sziget Festival';

UPDATE festivals
SET official_website = 'https://www.primaverasound.com'
WHERE name = 'Primavera Sound';

UPDATE festivals
SET official_website = 'https://www.roskilde-festival.dk'
WHERE name = 'Roskilde Festival';

UPDATE festivals
SET official_website = 'https://www.ultramusicfestival.com'
WHERE name = 'Ultra Music Festival';

UPDATE festivals
SET official_website = 'https://www.fujirockfestival.com'
WHERE name = 'Fuji Rock Festival';

UPDATE festivals
SET official_website = 'https://www.montreuxjazzfestival.com'
WHERE name = 'Montreux Jazz Festival';

UPDATE festivals
SET official_website = 'https://www.madcoolfestival.es'
WHERE name = 'Mad Cool Festival';

UPDATE festivals
SET official_website = 'https://www.opener.pl'
WHERE name = 'Open’er Festival';

UPDATE festivals
SET official_website = 'https://www.dourfestival.eu'
WHERE name = 'Dour Festival';

UPDATE festivals
SET official_website = 'https://www.balatonsound.com'
WHERE name = 'Balaton Sound';

UPDATE festivals
SET official_website = 'https://www.exitfest.org'
WHERE name = 'Exit Festival';

UPDATE festivals
SET official_website = 'https://www.outlookfestival.com'
WHERE name = 'Outlook Festival';

UPDATE festivals
SET official_website = 'https://www.boomfestival.org'
WHERE name = 'Boom Festival';

UPDATE festivals
SET official_website = 'https://www.sonarfestival.com'
WHERE name = 'Sonar Festival';

UPDATE festivals
SET official_website = 'https://www.dekmantelfestival.com'
WHERE name = 'Dekmantel Festival';

UPDATE festivals
SET official_website = 'https://www.mysteryland.nl'
WHERE name = 'Mysteryland';

UPDATE festivals
SET official_website = 'https://www.defqon1.nl'
WHERE name = 'Defqon.1';

UPDATE festivals
SET official_website = 'https://www.awakenings.com'
WHERE name = 'Awakenings Festival';

UPDATE festivals
SET official_website = 'https://www.welovegreen.fr'
WHERE name = 'We Love Green';

UPDATE festivals
SET official_website = 'https://www.solidays.org'
WHERE name = 'Solidays';

UPDATE festivals
SET official_website = 'https://www.mainsquarefestival.fr'
WHERE name = 'Main Square Festival';

UPDATE festivals
SET official_website = 'https://www.jazzavienne.com'
WHERE name = 'Jazz à Vienne';

UPDATE festivals
SET official_website = 'https://www.laroutedurock.com'
WHERE name = 'La Route du Rock';

UPDATE festivals
SET official_website = 'https://www.calviontherocks.com'
WHERE name = 'Calvi on the Rocks';

UPDATE festivals
SET official_website = 'https://www.garorock.com'
WHERE name = 'Garorock';

UPDATE festivals
SET official_website = 'https://www.reggaesunska.com'
WHERE name = 'Reggae Sun Ska';

UPDATE festivals
SET official_website = 'https://www.festival-interceltique.com'
WHERE name = 'Festival Interceltique';

UPDATE festivals
SET official_website = 'https://www.printempsdebourges.com'
WHERE name = 'Printemps de Bourges';

UPDATE festivals
SET official_website = 'https://www.eurockeennes.fr'
WHERE name = 'Eurockéennes';

UPDATE festivals
SET official_website = 'https://www.festivalnimes.com'
WHERE name = 'Festival de Nîmes';

UPDATE festivals
SET official_website = 'https://www.francofolies.fr'
WHERE name = 'Les Francofolies';

UPDATE festivals
SET official_website = 'https://www.festival-cannes.com'
WHERE name = 'Festival de Cannes';

UPDATE festivals
SET official_website = 'https://www.choregies.fr'
WHERE name = 'Chorégies d’Orange';

UPDATE festivals
SET official_website = 'https://www.festival-avignon.com'
WHERE name = 'Festival d’Avignon';

UPDATE festivals
SET official_website = 'https://www.nuitssonores.org'
WHERE name = 'Nuits Sonores';

UPDATE festivals
SET official_website = 'https://www.festivaldecarcassonne.org'
WHERE name = 'Festival de Carcassonne';

UPDATE festivals
SET official_website = 'https://www.lesescalestoulon.com'
WHERE name = 'Les Escales';

UPDATE festivals
SET official_website = 'https://www.jazzinmarciac.com'
WHERE name = 'Jazz in Marciac';

UPDATE festivals
SET official_website = 'https://www.festivalduboutdumonde.com'
WHERE name = 'Festival du Bout du Monde';


ALTER TABLE users ADD COLUMN participation_level INT DEFAULT 3;
