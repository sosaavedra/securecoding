#ifndef TRANSACTION_H
#define TRANSACTION_H

typedef struct Transaction Transaction;

struct Transaction{
    char *destination;
    char *amount;
    char *tanCode;
    Transaction *next;
    Transaction *prev;
};

Transaction *createTransaction(char *line);

void freeTransactions(Transaction *transactions);

void printTransactions (Transaction * transactions);

char *saveTransactions(Transaction *transactions, char *client_id);

#endif
