#include <iostream>

using namespace std;
/*
void sort(int *vec, int size){
	for(int i=0; i< size; i++)
		{
		if(*v

}*/

void sort( int *v, int size);

int main(){

int size= 10;

int v[10] = {5, 7, 6 , 5, 4, 5 , 6 ,8 , 9, 20};
int *vec;
//vec = &v[0];
vec = v;
sort( vec, size);
//sort decrescente
/*
cout << size << endl;
	for(int i=0; i< size-1; i++)
		{
		if(i<0){ i=-1; continue;}
		else if(v[i] >= v[i+1]) { continue;}
		else {
			int tmp = v[i];
			v[i] = v[i+1];
			v[i+1]= tmp;
			i = i-2;
			}
		}
*/
	for(int i=0; i< size; i++){ cout << v[i] << endl;}
		


return 0;
}

void sort( int *v, int size){
	for(int i=0; i< size-1; i++)
		{
		if(i<0){ i=-1; continue;}
		else if(*(v+i) >= *(v+i+1)) { continue;}
		else {
			int tmp = v[i];
			*(v+i) = *(v+i+1);
			v[i+1]= tmp;
			i = i-2;
			}
		}
}

