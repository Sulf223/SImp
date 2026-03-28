#include <iostream>
#include <string.h>
using namespace std;
struct produs{
             char den[30];
             int cant;
             float pret;} P[1000];
int i, n, poz, j, ok;
float s;
struct produs aux;
float maxp;
char nume[30];
int main()
{
    cout << "Dati n nr de produse =" ;
    cin >>n;cin.get();
    for(i=0;i<n;i++)
    {
      cout <<"Dati nume produs ";
      cin.get(P[i].den, 30); cin.get();
      cout <<"Dati cant  ";
      cin >>P[i].cant;
      cout <<"Dati pret  ";
      cin >>P[i].pret;cin.get();
    }
    maxp=P[0].pret; poz=0;
    for(i=0;i<n;i++)
        if(maxp <P[i].pret)
          {maxp=P[i].pret;poz=i;}
    cout <<P[poz].den<<" "<<maxp<<endl;

    for (i=0;i<n;i++)
      for(j=i+1;j<n;j++)
            if (strcmp(P[i].den,P[j].den)>0)
           { aux=P[i]; P[i]=P[j];
             P[j]=aux;
           }
    cout <<"Lista produselor "<<endl;
    for(i=0;i<n;i++)
      cout <<P[i].den<<" "<<P[i].cant<<" "<<P[i].pret<<endl;
    cout <<"Dati numele produsului de cautat ";
    cin.get(nume,30);
    ok=0;
    for(i=0;i<n;i++)
        if (strcmp(P[i].den, nume)==0)
               ok=1;
    if(ok==0)
       cout <<"Nu avem acest produs";
    else
        cout <<"Avem acest produs";
   cout <<endl;
   s=0;
   for(i=0;i<n;i++)
      s= s+ P[i].pret *P[i].cant;
   cout <<"Valoarea produselor este " <<s;

   cout <<"Lista produselor ieftine"<<endl;
    for(i=0;i<n;i++)
      if (P[i].pret <10)
         cout <<P[i].den<<" "<<P[i].cant<<" "<<P[i].pret<<endl;
    return 0;
}
