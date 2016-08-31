#include <iostream>
#include <vector>
#include <map>

using namespace std;

// Anche se non ancora sviluppati, devo dire che esistono Cane2 e e Gatto2 per dichiararli dentro a Visualizzatore:

class Cane2;
class Gatto2;

class Visualizzatore{
	public:
		virtual void verso(Cane2*)=0;
		virtual void verso(Gatto2*)=0;
};

class Visualizzatore1 : public Visualizzatore{
	public:
		void verso(Cane2 *){
			cout<<"bau bau!!!"<<endl;
		}
		void verso(Gatto2 *){
			cout<<"miao miao!!!"<<endl;
		}
};

class Visualizzatore2 : public Visualizzatore{
	public:
		void verso(Cane2 *){
			cout<<"abbaio alla luna!!!"<<endl;
		}
		void verso(Gatto2 *){
			cout<<"miagolo alla padrona!!!"<<endl;
		}
};

class Animale2{
	protected:
		string name;
	public:
		Animale2(){;}
		virtual ~Animale2(){;}
		virtual void verso(Visualizzatore*)=0;
		string nome(){
			return this->name;
		}
};

class Cane2 : public Animale2 {
	public:
		Cane2(){
			this->name = "Cane2";
		}
		void verso(Visualizzatore* v){
			v->verso(this);
		}
};

class Gatto2: public Animale2{
	public:
		Gatto2(){ 
			this->name= "Gatto2";
		}
		void verso(Visualizzatore * v){
			v->verso(this);
		}
};

int main(int argc, char ** argv){

Animale2 * c = new Cane2();
Animale2 * g = new Gatto2();

// Animale2 *a = (Animale2*)malloc(2*sizeof(Animale2*));
//  a[0]= *c;
// a[1]= *g;
/* for(int i=0; i<2; i++){ */
//		(a[i])->verso();
/* } */

vector<Animale2 *> vec;
vec.push_back(c);
vec.push_back(g);

// con questo creo un array associativo
std::map<string, Visualizzatore*> modalita;
modalita["visualizzatore0"]= new Visualizzatore1;
modalita["visualizzatore1"]= new Visualizzatore2;

// Oppure posso direttamente creare un array di Visualizzatori:
Visualizzatore **mode = new Visualizzatore*[2];
mode[0]=new Visualizzatore1;
mode[1]=new Visualizzatore2;

for(int j=0; j<2; j++){
	Visualizzatore * v;
	v = modalita[(string) ("visualizzatore"+j)]; //se uso la mappa
	//v = mode[j];
	cout<<"Sto usando il visualizzatore di tipo "<< j+1 << endl;
	for (vector<Animale2 *>::iterator it = vec.begin() ; it != vec.end(); ++it){
			cout<<"    sono un "<< (*it)->nome()<<" e... ";
			(*it)->verso(v);
	}
}

return 0;
}
