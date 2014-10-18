#include "utils.h"

#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#include "utils.h"

char *getValue(char *line){
    char *token;
    char *value;

    token = NULL;
    token = strtok(line, ">");

    if(token == NULL){
        return NULL;
    }

    token = strtok(NULL, ">");

    if(token == NULL){
        return NULL;
    }

    trim(token);

    int length;

    length = strlen(token);
    if(length == 0){
        return NULL;
    }

    value = malloc(length * sizeof(char));
    strcpy(value, token);

    return value;
}

int validAmount(char *amount){ return 1; }
int validTanCode(char *tanCode){ return 1; }
