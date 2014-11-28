#ifndef MYSQLLIB_H
#define MYSQLLIB_H

#include <mysql.h>

MYSQL *openDB();

int test_error(MYSQL *mysql, int status);
int test_stmt_error(MYSQL_STMT *stmt, int status);

#endif
