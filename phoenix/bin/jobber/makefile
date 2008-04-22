jobber: jobber.o error.o network.o job.o string.o client.o
	g++ -O0 -g jobber.o error.o network.o job.o string.o client.o -o $@
jobber.o: jobber.cpp
	g++ -O0 -g -c jobber.cpp
error.o: error.cpp
	g++ -O0 -g -c $?
network.o: network.cpp
	g++ -O0 -g -c $?
string.o: string.cpp
	g++ -O0 -g -c $?
client.o: client.cpp
	g++ -O0 -g -c client.cpp
job.o: job.cpp
	g++ -O0 -g -c job.cpp
clean:
	-rm jobber test *.o *~
