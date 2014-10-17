NAME = parser
CC = gcc
CFLAGS = -std=gnu99 -fstrict-overflow -Werror -Wall -Wshadow -Wstrict-overflow=5

OBJ_SEQ = $(NAME).o trim.o utils.o constants.h

all: $(NAME)

%.o: %.c trim.h
	$(CC) $(CFLAGS) -c -o $@ $<

$(NAME): $(OBJ_SEQ)
	$(CC) -o $@ $(OBJ_SEQ) $(CFLAGS)

clean:
	rm -f $(NAME) *.o
