CREATE TABLE updates ( id int, ctime timestamp, query text );
CREATE INDEX id_idx ON updates(id);
INSERT INTO updates (id,query) VALUES (0,'Init');

