-- drop table if exists statements

DROP TABLE IF EXISTS plantArea;



CREATE TABLE plantArea(
	plantAreaId SMALLINT UNSIGNED AUTO_INCREMENT, -- Primary Key
	plantAreaPlantId SMALLINT UNSIGNED NOT NULL, -- Foreign Key

	plantAreaStartDay TINYINT NOT NULL,
	plantAreaEndDay TINYINT NOT NULL,
	plantAreaStartMonth TINYINT NOT NULL,
	plantAreaEndMonth TINYINT NOT NULL,
	plantAreaNumber VARCHAR(2),
	-- index and create foreign key
	INDEX(plantAreaPlantId),
	FOREIGN KEY(plantAreaPlantId) REFERENCES plant(plantId),
	PRIMARY KEY (plantAreaId)
);



