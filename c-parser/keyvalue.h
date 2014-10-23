#ifndef KEYVALUE_H
#define KEYVALUE_H

typedef struct KeyValue KeyValue;

struct KeyValue{
    char *key;
    char *value;
};

KeyValue *createKeyValue(char **transactionLines);
char *getKeyValue(char *line);
char *getValue(char *key, KeyValue *keyValue);

#endif
