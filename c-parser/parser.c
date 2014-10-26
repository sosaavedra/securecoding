#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <ctype.h>
#include <errno.h>

#include "utils.h"
#include "constants.h"
#include "transaction.h"
#include "keyvalue.h"

int main (int argc, char *argv[]){
    FILE *transactionFile = fopen("input.txt", "r");

    if(transactionFile == NULL){
        perror("fopen()");
        fprintf(stderr, "failed in file %s at line# %d\n", __FILE__, __LINE__-4);
        return EXIT_FAILURE;
    }

    char **transactionLines;
    transactionLines = calloc(LINES_PER_TRANSACTION, sizeof (char));

    if(transactionLines == NULL){
        fprintf(stderr, "Insufficient memory at line #%d\n", __LINE__);

        fclose(transactionFile);

        return EXIT_FAILURE;
    }

    char *line = calloc(LINE_SIZE + 1, sizeof (char));

    if(line == NULL){
        fprintf(stderr, "Insufficient memory at line #%d\n", __LINE__);

        free(transactionLines);
        fclose(transactionFile);

        return EXIT_FAILURE;
    }

    Transaction *transactions = malloc(sizeof (struct Transaction));

    if(transactions == NULL){
        fprintf(stderr, "Insufficient memory at line #%d\n", __LINE__);

        free(transactionLines);
        free(line);
        fclose(transactionFile);

        return EXIT_FAILURE;
    }

    size_t lineNumber = 0;
    size_t globalLineNumber = 0;

    if(fgets(line, sizeof (line), transactionFile) != NULL){
        do{
            globalLineNumber++;

            trim(line);

            if(*line == '#'){
                switch(lineNumber){
                    case 0: break;
                    case 4:
                        lineNumber = 0;

                        Transaction *transaction = createTransaction(transactionLines);

                        if(transaction == NULL){
                           fprintf(stderr, "Transaction could not be created. Last line in file: %d\n", globalLineNumber);
       
                           freeTransactions(transactions);
                           free(transactionLines);
                           free(line);
                           fclose(transactionFile);

                            return EXIT_FAILURE;
                        }

                        if(transactions == NULL){
                            transactions = transaction;
                        } else {
                            transactions->next = transaction;
                        }
                    break;
                    default:
                        fprintf(stderr, "A valid transaction should only contain 4 values (one value per line)");
    
                        freeTransactions(transactions);
                        free(transactionLines);
                        free(line);
                        fclose(transactionFile);
    
                        return EXIT_FAILURE;
                }
            } else {
                size_t lineLength = strlen(line);
                *(transactionLines + lineNumber) = calloc(lineLength + 1, sizeof (char));
                memcpy(*(transactionLines + lineNumber), line, lineLength);
                *(transactionLines + lineNumber)[lineLength] = '\0';

                lineNumber++;
            }
        } while(fgets(line, sizeof (line), transactionFile) != NULL);
    }

    if(ferror(transactionFile)){
        fprintf(stderr, "fgets() failed in file %s at line # %d\n", __FILE__, __LINE__);

        freeTransactions(transactions);
        free(transactionLines);
        free(line);

        return EXIT_FAILURE;
    }

    printTransactions(transactions); 

    freeTransactions(transactions);
    free(transactionLines);
    free(line);

    fclose(transactionFile);

    return EXIT_SUCCESS;
}
