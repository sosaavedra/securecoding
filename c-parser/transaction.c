#include "transaction.h"
#include "utils.h"

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
    if(!value || strlen(value) != 8){
        fprintf(stderr, "There's a problem with the destination account number: \"%s\"\n", value);

        return NULL;
    }

    transaction->destination = malloc(sizeof(char) * (strlen(value) + 1));
    strcpy(transaction->destination, value);
    transaction->destination[strlen(value)] = '\0';

    char *end;

    value = strtok(NULL, "@");
    strtod(value, &end);
    if(end != NULL && strlen(end) > 0){
        fprintf(stderr, "Invalid amount %s\n", value);

        return NULL;
    }

    transaction->amount = malloc(sizeof(char) * (strlen(value) + 1));
    strcpy(transaction->amount, value);
    transaction->amount[strlen(value)] = '\0';

    value = strtok(NULL, "@");
    if(!value || strlen(value) != 15){
        fprintf(stderr, "There's a problem with the tan code: \"%s\"\n", value);

        return NULL;
    }

    transaction->tanCode = malloc(sizeof(char) * (strlen(value) + 1));
    strcpy(transaction->tanCode, value);
    transaction->tanCode[strlen(value)] = '\0';

    return transaction;
}

void freeTransactions(Transaction *transactions){}

void printTransactions (Transaction * transactions){
    printf("Printing values...\n");

    Transaction *next = transactions;

    if(next){
        do{
            printf("Destination: %s\n", next->destination);
            printf("Amount: %s\n", next->amount);
            printf("Tan code: %s\n", next->tanCode);

            next = next->next;
        } while(next);
    }
}

char *saveTransactions(Transaction *transactions, char *client_id){
    printf("MySQL client version: %s\n", mysql_get_client_info());

    MYSQL mysql;
    MYSQL_STMT *stmt;
    MYSQL_BIND sp_params[4];

    int status;
    unsigned long client_id_length;
    unsigned long destination_length;
    unsigned long amount_length;
    unsigned long tancode_length;

    if(mysql_init(&mysql)==NULL){
        printf("Failed to initate MySQL connection\n");

        return NULL;
    } 

    if (!mysql_real_connect(&mysql,"localhost","webuser","katanaX","banksys",0,NULL,0)){
        printf( "Failed to connect to MySQL: Error: %s\n",
        mysql_error(&mysql)); 

        return NULL;
    }

    printf("Logged on to database sucessfully\n");

    stmt = mysql_stmt_init(&mysql);

    if(!stmt){
        fprintf(stderr, "Could not initialize statement\n");

        return NULL;
    }

    status = mysql_stmt_prepare(stmt, "call performTransaction(?, ?, ?, ?, 3)", 38); 
    test_stmt_error(stmt, status);

    memset(sp_params, 0, sizeof(MYSQL_BIND));

    client_id_length = sizeof(client_id);
    sp_params[0].buffer_type = MYSQL_TYPE_LONG;
    sp_params[0].buffer = &client_id ;
    sp_params[0].buffer_length = client_id_length;
    sp_params[0].length = &client_id_length;
    sp_params[0].is_null = 0;

    destination_length = strlen(transactions->destination);
    sp_params[1].buffer_type = MYSQL_TYPE_STRING;
    sp_params[1].buffer = &transactions->destination;
    sp_params[1].buffer_length = destination_length; 
    sp_params[1].length = &destination_length;
    sp_params[1].is_null = 0;

    amount_length = strlen(transactions->amount);
    sp_params[2].buffer_type = MYSQL_TYPE_DOUBLE;
    sp_params[2].buffer = &transactions->amount;
    sp_params[2].buffer_length = amount_length;
    sp_params[2].length = &amount_length;
    sp_params[2].is_null = 0;
    
    tancode_length = strlen(transactions->tanCode);
    sp_params[3].buffer_type = MYSQL_TYPE_STRING;
    sp_params[3].buffer = &transactions->tanCode;
    sp_params[3].buffer_length = tancode_length;
    sp_params[3].length = &tancode_length;
    sp_params[3].is_null = 0;

    status = mysql_stmt_bind_param(stmt, sp_params);
    test_stmt_error(stmt, status);

    status = mysql_stmt_execute(stmt);
    test_stmt_error(stmt, status);

    mysql_stmt_close(stmt);

    mysql_close(&mysql);

    return NULL;
}
