/* HO APPARTAMENTO, CHE E' UN ELETTRODOMESTICO CHE
** CONTIENE VETTORI DI ELETTRODOMESTICI (FORNO, ASPIRAPOLVERE, ETC )
*/

// nel file.hh
#include <vector> //per usare vector

class Elettrodomestico;

class Appartamento {
public:
    Appartamento();
    void add ( Elettrodomestico * );
    
private:
    std::vector<Elettrodomestico*> elettrodomestici;
};

// in appartamento.cc

void Appartamento::add ( Elettrodomestico * e) {
    elettrodomestici.push_back(e);
}

double Appartamento::potenza() {
    double p = 0;
    for ( std::vector<Elettrodomestico*>::iterator i=elettrodomestici.begin();
          i!=elettrodomestici.end(); i++) {
        if ((*i)->stato()) {
            p += (*i)->potenza();
        }
    }
    return p;
}

void Appartamento::dump() {
    for ( std::vector<Elettrodomestico*>::iterator i=elettrodomestici.begin();
          i!=elettrodomestici.end(); i++) {
        cout << (**i) << endl;
    }
}  // per questo va implementato per gli elettrodomestici l'operatore <<


//// ALTRE OPZIONI DELLA CLASSE VECTOR IMPLEMENTATE PER BATTAGLIA

Esercito::~Esercito( ){
std::cout << "Distruttore dell'esercito!!" << endl;
for (vector<Uguerra*>::iterator i = e.begin(); i!=e.end(); i++  )
	{
	delete (*i);
	}
}

void Esercito::getlancio(int l){
	
	std::cout << (e.at(l))->lancio() << endl;
	
}

void Esercito::addUguerra (Uguerra* u){
	e.push_back (u);
	}

void Esercito::delUguerra (int num){
	//e.erase(num);
	e.pop_back();
	}
	
int Esercito::numUnita(){
	return e.size(); 
	}
