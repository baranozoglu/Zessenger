DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS blocked_users;
DROP TABLE IF EXISTS favorite_users;
DROP TABLE IF EXISTS favorite_messages;
DROP TABLE IF EXISTS favorite_user_categories;
DROP TABLE IF EXISTS logins;

CREATE TABLE users (
                       id INTEGER PRIMARY KEY,
                       first_name TEXT NOT NULL,
                       last_name TEXT NOT NULL,
                       email TEXT NOT NULL UNIQUE,
                       phone TEXT NOT NULL UNIQUE,
                       password TEXT NOT NULL,
                       username TEXT NOT NULL,
                       photo_url TEXT,
                       created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                       updated_at DATETIME
);

CREATE TABLE messages (
                          id INTEGER PRIMARY KEY,
                          text TEXT NOT NULL,
                          status_for_sender BOOLEAN DEFAULT true,
                          status_for_receiver BOOLEAN DEFAULT true,
                          isEdited BOOLEAN DEFAULT false,
                          sender_id INTEGER NOT NULL,
                          receiver_id INTEGER NOT NULL,
                          parent_message_id INTEGER,
                          created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                          updated_at DATETIME,
                          FOREIGN KEY (sender_id)
                              REFERENCES users (id),
                          FOREIGN KEY (receiver_id)
                              REFERENCES users (id),
                          FOREIGN KEY (parent_message_id)
                              REFERENCES messages (id)
);

CREATE TABLE blocked_users (
                               id INTEGER PRIMARY KEY,
                               user_id integer NOT NULL,
                               blocked_user_id integer NOT NULL,
                               created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                               updated_at DATETIME,
                               FOREIGN KEY (user_id)
                                   REFERENCES users (id),
                               FOREIGN KEY (blocked_user_id)
                                   REFERENCES users (id)
);

CREATE TABLE logins (
                        id INTEGER PRIMARY KEY,
                        user_id INTEGER NOT NULL,
                        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                        updated_at DATETIME,
                        FOREIGN KEY (user_id)
                            REFERENCES users (id)
);

CREATE TABLE favorite_user_categories (
                                          id INTEGER PRIMARY KEY,
                                          user_id INTEGER NOT NULL,
                                          name TEXT NOT NULL,
                                          created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                                          updated_at DATETIME,
                                          FOREIGN KEY (user_id)
                                              REFERENCES users (id)
)

CREATE TABLE favorite_users (
                                id INTEGER PRIMARY KEY,
                                favorite_user_id INTEGER NOT NULL,
                                user_id INTEGER NOT NULL,
                                favorite_user_category_id INTEGER NOT NULL,
                                nickname TEXT,
                                last_message_time DATETIME,
                                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                                updated_at DATETIME,
                                FOREIGN KEY (user_id)
                                    REFERENCES users (id),
                                FOREIGN KEY (favorite_user_id)
                                    REFERENCES users (id),
                                FOREIGN KEY (favorite_user_category_id)
                                    REFERENCES favorite_user_categories (id)
);

CREATE TABLE favorite_messages (
                                   id INTEGER PRIMARY KEY,
                                   message_id INTEGER NOT NULL,
                                   user_id INTEGER NOT NULL,
                                   sender_id INTEGER NOT NULL,
                                   receiver_id INTEGER NOT NULL,
                                   created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                                   updated_at DATETIME,
                                   FOREIGN KEY (user_id)
                                       REFERENCES users (id),
                                   FOREIGN KEY (sender_id)
                                       REFERENCES users (id),
                                   FOREIGN KEY (receiver_id)
                                       REFERENCES users (id),
                                   FOREIGN KEY (message_id)
                                       REFERENCES messages (id)
);

INSERT INTO "users" ("id", "first_name", "last_name", "email", "phone", "password", "username", "photo_url", "created_at", "updated_at") VALUES
('1', 'baran', 'ozoglu', 'baran@asd.com', '14353453453', 'admin', 'baranozoglu', '', NULL, NULL);

INSERT INTO "users" ("id", "first_name", "last_name", "email", "phone", "password", "username", "photo_url", "created_at", "updated_at") VALUES
('2', 'hakan', 'ozoglu', 'hakan@asd.com', '24353453453', 'admin', 'hakanozoglu', '', NULL, NULL);