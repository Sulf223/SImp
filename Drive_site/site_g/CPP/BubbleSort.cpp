#include <iostream>

using namespace std;
int x[1000],n;
int main()
{ int i,j,ok,aux;
    cout << "Dati n " ;
    cin >>n;
    for(i=0;i<n;i++)
        cin >>x[i];
    //Metoda bulelor
    do
    { ok=1;
      for(i=0;i<n-1;i++)
        if(x[i] > x[i+1])
        {
         aux=x[i];
         x[i]=x[i+1];
         x[i+1]=aux;
         ok=0;
        }
   } while (ok==0);


   cout <<"Vect ordonat este ";
   for(i=0;i<n;i++)
        cout <<x[i]<<" ";
    return 0;
}
