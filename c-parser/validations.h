#ifndef VALIDATIONS_H
#define VALIDATIONS_H

char * getValue (char *line);

int validAccountNumber(char *accountNumber);
int validAmount(char *amount);
int validTransactionType(char *transactionType);
int validTanCode(char *tanCode);

void exitWithError(int errnoValue, char *perrnoMessage, char *errorMessage);

#endif
