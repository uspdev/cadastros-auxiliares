create table PESSOA (
    codpes int not null,
    nompes varchar(120) not null,
    nompesttd varchar(120) null,
    nompesfon varchar(120) null,
    sexpes varchar(20) null,
    dtanas datetime not null
)

INSERT INTO PESSOA (codpes, nompes, nompesttd, dtanas)
VALUES (123456,'Fulano da Silva','Fulano da Silva','2018-10-05 00:00:00')