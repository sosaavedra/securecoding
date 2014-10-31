#include "utils.h"

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <ctype.h>

void rtrim(char *str){
    size_t n;

    n = strlen(str);

    while(n > 0 && isspace((unsigned char) str[n-1])){
        n--;
    }

    str[n] = '\0';
}

void ltrim(char *str){
    size_t n;

    n = 0;

    while(str[n] != '\0' && isspace((unsigned char) str[n])){
        n++;
    }

    memmove(str, str + n, strlen(str) - n + 1);
}

void trim(char *str){
    rtrim(str);
    ltrim(str);
}

void strtoupper(char *str){
    size_t n; 

    n = strlen(str);

    for(size_t i = 0; i < n; i++){
        if(str[i] > 96 && str[i] < 123){
            str[i] += 'A' - 'a';
        }
    }
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
