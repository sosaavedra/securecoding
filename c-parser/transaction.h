#ifndef TRANSACTION_H
#define TRANSACTION_H

typedef struct Transaction Transaction;

struct Transaction{
    char *origin;
    char *destination;
    char *amount;
    char *transactionType;
    char *tanCode;
    Transaction *next;
    Transaction *prev;
};

Transaction *createTransaction(char **transactionLines);

void freeTransactions(Transaction *transactions);

void printTransactions (Transaction * transactions);

char *saveTransaction(Transaction *transactions);

#endif
