TYPE=TRIGGERS
triggers='CREATE DEFINER=`root`@`localhost` TRIGGER before_insert_Fees_details\nBEFORE INSERT ON Fees_details\nFOR EACH ROW\nBEGIN\n    DECLARE date_str VARCHAR(8);\n    DECLARE increment_val INT;\n    DECLARE new_transaction_id VARCHAR(20);\n\n    -- Extract the current date in the format yyyymmdd\n    SET date_str = DATE_FORMAT(NOW(), \'%Y%m%d\');\n\n    -- Find the next increment value\n    SELECT COALESCE(MAX(SUBSTRING(transaction_id, 11) + 1), 1) INTO increment_val\n    FROM Fees_details\n    WHERE SUBSTRING(transaction_id, 3, 8) = date_str;\n\n    -- Generate the new transaction_id\n    SET new_transaction_id = CONCAT(\'TN\', date_str, LPAD(increment_val, 6, \'0\'));\n\n    -- Set the new_transaction_id for the current insert\n    SET NEW.transaction_id = new_transaction_id;\nEND'
sql_modes=1098907648
definers='root@localhost'
client_cs_names='utf8mb4'
connection_cl_names='utf8mb4_unicode_ci'
db_cl_names='utf8mb4_general_ci'
created=1700821702568553
