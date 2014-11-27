#include "mysqllib.h"

#include <stdio.h>
#include <stdlib.h>

int connect(MYSQL *mysql){
return 0;
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
