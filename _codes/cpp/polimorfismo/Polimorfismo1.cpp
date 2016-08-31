#include <iostream>
#include <vector>
#include <cstdlib>

using namespace std;

class Animale1{
	protected:
		string name;
	public:
		Animale1(){;}
		virtual ~Animale1(){;}
		virtual void verso(int)=0;
		string nome(){
			return this->name;
		}
};

class Cane1 : public Animale1 {
	public:
		Cane1(){
			this->name = "Cane1";
		}
		void verso(int i){
			if(i==0){cout <<  "bau bau!!!" << endl;}
			else if(i==1){cout <<  "abbaio alla luna!!!" << endl;}
			else{ cout<< "immesso parametro errato..."<< endl;}
		}
};

class Gatto1: public Animale1{
	public:
		Gatto1(){ 
			this->name= "Gatto1";
		}
		void verso(int i){
			if(i==0){cout <<  "mia miao!!!" << endl;}
			else if(i==1){cout <<  "miagolo alla padrona!!!" << endl;}
			else{ cout<< "immesso parametro errato..."<< endl;}
		}
};

int main(int argc, char ** argv){

Animale1 * c = new Cane1();
Animale1 * g = new Gatto1();

// Animale1 *v = (Animale1*)malloc(sizeof(Animale1*));
// v[0]= *c;
// v[1]= *g;
// for(int i=0; i<2; i++){
// 		(v[i])->verso();
// }

vector<Animale1 *> vec;
vec.push_back(c);
vec.push_back(g);

for(int j=0; j<2; j++){
	cout<<"Sto usando il visualizzatore di tipo "<< j<< endl;
	for (vector<Animale1 *>::iterator it = vec.begin() ; it != vec.end(); ++it){
			cout<<"    sono un "<< (*it)->nome()<<" e... ";
			(*it)->verso(j);
	}
}
return 0;
}
