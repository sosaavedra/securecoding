#include "transaction.h"
#include "mysqllib.h"
#include "constants.h"

#include <stdlib.h>
#include <stdio.h>
#include <string.h>

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

void addTransaction(Transaction *transactions, Transaction *transaction){
    if(transactions->destination == NULL){
        transactions = transaction;
    } else {
        if(transactions->prev == NULL){
            transactions->prev = transaction;
            transactions->next = transaction;
            transaction->prev = transactions;
        } else {
            transaction->prev = transactions->prev;
            transactions->prev->next = transaction;
            transactions->prev = transaction;
        }
    }
}

void freeTransactions(Transaction *transactions){
}

void printTransactions (Transaction * transactions){
    printf("Printing values...\n");

    Transaction *transaction = transactions;

    if(transaction){
        do{
            printf("Destination: %s\n", transaction->destination);
            printf("Amount: %s\n", transaction->amount);
            printf("Tan code: %s\n", transaction->tanCode);
            printf("Success: %d\n", transaction->success);
            printf("Level: %s\n", transaction->level);
            printf("Code: %d\n", transaction->code);
            printf("Message: %s\n", transaction->message);

            transaction = transaction->next;
        } while(transaction);
    }
}

void printTransactionError (Transaction * transactions){
    Transaction *transaction = transactions;

    int lineNumber = 1;

    if(transaction){
        do{
            if(!transaction->success){
                printf("Error in transaction #: %d - %s\n", lineNumber, transaction->message);
            }

            lineNumber++;
            transaction = transaction->next;
        } while(transaction);
    }
}


int saveTransactions(Transaction *transactions, char *client_id){
    char *performTransaction =  "call performTransaction(?, ?, ?, ?, 3)";

    MYSQL *mysql;
    MYSQL_STMT *stmt;
    MYSQL_BIND *in_params;
    MYSQL_BIND *out_params;

    int status;
    
    mysql = openConnection();

    if(mysql == NULL){
        return EXIT_FAILURE;
    } 

    stmt = mysql_stmt_init(mysql);
 
    if(!stmt){
        fprintf(stderr, "Could not initialize statement\n");
    
        return EXIT_FAILURE;
    }

    status = mysql_stmt_prepare(stmt, performTransaction, strlen(performTransaction));

    if(test_stmt_error(stmt, status)){
        return EXIT_FAILURE;
    }

    Transaction *transaction = transactions;
    unsigned long length[3];
    my_bool is_null[3];
    my_bool error[3];

    if(transaction){
        do{
            in_params = prepareInParameters(client_id, transaction);

            if(in_params == NULL){
                return EXIT_FAILURE;
            }
 
            status = mysql_stmt_bind_param(stmt, in_params);

            if(test_stmt_error(stmt, status)){
                return EXIT_FAILURE;
            }
           
            status = mysql_stmt_execute(stmt);
    
            if(test_stmt_error(stmt, status)){
                return EXIT_FAILURE;
            }

            int num_fields;

            num_fields = mysql_stmt_field_count(stmt);

            if(num_fields > 0){
                transaction->success = 0;

                out_params = prepareOutParameters(transaction, length, is_null, error);

                if(out_params == NULL){
                    return EXIT_FAILURE;
                }

                status = mysql_stmt_bind_result(stmt, out_params);

                if(test_stmt_error(stmt, status)){
                    return EXIT_FAILURE;
                }

                status = mysql_stmt_fetch(stmt);

                if(test_stmt_error(stmt, status)){
                    return EXIT_FAILURE;
                }

                releaseParameters(out_params);
            } else {
                transaction->success = 1;
            }

            while(mysql_stmt_next_result(stmt) == 0);

            transaction = transaction->next;

            releaseParameters(in_params);

        } while(transaction);
    }

    mysql_stmt_close(stmt);


    closeConnection(mysql);

    return EXIT_SUCCESS;
}

MYSQL_BIND *prepareInParameters(char *client_id, Transaction *transaction){
    MYSQL_BIND *in_params;

    unsigned long client_id_length;
    unsigned long destination_length;
    unsigned long amount_length;
    unsigned long tancode_length;

    in_params = malloc(sizeof(MYSQL_BIND) * 4);

    if(in_params == NULL){
        fprintf(stderr, "Insufficient memory at line #%d\n", __LINE__);

        return NULL;
    }

    memset(in_params, 0, sizeof(MYSQL_BIND) * 4);

    client_id_length = strlen(client_id);
 
    in_params[0].buffer_type = MYSQL_TYPE_STRING;
    in_params[0].buffer = (char *)client_id;
    in_params[0].buffer_length = client_id_length;
    
    destination_length = strlen(transaction->destination);
    in_params[1].buffer_type = MYSQL_TYPE_STRING;
    in_params[1].buffer = (char *)transaction->destination;
    in_params[1].buffer_length = destination_length; 
    
    amount_length = strlen(transaction->amount);
    in_params[2].buffer_type = MYSQL_TYPE_STRING;
    in_params[2].buffer = (char *)transaction->amount;
    in_params[2].buffer_length = amount_length;
    
    tancode_length = strlen(transaction->tanCode);
    in_params[3].buffer_type = MYSQL_TYPE_STRING;
    in_params[3].buffer = (char *)transaction->tanCode;
    in_params[3].buffer_length = tancode_length;

    return in_params;
}

MYSQL_BIND *prepareOutParameters(Transaction *transaction, unsigned long length[], my_bool is_null[], my_bool error[]){
    MYSQL_BIND *out_params;

    out_params = malloc(sizeof(MYSQL_BIND) * 3);

    if(out_params == NULL){
        fprintf(stderr, "Insufficient memory at line #%d\n", __LINE__);

        return NULL;
    }

    memset(out_params, 0, sizeof(MYSQL_BIND) * 3);

    transaction->level = malloc(sizeof(char) * RESPONSE_LENGTH);
    transaction->message = malloc(sizeof(char) * RESPONSE_LENGTH);

    if(transaction->level == NULL || transaction->message == NULL){
        fprintf(stderr, "Insufficient memory at line #%d\n", __LINE__);

        return NULL;
    }

    out_params[0].buffer_type = MYSQL_TYPE_STRING;
    out_params[0].buffer = transaction->level;
    out_params[0].buffer_length = RESPONSE_LENGTH;
    out_params[0].length = &length[0];
    out_params[0].is_null = &is_null[0];
    out_params[0].error = &error[0];

    out_params[1].buffer_type = MYSQL_TYPE_LONG;
    out_params[1].buffer = &transaction->code;
    out_params[1].length = &length[1];
    out_params[1].is_null = &is_null[1];
    out_params[1].error = &error[1];

    out_params[2].buffer_type = MYSQL_TYPE_STRING;
    out_params[2].buffer = transaction->message;
    out_params[2].buffer_length = RESPONSE_LENGTH;
    out_params[2].length = &length[2];
    out_params[2].is_null = &is_null[2];
    out_params[2].error = &error[2];

    return out_params;
}

void releaseParameters(MYSQL_BIND *params){
    free(params);
}
