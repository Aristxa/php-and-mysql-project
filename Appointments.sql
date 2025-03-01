USE HospitalDB;
INSERT INTO Takimet (PacientID, MjekID, DataTakimit, OraTakimit, Statusi, Pershkrimi)
VALUES
(1, 2, '2025-02-01', '09:00', 'Anuluar', 'Takimi p�r kontroll t� gjendjes sh�ndet�sore t� pacientit u anulua'),
(2, 2, '2025-02-02', '10:00', 'P�rfunduar', 'Takimi p�r trajtimin e dhimbjeve t� shpin�s ka p�rfunduar me sukses'),
(3, 3, '2025-02-03', '11:00', 'I Planifikuar', 'Takimi p�r kontrollin e rregullt t� zemr�s �sht� planifikuar p�r k�t� dat�'),
(4, 4, '2025-02-04', '12:00', 'Anuluar', 'Takimi p�r trajtimin e alergjive u anulua p�r shkak t� vones�s s� mjekut'),
(5, 5, '2025-02-05', '13:00', 'P�rfunduar', 'Takimi p�r vizit�n e par� pas operacionit t� krahut �sht� p�rfunduar me sukses'),
(6, 6, '2025-02-06', '14:00', 'I Planifikuar', 'Takimi p�r kontroll t� diabetit �sht� planifikuar p�r k�t� dat�'),
(7, 7, '2025-02-07', '15:00', 'Anuluar', 'Takimi p�r trajtimin e problemit me gishtin e k�mb�s u anulua p�r arsye t� tjera emergjente'),
(8, 8, '2025-02-08', '16:00', 'P�rfunduar', 'Takimi p�r kontrolle periodike dhe k�shilla p�r ushqyerje ka p�rfunduar'),
(9, 9, '2025-02-09', '17:00', 'I Planifikuar', 'Takimi p�r analiz�n e gjakut dhe k�shilla p�r p�rmir�simin e sh�ndetit �sht� planifikuar'),
(10, 10, '2025-02-10', '18:00', 'Anuluar', 'Takimi p�r kontrollin e syrit u anulua p�r arsye t� ndryshme'),
(11, 11, '2025-02-11', '19:00', 'P�rfunduar', 'Takimi p�r k�shilla dhe trajtim t� mundsh�m p�r alergjit� ka p�rfunduar me sukses'),
(12, 12, '2025-02-12', '20:00', 'I Planifikuar', 'Takimi p�r kontrollin e sh�ndetit t� mushk�rive �sht� planifikuar p�r k�t� dat�'),
(13, 13, '2025-02-13', '21:00', 'Anuluar', 'Takimi p�r trajtimin e migren�s u anulua p�r shkak t� situat�s s� papritur'),
(14, 14, '2025-02-14', '22:00', 'P�rfunduar', 'Takimi p�r analiz� t� nivelit t� kolesterolit dhe k�shilla p�r stilin e jetes�s ka p�rfunduar'),
(15, 15, '2025-02-15', '23:00', 'I Planifikuar', 'Takimi p�r kontrollin e gj�ndr�s tiroide �sht� planifikuar p�r k�t� dat�'),
(16, 16, '2025-02-16', '09:00', 'Anuluar', 'Takimi p�r kontrollin e sh�ndetit t� zorr�ve u anulua p�r arsye t� papritura'),
(17, 17, '2025-02-17', '10:00', 'P�rfunduar', 'Takimi p�r kontrollin dhe trajtimin e stresit dhe ankthit ka p�rfunduar me sukses'),
(18, 18, '2025-02-18', '11:00', 'I Planifikuar', 'Takimi p�r trajtimin e simptomave t� depresionit �sht� planifikuar p�r k�t� dat�'),
(19, 19, '2025-02-19', '12:00', 'Anuluar', 'Takimi p�r sh�rbimin e emergjenc�s u anulua p�r arsye t� ndryshme');

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
        RAISERROR ('Pacienti nuk mund t� ket� m� shum� se nj� takim n� nj� dit�.', 16, 1);
        ROLLBACK TRANSACTION;
    END;
END;
