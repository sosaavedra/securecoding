NAME = parser
CC = gcc
CFLAGS = -std=gnu99 -fstrict-overflow -Werror -Wall -Wshadow -Wstrict-overflow=5

OBJ_SEQ = $(NAME).o

all: $(NAME)

$(NAME): $(OBJ_SEQ)
	$(CC) -o $@ $(OBJ_SEQ) $(CFLAGS)

clean:
	rm -f $(NAME) *.o
