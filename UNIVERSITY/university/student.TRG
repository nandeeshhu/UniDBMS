TYPE=TRIGGERS
triggers='CREATE DEFINER=`root`@`localhost` TRIGGER after_student_registration\nAFTER INSERT ON student\nFOR EACH ROW\nBEGIN\n    -- Enroll the student in all courses of their department\n    INSERT INTO taken(Course_id, student_id)\n    SELECT c.Course_id, NEW.studentID\n    FROM courses c\n    WHERE c.dept_id = NEW.departmentID;\nEND'
sql_modes=1098907648
definers='root@localhost'
client_cs_names='utf8mb4'
connection_cl_names='utf8mb4_unicode_ci'
db_cl_names='utf8mb4_general_ci'
created=1700941524579218
