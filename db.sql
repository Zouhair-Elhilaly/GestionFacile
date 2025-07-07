create database Stock;
use Stock;

create table produits(
    id int ;
    nom varchar(50);
    prix decimal(10,2);
);


CREATE TABLE user_produits (
    user_id INT,
    produit_id INT,
    FOREIGN KEY (user_id) REFERENCES user(id),
    FOREIGN KEY (produit_id) REFERENCES produits(id)
);


create table user(
    id int  primary key auto_increment;
    nom varchar(50) not null;
    prenom varchar(50) not null;
    email varchar(50) not null;
    genre varchar(10) not null;
    nationalité varchar(50) not null;
    ville varchar(50) not null;
    age int not null;
    quantite int not null;
    mote_de_passe varchar(50) not null;
);


create table admin(
    id int  primary key auto_increment;
    nom varchar(50) not null;
    prenom varchar(50) not null;
    mote_de_passe varchar(50) not null;
);



-- foreign keys user produits
alter table admin add constraint Fk_admin_user foreign key (id) references user(id);
