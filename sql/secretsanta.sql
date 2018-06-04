-- creating DATABASE
create database secretsanta default character set utf8 default collate utf8_general_ci;

use secretsanta;

-- creating TABLES
create table users(
  ID integer auto_increment,
  email varchar(50) not null,
  fname varchar(20) not null,
  lname varchar(20) not null,
  password varchar(255) not null,
  admin tinyint(1) not null default 0,
  constraint pk_users primary key(ID),
  constraint uq_users unique(email)
);

create table games(
  ID integer auto_increment,
  name varchar(20) not null,
  constraint pk_games primary key(ID)
);

create table assignment(
  games_ID integer,
  users_ID_from integer,
  users_ID_to integer,
  constraint pk_games primary key(games_ID,users_ID_from,users_ID_to),
  constraint fk_assignment_games foreign key(games_ID) references games(ID) on delete cascade,
  constraint fk_assignment_users_from foreign key(users_ID_from) references users(ID),
  constraint fk_assignment_users_to foreign key(users_ID_to) references users(ID)
);

-- adding ADMIN ACCOUNT
-- $2y$10$T6dmVGyab8gEtS6n2MxGo./Dkrh5jJ9yT5hZKk1cNnm8cjr5u.TVa     password is ->    admin
insert into users values (1, 'admin@mail.com', 'John', 'Doe', '$2y$10$T6dmVGyab8gEtS6n2MxGo./Dkrh5jJ9yT5hZKk1cNnm8cjr5u.TVa', 1);
