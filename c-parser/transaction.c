#include "transaction.h"

#include <stdlib.h>
#include <stdio.h>

#include "keyvalue.h"

Transaction *createTransaction(char **transactionLines){
    KeyValue *keyValue = createKeyValue(transactionLines);

    Transaction *transaction = malloc(sizeof (struct Transaction));
    transaction->origin = getValue("origin", keyValue);
    transaction->destination = getValue("origin", keyValue);
    transaction->origin = getValue("amount", keyValue);
    transaction->origin = getValue("transaction_type", keyValue);
    transaction->origin = getValue("tan_code", keyValue);

    return NULL;
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
