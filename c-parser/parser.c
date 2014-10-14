#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#define TAN_CODE_SIZE 15
#define LINE_SIZE 64
#define ORIGIN_NOT_FOUND 1001
#define DESTINATION_NOT_FOUND 1002
#define AMOUNT_NOT_FOUND 1003
#define TRANSACTION_TYPE_NOT_FOUND 1004
#define TAN_CODE_NOT_FOUND 1005

int main (void){
    FILE *transactionFile = fopen("input.txt", "r");

    if(transactionFile == NULL){
        perror("fopen()");
        fprintf(stderr, "fopen() failed in file %s at line # %d\n", __FILE__, __LINE__-4);
        return EXIT_FAILURE;
    }

    char line[LINE_SIZE];

    int origin = -1;
/*
    int destination = -1;
    double amount = -1;
    char transactionType = '\0';
    char tanCode[TAN_CODE_SIZE]; 
    memset(tanCode, '\0', TAN_CODE_SIZE);
*/

    while(fgets(line, sizeof line, transactionFile) != NULL){
        printf("%s", line);
    }

    if(origin == -1){
        printf("Error %d: Origin account number not found!\n", ORIGIN_NOT_FOUND);
        return ORIGIN_NOT_FOUND;
    }

    if(ferror(transactionFile)){
        perror("fgets()");
        fprintf(stderr, "fgets() failed in file %s at line # %d\n", __FILE__, __LINE__);
        return EXIT_FAILURE;
    }

    printf("File successfully read!\n");
    fclose(transactionFile);
    return EXIT_SUCCESS;
}
