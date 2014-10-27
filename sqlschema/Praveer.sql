CREATE DEFINER=`root`@`localhost` PROCEDURE `getClientsToApprove`() NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER SELECT id,first_name,last_name,email FROM client WHERE activated_by='0' LIMIT 10

CREATE DEFINER=`root`@`localhost` PROCEDURE `getTransactionsToApprove`() NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER SELECT * FROM transaction;

CREATE DEFINER=`root`@`localhost` PROCEDURE `getClientDetails`(IN `in_client_id` INT(8)) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER SELECT * from client where id=in_client_id;

CREATE DEFINER=`root`@`localhost` PROCEDURE `getClientTransactionHistory`(IN `in_client_id` INT(8)) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER SELECT * from transaction_history where origin_account_id = (Select account_number from account where client_id=in_client_id);

CREATE DEFINER=`root`@`localhost` PROCEDURE `getClientAccountAndBalance`(IN `in_client_id` INT(8)) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER SELECT account_number,balance from account where client_id=in_client_id;

DELIMITER ;; CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteRejectedClient`(IN `in_client_id` INT(8)) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER BEGIN DELETE from client where id=in_client_id; DELETE from user where person_id=in_client_id and user_type_id=1; END;; DELIMITER ;

 
