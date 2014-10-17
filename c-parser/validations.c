#include "utils.h"

#include <stdio.h>
#include <string.h>

#include "utils.h"

char * getValue(char *line){
    char *token = NULL;

    token = strtok(line, ">");

    if(token == NULL){
        return NULL;
    }

    token = strtok(NULL, ">");

    if(token == NULL){
        return NULL;
    }

    trim(token);

    if(strlen(token) == 0){
        return NULL;
    }

    return token;
}

int validAccountNumber(char *accountNumber){ return 1; }
int validAmount(char *amount){ return 1; }
int validTransactionType(char *transactionType){ return 1; }
int validTanCode(char *tanCode){ return 1; }

void exitWithError(int errnoValue, char *perrnoMessage, char *errorMessage){

}
