CREATE DATABASE sportswear;

use sportswear;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255),
    password VARCHAR(255),
    fullName VARCHAR(255),
    dateOfBirth DATE,
    email VARCHAR(255),
    phone VARCHAR(255),
    addrress VARCHAR(255),
    gender VARCHAR(255),
    createdAt VARCHAR(255),
    status VARCHAR(255)
);
