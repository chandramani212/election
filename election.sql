create table users (
    
    id int(11) not null primary key auto_increment,
    voter_id varchar(20),
    cell_no int(11),
    email varchar(100),
    first_name varchar(20),
    last_name varchar(20),
    age smallint(5),
    gender enum('MALE','FEMALE'),
    date_of_birth date,
    blood_group varchar(5),
    caste varchar(10),
    occupation varchar(20),
    qualification varchar(60),
    religion varchar(50)
    
 );
 
create table country (
    
    id int(11) not null primary key auto_increment,
    name varchar(50),
    code varchar(5),
    status enum('1','0')
);


create table state (
    
    id int(11) not null primary key auto_increment,
	country_id int(11) not null,
    name varchar(50),
    code varchar(5),
    status enum('1','0')
);

create table city (
    
    id int(11) not null primary key auto_increment,
	state_id int(11) not null,
    name varchar(50),
    code varchar(5),
    status enum('1','0')
);

create table district (
    
    id int(11) not null primary key auto_increment,
	city_id int(11) not null,
    name varchar(50),
    code varchar(5),
    status enum('1','0')
);

create table taluka (
    
    id int(11) not null primary key auto_increment,
	district_id int(11) not null,
    name varchar(50),
    code varchar(5),
    status enum('1','0')
);


create table ward (
    
    id int(11) not null primary key auto_increment,
    name varchar(50),
    code varchar(5),
    status enum('1','0')
);


create table complaints (
    
    id int(11) not null primary key auto_increment,
    ward_id int(11) not null,
    message varchar(5),
    status enum('PENDING','SOLVED')
);

create table compliant_images (
	id int(11) not null primary key auto_increment,
	complaint_id int(11) not null,
	image varchar(255)
);

create table contact_person_types(
	id int(11) not null primary key auto_increment,
	type varchar(50),
	status enum('1','0')
	
);

create table contact_persons(
	id int(11) not null primary key auto_increment,
	type_id int(11) not null,
	name varchar(100),
	contact_no int(11),
	blood_group varchar(5),
	designation varchar(50)
	

);

create table news(
	id int(11) not null primary key auto_increment,
	title varchar(100),
	sub_title varchar(200),
	content varchar(255),
	image varchar(255),
	date date,
	status enum('1','0')
);


create table functions(
	id int(11) not null primary key auto_increment,
	title varchar(100),
	sub_title varchar(200),
	content text,
	image varchar(255),
	date date,
	status enum('1','0')
);

create table job_category(
	id int(11) not null primary key auto_increment,
	name varchar(100),
	status enum('1','0')
);

create table job_contact_persons(
	id int(11) not null primary key auto_increment,
	job_category_id int(11) not null,
	name varchar(100),
	contact_no int(11),
	status enum('1','0')
);

create table suggestions(
	id int(11) not null primary key auto_increment,
	user_id int(11) not null,
	suggestion text
	
);


create table user_request_for_tickets(
	id int(11) not null primary key auto_increment,
	ward_id int(11) not null,
	user_id int(11) not null,
	party_id int(11) not null,
	for_year varchar(5),
	got_ticket enum('YES','NO'),
	date date
	
);

create table vote_for_user_request_for_tickets(
	id int(11) not null primary key auto_increment,
	urft_id int(11) not null, -- primary key of user_request_for_ticket,
	user_id int(11) not null, -- from users table user who have voted
	status enum('1','0')
	
);

create table parties(
	id int(11) not null primary key auto_increment,
	name varchar(50),
	logo varchar(255),
	status enum('1','0')
);

create table user_elected_for_parties(
	id int(11) not null primary key auto_increment,
	party_id int(11) not null,
	user_id int(11) not null,
	for_year varchar(5),
	status enum('1','0')
);


create table vote_for_user_elected_for_parties(
	id int(11) not null primary key auto_increment,
	urfp_id int(11) not null, -- primary key of user_elected_for_parties,
	user_id int(11) not null -- from users table user who have voted
	
);
