#include <iostream>

using namespace std;
int x[1000],n;
int vf[100];/// int vf[m]  memoreaza frecventa cheilor care apar intre 0..m-1
/// vf[x]   reprezinta numarul de aparitii sau frecventa cheii x
int main()
{ int i,j,c;
    cout << "Dati n " ;
    cin >>n;
    for(i=0;i<n;i++)
        cin >>x[i];
        /// pregatirea vectrului frecventa
    for(i=0;i<100;i++)
        vf[i]=0;
    ///Metoda sortarii distributia cheilor, in ideea ca valorile sunt cuprinse intre 0...m-1
 ///  v= (12, 5, 9, 45, 23, 9, 89, 67, 45, 45, 23, 5, 3)  elementele sunt cuprinse intre 0..99
      for(i=0;i<n;i++)
           vf[x[i]]++;
       i=0;
    for(c=0;c<=99;c++)/// se parcurg cheile de ordonare si se distribuie
        for(j=1;j<=vf[c];j++)
           {
               x[i]= c;
                 i++;
           }
   cout <<"Vect ordonat este ";
   for(i=0;i<n;i++)
        cout <<x[i]<<" ";
    return 0;
}

