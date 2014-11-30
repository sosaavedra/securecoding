#ifndef TRANSACTION_H
#define TRANSACTION_H

#include <mysql.h>

typedef struct Transaction Transaction;

struct Transaction{
    char *destination;
    char *amount;
    char *tanCode;
    int success;
    char *level;
    int code;
    char *message;
    Transaction *next;
    Transaction *prev;
};

Transaction *createTransaction(char *line);

void addTransaction(Transaction *transactions, Transaction *transaction);

void freeTransactions(Transaction *transactions);

void printTransactions (Transaction * transactions);

int saveTransactions(Transaction *transactions, char *client_id);

MYSQL_BIND *prepareInParameters(char *client_id, Transaction *transaction);

MYSQL_BIND *prepareOutParameters(Transaction *transaction, unsigned long length[], my_bool is_null[], my_bool error[]);

void releaseParameters(MYSQL_BIND *params);

#endif
