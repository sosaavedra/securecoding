#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <ctype.h>
#include <errno.h>

#include "utils.h"
#include "constants.h"
#include "transaction.h"

int main (int argc, char **argv){

    char *fileName;
    char *client_id;

    if(argc != 3){
        fprintf(stderr, "Wrong number of parameters. Expected 2, found %d\n", (argc - 1));

        return EXIT_FAILURE;
    }

    fileName = argv[1];
    client_id = argv[2];

    for(int i = 0; i < strlen(client_id); i++){
        if(!isdigit(*(client_id + i))){
            fprintf(stderr, "Account number %s must be integer\n", client_id);
    
            return EXIT_FAILURE;
        }
    }

    FILE *transactionFile = fopen(fileName, "r");

    if(transactionFile == NULL){
        perror("fopen()");
        fprintf(stderr, "failed in file %s at line# %d\n", __FILE__, __LINE__-4);
        return EXIT_FAILURE;
    }

    char *line = malloc((LINE_SIZE + 1) *  sizeof (char));

    if(line == NULL){
        fprintf(stderr, "Insufficient memory at line #%d\n", __LINE__);

        fclose(transactionFile);

        return EXIT_FAILURE;
    }

    size_t lineNumber = 0;

    Transaction *transactions;

    if(fgets(line, (LINE_SIZE + 1), transactionFile) != NULL){
        lineNumber++;

        trim(line);

        transactions = createTransaction(line);

        if(transactions == NULL){
            fprintf(stderr, "Transaction in line %zu could not be created\n", lineNumber);
       
            free(line);
            fclose(transactionFile);
    
            return EXIT_FAILURE;
        }

        while(fgets(line, (LINE_SIZE + 1), transactionFile) != NULL){
            lineNumber++;
    
            trim(line);
    
            Transaction *transaction = createTransaction(line);
    
            if(transaction == NULL){
               fprintf(stderr, "Transaction in line %zu could not be created\n", lineNumber);
       
               freeTransactions(transactions);
               free(line);
               fclose(transactionFile);
    
                return EXIT_FAILURE;
            }

            addTransaction(transactions, transaction);
        }
    }

    if(ferror(transactionFile)){
        fprintf(stderr, "fgets() failed in file %s at line # %d\n", __FILE__, __LINE__);

        freeTransactions(transactions);
        free(line);

        return EXIT_FAILURE;
    }

    saveTransactions(transactions, client_id);
    printTransactionError(transactions);

    freeTransactions(transactions);

    fclose(transactionFile);

    return EXIT_SUCCESS;
}
