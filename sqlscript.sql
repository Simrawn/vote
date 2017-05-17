drop table appuser;
drop table classes;
drop table course_enrollment;

CREATE table appuser(
	username varchar(20) primary key, 
	password varchar(20) not Null, 
	firstname varchar(20) not Null,
	lastname varchar(20) not Null,
	email varchar(100) not Null,
	designation varchar(20) not Null 

);

CREATE table classes(
	classname varchar(20) primary key,
	classcode varchar(20) not Null,
	professor varchar(20) not Null,
	iGetIt int DEFAULT 0,
	iDontGetIt int DEFAULT 0,
	total_enrolled int DEFAULT 0
);

CREATE table course_enrollment(
	classname varchar(20) not Null,
	student_username varchar(20) not Null
	unique (classname, student_username);

); 


--create trigger on enrolling a new student 

INSERT INTO appuser VALUES ('Hamid', 'hello', 'Hamid', 'Yoqoub', 'Hamid@gmail.com', 'instructor');

INSERT INTO appuser VALUES ('NimJ', 'hello', 'Nim', 'Jay', 'nim@gmail.com', 'student');

--INSERT INTO classes VALUES ('csc321', '1234','Hamid');
--INSERT INTO classes VALUES ('csc309', '4321', 'Hamid');

--INSERT INTO course_enrollment VALUES ('csc321', 'NimJ');

