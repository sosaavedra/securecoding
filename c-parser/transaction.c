#include "transaction.h"

#include <stdlib.h>
#include <stdio.h>
#include <string.h>
#include <mysql.h>

Transaction *createTransaction(char *line){
    char *value;

    Transaction *transaction = malloc(sizeof (struct Transaction));

    if(transaction == NULL){
        fprintf(stderr, "Insufficient memory at line #%d\n", __LINE__);

        return NULL;
    }

    value = strtok(line, "@");
    transaction->destination = malloc(sizeof(char) * (strlen(value) + 1));
    strcpy(transaction->destination, value);
    transaction->destination[strlen(value)] = '\0';

    value = strtok(NULL, "@");
    transaction->amount = malloc(sizeof(char) * (strlen(value) + 1));
    strcpy(transaction->amount, value);
    transaction->amount[strlen(value)] = '\0';

    value = strtok(NULL, "@");
    transaction->tanCode = malloc(sizeof(char) * (strlen(value) + 1));
    strcpy(transaction->tanCode, value);
    transaction->tanCode[strlen(value)] = '\0';

    return transaction;
}

void freeTransactions(Transaction *transactions){}

void printTransactions (Transaction * transactions){
    printf("Printing values...\n");

    Transaction *next = transactions;

    if(next != NULL){
        do{
            printf("Destination: %s\n", next->destination);
            printf("Amount: %s\n", next->amount);
            printf("Tan code: %s\n", next->tanCode);

            next = next->next;
        } while(next != NULL);
    }
}

char *saveTransactions(Transaction *transactions, char *client_id){
    printf("MySQL client version: %s\n", mysql_get_client_info());

    MYSQL mysql;
    MYSQL_RES *res;
    MYSQL_ROW row; 

    if(mysql_init(&mysql)==NULL){
        printf("\nFailed to initate MySQL connection");
        exit(EXIT_FAILURE);
    } 

    if (!mysql_real_connect(&mysql,"localhost","webuser","katanaX","banksys",0,NULL,0)){
        printf( "Failed to connect to MySQL: Error: %s\n",
        mysql_error(&mysql)); 

         exit(EXIT_FAILURE);
    }

    printf("Logged on to database sucessfully");

    char *query = "call performTransaction %s, %s, %s, %s, 3";

    if (mysql_query(&mysql, query)) {
        fprintf(stderr, "%s\n", mysql_error(&mysql));
        exit(EXIT_FAILURE);
    }

    res = mysql_use_result(&mysql);

    /* output table name */
    printf("MySQL Tables in mysql database:\n");

    while ((row = mysql_fetch_row(res)) != NULL)
        printf("%s \n", row[0]);

    mysql_free_result(res);

    mysql_close(&mysql);

    return NULL;
}
