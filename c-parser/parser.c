#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <ctype.h>
#include <errno.h>

#include "trim.h"
#include "constants.h"

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

    int lineNumber = 0;

/*
  * Convert values to upper case
  * Free memory
  * Close file

  * Validations (Integers):
  * Not an integer (Letters, numbers and everything else)
  * More or less than 6 digits
  * Empty value

  * Validations (Double)
  * Not a double (Letters, numbers and everything else but '.'
  * Empty value

  * Validations Transaction type
  * Not a character
  * More than one character
  * Character other than 'D', 'W', 'T'
  * Empty value

  * Validations Tan code
  * Special characters
  * More or less than 15 letters/numbers
  * Empty value
*/
    int origin = -1;
    int destination = -1;
    double amount = -1;
    char transactionType = '\0';
    char *tanCode = calloc(TAN_CODE_SIZE, sizeof(char));

    while(fgets(line, LINE_SIZE * sizeof(char), transactionFile) != NULL){
        lineNumber++;

        if(lineNumber > 5){
            break;
        }


        switch(lineNumber){
            case 1: value = getValue(line);
                    origin = strtol(value, &end, 10);

                if(validAccountNumber(value) < 1){
                        fprintf(stderr, "Origin %s not an integer", value);
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
                    transactionType = value[0];
                    transactionType = toupper(transactionType);

                    if(errno){
                        perror("Transaction type)");
                        fprintf(stderr, "%s not a character", value);
                        exit(NOT_A_CHARACTER);
                    }
                break;
            case 5: tanCode = getValue(line);

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
    printf("Transaction type: %c\n", transactionType);
    printf("Tan code: %s\n", tanCode);

    free(line);
    fclose(transactionFile);

    return EXIT_SUCCESS;
}