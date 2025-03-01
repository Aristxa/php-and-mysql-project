CREATE DATABASE HospitalDB;
GO
USE HospitalDB;
GO

CREATE TABLE Users (
    UserID INT IDENTITY(1,1) PRIMARY KEY,
    Username NVARCHAR(50) UNIQUE NOT NULL,
    Password NVARCHAR(255) NOT NULL,  -- Store hashed passwords
    Role NVARCHAR(20) CHECK (Role IN ('Admin', 'Doctor', 'Patient'))
);

CREATE TABLE Pacientet (
    PacientID INT IDENTITY(1,1) PRIMARY KEY,
    Emri NVARCHAR(50) NOT NULL,
    Mbiemri NVARCHAR(50) NOT NULL,
    Datelindja DATE NOT NULL,
    Gjinia CHAR(1) CHECK (Gjinia IN ('M', 'F')),
    Adresa NVARCHAR(255),
    Tel VARCHAR(15),
    Email VARCHAR(100) UNIQUE,
    NrSigurimi VARCHAR(20) UNIQUE,
    DataRegjistrimit DATETIME DEFAULT GETDATE()
);
CREATE TABLE Mjeket (
    MjekID INT IDENTITY(1,1) PRIMARY KEY,
    Emri NVARCHAR(50) NOT NULL,
    Mbiemri NVARCHAR(50) NOT NULL,
    Specializimi NVARCHAR(100) NOT NULL,
    Tel VARCHAR(15),
    Email NVARCHAR(100) UNIQUE,
    DataPunësimit DATE DEFAULT GETDATE()
);
CREATE TABLE OrariMjekeve (
    OrarID INT IDENTITY(1,1) PRIMARY KEY,
    MjekID INT FOREIGN KEY REFERENCES Mjeket(MjekID) ON DELETE CASCADE,
    Dita NVARCHAR(10) CHECK (Dita IN ('E Hënë', 'E Martë', 'E Mërkurë', 'E Enjte', 'E Premte', 'E Shtunë', 'E Diel')),
    OraFillimit TIME NOT NULL,
    OraMbarimit TIME NOT NULL
);
CREATE TABLE Takimet (
    TakimID INT IDENTITY(1,1) PRIMARY KEY,
    PacientID INT FOREIGN KEY REFERENCES Pacientet(PacientID) ON DELETE CASCADE,
    MjekID INT FOREIGN KEY REFERENCES Mjeket(MjekID) ON DELETE CASCADE,
    DataTakimit DATE NOT NULL,
    OraTakimit TIME NOT NULL,
    Statusi NVARCHAR(20) CHECK (Statusi IN ('I Planifikuar', 'Përfunduar', 'Anuluar')),
    Pershkrimi NVARCHAR(255)
);
SELECT * FROM Takimet;

CREATE TABLE MedikamentetPerHistorik (
    MedikamentID INT IDENTITY(1,1),
    HistID INT FOREIGN KEY REFERENCES HistorikuMjekesor(HistID) ON DELETE CASCADE,
    MedikamentEmri NVARCHAR(100),
	PRIMARY KEY (MedikamentID, HistID),
);
SELECT * FROM MedikamentetPerHistorik;


CREATE TABLE HistorikuMjekesor (
    HistID INT IDENTITY(1,1) PRIMARY KEY,
    PacientID INT FOREIGN KEY REFERENCES Pacientet(PacientID) ON DELETE CASCADE,
    MjekID INT FOREIGN KEY REFERENCES Mjeket(MjekID) ON DELETE CASCADE,
    DataVizites DATE NOT NULL,
    Diagnoza NVARCHAR(255) NOT NULL,
    Trajtimi NVARCHAR(255),
    Medikamente NVARCHAR(255)
);

CREATE TABLE Faturat (
    FatureID INT IDENTITY(1,1) PRIMARY KEY,
    PacientID INT FOREIGN KEY REFERENCES Pacientet(PacientID) ON DELETE CASCADE,
    DataFaturimit DATE DEFAULT GETDATE(),
    Shuma DECIMAL(10,2) NOT NULL,
    Statusi NVARCHAR(20) CHECK (Statusi IN ('E Paguar', 'E Papaguar'))
);
SELECT * FROM Faturat;

CREATE TABLE TakimetPartition (
    TakimID INT IDENTITY(1,1),
    PacientID INT FOREIGN KEY REFERENCES Pacientet(PacientID) ON DELETE CASCADE,
    MjekID INT FOREIGN KEY REFERENCES Mjeket(MjekID) ON DELETE CASCADE,
    DataTakimit DATE NOT NULL,
    OraTakimit TIME NOT NULL,
    Statusi NVARCHAR(20) CHECK (Statusi IN ('I Planifikuar', 'Përfunduar', 'Anuluar')),
    Pershkrimi NVARCHAR(255),
    CONSTRAINT PK_Takimet PRIMARY KEY (TakimID, Statusi)  -- Primary Key includes partitioning column
) ON ps_Takimet_Statusi (Statusi);


CREATE INDEX idx_Pacient_Email ON Pacientet(Email);
CREATE INDEX idx_Takimet_Data ON Takimet(DataTakimit);
CREATE INDEX idx_Historiku_Pacient ON HistorikuMjekesor(PacientID);

