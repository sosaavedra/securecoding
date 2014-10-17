char * getValue(char *line){
    char *token = NULL;

    token = strtok(line, ">");
    token = strtok(NULL, ">");

    trim(token);

    return token;
}

int validAccountNumber(char *accountNumber){ return 1; }
int validAmount(char *amount){ return 1; }
int validTransactionType(char *transactionType){ return 1; }
int validTanCode(char *tanCode){ return 1; }
