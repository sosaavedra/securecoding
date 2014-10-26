#include "keyvalue.h"

#include <stdlib.h>
#include <string.h>

#include "utils.h"
#include "constants.h"


KeyValue *createKeyValue(char **transactionLines){
    KeyValue *keyValue = calloc(LINES_PER_TRANSACTION, sizeof(struct KeyValue));

    if(keyValue == NULL){
        return NULL;
    }

    keyValue->key = "destination";
    (*keyValue + 1)->key = "amount";
    *(keyValue->key + 2) = "transactionType";
    *(keyValue->key + 3) = "tanCode";

    //tmpKV = 
/*
    value = getValue(line);
    
    if (value == NULL){
        free(line);
        fclose(transactionFile);
    
        fprintf(stderr, "Value not found in line %d", lineNumber);
        exit(EXIT_FAILURE);
    }
    
    switch(lineNumber){
        case 1:
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
    
                tanCode = calloc(TAN_CODE_SIZE + 1, sizeof(char));   
    
                strcpy(tanCode, value);
                tanCode[TAN_CODE_SIZE] = '\0';
            break;
    }
    
    free(value);
*/
    return NULL;

}

KeyValue *getKeyValue(char *line){
    KeyValue *keyValue = malloc(sizeof (struct KeyValue));
    char *key;
    char *value;

    key = NULL;
    key = strtok(line, ">");

    if(key == NULL){
        return NULL;
    }

    value = strtok(NULL, ">");

    if(value == NULL){
        return NULL;
    }

    trim(value);

    if(strlen(value) == 0){
        return NULL;
    }

    keyValue->key = key;
    keyValue->value = value;

    return keyValue;
}


char *getValue(char *key, KeyValue *keyValue){
    return NULL;
}
