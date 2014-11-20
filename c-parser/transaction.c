#include "transaction.h"
#include "utils.h"
#include "constants.h"

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
    char *performTransaction =  "call performTransaction(?, ?, ?, ?, 3)";

    MYSQL mysql;
    MYSQL_STMT *stmt;
    MYSQL_BIND sp_params[4];
    MYSQL_BIND sp_result[3];

    int status;
    unsigned long client_id_length;
    unsigned long destination_length;
    unsigned long amount_length;
    unsigned long tancode_length;

    char level[RESPONSE_LENGTH];
    int code;
    char message[RESPONSE_LENGTH];

    unsigned long length[3];
    my_bool is_null[3];
    my_bool error[3];

    if(mysql_init(&mysql)==NULL){
        printf("Failed to initate MySQL connection\n");

        return NULL;
    } 

    if (!mysql_real_connect(&mysql,"localhost","webuser","katanaX","banksys",0,NULL,0)){
        printf( "Failed to connect to MySQL: Error: %s\n", mysql_error(&mysql)); 

        return NULL;
    }

    stmt = mysql_stmt_init(&mysql);

    if(!stmt){
        fprintf(stderr, "Could not initialize statement\n");

        return NULL;
    }

    status = mysql_stmt_prepare(stmt, performTransaction, strlen(performTransaction));
    test_stmt_error(stmt, status);

    printf("call performTransaction(%s, %s, %s, %s, 3)\n", client_id, transactions->destination, transactions->amount, transactions->tanCode);

    memset(sp_params, 0, sizeof(sp_params));

    client_id_length = strlen(client_id);
    sp_params[0].buffer_type = MYSQL_TYPE_STRING;
    sp_params[0].buffer = (char *)client_id;
    sp_params[0].buffer_length = client_id_length;

    destination_length = strlen(transactions->destination);
    sp_params[1].buffer_type = MYSQL_TYPE_STRING;
    sp_params[1].buffer = (char *)transactions->destination;
    sp_params[1].buffer_length = destination_length; 

    amount_length = strlen(transactions->amount);
    sp_params[2].buffer_type = MYSQL_TYPE_STRING;
    sp_params[2].buffer = (char *)transactions->amount;
    sp_params[2].buffer_length = amount_length;
    
    tancode_length = strlen(transactions->tanCode);
    sp_params[3].buffer_type = MYSQL_TYPE_STRING;
    sp_params[3].buffer = (char *)transactions->tanCode;
    sp_params[3].buffer_length = tancode_length;

    status = mysql_stmt_bind_param(stmt, sp_params);
    test_stmt_error(stmt, status);

    status = mysql_stmt_execute(stmt);
    test_stmt_error(stmt, status);

    memset(sp_result, 0, sizeof(sp_result));

    sp_result[0].buffer_type = MYSQL_TYPE_STRING;
    sp_result[0].buffer = &level;
    sp_result[0].buffer_length = RESPONSE_LENGTH;
    sp_result[0].length = &length[0];
    sp_result[0].is_null = &is_null[0];
    sp_result[0].error = &error[0];

    sp_result[1].buffer_type = MYSQL_TYPE_LONG;
    sp_result[1].buffer = &code;
    sp_result[1].length = &length[1];
    sp_result[1].is_null = &is_null[1];
    sp_result[1].error = &error[1];

    sp_result[2].buffer_type = MYSQL_TYPE_STRING;
    sp_result[2].buffer = &message;
    sp_result[2].buffer_length = RESPONSE_LENGTH;
    sp_result[2].length = &length[2];
    sp_result[2].is_null = &is_null[2];
    sp_result[2].error = &error[2];


    status = mysql_stmt_bind_result(stmt, sp_result);
    test_stmt_error(stmt, status);

    status = mysql_stmt_store_result(stmt);
    test_stmt_error(stmt, status);

    status = mysql_stmt_fetch(stmt);
    test_stmt_error(stmt, status);

    printf("Level: %s\n", level);
    printf("Code: %d\n", code);
    printf("Message: %s\n", message);

    mysql_stmt_close(stmt);

    mysql_close(&mysql);

    return NULL;
}
