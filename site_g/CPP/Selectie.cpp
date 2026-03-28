#include <iostream>

using namespace std;
int x[1000],n;
int main()
{ int i,j,minx,poz;
    cout << "Dati n " ;
    cin >>n;
    for(i=0;i<n;i++)
        cin >>x[i];
    //Metoda Selectiei

      for(i=0;i<n-1;i++)
      {
        minx=x[i];poz=i;
        for(j=i+1;j<n;j++)
         if(minx > x[j])
         {
          minx=x[j];
          poz=j;
         }
        //x[i] cu x[poz]
        x[poz]=x[i];
        x[i]= minx;
      }
   cout <<"Vect ordonat este ";
   for(i=0;i<n;i++)
        cout <<x[i]<<" ";
    return 0;
}

