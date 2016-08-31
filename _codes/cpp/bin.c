/*
 * =====================================================================================
 *
 *       Filename:  bin.c
 *
 *    Description:  
 *
 *        Version:  1.0
 *        Created:  08/24/2013 02:14:55 AM
 *       Revision:  none
 *       Compiler:  gcc
 *
 *         Author:  YOUR NAME (), 
 *   Organization:  
 *
 * =====================================================================================
 */

#include <stdio.h>
#include <stdlib.h>
#include <math.h>

// fattoriale
int fac(int n){
    if(n<0) {printf("non puoi inserire numeri negativi... \n"); return 0;}
    if(n==0) return 1;
    else n= n*fac(n-1);
    return n;
}

// n!/(n-k)! -> possibili disposizioni di n elementi in k caselle, con ordinamento
int pseudobin(int n, int k){
    int x = n-k;
    if(x<0){ printf("attenzione: n-k deve essere un numero positivo!\n"); return 0;}
    return fac(n)/ fac(n-k);
}

// binomiale: n su k -> possibili disposizioni di n elementi in k caselle, senza ordinamento
int bin(int n, int k){
    int x = n-k;
    if(x<0){ printf("attenzione: n-k deve essere un numero positivo!\n"); return 0;}
    return fac(n)/( fac(x) * fac(k) );
}


int main(int argc, char * argv[]){
    int check=20;
    int sum;
    
    for(int i=0; i<=check; i++)
    {
        sum = 0;
        for(int j=0; j<=i; j++)
        {
            sum += bin(i,j);
        }
        if (sum == pow(2,i)) {printf(" verificato per n = %d, con risultato %d \n", i,sum);}
        else {
            printf("non funziona per n = %d, in quanto la somma binomiale fa %d e 2^%d fa %d \n", i, sum, i,(int)pow(2,i));
            sum = 0;
            for(int j=0; j<=i; j++)
            {
                sum += bin(i,j);
                printf("--- somma vale %d, mentre bin(%d, %d) vale %d --- \n",sum, i,j, bin(i,j));
            }
        }
    }
    
// system("pause");   
}

