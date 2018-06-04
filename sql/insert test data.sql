USE secretsanta;

INSERT INTO `users` (`ID`, `email`, `fname`, `lname`, `password`, `admin`) VALUES
(2, 'samuel.serif@mail.com', 'Samuel', 'Serif', '$2a$07$KqD2oLnqQ4O4zjLWbUQGruCaQCeSqWI81l4mWT/CM33/IUhyVK6hW', 0),
(3, 'will.barrow@mail.com', 'Will', 'Barrow', '$2a$07$c2ENzjFPnP29Svnq5S5wQevmEIfbXQlET/skHye8w21NVsC6JTiGy', 0),
(4, 'justin.case@mail.com', 'Justin', 'Case', '$2a$07$shH7YMJcmcyhb9HILIgBVOEV7nwYp0VlQmZgXtWVNqSKP6k9WPbd.', 0);

INSERT INTO `games` (`ID`, `name`) VALUES
(1, 'First Game');

INSERT INTO `assignment` (`games_ID`, `users_ID_from`, `users_ID_to`) VALUES
(1, 2, 4),
(1, 3, 2),
(1, 4, 3);
