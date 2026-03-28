#include <iostream>

using namespace std;
int x[1000],n;
int main()
{ int i,j,ok,aux;
    cout << "Dati n " ;
    cin >>n;
    for(i=0;i<n;i++)
        cin >>x[i];
    //Metoda interschimbare

      for(i=0;i<n-1;i++)
       for(j=i+1;j<n;j++)
        if(x[i] > x[j])
        {
         aux=x[i];
         x[i]=x[j];
         x[j]=aux;
        }



   cout <<"Vect ordonat este ";
   for(i=0;i<n;i++)
        cout <<x[i]<<" ";
    return 0;
}

