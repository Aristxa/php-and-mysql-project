USE HospitalDB;
CREATE FUNCTION GetNumriPacienteve()
RETURNS INT
AS
BEGIN
    DECLARE @numri INT;
    SELECT @numri = COUNT(*) FROM Pacientet;
    RETURN @numri;
END;
 
SELECT dbo.GetNumriPacienteve() AS NumriTotalPacientëve;

CREATE FUNCTION FatureTotale(@PacientID INT)
RETURNS DECIMAL(10,2)
AS
BEGIN
    DECLARE @Totali DECIMAL(10,2);
    SELECT @Totali = SUM(Shuma) FROM Faturat WHERE PacientID = @PacientID;
    RETURN @Totali;
END;
SELECT PacientID, dbo.FatureTotale(PacientID) AS TotaliFaturave
FROM Pacientet;

CREATE VIEW View_Takimet_Mjekut AS
SELECT T.TakimID, P.Emri AS Pacienti, P.Mbiemri, T.DataTakimit, T.OraTakimit
FROM Takimet T
JOIN Pacientet P ON T.PacientID = P.PacientID;
SELECT * FROM View_Takimet_Mjekut;

CREATE VIEW View_Faturat_Papagura AS
SELECT F.FatureID, P.Emri, P.Mbiemri, F.Shuma, F.Statusi
FROM Faturat F
JOIN Pacientet P ON F.PacientID = P.PacientID
WHERE F.Statusi = 'E Papaguar';
SELECT * FROM View_Faturat_Papagura;



CREATE VIEW Raporti_Admin AS
SELECT 
    (SELECT COUNT(*) FROM Pacientet) AS NumriPacientet,
    (SELECT COUNT(*) FROM Takimet WHERE Statusi = 'I Planifikuar') AS TakimeTePlanifikuara;
SELECT * FROM Raporti_Admin;

CREATE PARTITION FUNCTION pf_Takimet_Statusi (NVARCHAR(20))
AS RANGE LEFT FOR VALUES ('I Planifikuar', 'Përfunduar', 'Anuluar');


SELECT TakimID, Statusi, $PARTITION.pf_Takimet_Statusi(Statusi) AS PartitionNumber
FROM Takimet;

CREATE PARTITION SCHEME ps_Takimet_Statusi
AS PARTITION pf_Takimet_Statusi ALL TO ([PRIMARY]);

SELECT * FROM sys.partition_schemes;


CREATE VIEW V_Takimet_Pacientit AS
SELECT T.TakimID, P.Emri, P.Mbiemri, M.Emri AS Mjeku, T.DataTakimit, T.OraTakimit, T.Statusi
FROM Takimet T
JOIN Pacientet P ON T.PacientID = P.PacientID
JOIN Mjeket M ON T.MjekID = M.MjekID;
SELECT * FROM V_Takimet_Pacientit;

CREATE PROCEDURE ShtoTakim
    @PacientID INT,
    @MjekID INT,
    @DataTakimit DATE,
    @OraTakimit TIME,
    @Statusi NVARCHAR(20),
    @Pershkrimi NVARCHAR(255)
AS
BEGIN
    INSERT INTO Takimet (PacientID, MjekID, DataTakimit, OraTakimit, Statusi, Pershkrimi)
    VALUES (@PacientID, @MjekID, @DataTakimit, @OraTakimit, @Statusi, @Pershkrimi);
END;

CREATE TRIGGER TRG_CheckDuplicateTakim
ON Takimet
AFTER INSERT
AS
BEGIN
    IF EXISTS (
        SELECT 1 FROM Takimet
        WHERE PacientID IN (SELECT PacientID FROM inserted)
        AND DataTakimit IN (SELECT DataTakimit FROM inserted)
        HAVING COUNT(*) > 1
    )
    BEGIN
        RAISERROR ('Pacienti nuk mund të ketë më shumë se një takim në një ditë.', 16, 1);
        ROLLBACK TRANSACTION;
    END;
END;
DROP TRIGGER TRG_CheckDuplicateTakim;

CREATE TRIGGER trg_CheckNumriSigurimi
ON Pacientet
FOR INSERT, UPDATE
AS
BEGIN
    IF EXISTS (
        SELECT 1 FROM inserted
        WHERE LEN(NrSigurimi) <> 10
    )
    BEGIN
        RAISERROR ('❌ Numri i Sigurimit duhet të ketë saktësisht 10 shifra!', 16, 1);
        ROLLBACK TRANSACTION;
    END
END;



CREATE PROCEDURE UpdateFatureStatus
    @FatureID INT,
    @Statusi NVARCHAR(50)
AS
BEGIN
    UPDATE Faturat SET Statusi = @Statusi WHERE FatureID = @FatureID;
END;


SELECT name 
FROM sys.triggers 
WHERE parent_class_desc = 'OBJECT_OR_COLUMN';

