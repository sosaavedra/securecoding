#include "mysqllib.h"

#include <stdio.h>
#include <stdlib.h>

MYSQL *openDB(){
    MYSQL *mysql;

    mysql = mysql_init(NULL);

    if(mysql == NULL){
        printf("Failed to initate MySQL connection\n");

        return NULL;
    } 

    if (!mysql_real_connect(mysql,"localhost","parser","vEq7saf@&eVU","banksys",0,NULL,CLIENT_MULTI_RESULTS)){
        printf( "Failed to connect to MySQL: Error: %s\n", mysql_error(mysql)); 

        return NULL;
    }

    return mysql;
}

int test_error(MYSQL *mysql, int status){
   if(status){
        fprintf(stderr, "MySQL Error: %s (errno: %d)\n", mysql_error(mysql), mysql_errno(mysql));

        return EXIT_FAILURE;
    } 

    return 0;
}

int test_stmt_error(MYSQL_STMT *stmt, int status){
    if(status){
        fprintf(stderr, "Statement Error: %s (errno: %d)\n", mysql_stmt_error(stmt), mysql_stmt_errno(stmt));

        return EXIT_FAILURE;
    }

    return 0;
}
