// ESEMPIO CHAR A LUNGHEZZA FISSATA 

// nel file.hh
class Animale {
public:
    Animale(char * n);
    char * getName();
    private:
    char name[80];
   };
   
// nel file.cc
#include <string.h>

Animale::Animale(char * n) {
    strcpy(name,n);
    posizione = 0;
}

char * Animale::getName() {
    return name;
}

//posso anche ritornare direttamente il nome
const char * Animale::name() const {
    return "Cawallo";
}
//esempio
Animale * a1 = new Cavallo("Furia1",2,1);

//usare getName
void Reporter::report ( Animale * a ) {
    cout << "T:" <<a->getName() << ":" << a->getPosition() << endl;
}
