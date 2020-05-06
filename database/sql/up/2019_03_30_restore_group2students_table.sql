CREATE TABLE `group2students` (
      `group2studentsID` INT(10) NOT NULL AUTO_INCREMENT,
      `groupID` INT(10) NOT NULL,
      `studentID` INT(11) NOT NULL,
      `active` INT(10) NOT NULL DEFAULT '1',
      `updateDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`group2studentsID`),
      INDEX `group2students_groupid_index` (`groupID`),
      INDEX `group2students_studentid_index` (`studentID`)
)
    COLLATE='latin1_swedish_ci'
    ENGINE=MyISAM
;