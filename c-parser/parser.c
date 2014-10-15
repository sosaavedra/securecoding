#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <ctype.h>
#include <errno.h>

#include "trim.h"
#include "constants.h"

char * getValue (char *line);

int main (int argc, char *argv[]){
    FILE *transactionFile = fopen("input.txt", "r");

    if(transactionFile == NULL){
        perror("fopen()");
        fprintf(stderr, "fopen() failed in file %s at line # %d\n", __FILE__, __LINE__-4);
        return EXIT_FAILURE;
    }

    char *line = calloc(LINE_SIZE, sizeof (char));
    char *value = NULL; 
    char *end;

    int line_number = 0;

    int origin = -1;
    int destination = -1;
    double amount = -1;
    char transaction_type = '\0';
    char *tan_code = calloc(TAN_CODE_SIZE, sizeof(char));

    while(fgets(line, LINE_SIZE * sizeof(char), transactionFile) != NULL){
        line_number++;

        if(line_number > 5){
            break;
        }


        switch(line_number){
            case 1: value = getValue(line);
                    origin = strtol(value, &end, 10);

                    if(errno == 0){
                        perror("Origin");
                        fprintf(stderr, "%s not an integer", value);
                        exit(EXIT_FAILURE);
                    }
                break;
            case 2: value = getValue(line);
                    destination = strtol(value, NULL, 10);

                    if(errno){
                        perror("Destination");
                        fprintf(stderr, "%s not an integer", value);
                        exit(NOT_AN_INTEGER);
                    }
                break;
            case 3: value = getValue(line);
                    errno = 0;
                    amount = strtod(value, NULL);

                    if(errno){
                        perror("Amount");
                        fprintf(stderr, "%s not a double", value);
                        exit(NOT_A_DOUBLE);
                    }
                break;
            case 4: value = getValue(line);
                    transaction_type = value[0];
                    transaction_type = toupper(transaction_type);

                    if(errno){
                        perror("Transaction type)");
                        fprintf(stderr, "%s not a character", value);
                        exit(NOT_A_CHARACTER);
                    }
                break;
            case 5: tan_code = getValue(line);

                    if(errno){
                        perror("Tan code");
                        fprintf(stderr, "%s not alphanumeric", value);
                        exit(NOT_ALPHANUMERIC);
                    }
                break;
        }
    }

    if(ferror(transactionFile)){
        perror("fgets()");
        fprintf(stderr, "fgets() failed in file %s at line # %d\n", __FILE__, __LINE__);
        return EXIT_FAILURE;
    }

    printf("Origin: %d\n", origin);
    printf("Destination: %d\n", destination);
    printf("Amount: %f\n", amount);
    printf("Transaction type: %c\n", transaction_type);
    printf("Tan code: %s\n", tan_code);

    free(line);
    fclose(transactionFile);

    return EXIT_SUCCESS;
}

char * getValue(char *line){
    char *token = NULL;

    token = strtok(line, ">");
    token = strtok(NULL, ">");

    trim(token);

    return token;
}
