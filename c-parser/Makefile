NAME = parser
CC = gcc
CFLAGS = -g -std=gnu99 -fstrict-overflow -Werror -Wall -Wshadow -Wstrict-overflow=5

OBJ_SEQ = constants.h utils.o transaction.o keyvalue.o $(NAME).o

all: $(NAME)

parser.o: parser.c
	$(CC) $(CFLAGS) -c -o $@ $<

transaction.o: transaction.c transaction.h
	$(CC) $(CFLAGS) -c -o $@ $<

keyvalue.o: keyvalue.c keyvalue.h
	$(CC) $(CFLAGS) -c -o $@ $<

utils.o: utils.c utils.h
	$(CC) $(CFLAGS) -c -o $@ $<

$(NAME): $(OBJ_SEQ)
	$(CC) -o $@ $(OBJ_SEQ) $(CFLAGS)

clean:
	rm -f $(NAME) *.o
