USE HospitalDB;
INSERT INTO Takimet (PacientID, MjekID, DataTakimit, OraTakimit, Statusi, Pershkrimi)
VALUES
(1, 2, '2025-02-01', '09:00', 'Anuluar', 'Takimi për kontroll të gjendjes shëndetësore të pacientit u anulua'),
(2, 2, '2025-02-02', '10:00', 'Përfunduar', 'Takimi për trajtimin e dhimbjeve të shpinës ka përfunduar me sukses'),
(3, 3, '2025-02-03', '11:00', 'I Planifikuar', 'Takimi për kontrollin e rregullt të zemrës është planifikuar për këtë datë'),
(4, 4, '2025-02-04', '12:00', 'Anuluar', 'Takimi për trajtimin e alergjive u anulua për shkak të vonesës së mjekut'),
(5, 5, '2025-02-05', '13:00', 'Përfunduar', 'Takimi për vizitën e parë pas operacionit të krahut është përfunduar me sukses'),
(6, 6, '2025-02-06', '14:00', 'I Planifikuar', 'Takimi për kontroll të diabetit është planifikuar për këtë datë'),
(7, 7, '2025-02-07', '15:00', 'Anuluar', 'Takimi për trajtimin e problemit me gishtin e këmbës u anulua për arsye të tjera emergjente'),
(8, 8, '2025-02-08', '16:00', 'Përfunduar', 'Takimi për kontrolle periodike dhe këshilla për ushqyerje ka përfunduar'),
(9, 9, '2025-02-09', '17:00', 'I Planifikuar', 'Takimi për analizën e gjakut dhe këshilla për përmirësimin e shëndetit është planifikuar'),
(10, 10, '2025-02-10', '18:00', 'Anuluar', 'Takimi për kontrollin e syrit u anulua për arsye të ndryshme'),
(11, 11, '2025-02-11', '19:00', 'Përfunduar', 'Takimi për këshilla dhe trajtim të mundshëm për alergjitë ka përfunduar me sukses'),
(12, 12, '2025-02-12', '20:00', 'I Planifikuar', 'Takimi për kontrollin e shëndetit të mushkërive është planifikuar për këtë datë'),
(13, 13, '2025-02-13', '21:00', 'Anuluar', 'Takimi për trajtimin e migrenës u anulua për shkak të situatës së papritur'),
(14, 14, '2025-02-14', '22:00', 'Përfunduar', 'Takimi për analizë të nivelit të kolesterolit dhe këshilla për stilin e jetesës ka përfunduar'),
(15, 15, '2025-02-15', '23:00', 'I Planifikuar', 'Takimi për kontrollin e gjëndrës tiroide është planifikuar për këtë datë'),
(16, 16, '2025-02-16', '09:00', 'Anuluar', 'Takimi për kontrollin e shëndetit të zorrëve u anulua për arsye të papritura'),
(17, 17, '2025-02-17', '10:00', 'Përfunduar', 'Takimi për kontrollin dhe trajtimin e stresit dhe ankthit ka përfunduar me sukses'),
(18, 18, '2025-02-18', '11:00', 'I Planifikuar', 'Takimi për trajtimin e simptomave të depresionit është planifikuar për këtë datë'),
(19, 19, '2025-02-19', '12:00', 'Anuluar', 'Takimi për shërbimin e emergjencës u anulua për arsye të ndryshme');

DROP TRIGGER IF EXISTS TRG_CheckDuplicateTakim;

CREATE TRIGGER TRG_CheckDuplicateTakim  
ON Takimet  
AFTER INSERT  
AS  
BEGIN
    -- Check if the newly inserted patient already has a meeting on the same day
    IF EXISTS (
        SELECT 1 
        FROM Takimet t
        JOIN inserted i ON t.PacientID = i.PacientID
        WHERE t.DataTakimit = i.DataTakimit
        AND t.TakimID <> i.TakimID -- Ensure that it's not the inserted row itself
    )
    BEGIN
        RAISERROR ('Pacienti nuk mund të ketë më shumë se një takim në një ditë.', 16, 1);
        ROLLBACK TRANSACTION;
    END;
END;
