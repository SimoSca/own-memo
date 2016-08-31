// HO GARA, CHE CONTIENE UN VETTORE DI ANIMALI, QUALI CAVALLO, CANE, ETC.

//nel file.hh
public:
void add(Animale*);
private:
//Animale* animali[100];
Animale **animali;
int size;// da inizializzare a zero ovviamente


//nel file.cc
#include <string.h>

void Gara::add(Animale* an){
                        // SE USO IL VETTORE STATICO Animale* animali[100];
                        //if(size >= 100){ cout << "+++++++++++ TROPPI ANIMALI, ME SPIASE!!! ++++++++++" << endl;}
                        //else {animali[size] = an;}
                        size++;
                        Animale **tmp= new Animale*[size];
                        if(size == 1){animali = tmp;
                                        animali[size-1]=an;}
                        else{
                            memcpy(tmp, animali, size*sizeof(Animale *));
                            delete[] animali;
                            animali=tmp;
                            animali[size-1] = an;

                        }
                  }
                  
// per usare i suoi metodi
animali[k]->setPosition(oldposition);
//((Reporter*)theReporter)->report(animali[k]);

