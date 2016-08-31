/*
 * =====================================================================================
 *
 *       Filename:  const.cpp
 *
 *    Description:  file creato per testare la presenza del termine "const"
 *
 *        Version:  1.0
 *        Created:  10/09/2013 01:28:16 AM
 *       Revision:  none
 *       Compiler:  gcc
 *
 *         Author:  Simone Scardoni
 *   Organization:  www.ilnullatore.altervista.org
 *
 * =====================================================================================
 */

#include <iostream>

using namespace std;

const string ER = "error: assignment of reat-only";


//funzione per testare const con variabili normali e puntatori
//
void const_var_ptr(){
	int i = 42;
	int j = 28;

	cout<<"Con i puntatori il termine const sostanzialmente vincola ad essere costante il termine alla sua sinistra: se e' un tipo di variabile, allora il puntatore non puo' modificare il valore di tale variabile (numero, stringa, etc). " << endl;
	cout<< "se invece alla sua sinistra c'e' l'asterisco *, allora il puntatore non puo' modificare il fatto di puntare a una specifica zona (quindi non puo' cambiare la zona a cui punta), ma puo' cambiare i valori in essa contenuta!" << endl << endl;

	cout <<"i:    " << i << " &i " << &i << endl;
	cout <<"j:    " << j << " &j " << &j << endl;

	const int *pc = &i; //Also: "int const *pc"
	cout << endl << "const int *pc "<< *pc <<" -- pc " << pc << "  *** &cp : "<< &pc << endl;
	//*pc = 41; //Wrong: ER location '*pc'
	i = 47; // questo valore puo' essere modificato, anche se *pc lo sta puntando
	cout << "i = " << i << "  ,  " <<" *pc "<< *pc <<" -- pc " << pc << "  *** &cp : "<< &pc << endl;
	pc = &j;
	cout << "pc = &j; "<< pc <<" -- *pc " << *pc <<"  *** &cp : "<< &pc << endl;
	cout << "in questo primo caso l'unica cosa che no nposso fare e' modificare il valore della zona di memoria a cui punta pc, direttamente con pc stesso. Pero' puo' essere modificata senza usare pc. Posso anche cambiare la zona a cui il puntatore della zona punta, ma non posso usare *pc per cambiare il valore contenuto in quella zona."<< endl<< endl;


	int *const cp = &i;
	cout << "int *const cp "<< cp << " -- *cp  " << *cp << "  *** &cp : "<< &cp << endl;

	*cp = 41;
	//cp = &j; //Wrong: ER variable 'cp'
	cout << "*cp = 41;  " << *cp << " -- cp "<< cp <<"  *** &cp : "<< &cp << " -- i " << i << endl;
	i=45;
	cout << "i = 45;  " << *cp << " -- cp "<< cp <<"  *** &cp : "<< &cp << endl;
	cout << "in questo caso si puo' utilizzare il puntatore per cambiare il valore numerico, ma non si puo' assolutissimamente modificare il riferimento alla zona di memoria a cui punta." << endl << endl;

	const int *const cpc = &i;
	cout << cpc << endl;
	//*cpc = 41; //Wrong: ER location '*(const int*)cpc'
	//cpc = &j; //Wrong: ER variable 'cpc'
	cout << *cpc << endl;
	cout <<" Qui e' tutto costante, quindi non posso ne cambiare la zona a cui punta il puntatore, ne il contenuto di quella zona" << endl << endl;
}

int main(int argc, char ** argv){

const_var_ptr();


return 0;
}
