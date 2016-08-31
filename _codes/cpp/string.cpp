/* HO CERCHIO COME SPECIALIZZAZIONE DI SHAPE, E AL LIMITE GROUP 
** COME GRUPPO DI SHAPE
*/

//in shape.hh (e' puramente virtuale)

/*  NOTA BENE!!!!
** IN SHAPE, STRING NAME E' PROTECTED, POICHE' IN REALTA'
** NAME VIENE INIZIALIZZATO IN UNA CLASSE FIGLIA, OSSIA CERCHIO,
** IN QUANTO SHAPE E' PURAMENTE VIRTUALE
*/
#include <string>
using namespace std;

class Shape {
public:
  Shape ( int c=0,string n=""  ) { color = c; name=n; }
  virtual ~Shape() {cout << "\tDistruttore di shape :" << name << endl;}
  virtual void draw()  = 0;
  virtual void scale(double s) = 0;
  //metodo aggiunto da me come esempio:
  string getName() { return name; }//sarebbe al limite da implementare nel file.cc
  
protected:
  string name;
  // SENZA using namespace std, devo scrivere std::string name="" , e std::string name; 
};

//in cerchio.hh
#include "shape.hh"
class Cerchio : public Shape {
public:
  Cerchio ( Point p, int r, int color=1, string name="" );
  ~Cerchio () { cout << "Distruttore cerchio " << name << endl;}
  void draw ();
  void scale ( double s );
private:
  Point center;
  int r;
};

// in cerchio.cc
#include "cerchio.hh"
Cerchio::Cerchio ( Point p, int r, int c, string n ) : Shape ( c, n ) {
    center = p;
    this->r = r;
}
void Cerchio::draw() {
    int x = center.getX();
    int y = center.getY();
    circle(screen, x, y, r, color);
}
void Cerchio::scale ( double s ) {
    r = (int)((double)r*s);
}

// in group.hh
#include <vector>
#include "shape.hh"

class Group : public Shape {

public:
  Group ( string name="" );
  ~Group ();
  void draw () ;
  void scale ( double s );
  void addShape ( Shape* s);
private:
  vector<Shape*> v;
};

//in group.cc
#include "group.hh"
Group::Group ( string name ) : Shape ( 0,name ) {
}
Group::~Group ( ) {
    cout << "Distruttore del gruppo " << name << endl;
    for ( vector<Shape*>::iterator i=v.begin(); i!=v.end(); i++ ) {
        delete (*i);
    }
}
void Group::draw()  {
    for ( vector<Shape*>::iterator i=v.begin(); i!=v.end(); i++ ) {
        (*i)->draw();
    }
}
void Group::addShape ( Shape* s) {
    v.push_back (s);
}
void Group::scale ( double s ) {
    for ( vector<Shape*>::iterator i=v.begin(); i!=v.end(); i++ ) {
        (*i)->scale(s);
    }
}

//PER USARLO NEL MAIN
Group * g = new Group("G1"); 
