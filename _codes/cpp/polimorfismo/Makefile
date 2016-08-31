CC=g++
CXXFLAGS=-Wall -I.
TARGET=segfault
SRCLIST=$(wildcard *.cpp)
OBJLIST=$(patsubst %.cpp,%.o,$(SRCLIST))

# $@ = elkemento a sinistra dei :
# $^ = elkemento a destra dei :
# $< = primo elemento della lista delle dipendenze :

%.o : %.cpp # Quello che voglio fare : quello che mi serve x farlo

ifndef VERBOSE

	$(CC) $(CXXFLAGS) -c -o $@ $<

else

	@echo 'Compiling $< . . .'
	@$(CC) $(CXXFLAGS) -c -o $@ $<
	
endif

$(TARGET): $(OBJLIST)

ifndef VERBOSE

	$(CC) -o $(TARGET) $^

else

	@echo 'Linking . . .'
	@$(CC) -o $(TARGET) $^
	
endif

dep:
	@echo 'Making dependencies'
	$(CC) -MM *.cc > dependencies
	
clean:
	
	@rm -rf *.o
	
-include dependencies
