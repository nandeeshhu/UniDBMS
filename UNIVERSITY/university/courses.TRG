TYPE=TRIGGERS
triggers='CREATE DEFINER=`root`@`localhost` TRIGGER after_course_creation\nAFTER INSERT ON courses\nFOR EACH ROW\nBEGIN\n    -- Enroll all students in the newly added course\n    INSERT INTO taken (Course_id, student_id)\n    SELECT NEW.Course_id, s.StudentID\n    FROM student s\n    WHERE s.departmentID = NEW.dept_id;\nEND'
sql_modes=1098907648
definers='root@localhost'
client_cs_names='utf8mb4'
connection_cl_names='utf8mb4_unicode_ci'
db_cl_names='utf8mb4_general_ci'
created=1700941524594914
