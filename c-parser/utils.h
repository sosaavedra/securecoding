#ifndef UTILS_H
#define UTILS_H

#include <mysql.h>

void rtrim(char *str);
void ltrim(char *str);
void trim(char *str);

void strtoupper(char *str);

int test_error(MYSQL *mysql, int status);
int test_stmt_error(MYSQL_STMT *stmt, int status);

#endif
