#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <ctype.h>
#include <errno.h>

#include "utils.h"
#include "validations.h"
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

    int lineNumber = 0;

/*
  * Validations (Double)
  * Not a double (Letters, numbers and everything else but '.'
*/
    int origin = -1;
    int destination = -1;
    double amount = -1;
    char transactionType = '\0';
    char *tanCode = NULL;

    while(fgets(line, LINE_SIZE * sizeof(char), transactionFile) != NULL){
        lineNumber++;

        if(lineNumber > 5){
            break;
        }

        value = getValue(line);

        if (value == NULL){
            free(line);
            fclose(transactionFile);

            fprintf(stderr, "Value not found in line %d", lineNumber);
            exit(EXIT_FAILURE);
        }

        switch(lineNumber){
            case 1: case 2 :
                    if(strlen(value) != 6){
                        fprintf(stderr, "Invalid length for %s in line %d. Length must be %d", value, lineNumber, ACCOUNT_NUMBER_LENGTH);

                        free(value);
                        free(line);
                        fclose(transactionFile);
                        
                        exit(EXIT_FAILURE);
                    }
                    
                    for(int i = 0; i < ACCOUNT_NUMBER_LENGTH; i++){
                        if(!isdigit(*(value + i))){
                            fprintf(stderr, "Invalid number %s in line %d", value, lineNumber);

                            free(value);
                            free(line);
                            fclose(transactionFile);
                            
                            exit(EXIT_FAILURE);
                        }
                    }

                    if(lineNumber == 1){
                        origin = strtol(value, NULL, 10);
                    } else {
                        destination = strtol(value, NULL, 10);
                    }
                break;
            case 3: {
                    char *end;
                    amount = strtod(value, &end);

                    if(end != NULL && strlen(end) > 0){
                        fprintf(stderr, "Invalid amount %s in line %d", value, lineNumber);

                        free(value);
                        free(line);
                        fclose(transactionFile);
                        
                        exit(EXIT_FAILURE);
                    }
                break;
            }
            case 4: strtoupper(value);

                    if(strlen(value) > 1 || (*value != 'D' && *value != 'W' && *value != 'T')){
                        fprintf(stderr, "Invalid transaction type \'%s\' in line %d. Valid values: \'D\', \'W\' and \'T\'",value, lineNumber);

                        free(value);
                        free(line);
                        fclose(transactionFile);
                        
                        exit(EXIT_FAILURE);
                    }

                    transactionType = *value;
                break;
            case 5: if(strlen(value) != TAN_CODE_SIZE){
                        fprintf(stderr, "Invalid length for %s in line %d. Length must be %d", value, lineNumber, TAN_CODE_SIZE);

                        free(value);
                        free(line);
                        fclose(transactionFile);
                        
                        exit(EXIT_FAILURE);
                    }

                    for(int i = 0; i < TAN_CODE_SIZE; i++){
                        if(!isalnum(*(value + i))){
                            fprintf(stderr, "Invalid TAN code %s in line %d. Must be alphanumeric", value, lineNumber);

                            free(value);
                            free(line);
                            fclose(transactionFile);
                            
                            exit(EXIT_FAILURE);
                        }
                    }

                    strtoupper(value);

                    tanCode = malloc(TAN_CODE_SIZE * sizeof(char));   
 
                    strcpy(tanCode, value);
                break;
        }

        free(value);
    }

    if(ferror(transactionFile)){
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
