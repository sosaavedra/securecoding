#include "transaction.h"

#include <stdlib.h>
#include <stdio.h>

#include "keyvalue.h"

Transaction *createTransaction(char **transactionLines){
    KeyValue *keyValue = createKeyValue(transactionLines);

    if(keyValue == NULL){
        return NULL;
    }

    Transaction *transaction = malloc(sizeof (struct Transaction));

    if(transaction == NULL){
        return NULL;
    }

    transaction->origin = getValue("origin", keyValue);
    transaction->destination = getValue("origin", keyValue);
    transaction->amount = getValue("amount", keyValue);
    transaction->transactionType = getValue("transaction_type", keyValue);
    transaction->tanCode = getValue("tan_code", keyValue);

    return transaction;
}

void freeTransactions(Transaction *transactions){}

void printTransactions (Transaction * transactions){
    printf("Printing values...\n");
/*
    printf("Origin: %d\n", origin);
    printf("Destination: %d\n", destination);
    printf("Amount: %f\n", amount);
    printf("Transaction type: %c\n", transactionType);
    printf("Tan code: %s\n", tanCode);
*/
}

char *saveTransaction(Transaction *transactions){
    return NULL;
}
