NAME = parser
CC = gcc
CFLAGS = -g -std=gnu99 -fstrict-overflow -Werror -Wall -Wshadow -Wstrict-overflow=5
MYSQLFLAGS = -I/usr/include/mysql -L/usr/lib/mysql -lmysqlclient

OBJ_SEQ = constants.h utils.o transaction.o $(NAME).o

all: $(NAME)

parser.o: parser.c
	$(CC) $(CFLAGS) $(MYSQLFLAGS) -c -o $@ $<

transaction.o: transaction.c transaction.h
	$(CC) $(CFLAGS) $(MYSQLFLAGS) -c -o $@ $<

utils.o: utils.c utils.h
	$(CC) $(CFLAGS) $(MYSQLFLAGS) -c -o $@ $<

$(NAME): $(OBJ_SEQ)
	$(CC) -o $@ $(OBJ_SEQ) $(CFLAGS) $(MYSQLFLAGS) 

clean:
	rm -f $(NAME) *.o
