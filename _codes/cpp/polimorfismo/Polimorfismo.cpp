#include <iostream>
#include <vector>

using namespace std;

class Animale{
	protected:
		string name;
	public:
		Animale(){}
		virtual ~Animale(){;}
		virtual void verso()=0;
};

class Cane : public Animale {
	public:
		Cane(){
			this->name = "Cane";
		}
		void verso() const{
		cout <<  "bau bau!!!" << endl;
		}
};

class Gatto: public Animale{

	public:
		Gatto(){ 
				this->name= "Gatto";
		}
		void verso() const{
				cout <<  "miao miao!!!" << endl;
		}
};

int main(int argc, char ** argv){

Animale * c = new Cane();
Animale * g = new Gatto();

Animale **v = new Animale*[2];
v[0]= c;
v[1]= g;
for(int i=0; i<2; i++){
		v[i]->verso();
}
vector<Animale *> vec;
vec.push_back(c);
vec.push_back(g);

for (vector<Animale *>::iterator it = vec.begin() ; it != vec.end(); ++it)   (*it)->verso();

return 0;
}
